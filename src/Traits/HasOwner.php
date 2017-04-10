<?php

/*
 * This file is part of Laravel Ownership.
 *
 * (c) Anton Komarev <a.komarev@cybercog.su>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cog\Ownership\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Cog\Ownership\Observers\ModelObserver;
use Cog\Ownership\Exceptions\InvalidOwnerType;
use Cog\Ownership\Contracts\CanBeOwner as CanBeOwnerContract;

/**
 * Class HasOwner.
 *
 * @package Cog\Ownership\Traits
 */
trait HasOwner
{
    /**
     * @var \Cog\Ownership\Contracts\CanBeOwner|null
     */
    private $defaultOwner;

    /**
     * Boot the HasOwner trait for a model.
     *
     * @return void
     */
    public static function bootHasOwner()
    {
        static::observe(new ModelObserver);
    }

    /**
     * Owner of the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ownedBy()
    {
        return $this->belongsTo($this->getOwnerModel(), $this->getOwnerForeignKey(), $this->getOwnerPrimaryKey());
    }

    /**
     * Get the model owner. Alias for `ownedBy()` method.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->ownedBy();
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
     * Get default owner.
     *
     * @return \Cog\Ownership\Contracts\CanBeOwner|null
     */
    public function defaultOwner()
    {
        return $this->defaultOwner;
    }

    /**
     * Set owner as default for entity.
     *
     * @param \Cog\Ownership\Contracts\CanBeOwner|null $owner
     * @return $this
     */
    public function withDefaultOwner(CanBeOwnerContract $owner = null)
    {
        $this->defaultOwner = $owner ?: $this->resolveDefaultOwner();
        if (isset($this->withDefaultOwnerOnCreate)) {
            $this->withDefaultOwnerOnCreate = false;
        }

        return $this;
    }

    /**
     * Remove default owner for entity.
     *
     * @return $this
     */
    public function withoutDefaultOwner()
    {
        $this->defaultOwner = null;
        if (isset($this->withDefaultOwnerOnCreate)) {
            $this->withDefaultOwnerOnCreate = false;
        }

        return $this;
    }

    /**
     * If default owner should be set on entity create.
     *
     * @return bool
     */
    public function isDefaultOwnerOnCreateRequired()
    {
        return isset($this->withDefaultOwnerOnCreate) ? (bool) $this->withDefaultOwnerOnCreate : false;
    }

    /**
     * Resolve entity default owner.
     *
     * @return \Cog\Ownership\Contracts\CanBeOwner|null
     */
    public function resolveDefaultOwner()
    {
        return Auth::user();
    }

    /**
     * Changes owner of the model.
     *
     * @param \Cog\Ownership\Contracts\CanBeOwner $owner
     * @return $this
     *
     * @throws \Cog\Ownership\Exceptions\InvalidOwnerType
     */
    public function changeOwnerTo(CanBeOwnerContract $owner)
    {
        $allowedOwnerType = $this->getOwnerModel();
        if (!$owner instanceof $allowedOwnerType) {
            throw InvalidOwnerType::notAllowed($this, $owner);
        }

        return $this->ownedBy()->associate($owner);
    }

    /**
     * Abandons owner of the model.
     *
     * @return $this
     */
    public function abandonOwner()
    {
        return $this->ownedBy()->dissociate();
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

        return $owner->getKey() === $this->getOwner()->getKey();
    }

    /**
     * Checks if model not owned by given owner.
     *
     * @param \Cog\Ownership\Contracts\CanBeOwner $owner
     * @return bool
     */
    public function isNotOwnedBy(CanBeOwnerContract $owner)
    {
        return !$this->isOwnedBy($owner);
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
        return $query->where($this->getOwnerForeignKey(), $owner->getKey());
    }

    /**
     * Scope a query to exclude models by owner.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Cog\Ownership\Contracts\CanBeOwner $owner
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereNotOwnedBy(Builder $query, CanBeOwnerContract $owner)
    {
        return $query->where($this->getOwnerForeignKey(), '!=', $owner->getKey());
    }

    /**
     * Get owner model name.
     *
     * @return string
     */
    protected function getOwnerModel()
    {
        return $this->ownerModel ?: app(CanBeOwnerContract::class);
    }

    /**
     * Get owner model primary key.
     *
     * @return string
     */
    protected function getOwnerPrimaryKey()
    {
        return $this->ownerPrimaryKey ?: 'id';
    }

    /**
     * Get owner foreign key.
     *
     * @return string
     */
    protected function getOwnerForeignKey()
    {
        return $this->ownerForeignKey ?: 'owned_by_id';
    }
}
