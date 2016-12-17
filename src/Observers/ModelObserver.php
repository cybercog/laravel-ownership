<?php

/*
 * This file is part of Laravel Ownership.
 *
 * (c) CyberCog <support@cybercog.su>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cog\Ownership\Observers;

use Cog\Ownership\Contracts\HasOwner as HasOwnerContract;
use Illuminate\Support\Facades\Auth;

/**
 * Class ModelObserver.
 *
 * @package Cog\Ownership\Observers
 */
class ModelObserver
{
    /**
     * Handle the deleted event for the model.
     *
     * @param \Cog\Ownership\Contracts\HasOwner $model
     * @return void
     */
    public function creating(HasOwnerContract $model)
    {
        if ($this->isDefaultOwnerOnCreateRequired($model)) {
            $owner = $this->getDefaultOwner($model);
            if ($owner) {
                $model->changeOwnerTo($owner);
            }
        }
    }

    /**
     * Require to set default owner on create (defaults to false).
     *
     * @param \Cog\Ownership\Contracts\HasOwner $model
     * @return bool
     */
    protected function isDefaultOwnerOnCreateRequired(HasOwnerContract $model)
    {
        return isset($model->setDefaultOwnerOnCreate) ? (bool) $model->setDefaultOwnerOnCreate : false;
    }

    /**
     * Get model default owner.
     *
     * @param \Cog\Ownership\Contracts\HasOwner $model
     * @return \Cog\Ownership\Contracts\CanBeOwner|null
     */
    protected function getDefaultOwner(HasOwnerContract $model)
    {
        if (method_exists($model, 'getDefaultOwner')) {
            return $model->getDefaultOwner();
        }

        return Auth::user();
    }
}
