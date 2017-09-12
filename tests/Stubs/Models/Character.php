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

use Illuminate\Database\Eloquent\Model;
use Cog\Contracts\Ownership\CanBeOwner as CanBeOwnerContract;

/**
 * Class Character.
 *
 * @package Cog\Tests\Laravel\Ownership\Stubs\Models
 */
class Character extends Model implements CanBeOwnerContract
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'characters';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];
}
