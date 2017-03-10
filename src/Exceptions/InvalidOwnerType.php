<?php

/*
 * This file is part of Laravel Ownership.
 *
 * (c) Anton Komarev <a.komarev@cybercog.su>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cog\Ownership\Exceptions;

use Exception;
use Cog\Ownership\Contracts\HasOwner;
use Cog\Ownership\Contracts\CanBeOwner as CanBeOwnerContract;

/**
 * Class InvalidOwnerType.
 *
 * @package Cog\Ownership\Exceptions
 */
class InvalidOwnerType extends Exception
{
    /**
     * Owner of the provided type is not allowed to own this model.
     *
     * @param \Cog\Ownership\Contracts\HasOwner $model
     * @param \Cog\Ownership\Contracts\CanBeOwner $owner
     * @return static
     */
    public static function notAllowed(HasOwner $model, CanBeOwnerContract $owner)
    {
        return new static(sprintf('Model `%s` not allows owner of type `%s`.', get_class($model), get_class($owner)));
    }
}
