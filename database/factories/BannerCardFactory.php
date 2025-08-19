<?php

namespace Database\Factories;

use App\Models\BannerCard;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BannerCard>
 */
class BannerCardFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BannerCard::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph(2),
            'background_image' => 'banner-cards/' . $this->faker->uuid() . '.jpg',
            'display_order' => $this->faker->numberBetween(1, 6),
            'is_active' => $this->faker->boolean(80), // 80% chance of being active
        ];
    }

    /**
     * Indicate that the banner card is active.
     */
    public function active(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the banner card is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Set a specific display order.
     */
    public function order(int $order): static
    {
        return $this->state(fn(array $attributes) => [
            'display_order' => $order,
        ]);
    }

    /**
     * Create a sequence of banner cards with sequential display orders.
     */
    public function sequence(...$orders): static
    {
        return $this->sequence(
            ...array_map(fn($order) => ['display_order' => $order], $orders)
        );
    }
}
