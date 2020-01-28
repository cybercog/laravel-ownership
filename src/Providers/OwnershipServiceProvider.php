<?php

/*
 * This file is part of Laravel Ownership.
 *
 * (c) Anton Komarev <anton@komarev.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cog\Laravel\Ownership\Providers;

use Cog\Contracts\Ownership\CanBeOwner as CanBeOwnerContract;
use Illuminate\Contracts\Foundation\Application as ApplicationContract;
use Illuminate\Support\ServiceProvider;

/**
 * Class OwnershipServiceProvider.
 *
 * @package Cog\Laravel\Ownership\Providers
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
        $this->app->bind(CanBeOwnerContract::class, function (ApplicationContract $app) {
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
