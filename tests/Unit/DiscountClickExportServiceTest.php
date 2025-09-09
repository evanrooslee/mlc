<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\DiscountClickExportService;
use App\Models\DiscountClick;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class DiscountClickExportServiceTest extends TestCase
{
    use RefreshDatabase;

    protected DiscountClickExportService $exportService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->exportService = new DiscountClickExportService();
    }

    /** @test */
    public function it_can_export_discount_clicks_to_excel()
    {
        // Arrange
        Excel::fake();

        $user = User::factory()->create(['name' => 'John Doe']);

        DiscountClick::create([
            'phone_number' => '081234567890',
            'user_id' => $user->id,
            'user_name' => $user->name,
            'clicked_at' => Carbon::now()
        ]);

        DiscountClick::create([
            'phone_number' => '081987654321',
            'user_id' => null,
            'user_name' => null,
            'clicked_at' => Carbon::now()->subHour()
        ]);

        // Act
        $response = $this->exportService->exportToExcel();

        // Assert
        Excel::assertDownloaded('discount_clicks_' . Carbon::now()->format('Y-m-d') . '.xlsx');
    }

    /** @test */
    public function it_can_store_excel_file()
    {
        // Arrange
        Excel::fake();

        DiscountClick::create([
            'phone_number' => '081234567890',
            'user_id' => null,
            'user_name' => null,
            'clicked_at' => Carbon::now()
        ]);

        // Act
        $result = $this->exportService->storeExcel('exports');

        // Assert
        Excel::assertStored('exports/discount_clicks_' . Carbon::now()->format('Y-m-d') . '.xlsx');
    }
}
