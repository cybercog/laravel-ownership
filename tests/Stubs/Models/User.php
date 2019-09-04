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

use Illuminate\Foundation\Auth\User as Authenticatable;
use Cog\Contracts\Ownership\CanBeOwner as CanBeOwnerContract;

/**
 * Class User.
 *
 * @package Cog\Tests\Laravel\Ownership\Stubs\Models
 */
class User extends Authenticatable implements CanBeOwnerContract
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];
}
