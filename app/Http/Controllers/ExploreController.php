<?php

namespace App\Http\Controllers;

use App\Models\Pin; // Pastikan Model Pin sudah ada
use Illuminate\Http\Request;

class ExploreController extends Controller
{
    public function index()
    {
        // Mengambil semua pin dari database tanpa filter user_id
        // with('user') digunakan agar kita bisa menampilkan nama pembuatnya
        $pins = Pin::with('user')->latest()->paginate(15);

        return view('explore', compact('pins'));
    }
}