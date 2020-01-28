<?php

/*
 * This file is part of Laravel Ownership.
 *
 * (c) Anton Komarev <anton@komarev.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cog\Tests\Laravel\Ownership\Stubs\Models;

use Cog\Contracts\Ownership\CanBeOwner as CanBeOwnerContract;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Group.
 *
 * @package Cog\Tests\Laravel\Ownership\Stubs\Models
 */
class Group extends Model implements CanBeOwnerContract
{
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'gid';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'groups';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'name',
    ];
}
