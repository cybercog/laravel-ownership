<?php

/*
 * This file is part of Laravel Ownership.
 *
 * (c) Anton Komarev <a.komarev@cybercog.su>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cog\Tests\Laravel\Ownership\Unit\Traits;

use Cog\Contracts\Ownership\Exceptions\InvalidDefaultOwner;
use Cog\Tests\Laravel\Ownership\TestCase;
use Cog\Tests\Laravel\Ownership\Stubs\Models\User;
use Cog\Contracts\Ownership\Exceptions\InvalidOwnerType;
use Cog\Tests\Laravel\Ownership\Stubs\Models\Character;
use Cog\Tests\Laravel\Ownership\Stubs\Models\EntityWithOwner;
use Cog\Tests\Laravel\Ownership\Stubs\Models\EntityWithDefaultOwner;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class HasOwnerTest.
 *
 * @package Cog\Tests\Laravel\Ownership\Unit\Traits
 */
class HasOwnerTest extends TestCase
{
    /** @test */
    public function it_can_get_owner_relation()
    {
        $user = factory(User::class)->create();
        $entity = factory(EntityWithOwner::class)->create([
            'owned_by_id' => $user->getKey(),
        ]);

        $this->assertInstanceOf(BelongsTo::class, $entity->ownedBy());
    }

    /** @test */
    public function it_can_get_owner_relation_using_alias()
    {
        $user = factory(User::class)->create();
        $entity = factory(EntityWithOwner::class)->create([
            'owned_by_id' => $user->getKey(),
        ]);

        $this->assertInstanceOf(BelongsTo::class, $entity->owner());
    }

    /** @test */
    public function it_can_belong_to_owner()
    {
        $user = factory(User::class)->create();
        $entity = factory(EntityWithOwner::class)->create([
            'owned_by_id' => $user->getKey(),
        ]);

        $this->assertInstanceOf(User::class, $entity->ownedBy);
    }

    /** @test */
    public function it_can_belong_to_owner_using_alias()
    {
        $user = factory(User::class)->create();
        $entity = factory(EntityWithOwner::class)->create([
            'owned_by_id' => $user->getKey(),
        ]);

        $this->assertInstanceOf(User::class, $entity->owner);
    }

    /** @test */
    public function it_can_get_owner()
    {
        $user = factory(User::class)->create();
        $entity = factory(EntityWithOwner::class)->create([
            'owned_by_id' => $user->getKey(),
        ]);

        $this->assertInstanceOf(User::class, $entity->getOwner());
    }

    /** @test */
    public function it_can_change_owner()
    {
        $user = factory(User::class)->create();
        $newUser = factory(User::class)->create();
        $entity = factory(EntityWithOwner::class)->create([
            'owned_by_id' => $user->getKey(),
        ]);
        $entity->changeOwnerTo($newUser);

        $this->assertEquals($newUser->getKey(), $entity->getOwner()->getKey());
    }

    /** @test */
    public function it_can_abandon_owner()
    {
        $user = factory(User::class)->create();
        $entity = factory(EntityWithOwner::class)->create([
            'owned_by_id' => $user->getKey(),
        ]);
        $this->assertInstanceOf(User::class, $entity->getOwner());

        $entity->abandonOwner();

        $this->assertNull($entity->getOwner());
    }

    /** @test */
    public function it_can_check_if_has_owner()
    {
        $user = factory(User::class)->create();
        $entity = factory(EntityWithOwner::class)->create([
            'owned_by_id' => $user->getKey(),
        ]);

        $this->assertTrue($entity->hasOwner());
    }

    /** @test */
    public function it_can_check_if_dont_have_owner()
    {
        $entity = factory(EntityWithOwner::class)->create([
            'owned_by_id' => null,
        ]);

        $this->assertFalse($entity->hasOwner());
    }

    /** @test */
    public function it_can_return_true_from_is_owned_by()
    {
        $user = factory(User::class)->create();
        $entity = factory(EntityWithOwner::class)->create([
            'owned_by_id' => $user->getKey(),
        ]);

        $this->assertTrue($entity->isOwnedBy($user));
    }

    /** @test */
    public function it_can_return_false_from_is_owned_by()
    {
        $user = factory(User::class)->create();
        $entity = factory(EntityWithOwner::class)->create([
            'owned_by_id' => $user->getKey(),
        ]);
        $notOwnerUser = factory(User::class)->create();

        $this->assertFalse($entity->isOwnedBy($notOwnerUser));
    }

    /** @test */
    public function it_can_return_true_from_is_not_owned_by()
    {
        $user = factory(User::class)->create();
        $entity = factory(EntityWithOwner::class)->create([
            'owned_by_id' => $user->getKey(),
        ]);
        $notOwnerUser = factory(User::class)->create();

        $this->assertTrue($entity->isNotOwnedBy($notOwnerUser));
    }

    /** @test */
    public function it_can_return_false_from_is_not_owned_by()
    {
        $user = factory(User::class)->create();
        $entity = factory(EntityWithOwner::class)->create([
            'owned_by_id' => $user->getKey(),
        ]);

        $this->assertFalse($entity->isNotOwnedBy($user));
    }

    /** @test */
    public function it_can_scope_models_by_owner()
    {
        $user1 = factory(User::class)->create();
        factory(EntityWithOwner::class, 4)->create([
            'owned_by_id' => $user1->getKey(),
        ]);
        $user2 = factory(User::class)->create();
        factory(EntityWithOwner::class, 3)->create([
            'owned_by_id' => $user2->getKey(),
        ]);

        $this->assertCount(4, EntityWithOwner::whereOwnedBy($user1)->get());
    }

    /** @test */
    public function it_can_scope_models_not_owned_by_owner()
    {
        $user1 = factory(User::class)->create();
        factory(EntityWithOwner::class, 4)->create([
            'owned_by_id' => $user1->getKey(),
        ]);
        $user2 = factory(User::class)->create();
        factory(EntityWithOwner::class, 3)->create([
            'owned_by_id' => $user2->getKey(),
        ]);

        $this->assertCount(3, EntityWithOwner::whereNotOwnedBy($user1)->get());
    }

    /** @test */
    public function it_can_set_default_owner_on_create()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $entity = factory(EntityWithDefaultOwner::class)->make([
            'owned_by_id' => null,
        ]);
        $entity->save();

        $this->assertInstanceOf(User::class, $entity->ownedBy);
    }

    /** @test */
    public function it_cannot_set_default_owner_on_create_for_guest()
    {
        $entity = factory(EntityWithDefaultOwner::class)->make([
            'owned_by_id' => null,
        ]);
        $entity->save();

        $this->assertNull($entity->ownedBy);
    }

    /** @test */
    public function it_can_manually_set_default_owner_on_create()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $entity = factory(EntityWithOwner::class)->make([
            'owned_by_id' => null,
        ]);
        $entity->withDefaultOwner()->save();

        $this->assertInstanceOf(User::class, $entity->ownedBy);
    }

    /** @test */
    public function it_can_manually_set_custom_default_owner_on_create()
    {
        $user = factory(User::class)->create();
        $user2 = factory(User::class)->create();
        $this->actingAs($user);
        $entity = factory(EntityWithOwner::class)->make([
            'owned_by_id' => null,
        ]);
        $entity->withDefaultOwner($user2)->save();

        $this->assertInstanceOf(User::class, $entity->ownedBy);
        $this->assertEquals($user2->getKey(), $entity->ownedBy->getKey());
    }

    /** @test */
    public function it_can_manually_override_default_owner_on_create()
    {
        $user = factory(User::class)->create();
        $user2 = factory(User::class)->create();
        $this->actingAs($user);
        $entity = factory(EntityWithDefaultOwner::class)->make([
            'owned_by_id' => null,
        ]);
        $entity->withDefaultOwner($user2)->save();

        $this->assertInstanceOf(User::class, $entity->ownedBy);
        $this->assertEquals($user2->getKey(), $entity->ownedBy->getKey());
    }

    /** @test */
    public function it_can_manually_unset_default_owner_on_create()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $entity = factory(EntityWithDefaultOwner::class)->make([
            'owned_by_id' => null,
        ]);
        $entity->withoutDefaultOwner()->save();

        $this->assertNull($entity->ownedBy);
    }

    /** @test */
    public function it_can_prevent_set_owner_of_not_allowed_type()
    {
        $this->expectException(InvalidOwnerType::class);

        $character = factory(Character::class)->create();
        $entity = factory(EntityWithOwner::class)->create();

        $entity->changeOwnerTo($character);
    }

    /** @test */
    public function it_can_return_true_on_is_owned_by_default_owner()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $entity = factory(EntityWithOwner::class)->create([
            'owned_by_id' => $user->getKey(),
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
        $entity = factory(EntityWithOwner::class)->create([
            'owned_by_id' => $user2->getKey(),
        ]);

        $isNotOwnedByCurrentUser = $entity->isOwnedByDefaultOwner();

        $this->assertFalse($isNotOwnedByCurrentUser);
    }

    /** @test */
    public function it_can_throw_an_exception_on_is_owned_by_default_owner_check_if_default_owner_is_null()
    {
        $this->expectException(InvalidDefaultOwner::class);

        $user = factory(User::class)->create();
        $entity = factory(EntityWithOwner::class)->create([
            'owned_by_id' => $user->getKey(),
        ]);

        $entity->isOwnedByDefaultOwner();
    }
}
