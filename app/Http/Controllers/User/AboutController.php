<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

/**
 * Controller untuk menampilkan informasi About Us (Tentang Kami).
 * Sesuai Standar Kompetensi USK: Melihat detail halaman About Us.
 */
class AboutController extends Controller
{
    /**
     * Menampilkan halaman About Us lengkap dengan profil dan visi misi toko buku.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        return view('about');
    }
}
