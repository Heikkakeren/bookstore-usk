<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\View\View;

/**
 * Controller untuk melihat riwayat pesanan bagi pengguna (User).
 * Sesuai Standar Kompetensi USK: Payment at Delivery & Riwayat Pesanan.
 */
class OrderHistoryController extends Controller
{
    /**
     * Menampilkan daftar seluruh riwayat pesanan buku milik pengguna aktif.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        $orders = Order::with('orderItems.book')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('orders.history', compact('orders'));
    }

    /**
     * Menampilkan rincian faktur pembelian dan status pesanan tertentu.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\View\View
     */
    public function show(Order $order): View
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load(['orderItems.book', 'user']);

        return view('user.order-detail', compact('order'));
    }
}
