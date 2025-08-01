<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PaymentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'packet_id' => 'required|integer|exists:packets,id'
        ]);

        try {
            $paymentId = DB::table('payments')->insertGetId([
                'user_id' => Auth::id(),
                'packet_id' => $request->packet_id,
                'status' => 'Belum Bayar', // Default status
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);

            return redirect()->route('beli-konfirmasi')->with([
                'success' => 'Pemesanan berhasil dibuat!',
                'payment_id' => $paymentId
            ]);

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memproses pesanan. Silakan coba lagi.');
        }
    }
}