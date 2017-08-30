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

use Illuminate\Foundation\Auth\User as Authenticatable;
use Cog\Ownership\Contracts\CanBeOwner as CanBeOwnerContract;

/**
 * Class User.
 *
 * @package Cog\Ownership\Tests\Stubs\Models
 */
class User extends Authenticatable implements CanBeOwnerContract
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];
}
