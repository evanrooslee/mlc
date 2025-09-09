<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TrackDiscountClickRequest;
use App\Models\DiscountClick;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class DiscountController extends Controller
{
    public function index()
    {
        return view('admin.discounts');
    }



    /**
     * Track discount button click from landing page
     */
    public function trackClick(TrackDiscountClickRequest $request): JsonResponse
    {
        try {
            $data = [
                'phone_number' => $request->phone_number,
                'clicked_at' => now(),
            ];

            // Check if user is authenticated and capture user data
            if (Auth::check()) {
                $user = Auth::user();
                $data['user_id'] = $user->id;
                $data['user_name'] = $user->name;
            }

            // Store the discount click record
            DiscountClick::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Click tracked successfully'
            ]);
        } catch (\Exception $e) {
            // Log the error but don't break user experience
            Log::error('Failed to track discount click', [
                'phone_number' => $request->phone_number,
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to track click, but you can still proceed'
            ], 500);
        }
    }
}
