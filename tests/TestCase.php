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

use Cog\Tests\Laravel\Ownership\Stubs\Models\Character;
use Cog\Tests\Laravel\Ownership\Stubs\Models\User;
use Illuminate\Database\Eloquent\Relations\Relation;
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
    }

    /**
     * Define environment setup.
     *
     * @param \Illuminate\Foundation\Application $app
     * @return void
     */
    protected function getEnvironmentSetUp($app): void
    {
        $this->setDefaultUserModel($app);
    }

    /**
     * Load package service provider.
     *
     * @param \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app): array
    {
        return [
            \Cog\Laravel\Ownership\Providers\OwnershipServiceProvider::class,
            \Orchestra\Database\ConsoleServiceProvider::class,
        ];
    }

    /**
     * Register test migrations.
     *
     * @return void
     */
    protected function registerMigrations(): void
    {
        $this->loadMigrationsFrom([
            '--realpath' => realpath(__DIR__ . '/database/migrations'),
        ]);
    }

    /**
     * Perform unit test database migrations.
     *
     * @return void
     */
    protected function migrateUnitTestTables(): void
    {
        $this->artisan('migrate');
    }

    /**
     * Register package related model factories.
     *
     * @return void
     */
    private function registerPackageFactories(): void
    {
        $pathToFactories = realpath(__DIR__ . '/database/factories');
        $this->withFactories($pathToFactories);
    }

    /**
     * Set default user model used by tests.
     *
     * @param $app
     * @return void
     */
    private function setDefaultUserModel($app): void
    {
        $app['config']->set('auth.providers.users.model', User::class);
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
