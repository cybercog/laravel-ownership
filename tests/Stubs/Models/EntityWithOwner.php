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

use Cog\Contracts\Ownership\Ownable as OwnableContract;
use Cog\Laravel\Ownership\Traits\HasOwner;
use Illuminate\Database\Eloquent\Model;

/**
 * Class EntityWithOwner.
 *
 * @package Cog\Tests\Laravel\Ownership\Stubs\Models
 */
class EntityWithOwner extends Model implements OwnableContract
{
    use HasOwner;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'entity_with_owners';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];
}
