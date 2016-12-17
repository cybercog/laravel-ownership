<?php

/*
 * This file is part of Laravel Ownership.
 *
 * (c) CyberCog <support@cybercog.su>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cog\Ownership\Tests\Stubs\Models;

use Cog\Ownership\Traits\HasOwner;
use Illuminate\Database\Eloquent\Model;
use Cog\Ownership\Contracts\HasOwner as HasOwnerContract;

/**
 * Class EntityWithDefaultCustomizedOwner.
 *
 * @package Cog\Ownership\Tests\Stubs\Models
 */
class EntityWithDefaultCustomizedOwner extends Model implements HasOwnerContract
{
    use HasOwner;

    protected $withDefaultOwnerOnCreate = true;

    /**
     * Owner model name.
     *
     * @var string
     */
    protected $ownerModel = Group::class;

    /**
     * Owner model primary key.
     *
     * @var string
     */
    protected $ownerPrimaryKey = 'gid';

    /**
     * Owner model foreign key.
     *
     * @var string
     */
    protected $ownerForeignKey = 'group_id';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'entity_with_customized_owner';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Get model default owner.
     *
     * @return \Cog\Ownership\Contracts\CanBeOwner
     */
    public function resolveDefaultOwner()
    {
        return factory(Group::class)->create([
            'name' => 'default-group-owner',
        ]);
    }
}
