<?php

/*
 * This file is part of Laravel Ownership.
 *
 * (c) Anton Komarev <a.komarev@cybercog.su>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cog\Contracts\Ownership;

use Cog\Contracts\Ownership\CanBeOwner as CanBeOwnerContract;
use Illuminate\Database\Eloquent\Builder;

/**
 * Interface Ownable.
 *
 * @package Cog\Contracts\Ownership
 */
interface Ownable
{
    /**
     * Owner of the model.
     *
     * @return mixed
     */
    public function ownedBy();

    /**
     * Get the model owner. Alias for `ownedBy()` method.
     *
     * @return mixed
     */
    public function owner();

    /**
     * Get the model owner.
     *
     * @return \Cog\Contracts\Ownership\CanBeOwner
     */
    public function getOwner();

    /**
     * Get default owner.
     *
     * @return null|\Cog\Contracts\Ownership\CanBeOwner
     */
    public function defaultOwner();

    /**
     * Set owner as default for entity.
     *
     * @param null|\Cog\Contracts\Ownership\CanBeOwner $owner
     * @return \Cog\Contracts\Ownership\Ownable
     */
    public function withDefaultOwner(CanBeOwnerContract $owner = null);

    /**
     * Remove default owner for entity.
     *
     * @return \Cog\Contracts\Ownership\Ownable
     */
    public function withoutDefaultOwner();

    /**
     * If default owner should be set on entity create.
     *
     * @return bool
     */
    public function isDefaultOwnerOnCreateRequired();

    /**
     * Resolve entity default owner.
     *
     * @return null|\Cog\Contracts\Ownership\CanBeOwner
     */
    public function resolveDefaultOwner();

    /**
     * Changes owner of the model.
     *
     * @param \Cog\Contracts\Ownership\CanBeOwner $owner
     * @return \Cog\Contracts\Ownership\Ownable
     */
    public function changeOwnerTo(CanBeOwnerContract $owner);

    /**
     * Abandons owner of the model.
     *
     * @return \Cog\Contracts\Ownership\Ownable
     */
    public function abandonOwner();

    /**
     * Determines if model has owner.
     *
     * @return bool
     */
    public function hasOwner();

    /**
     * Checks if model owned by given owner.
     *
     * @param \Cog\Contracts\Ownership\CanBeOwner $owner
     * @return bool
     */
    public function isOwnedBy(CanBeOwnerContract $owner);

    /**
     * Checks if model not owned by given owner.
     *
     * @param \Cog\Contracts\Ownership\CanBeOwner $owner
     * @return bool
     */
    public function isNotOwnedBy(CanBeOwnerContract $owner);

    /**
     * Scope a query to only include models by owner.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Cog\Contracts\Ownership\CanBeOwner $owner
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereOwnedBy(Builder $query, CanBeOwnerContract $owner);

    /**
     * Scope a query to only include models by owner.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Cog\Contracts\Ownership\CanBeOwner $owner
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereNotOwnedBy(Builder $query, CanBeOwnerContract $owner);
}
