<?php

/*
 * This file is part of Laravel Ownership.
 *
 * (c) Anton Komarev <a.komarev@cybercog.su>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cog\Tests\Laravel\Ownership\Stubs\Models;

use Cog\Contracts\Laravel\Ownership\Ownable as OwnableContract;
use Cog\Laravel\Ownership\Traits\HasOwner;
use Illuminate\Database\Eloquent\Model;

/**
 * Class EntityWithDefaultCustomizedOwner.
 *
 * @package Cog\Tests\Laravel\Ownership\Stubs\Models
 */
class EntityWithDefaultCustomizedOwner extends Model implements OwnableContract
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
    protected $table = 'entity_with_customized_owners';

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
     * @return \Cog\Contracts\Laravel\Ownership\CanBeOwner
     */
    public function resolveDefaultOwner()
    {
        return factory(Group::class)->create([
            'name' => 'default-group-owner',
        ]);
    }
}
