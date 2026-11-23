<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\View\View;

/**
 * Controller untuk mengelola daftar pengguna terdaftar (Admin).
 * Sesuai Standar Kompetensi USK: List User yang sudah terdaftar.
 */
class UserManagementController extends Controller
{
    /**
     * Menampilkan daftar seluruh user/pelanggan yang sudah terdaftar.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        $users = User::where('role', 'user')->latest()->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Menampilkan detail riwayat dan profil user tertentu.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\View\View
     */
    public function show(User $user): View
    {
        $user->load('orders');

        return view('admin.users.show', compact('user'));
    }
}
