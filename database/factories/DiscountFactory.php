<?php

namespace Database\Factories;

use App\Models\Discount;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Discount>
 */
class DiscountFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Discount::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => strtoupper($this->faker->unique()->lexify('??????')),
            'percentage' => $this->faker->numberBetween(5, 50),
            'is_valid' => $this->faker->boolean(80), // 80% chance of being valid
        ];
    }

    /**
     * Indicate that the discount is valid.
     */
    public function valid(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_valid' => true,
        ]);
    }

    /**
     * Indicate that the discount is invalid.
     */
    public function invalid(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_valid' => false,
        ]);
    }
}
