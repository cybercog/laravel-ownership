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

use Cog\Ownership\Traits\HasMorphOwner;
use Illuminate\Database\Eloquent\Model;
use Cog\Ownership\Contracts\HasOwner as HasOwnerContract;

/**
 * Class EntityWithMorphOwner.
 *
 * @package Cog\Ownership\Tests\Stubs\Models
 */
class EntityWithMorphOwner extends Model implements HasOwnerContract
{
    use HasMorphOwner;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'entity_with_morph_owner';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];
}
