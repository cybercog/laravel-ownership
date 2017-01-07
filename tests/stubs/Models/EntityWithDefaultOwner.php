<?php

/*
 * This file is part of Laravel Ownership.
 *
 * (c) Anton Komarev <a.komarev@cybercog.su>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cog\Ownership\Tests\Stubs\Models;

use Cog\Ownership\Traits\HasOwner;
use Illuminate\Database\Eloquent\Model;
use Cog\Ownership\Contracts\HasOwner as HasOwnerContract;

/**
 * Class EntityWithDefaultOwner.
 *
 * @package Cog\Ownership\Tests\Stubs\Models
 */
class EntityWithDefaultOwner extends Model implements HasOwnerContract
{
    use HasOwner;

    protected $withDefaultOwnerOnCreate = true;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'entity_with_owner';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];
}
