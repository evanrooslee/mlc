<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PacketController extends Controller
{
    public function beli_paket()
    {
        return view('beli-paket');
    }

    public function beli_konfirmasi()
    {
        return view('beli-konfirmasi');
    }
}
