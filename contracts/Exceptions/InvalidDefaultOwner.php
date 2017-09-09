<?php

/*
 * This file is part of Laravel Ownership.
 *
 * (c) Anton Komarev <a.komarev@cybercog.su>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cog\Contracts\Laravel\Ownership\Exceptions;

use Cog\Contracts\Laravel\Ownership\Ownable as OwnableContract;
use Exception;

/**
 * Class InvalidDefaultOwner.
 *
 * @package Cog\Contracts\Laravel\Ownership\Exceptions
 */
class InvalidDefaultOwner extends Exception
{
    /**
     * Default owner for ownable model is null.
     *
     * @param \Cog\Contracts\Laravel\Ownership\Ownable $ownable
     * @return static
     */
    public static function isNull(OwnableContract $ownable)
    {
        return new static(sprintf('Model `%s` default owner is null.', get_class($ownable)));
    }
}
