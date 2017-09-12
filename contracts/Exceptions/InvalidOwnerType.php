<?php

/*
 * This file is part of Laravel Ownership.
 *
 * (c) Anton Komarev <a.komarev@cybercog.su>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cog\Contracts\Ownership\Exceptions;

use Cog\Contracts\Ownership\Ownable as OwnableContract;
use Cog\Contracts\Ownership\CanBeOwner as CanBeOwnerContract;
use Exception;

/**
 * Class InvalidOwnerType.
 *
 * @package Cog\Contracts\Ownership\Exceptions
 */
class InvalidOwnerType extends Exception
{
    /**
     * Owner of the provided type is not allowed to own this model.
     *
     * @param \Cog\Contracts\Ownership\Ownable $ownable
     * @param \Cog\Contracts\Ownership\CanBeOwner $owner
     * @return static
     */
    public static function notAllowed(OwnableContract $ownable, CanBeOwnerContract $owner)
    {
        return new static(sprintf('Model `%s` not allows owner of type `%s`.', get_class($ownable), get_class($owner)));
    }
}
