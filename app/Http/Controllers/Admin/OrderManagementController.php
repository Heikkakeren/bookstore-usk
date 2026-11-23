<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controller untuk mengelola daftar pesanan buku dari user (Admin).
 * Sesuai Standar Kompetensi USK: List pesanan buku dari user yang berbeda-beda.
 */
class OrderManagementController extends Controller
{
    /**
     * Menampilkan daftar seluruh pesanan buku dari berbagai pengguna.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        $orders = Order::with('user')->latest()->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Menampilkan rincian detail pesanan dan item buku yang dibeli.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\View\View
     */
    public function show(Order $order): View
    {
        $order->load(['user', 'orderItems.book']);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Memperbarui status pemrosesan pesanan (pending/processing/completed/cancelled).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,processing,completed,cancelled'],
        ], [
            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Status tidak valid.',
        ]);

        $order->update(['status' => $validated['status']]);

        return back()->with('success', 'Status pesanan '.$order->order_code.' berhasil diperbarui menjadi '.ucfirst($validated['status']).'.');
    }
}
