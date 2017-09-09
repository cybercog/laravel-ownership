<?php

/*
 * This file is part of Laravel Ownership.
 *
 * (c) Anton Komarev <a.komarev@cybercog.su>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cog\Laravel\Ownership\Observers;

use Cog\Contracts\Laravel\Ownership\Ownable as OwnableContract;

/**
 * Class OwnableObserver.
 *
 * @package Cog\Laravel\Ownership\Observers
 */
class OwnableObserver
{
    /**
     * Handle the deleted event for the model.
     *
     * @param \Cog\Contracts\Laravel\Ownership\Ownable $ownable
     * @return void
     */
    public function creating(OwnableContract $ownable)
    {
        if ($ownable->isDefaultOwnerOnCreateRequired()) {
            $ownable->withDefaultOwner();
        }

        if ($owner = $ownable->defaultOwner()) {
            $ownable->changeOwnerTo($owner);
        }
    }
}
