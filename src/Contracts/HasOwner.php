<?php

/*
 * This file is part of Laravel Ownership.
 *
 * (c) CyberCog <support@cybercog.su>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cog\Ownership\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Cog\Ownership\Contracts\CanBeOwner as CanBeOwnerContract;

/**
 * Interface HasOwner.
 *
 * @package Cog\Ownership\Contracts
 */
interface HasOwner
{
    /**
     * Owner of the model.
     *
     * @return mixed
     */
    public function ownedBy();

    /**
     * Get the model owner.
     *
     * @return \Cog\Ownership\Contracts\CanBeOwner
     */
    public function getOwner();

    /**
     * Get default owner.
     *
     * @return \Cog\Ownership\Contracts\CanBeOwner|null
     */
    public function defaultOwner();

    /**
     * Set owner as default for entity.
     *
     * @param \Cog\Ownership\Contracts\CanBeOwner|null $owner
     * @return $this
     */
    public function withDefaultOwner(CanBeOwnerContract $owner = null);

    /**
     * Remove default owner for entity.
     *
     * @return $this
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
     * @return \Cog\Ownership\Contracts\CanBeOwner|null
     */
    public function resolveDefaultOwner();

    /**
     * Changes owner of the model.
     *
     * @param \Cog\Ownership\Contracts\CanBeOwner $owner
     * @return $this
     */
    public function changeOwnerTo(CanBeOwnerContract $owner);

    /**
     * Abandons owner of the model.
     *
     * @return $this
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
     * @param \Cog\Ownership\Contracts\CanBeOwner $owner
     * @return bool
     */
    public function isOwnedBy(CanBeOwnerContract $owner);

    /**
     * Checks if model not owned by given owner.
     *
     * @param \Cog\Ownership\Contracts\CanBeOwner $owner
     * @return bool
     */
    public function isNotOwnedBy(CanBeOwnerContract $owner);

    /**
     * Scope a query to only include models by owner.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Cog\Ownership\Contracts\CanBeOwner $owner
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereOwnedBy(Builder $query, CanBeOwnerContract $owner);

    /**
     * Scope a query to only include models by owner.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Cog\Ownership\Contracts\CanBeOwner $owner
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereNotOwnedBy(Builder $query, CanBeOwnerContract $owner);
}
