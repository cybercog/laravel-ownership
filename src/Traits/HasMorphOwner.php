<?php

/*
 * This file is part of Laravel Ownership.
 *
 * (c) CyberCog <support@cybercog.su>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cog\Ownership\Traits;

use Cog\Ownership\Contracts\CanBeOwner as CanBeOwnerContract;
use Cog\Ownership\Observers\ModelObserver;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class HasMorphOwner.
 *
 * @package Cog\Ownership\Traits
 */
trait HasMorphOwner
{
    /**
     * Set owner on model create (authenticated user by default).
     *
     * @var bool
     */
    public $setDefaultOwnerOnCreate = false;

    /**
     * Boot the HasMorphOwner trait for a model.
     *
     * @return void
     */
    public static function bootHasMorphOwner()
    {
        static::observe(new ModelObserver);
    }

    /**
     * Owner of the model.
     *
     * @return mixed
     */
    public function ownedBy()
    {
        return $this->morphTo('owned_by');
    }

    /**
     * Get the model owner.
     *
     * @return \Cog\Ownership\Contracts\CanBeOwner
     */
    public function getOwner()
    {
        return $this->ownedBy;
    }

    /**
     * Changes owner of the model.
     *
     * @param \Cog\Ownership\Contracts\CanBeOwner $owner
     * @return $this
     */
    public function changeOwnerTo(CanBeOwnerContract $owner)
    {
        return $this->ownedBy()->associate($owner);
    }

    /**
     * Abandons owner of the model.
     *
     * @return $this
     */
    public function abandonOwner()
    {
        $model = $this->ownedBy()->dissociate();
        $this->load('ownedBy');

        return $model;
    }

    /**
     * Determines if model has owner.
     *
     * @return bool
     */
    public function hasOwner()
    {
        return !is_null($this->getOwner());
    }

    /**
     * Checks if model owned by given owner.
     *
     * @param \Cog\Ownership\Contracts\CanBeOwner $owner
     * @return bool
     */
    public function isOwnedBy(CanBeOwnerContract $owner)
    {
        if (!$this->hasOwner()) {
            return false;
        }

        return $owner->getKey() === $this->getOwner()->getKey() && $owner->getMorphClass() === $this->getOwner()->getMorphClass();
    }

    /**
     * Scope a query to only include models by owner.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Cog\Ownership\Contracts\CanBeOwner $owner
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereOwnedBy(Builder $query, CanBeOwnerContract $owner)
    {
        return $query->where([
            'owned_by_id' => $owner->getKey(),
            'owned_by_type' => $owner->getMorphClass(),
        ]);
    }
}
