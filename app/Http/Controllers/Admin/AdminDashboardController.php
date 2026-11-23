<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Order;
use App\Models\User;
use Illuminate\View\View;

/**
 * Controller untuk mengelola ringkasan statistik dan metrik operasional toko pada Dashboard Admin.
 * Sesuai Standar Kompetensi USK: Control dan Monitoring Keseluruhan Data Buku, User, dan Pesanan.
 */
class AdminDashboardController extends Controller
{
    /**
     * Menampilkan dashboard utama administrator berisi ringkasan metrik buku, kategori, user, dan pesanan terbaru.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        $totalBooks = Book::count();
        $totalCategories = Category::count();
        $totalUsers = User::where('role', 'user')->count();
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $unreadContacts = Contact::where('is_read', false)->count();
        $recentOrders = Order::with('user')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalBooks',
            'totalCategories',
            'totalUsers',
            'totalOrders',
            'pendingOrders',
            'unreadContacts',
            'recentOrders'
        ));
    }
}
