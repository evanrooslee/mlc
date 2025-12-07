<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Packet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Models\User;

class ProfileController extends Controller
{
    public function view_profile()
    {
        try {
            // Cache popular packets for 30 minutes to improve performance
            $popularPackets = Cache::remember('popular_packets', 1800, function () {
                return Packet::popular()->get();
            });

            // If no packets found, create empty collection
            if ($popularPackets->isEmpty()) {
                Log::info('No popular packets found, displaying empty state');
            }
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Failed to fetch popular packets: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => Auth::id()
            ]);

            // Try to get cached data even if it's stale, or fallback to empty collection
            $popularPackets = Cache::get('popular_packets', collect());
        }

        return view('user.profile', compact('popularPackets'));
    }

    public function view_kelas()
    {
        /** @var User $user */
        $user = Auth::user();

        // Get active packets for the user
        $activePacketIds = $user->packets()->pluck('packets.id');
        $activePackets = Packet::whereIn('id', $activePacketIds)->get();

        // Get other packets (not active) with pagination
        $otherPackets = Packet::whereNotIn('id', $activePacketIds)->paginate(5);

        return view('user.kelas', compact('activePackets', 'otherPackets'));
    }

    public function update_profile(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone_number' => 'required|regex:/^[0-9+\-]+$/' . '|max:15',
            'parent_name' => 'required|string|max:255',
            'parents_phone_number' => 'required|regex:/^[0-9+\-]+$/' . '|max:15',
            'school' => 'required|string|max:255',
            'grade' => 'required|string|max:2',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone_number = $validated['phone_number'];
        $user->parent_name = $validated['parent_name'];
        $user->parents_phone_number = $validated['parents_phone_number'];
        $user->school = $validated['school'];
        $user->grade = $validated['grade'];
        $user->save();

        return redirect()->route('user.profile')->with('success', 'Profile updated successfully!');
    }
}
