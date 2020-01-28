<?php

/*
 * This file is part of Laravel Ownership.
 *
 * (c) Anton Komarev <anton@komarev.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cog\Tests\Laravel\Ownership\Unit\Traits;

use Cog\Contracts\Ownership\Exceptions\InvalidDefaultOwner;
use Cog\Tests\Laravel\Ownership\Stubs\Models\Character;
use Cog\Tests\Laravel\Ownership\Stubs\Models\EntityWithDefaultMorphOwner;
use Cog\Tests\Laravel\Ownership\Stubs\Models\EntityWithMorphOwner;
use Cog\Tests\Laravel\Ownership\Stubs\Models\User;
use Cog\Tests\Laravel\Ownership\TestCase;

/**
 * Class HasMorphOwnerTest.
 *
 * @package Cog\Tests\Laravel\Ownership\Unit\Traits
 */
class HasMorphOwnerTest extends TestCase
{
    /** @test */
    public function it_can_belong_to_owner()
    {
        $character = factory(Character::class)->create();
        $entity = factory(EntityWithMorphOwner::class)->create([
            'owned_by_id' => $character->getKey(),
            'owned_by_type' => $character->getMorphClass(),
        ]);

        $this->assertInstanceOf(Character::class, $entity->ownedBy);
    }

    /** @test */
    public function it_can_get_owner()
    {
        $character = factory(Character::class)->create();
        $entity = factory(EntityWithMorphOwner::class)->create([
            'owned_by_id' => $character->getKey(),
            'owned_by_type' => $character->getMorphClass(),
        ]);

        $this->assertInstanceOf(Character::class, $entity->getOwner());
    }

    /** @test */
    public function it_can_change_owner()
    {
        $character = factory(Character::class)->create();
        $newUser = factory(Character::class)->create();
        $entity = factory(EntityWithMorphOwner::class)->create([
            'owned_by_id' => $character->getKey(),
            'owned_by_type' => $character->getMorphClass(),
        ]);
        $entity->changeOwnerTo($newUser);

        $this->assertEquals($newUser->getKey(), $entity->getOwner()->getKey());
    }

    /** @test */
    public function it_can_abandon_owner()
    {
        $character = factory(Character::class)->create();
        $entity = factory(EntityWithMorphOwner::class)->create([
            'owned_by_id' => $character->getKey(),
            'owned_by_type' => $character->getMorphClass(),
        ]);
        $this->assertInstanceOf(Character::class, $entity->getOwner());

        $entity->abandonOwner();

        $this->assertNull($entity->getOwner());
    }

    /** @test */
    public function it_can_check_if_has_owner()
    {
        $character = factory(Character::class)->create();
        $entity = factory(EntityWithMorphOwner::class)->create([
            'owned_by_id' => $character->getKey(),
            'owned_by_type' => $character->getMorphClass(),
        ]);

        $this->assertTrue($entity->hasOwner());
    }

    /** @test */
    public function it_can_check_if_dont_have_owner()
    {
        $entity = factory(EntityWithMorphOwner::class)->create([
            'owned_by_id' => null,
            'owned_by_type' => null,
        ]);

        $this->assertFalse($entity->hasOwner());
    }

    /** @test */
    public function it_can_return_true_from_is_owned_by()
    {
        $character = factory(Character::class)->create();
        $entity = factory(EntityWithMorphOwner::class)->create([
            'owned_by_id' => $character->getKey(),
            'owned_by_type' => $character->getMorphClass(),
        ]);

        $this->assertTrue($entity->isOwnedBy($character));
    }

    /** @test */
    public function it_can_return_false_from_is_owned_by()
    {
        $character = factory(Character::class)->create();
        $entity = factory(EntityWithMorphOwner::class)->create([
            'owned_by_id' => $character->getKey(),
            'owned_by_type' => $character->getMorphClass(),
        ]);
        $notOwnerUser = factory(Character::class)->create();

        $this->assertFalse($entity->isOwnedBy($notOwnerUser));
    }

    /** @test */
    public function it_can_return_true_from_is_not_owned_by()
    {
        $character = factory(Character::class)->create();
        $entity = factory(EntityWithMorphOwner::class)->create([
            'owned_by_id' => $character->getKey(),
            'owned_by_type' => $character->getMorphClass(),
        ]);
        $notOwnerUser = factory(Character::class)->create();

        $this->assertTrue($entity->isNotOwnedBy($notOwnerUser));
    }

    /** @test */
    public function it_can_return_false_from_is_not_owned_by()
    {
        $character = factory(Character::class)->create();
        $entity = factory(EntityWithMorphOwner::class)->create([
            'owned_by_id' => $character->getKey(),
            'owned_by_type' => $character->getMorphClass(),
        ]);

        $this->assertFalse($entity->isNotOwnedBy($character));
    }

    /** @test */
    public function it_can_scope_models_by_owner()
    {
        $character = factory(Character::class)->create();
        factory(EntityWithMorphOwner::class, 4)->create([
            'owned_by_id' => $character->getKey(),
            'owned_by_type' => $character->getMorphClass(),
        ]);
        $user = factory(User::class)->create();
        factory(EntityWithMorphOwner::class, 3)->create([
            'owned_by_id' => $user->getKey(),
            'owned_by_type' => $user->getMorphClass(),
        ]);

        $this->assertCount(4, EntityWithMorphOwner::whereOwnedBy($character)->get());
    }

    /** @test */
    public function it_can_scope_models_not_owned_by_owner()
    {
        $character = factory(Character::class)->create();
        factory(EntityWithMorphOwner::class, 4)->create([
            'owned_by_id' => $character->getKey(),
            'owned_by_type' => $character->getMorphClass(),
        ]);
        $user = factory(User::class)->create();
        factory(EntityWithMorphOwner::class, 3)->create([
            'owned_by_id' => $user->getKey(),
            'owned_by_type' => $user->getMorphClass(),
        ]);

        $this->assertCount(3, EntityWithMorphOwner::whereNotOwnedBy($character)->get());
    }

    /** @test */
    public function it_can_set_default_owner_on_create()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $entity = factory(EntityWithDefaultMorphOwner::class)->make([
            'owned_by_id' => null,
            'owned_by_type' => null,
        ]);
        $entity->save();

        $this->assertInstanceOf(User::class, $entity->ownedBy);
    }

    /** @test */
    public function it_cannot_set_default_owner_on_create_for_guest()
    {
        $entity = factory(EntityWithDefaultMorphOwner::class)->make([
            'owned_by_id' => null,
            'owned_by_type' => null,
        ]);
        $entity->save();

        $this->assertNull($entity->ownedBy);
    }

    /** @test */
    public function it_can_manually_set_default_owner_on_create()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $entity = factory(EntityWithMorphOwner::class)->make([
            'owned_by_id' => null,
            'owned_by_type' => null,
        ]);
        $entity->withDefaultOwner()->save();

        $this->assertInstanceOf(User::class, $entity->ownedBy);
    }

    /** @test */
    public function it_can_manually_set_custom_default_owner_on_create()
    {
        $user = factory(User::class)->create();
        $character = factory(Character::class)->create();
        $this->actingAs($user);
        $entity = factory(EntityWithMorphOwner::class)->make([
            'owned_by_id' => null,
            'owned_by_type' => null,
        ]);
        $entity->withDefaultOwner($character)->save();

        $this->assertInstanceOf(Character::class, $entity->ownedBy);
        $this->assertEquals($character->getKey(), $entity->ownedBy->getKey());
    }

    /** @test */
    public function it_can_manually_override_default_owner_on_create()
    {
        $user = factory(User::class)->create();
        $character = factory(Character::class)->create();
        $this->actingAs($user);
        $entity = factory(EntityWithDefaultMorphOwner::class)->make([
            'owned_by_id' => null,
            'owned_by_type' => null,
        ]);
        $entity->withDefaultOwner($character)->save();

        $this->assertInstanceOf(Character::class, $entity->ownedBy);
        $this->assertEquals($character->getKey(), $entity->ownedBy->getKey());
    }

    /** @test */
    public function it_can_manually_unset_default_owner_on_create()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $entity = factory(EntityWithDefaultMorphOwner::class)->make([
            'owned_by_id' => null,
            'owned_by_type' => null,
        ]);
        $entity->withoutDefaultOwner()->save();

        $this->assertNull($entity->ownedBy);
    }

    /** @test */
    public function it_can_return_true_on_is_owned_by_default_owner()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $entity = factory(EntityWithMorphOwner::class)->create([
            'owned_by_id' => $user->getKey(),
            'owned_by_type' => $user->getMorphClass(),
        ]);

        $isOwnedByCurrentUser = $entity->isOwnedByDefaultOwner();

        $this->assertTrue($isOwnedByCurrentUser);
    }

    /** @test */
    public function it_can_return_false_on_is_owned_by_default_owner()
    {
        $user1 = factory(User::class)->create();
        $user2 = factory(User::class)->create();
        $this->actingAs($user1);
        $entity = factory(EntityWithMorphOwner::class)->create([
            'owned_by_id' => $user2->getKey(),
            'owned_by_type' => $user2->getMorphClass(),
        ]);

        $isNotOwnedByCurrentUser = $entity->isOwnedByDefaultOwner();

        $this->assertFalse($isNotOwnedByCurrentUser);
    }

    /** @test */
    public function it_can_throw_an_exception_on_is_owned_by_default_owner_check_if_default_owner_is_null()
    {
        $this->expectException(InvalidDefaultOwner::class);

        $user = factory(User::class)->create();
        $entity = factory(EntityWithMorphOwner::class)->create([
            'owned_by_id' => $user->getKey(),
            'owned_by_type' => $user->getMorphClass(),
        ]);

        $entity->isOwnedByDefaultOwner();
    }
}
