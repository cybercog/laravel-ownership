<?php

/*
 * This file is part of Laravel Ownership.
 *
 * (c) CyberCog <support@cybercog.su>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cog\Ownership\Providers;

use Cog\Ownership\Contracts\CanBeOwner as CanBeOwnerContract;
use Illuminate\Support\ServiceProvider;

/**
 * Class OwnershipServiceProvider.
 *
 * @package Cog\Ownership\Providers
 */
class OwnershipServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->bindUserModel();
    }

    /**
     * Bind User model from config to Owner contract.
     *
     * @return void
     */
    protected function bindUserModel()
    {
        $this->app->bind(CanBeOwnerContract::class, function ($app) {
            $config = $app->make('config');

            return $config->get('auth.providers.users.model', $config->get('auth.model'));
        });
    }
}
