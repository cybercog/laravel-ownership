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
use Cog\Tests\Laravel\Ownership\Stubs\Models\Group;
use Cog\Contracts\Ownership\Exceptions\InvalidOwnerType;
use Cog\Tests\Laravel\Ownership\Stubs\Models\EntityWithCustomizedOwner;
use Cog\Tests\Laravel\Ownership\Stubs\Models\EntityWithDefaultCustomizedOwner;

/**
 * Class HasCustomizedOwnerTest.
 *
 * @package Cog\Tests\Laravel\Ownership\Unit\Traits
 */
class HasCustomizedOwnerTest extends TestCase
{
    /** @test */
    public function it_can_belong_to_owner()
    {
        $group = factory(Group::class)->create();
        $entity = factory(EntityWithCustomizedOwner::class)->create([
            'group_id' => $group->getKey(),
        ]);

        $this->assertInstanceOf(Group::class, $entity->ownedBy);
    }

    /** @test */
    public function it_can_get_owner()
    {
        $group = factory(Group::class)->create();
        $entity = factory(EntityWithCustomizedOwner::class)->create([
            'group_id' => $group->getKey(),
        ]);

        $this->assertInstanceOf(Group::class, $entity->getOwner());
    }

    /** @test */
    public function it_can_change_owner()
    {
        $group = factory(Group::class)->create();
        $newUser = factory(Group::class)->create();
        $entity = factory(EntityWithCustomizedOwner::class)->create([
            'group_id' => $group->getKey(),
        ]);
        $entity->changeOwnerTo($newUser);

        $this->assertEquals($newUser->getKey(), $entity->getOwner()->getKey());
    }

    /** @test */
    public function it_can_abandon_owner()
    {
        $group = factory(Group::class)->create();
        $entity = factory(EntityWithCustomizedOwner::class)->create([
            'group_id' => $group->getKey(),
        ]);
        $this->assertInstanceOf(Group::class, $entity->getOwner());

        $entity->abandonOwner();

        $this->assertNull($entity->getOwner());
    }

    /** @test */
    public function it_can_check_if_has_owner()
    {
        $group = factory(Group::class)->create();
        $entity = factory(EntityWithCustomizedOwner::class)->create([
            'group_id' => $group->getKey(),
        ]);

        $this->assertTrue($entity->hasOwner());
    }

    /** @test */
    public function it_can_check_if_dont_have_owner()
    {
        $entity = factory(EntityWithCustomizedOwner::class)->create([
            'group_id' => null,
        ]);

        $this->assertFalse($entity->hasOwner());
    }

    /** @test */
    public function it_can_return_true_from_is_owned_by()
    {
        $group = factory(Group::class)->create();
        $entity = factory(EntityWithCustomizedOwner::class)->create([
            'group_id' => $group->getKey(),
        ]);

        $this->assertTrue($entity->isOwnedBy($group));
    }

    /** @test */
    public function it_can_return_false_from_is_owned_by()
    {
        $group = factory(Group::class)->create();
        $entity = factory(EntityWithCustomizedOwner::class)->create([
            'group_id' => $group->getKey(),
        ]);
        $notOwnerUser = factory(Group::class)->create();

        $this->assertFalse($entity->isOwnedBy($notOwnerUser));
    }

    /** @test */
    public function it_can_return_true_from_is_not_owned_by()
    {
        $group = factory(Group::class)->create();
        $entity = factory(EntityWithCustomizedOwner::class)->create([
            'group_id' => $group->getKey(),
        ]);
        $notOwner = factory(Group::class)->create();

        $this->assertTrue($entity->isNotOwnedBy($notOwner));
    }

    /** @test */
    public function it_can_return_false_from_is_not_owned_by()
    {
        $group = factory(Group::class)->create();
        $entity = factory(EntityWithCustomizedOwner::class)->create([
            'group_id' => $group->getKey(),
        ]);

        $this->assertFalse($entity->isNotOwnedBy($group));
    }

    /** @test */
    public function it_can_scope_models_by_owner()
    {
        $group1 = factory(Group::class)->create();
        factory(EntityWithCustomizedOwner::class, 4)->create([
            'group_id' => $group1->getKey(),
        ]);
        $group2 = factory(Group::class)->create();
        factory(EntityWithCustomizedOwner::class, 3)->create([
            'group_id' => $group2->getKey(),
        ]);

        $this->assertCount(4, EntityWithCustomizedOwner::whereOwnedBy($group1)->get());
    }

    /** @test */
    public function it_can_scope_models_not_owned_by_owner()
    {
        $group1 = factory(Group::class)->create();
        factory(EntityWithCustomizedOwner::class, 4)->create([
            'group_id' => $group1->getKey(),
        ]);
        $group2 = factory(Group::class)->create();
        factory(EntityWithCustomizedOwner::class, 3)->create([
            'group_id' => $group2->getKey(),
        ]);

        $this->assertCount(3, EntityWithCustomizedOwner::whereNotOwnedBy($group1)->get());
    }

    /** @test */
    public function it_can_set_default_owner_on_create()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $entity = factory(EntityWithDefaultCustomizedOwner::class)->make([
            'group_id' => null,
        ]);
        $entity->save();

        $this->assertInstanceOf(Group::class, $entity->ownedBy);
        $this->assertEquals('default-group-owner', $entity->ownedBy->name);
    }

    /** @test */
    public function it_can_manually_set_default_owner_on_create()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $entity = factory(EntityWithCustomizedOwner::class)->make([
            'group_id' => null,
        ]);
        $entity->withDefaultOwner()->save();

        $this->assertInstanceOf(Group::class, $entity->ownedBy);
        $this->assertEquals('default-group-owner', $entity->ownedBy->name);
    }

    /** @test */
    public function it_can_manually_set_custom_default_owner_on_create()
    {
        $user = factory(User::class)->create();
        $group = factory(Group::class)->create();
        $this->actingAs($user);
        $entity = factory(EntityWithCustomizedOwner::class)->make([
            'group_id' => null,
        ]);
        $entity->withDefaultOwner($group)->save();

        $this->assertInstanceOf(Group::class, $entity->ownedBy);
        $this->assertEquals($group->getKey(), $entity->ownedBy->getKey());
    }

    /** @test */
    public function it_can_manually_override_default_owner_on_create()
    {
        $user = factory(User::class)->create();
        $group = factory(Group::class)->create();
        $this->actingAs($user);
        $entity = factory(EntityWithDefaultCustomizedOwner::class)->make([
            'group_id' => null,
        ]);
        $entity->withDefaultOwner($group)->save();

        $this->assertInstanceOf(Group::class, $entity->ownedBy);
        $this->assertEquals($group->getKey(), $entity->ownedBy->getKey());
    }

    /** @test */
    public function it_can_manually_unset_default_owner_on_create()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $entity = factory(EntityWithDefaultCustomizedOwner::class)->make([
            'group_id' => null,
        ]);
        $entity->withoutDefaultOwner()->save();

        $this->assertNull($entity->ownedBy);
    }

    /** @test */
    public function it_can_prevent_set_owner_of_not_allowed_type()
    {
        $this->expectException(InvalidOwnerType::class);

        $character = factory(User::class)->create();
        $entity = factory(EntityWithCustomizedOwner::class)->create();
        $entity->changeOwnerTo($character);
    }

    /** @test */
    public function it_can_return_true_on_is_owned_by_default_owner()
    {
        $user = factory(User::class)->create();
        $group = factory(Group::class)->create([
            'user_id' => $user->getKey(),
        ]);
        $this->actingAs($user);
        $entity = factory(EntityWithCustomizedOwner::class)->create([
            'group_id' => $group->getKey(),
        ]);

        $isOwnedByCurrentUser = $entity->isOwnedByDefaultOwner();

        $this->assertTrue($isOwnedByCurrentUser);
    }

    /** @test */
    public function it_can_return_false_on_is_owned_by_default_owner()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);
        factory(Group::class)->create([
            'user_id' => $user->getKey(),
        ]);
        $group = factory(Group::class)->create();
        $entity = factory(EntityWithCustomizedOwner::class)->create([
            'group_id' => $group->getKey(),
        ]);

        $isNotOwnedByCurrentUser = $entity->isOwnedByDefaultOwner();

        $this->assertFalse($isNotOwnedByCurrentUser);
    }

    /** @test */
    public function it_can_throw_an_exception_on_is_owned_by_default_owner_check_if_default_owner_is_null()
    {
        $this->expectException(InvalidDefaultOwner::class);

        $group = factory(Group::class)->create();
        $entity = factory(EntityWithCustomizedOwner::class)->create([
            'group_id' => $group->getKey(),
        ]);

        $entity->isOwnedByDefaultOwner();
    }
}
