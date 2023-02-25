<?php

/*
 * This file is part of Laravel Ban.
 *
 * (c) Anton Komarev <anton@komarev.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Cog\Tests\Laravel\Ownership\Database\Factories;

use Cog\Tests\Laravel\Ownership\Stubs\Models\EntityWithMorphOwner;
use Illuminate\Database\Eloquent\Factories\Factory;

final class EntityWithMorphOwnerFactory extends Factory
{
    protected $model = EntityWithMorphOwner::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'owned_by_id' => null,
            'owned_by_type' => null,
        ];
    }
}
