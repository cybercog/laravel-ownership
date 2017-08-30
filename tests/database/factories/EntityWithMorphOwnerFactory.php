<?php

/*
 * This file is part of Laravel Ownership.
 *
 * (c) Anton Komarev <a.komarev@cybercog.su>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Cog\Ownership\Tests\Stubs\Models\EntityWithMorphOwner;
use Faker\Generator;

/* @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(EntityWithMorphOwner::class, function (Generator $faker) {
    return [
        'name' => $faker->word,
        'owned_by_id' => null,
        'owned_by_type' => null,
    ];
});
