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

use Illuminate\Support\ServiceProvider;
use Cog\Ownership\Contracts\CanBeOwner as CanBeOwnerContract;

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

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            CanBeOwnerContract::class,
        ];
    }
}
