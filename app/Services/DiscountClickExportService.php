<?php

namespace App\Services;

use App\Models\DiscountClick;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class DiscountClickExportService
{
    /**
     * Export discount clicks to Excel file
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportToExcel()
    {
        $filename = 'discount_clicks_' . Carbon::now()->format('Y-m-d') . '.xlsx';

        return Excel::download(new DiscountClickExport(), $filename);
    }

    /**
     * Generate Excel file and store it
     *
     * @param string $path
     * @return bool
     */
    public function storeExcel(string $path)
    {
        $filename = 'discount_clicks_' . Carbon::now()->format('Y-m-d') . '.xlsx';

        return Excel::store(new DiscountClickExport(), $path . '/' . $filename);
    }
}

class DiscountClickExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    /**
     * Return collection of discount clicks
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return DiscountClick::with('user')
            ->orderBy('clicked_at', 'desc')
            ->get();
    }

    /**
     * Define the headings for the Excel file
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'Nomor HP',
            'Nama',
            'Time Stamp'
        ];
    }

    /**
     * Map the data for each row
     *
     * @param mixed $discountClick
     * @return array
     */
    public function map($discountClick): array
    {
        return [
            $discountClick->phone_number,
            $discountClick->user_name ?? ($discountClick->user ? $discountClick->user->name : ''),
            $discountClick->clicked_at->format('Y-m-d H:i:s')
        ];
    }

    /**
     * Apply styles to the worksheet
     *
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold
            1 => ['font' => ['bold' => true]],
        ];
    }
}
