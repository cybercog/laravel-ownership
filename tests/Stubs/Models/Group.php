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

use Illuminate\Database\Eloquent\Model;
use Cog\Ownership\Contracts\CanBeOwner as CanBeOwnerContract;

/**
 * Class Group.
 *
 * @package Cog\Ownership\Tests\Stubs\Models
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
    protected $table = 'group';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];
}
