<?php

/*
 * This file is part of Laravel Ownership.
 *
 * (c) CyberCog <support@cybercog.su>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cog\Ownership\Tests;

use Cog\Ownership\Tests\Stubs\Models\Character;
use Cog\Ownership\Tests\Stubs\Models\User;
use Illuminate\Database\Eloquent\Relations\Relation;
use Orchestra\Testbench\TestCase as Orchestra;

/**
 * Class TestCase.
 *
 * @package Cog\Ownership\Tests
 */
abstract class TestCase extends Orchestra
{
    /**
     * Actions to be performed on PHPUnit start.
     */
    protected function setUp()
    {
        parent::setUp();

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
            \Cog\Ownership\Providers\OwnershipServiceProvider::class,
        ];
    }

    /**
     * Perform unit test database migrations.
     */
    protected function migrateUnitTestTables()
    {
        $this->artisan('migrate', [
            '--realpath' => realpath(__DIR__ . '/database/migrations'),
        ]);
    }

    /**
     * Register package related model factories.
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
     */
    private function setDefaultUserModel($app)
    {
        $app['config']->set('auth.providers.users.model', User::class);
        dump('Set user model in config: ' . User::class);
    }

    protected function registerTestMorphMaps()
    {
        Relation::morphMap([
            'character' => Character::class,
        ]);
    }
}
