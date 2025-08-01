<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //
    public function register()
    {
        return view('auth.register');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone_number' => 'required|regex:/^[0-9+\-]+$/|max:15',
            'parents_phone_number' => 'required|regex:/^[0-9+\-]+$/|max:15',
            'school' => 'required|string|max:255',
            'grade' => 'required|string|max:2',
            'password' => 'required|string|min:8',
        ]);
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'parents_phone_number' => $request->parents_phone_number,
            'school' => $request->school,
            'grade' => $request->grade,
            'password' => Hash::make($request->password),
            'role' => 'student', // Set default role as student
        ]);
        
        // Auto-login after registration
        Auth::login($user);
        
        return redirect('/')->with('success', 'Akun berhasil dibuat!');
    }
    
    public function login()
    {
        return view('auth.login');
    }
    
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'phone_number' => 'required|regex:/^[0-9+\-]+$/|max:15',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            if (Auth::user()->role == 'admin') {
                return redirect()->route('admin.pembayaran');
            }
            
            return redirect()->intended('/');
        }
        
        return back()->withErrors([
            'phone_number' => 'Nomor HP atau password yang dimasukkan tidak valid.',
        ])->onlyInput('phone_number');
    }
    
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }
}
