<?php

/*
 * This file is part of Laravel Ownership.
 *
 * (c) Anton Komarev <anton@komarev.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cog\Tests\Laravel\Ownership;

use Cog\Laravel\Ownership\Providers\OwnershipServiceProvider;
use Cog\Tests\Laravel\Ownership\Stubs\Models\Character;
use Cog\Tests\Laravel\Ownership\Stubs\Models\User;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Foundation\Application;
use Orchestra\Testbench\TestCase as Orchestra;

/**
 * Class TestCase.
 *
 * @package Cog\Tests\Laravel\Ownership
 */
abstract class TestCase extends Orchestra
{
    /**
     * Actions to be performed on PHPUnit start.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->registerMigrations();
        $this->migrateUnitTestTables();
        $this->registerPackageFactories();
        $this->registerTestMorphMaps();
        $this->setDefaultUserModel();
    }

    /**
     * Load package service provider.
     *
     * @param Application $app
     * @return array
     */
    protected function getPackageProviders($app): array
    {
        return [
            OwnershipServiceProvider::class,
        ];
    }

    /**
     * Register test migrations.
     *
     * @return void
     */
    protected function registerMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
    }

    /**
     * Perform unit test database migrations.
     *
     * @return void
     */
    protected function migrateUnitTestTables(): void
    {
        $this->artisan('migrate', ['--database' => 'sqlite'])->run();
    }

    /**
     * Register package related model factories.
     *
     * @return void
     */
    private function registerPackageFactories(): void
    {
        $this->withFactories(__DIR__ . '/database/factories');
    }

    /**
     * Set default user model used by tests.
     *
     * @return void
     */
    private function setDefaultUserModel(): void
    {
        $this->app
            ->make('config')
            ->set('auth.providers.users.model', User::class);
    }

    /**
     * Register morph map for test cases.
     *
     * @return void
     */
    protected function registerTestMorphMaps(): void
    {
        Relation::morphMap([
            'character' => Character::class,
        ]);
    }
}
