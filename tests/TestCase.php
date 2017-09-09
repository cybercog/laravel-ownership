<?php

/*
 * This file is part of Laravel Ownership.
 *
 * (c) Anton Komarev <a.komarev@cybercog.su>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cog\Tests\Laravel\Ownership;

use Cog\Tests\Laravel\Ownership\Stubs\Models\User;
use Orchestra\Testbench\TestCase as Orchestra;
use Cog\Tests\Laravel\Ownership\Stubs\Models\Character;
use Illuminate\Database\Eloquent\Relations\Relation;

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
    protected function setUp()
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
    protected function getEnvironmentSetUp($app)
    {
        $this->setDefaultUserModel($app);
    }

    /**
     * Load package service provider.
     *
     * @param \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app)
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
    protected function registerMigrations()
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
    protected function migrateUnitTestTables()
    {
        $this->artisan('migrate');
    }

    /**
     * Register package related model factories.
     *
     * @return void
     */
    private function registerPackageFactories()
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
    private function setDefaultUserModel($app)
    {
        $app['config']->set('auth.providers.users.model', User::class);
    }

    /**
     * Register morph map for test cases.
     *
     * @return void
     */
    protected function registerTestMorphMaps()
    {
        Relation::morphMap([
            'character' => Character::class,
        ]);
    }
}
