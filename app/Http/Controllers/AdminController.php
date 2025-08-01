<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index()
    {
        return view('admin.pembayaran');
    }

    public function pembayaranData()
    {
        $payments = DB::table('payments')
            ->join('users', 'payments.user_id', '=', 'users.id')
            ->join('packets', 'payments.packet_id', '=', 'packets.id')
            ->select(
                'payments.user_id',
                'payments.packet_id',
                'users.name as student_name',
                'users.phone_number as student_phone',
                'users.parents_phone_number as parent_phone',
                'packets.title as pesanan',
                'payments.status'
            );

        return DataTables::of($payments)
            ->addColumn('parent_name', function ($payment) {
                return 'Ayah ' . explode(' ', $payment->student_name)[0];
            })
            ->toJson();
    }

    // New method to verify a payment and mark it as "Sudah Bayar"
    public function verifyPayment(Request $request)
    {
        $validated = $request->validate([
            'user_id'   => ['required', 'integer', 'exists:users,id'],
            'packet_id' => ['required', 'integer', 'exists:packets,id'],
        ]);

        // Update payment status
        DB::table('payments')
            ->where('user_id', $validated['user_id'])
            ->where('packet_id', $validated['packet_id'])
            ->update(['status' => 'Sudah Bayar']);

        // Create packet-user relationship if it doesn't exist
        DB::table('packet_user')->insertOrIgnore([
            'user_id' => $validated['user_id'],
            'packet_id' => $validated['packet_id']
        ]);

        return response()->json(['success' => true]);
    }

    public function dataSiswa()
    {
        return view('admin.data-siswa');
    }

    public function dataSiswaData()
    {
        $students = User::where('role', 'student')->with('packets');

        return DataTables::of($students)
            ->addColumn('packets', function ($student) {
                if ($student->packets->isEmpty()) {
                    return '-';
                }
                return $student->packets->pluck('code')->implode(', ');
            })
            ->toJson();
    }

    public function pengaturan()
    {
        return view('admin.pengaturan');
    }

    public function updatePengaturan(Request $request) {}
}
