<?php

namespace Tests\Unit;

use App\Models\DiscountClick;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DiscountClickTest extends TestCase
{
    use RefreshDatabase;

    public function test_discount_click_can_be_created_with_required_fields(): void
    {
        $discountClick = DiscountClick::create([
            'phone_number' => '081234567890',
            'clicked_at' => now()
        ]);

        $this->assertDatabaseHas('discount_clicks', [
            'phone_number' => '081234567890',
            'user_id' => null,
            'user_name' => null
        ]);

        $this->assertInstanceOf(DiscountClick::class, $discountClick);
    }

    public function test_discount_click_can_be_created_with_user_information(): void
    {
        $user = User::factory()->create(['name' => 'John Doe']);

        $discountClick = DiscountClick::create([
            'phone_number' => '081234567890',
            'user_id' => $user->id,
            'user_name' => $user->name,
            'clicked_at' => now()
        ]);

        $this->assertDatabaseHas('discount_clicks', [
            'phone_number' => '081234567890',
            'user_id' => $user->id,
            'user_name' => 'John Doe'
        ]);
    }

    public function test_discount_click_belongs_to_user(): void
    {
        $user = User::factory()->create();

        $discountClick = DiscountClick::create([
            'phone_number' => '081234567890',
            'user_id' => $user->id,
            'user_name' => $user->name,
            'clicked_at' => now()
        ]);

        $this->assertInstanceOf(User::class, $discountClick->user);
        $this->assertEquals($user->id, $discountClick->user->id);
    }

    public function test_discount_click_can_exist_without_user(): void
    {
        $discountClick = DiscountClick::create([
            'phone_number' => '081234567890',
            'clicked_at' => now()
        ]);

        $this->assertNull($discountClick->user);
        $this->assertNull($discountClick->user_id);
    }

    public function test_clicked_at_is_cast_to_datetime(): void
    {
        $now = now();

        $discountClick = DiscountClick::create([
            'phone_number' => '081234567890',
            'clicked_at' => $now
        ]);

        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $discountClick->clicked_at);
        $this->assertEquals($now->format('Y-m-d H:i:s'), $discountClick->clicked_at->format('Y-m-d H:i:s'));
    }

    public function test_fillable_attributes_are_correctly_set(): void
    {
        $discountClick = new DiscountClick();

        $expectedFillable = [
            'phone_number',
            'user_id',
            'user_name',
            'clicked_at'
        ];

        $this->assertEquals($expectedFillable, $discountClick->getFillable());
    }
}
