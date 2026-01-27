<?php

namespace Database\Factories;

use App\Models\Discount;
use App\Models\Packet;
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
        $tingkatan = $this->faker->randomElement(['SMP', 'SMA']);
        $grade = $tingkatan === 'SMP'
            ? $this->faker->numberBetween(7, 9)
            : $this->faker->numberBetween(10, 12);

        return [
            'title' => $this->faker->sentence(3),
            'code' => strtoupper($this->faker->unique()->bothify('PKT###')),
            'tingkatan' => $tingkatan,
            'kurikulum' => $this->faker->randomElement(['NAS', 'NAS+/International']),
            'grade' => $grade,
            'subject' => $this->faker->randomElement(['Matematika', 'Fisika', 'Kimia', 'Campuran']),
            'type' => 'standard',
            'benefit' => $this->faker->paragraph(),
            'sesi' => 8,
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
        return $this->state(fn (array $attributes) => [
            'discount_id' => Discount::factory(),
        ]);
    }

    /**
     * Indicate that the packet is premium type.
     */
    public function premium(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'premium',
        ]);
    }

    /**
     * Indicate that the packet is standard type.
     */
    public function standard(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'standard',
        ]);
    }
}
