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
        if ($model->isDefaultOwnerOnCreateRequired()) {
            $model->withDefaultOwner();
        }

        if ($owner = $model->defaultOwner()) {
            $model->changeOwnerTo($owner);
        }
    }
}
