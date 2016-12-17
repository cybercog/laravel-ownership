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

use Illuminate\Database\Eloquent\Model;
use Cog\Ownership\Contracts\CanBeOwner as CanBeOwnerContract;

/**
 * Class Character.
 *
 * @package Cog\Ownership\Tests\Stubs\Models
 */
class Character extends Model implements CanBeOwnerContract
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'character';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];
}
