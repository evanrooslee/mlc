<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Packet;
use App\Models\Discount;

class BuypacketController extends Controller
{
    public function beliPaket($packet_id)
    {
        // Validate packet ID is numeric
        if (!is_numeric($packet_id) || $packet_id <= 0) {
            abort(404, 'Invalid packet ID provided');
        }

        // Find the packet by ID with error handling
        try {
            $packet = Packet::findOrFail($packet_id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            abort(404, 'Packet not found');
        }

        // Process benefits - convert string to array if needed
        if (is_string($packet->benefit)) {
            $benefits = !empty($packet->benefit) ? explode('|', $packet->benefit) : ['Informasi benefit tersedia'];
        } else {
            $benefits = $packet->benefits ?? ['Informasi benefit tersedia'];
        }

        // Calculate discounted price if discount exists
        $originalPrice = $packet->price;
        $discountPercentage = 0;
        $discountAmount = 0;
        $finalPrice = $originalPrice;

        if ($packet->discount && $packet->discount->percentage > 0) {
            $discountPercentage = $packet->discount->percentage;
            $discountAmount = ($originalPrice * $discountPercentage) / 100;
            $finalPrice = $originalPrice - $discountAmount;
        }

        return view('beli-paket', compact('packet', 'benefits', 'originalPrice', 'discountPercentage', 'discountAmount', 'finalPrice'));
    }

    public function validateDiscount(Request $request)
    {
        $discountCode = strtoupper(trim($request->discount_code));

        // Find discount in database with current timestamp check
        $discount = Discount::where('code', $discountCode)
            ->where('is_valid', 1)
            ->first();

        if (!$discount) {
            return response()->json([
                'success' => false,
                'message' => 'Kode diskon tidak valid atau sudah kadaluarsa'
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => "Kode diskon {$discountCode} berhasil diterapkan! Hemat {$discount->percentage}%",
            'discount' => [
                'code' => $discount->code,
                'percentage' => $discount->percentage
            ]
        ]);
    }

    public function getAvailableDiscounts()
    {
        // Get all valid discount codes
        $discounts = Discount::where('is_valid', 1)
            ->select('code', 'percentage', 'is_valid')
            ->orderBy('percentage', 'desc')
            ->get();

        return response()->json($discounts);
    }

    // Optional: Method to add new discount codes
    public function addDiscountCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:discounts,code',
            'percentage' => 'required|integer|min:1|max:100'
        ]);

        $discount = Discount::create([
            'code' => strtoupper($request->code),
            'percentage' => $request->percentage,
            'is_valid' => 1
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kode diskon berhasil ditambahkan',
            'discount' => $discount
        ]);
    }

    // Optional: Method to deactivate discount codes
    public function deactivateDiscountCode($id)
    {
        $discount = Discount::findOrFail($id);
        $discount->update(['is_valid' => 0]);

        return response()->json([
            'success' => true,
            'message' => 'Kode diskon berhasil dinonaktifkan'
        ]);
    }
}
