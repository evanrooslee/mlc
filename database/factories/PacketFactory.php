<?php

namespace Database\Factories;

use App\Models\Packet;
use App\Models\Discount;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Packet>
 */
class PacketFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Packet::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'code' => strtoupper($this->faker->unique()->bothify('PKT###')),
            'grade' => $this->faker->numberBetween(1, 12),
            'subject' => $this->faker->randomElement(['Mathematics', 'Science', 'English', 'History', 'Geography']),
            'type' => $this->faker->randomElement(['premium', 'standard']),
            'benefit' => $this->faker->paragraph(),
            'price' => $this->faker->numberBetween(50000, 500000),
            'discount_id' => null, // Default to no discount
            'image' => $this->faker->imageUrl(640, 480, 'education'),
        ];
    }

    /**
     * Indicate that the packet has a discount.
     */
    public function withDiscount(): static
    {
        return $this->state(fn(array $attributes) => [
            'discount_id' => Discount::factory(),
        ]);
    }

    /**
     * Indicate that the packet is premium type.
     */
    public function premium(): static
    {
        return $this->state(fn(array $attributes) => [
            'type' => 'premium',
        ]);
    }

    /**
     * Indicate that the packet is standard type.
     */
    public function standard(): static
    {
        return $this->state(fn(array $attributes) => [
            'type' => 'standard',
        ]);
    }
}
