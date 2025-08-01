<?php

namespace Tests\Unit;

use App\Models\Discount;
use App\Models\Packet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DiscountModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_fillable_attributes()
    {
        $discount = new Discount();
        $expected = ['code', 'percentage', 'is_valid'];

        $this->assertEquals($expected, $discount->getFillable());
    }

    /** @test */
    public function it_casts_attributes_correctly()
    {
        $discount = new Discount();
        $casts = $discount->getCasts();

        $this->assertEquals('integer', $casts['percentage']);
        $this->assertEquals('boolean', $casts['is_valid']);
    }

    /** @test */
    public function it_has_many_packets()
    {
        $discount = Discount::factory()->create();
        $packet = Packet::factory()->create(['discount_id' => $discount->id]);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $discount->packets);
        $this->assertTrue($discount->packets->contains($packet));
    }

    /** @test */
    public function it_can_scope_valid_discounts()
    {
        $validDiscount = Discount::factory()->create(['is_valid' => true]);
        $invalidDiscount = Discount::factory()->create(['is_valid' => false]);

        $validDiscounts = Discount::valid()->get();

        $this->assertTrue($validDiscounts->contains($validDiscount));
        $this->assertFalse($validDiscounts->contains($invalidDiscount));
    }

    /** @test */
    public function it_can_create_discount_with_valid_data()
    {
        $discountData = [
            'code' => 'TEST10',
            'percentage' => 10,
            'is_valid' => true
        ];

        $discount = Discount::create($discountData);

        $this->assertDatabaseHas('discounts', $discountData);
        $this->assertEquals('TEST10', $discount->code);
        $this->assertEquals(10, $discount->percentage);
        $this->assertTrue($discount->is_valid);
    }
}
