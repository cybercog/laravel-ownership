<?php

/*
 * This file is part of Laravel Ownership.
 *
 * (c) Anton Komarev <a.komarev@cybercog.su>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Cog\Tests\Laravel\Ownership\Stubs\Models\Character;

/* @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Character::class, function (\Faker\Generator $faker) {
    return [
        'name' => $faker->name,
    ];
});
