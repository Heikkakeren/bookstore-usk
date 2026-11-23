<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Stock;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

/**
 * Controller untuk mengelola alur Checkout dan Pembayaran Pelanggan (User).
 * Sesuai Standar Kompetensi USK:
 * - Add to Cart Buku (Order memasukkan buku ke dalam keranjang)
 * - Payment at Delivery (Pembayaran buku sebelum buku dikirim)
 */
class CheckoutController extends Controller
{
    /**
     * Menampilkan halaman formulir checkout beserta ringkasan keranjang belanja.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index(): View|RedirectResponse
    {
        $cartItems = Cart::with(['book.category', 'book.stock'])
            ->where('user_id', auth()->id())
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja Anda masih kosong.');
        }

        // Cek validasi ketersediaan stok fisik di gudang sebelum checkout
        foreach ($cartItems as $item) {
            $stock = $item->book->stock?->quantity ?? 0;
            if ($stock <= 0) {
                return redirect()->route('cart.index')->with('error', "Buku '{$item->book->title}' dalam keranjang Anda telah habis diborong pembeli lain. Silakan sesuaikan keranjang belanja Anda.");
            }
            if ($stock < $item->quantity) {
                return redirect()->route('cart.index')->with('error', "Stok buku '{$item->book->title}' tersisa hanya {$stock} pcs. Silakan kurangi jumlah pesanan Anda di keranjang.");
            }
        }

        $totalPrice = $cartItems->sum(fn (Cart $item) => $item->quantity * $item->book->price);
        $user = auth()->user();

        return view('checkout.index', compact('cartItems', 'totalPrice', 'user'));
    }

    /**
     * Memproses transaksi pemesanan dengan Payment at Delivery (pembayaran sebelum pengiriman),
     * memotong stok secara atomik melalui DB::transaction, dan mengosongkan keranjang belanja.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function process(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'shipping_address' => ['required', 'string', 'max:500'],
            'payment_method' => ['required', 'in:transfer,qris,gopay'],
        ], [
            'shipping_address.required' => 'Alamat pengiriman buku wajib diisi.',
            'payment_method.required' => 'Metode pembayaran wajib dipilih.',
            'payment_method.in' => 'Metode pembayaran tidak valid. Silakan pilih Transfer Bank, QRIS, atau GoPay.',
        ]);

        $cartItems = Cart::with(['book.stock'])
            ->where('user_id', auth()->id())
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja Anda kosong.');
        }

        // Validasi ketersediaan stok fisik di tabel stocks
        foreach ($cartItems as $cartItem) {
            $availableStock = $cartItem->book->stock?->quantity ?? 0;
            if ($availableStock <= 0) {
                return redirect()->route('cart.index')->with('error', "Pesanan gagal! Buku '{$cartItem->book->title}' sudah habis diborong pembeli lain.");
            }
            if ($availableStock < $cartItem->quantity) {
                return redirect()->route('cart.index')->with('error', "Stok buku '{$cartItem->book->title}' tidak mencukupi. Tersisa: {$availableStock} pcs.");
            }
        }

        $totalPrice = $cartItems->sum(fn (Cart $item) => $item->quantity * $item->book->price);
        $orderCode = 'ORD-' . strtoupper(Str::random(10));

        try {
            DB::transaction(function () use ($cartItems, $totalPrice, $orderCode, $validated) {
                // Lock row di level DB untuk mencegah race condition
                foreach ($cartItems as $cartItem) {
                    $stock = Stock::where('book_id', $cartItem->book_id)->lockForUpdate()->first();
                    if (! $stock || $stock->quantity < $cartItem->quantity) {
                        throw new \Exception("Maaf, buku '{$cartItem->book->title}' baru saja habis dibeli pelanggan lain!");
                    }
                }

                $order = Order::create([
                    'user_id' => auth()->id(),
                    'order_code' => $orderCode,
                    'total_price' => $totalPrice,
                    'payment_method' => $validated['payment_method'],
                    'status' => 'pending',
                    'shipping_address' => $validated['shipping_address'],
                ]);

                foreach ($cartItems as $cartItem) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'book_id' => $cartItem->book_id,
                        'quantity' => $cartItem->quantity,
                        'price' => $cartItem->book->price,
                    ]);

                    // Kurangi stok fisik di tabel stocks secara aman
                    Stock::where('book_id', $cartItem->book_id)->decrement('quantity', $cartItem->quantity);
                }

                // Kosongkan keranjang belanja milik user
                Cart::where('user_id', auth()->id())->delete();
            });

            return redirect()->route('orders.history')->with('success', "Pesanan Anda berhasil dibuat (Kode: {$orderCode})! Silakan selesaikan pembayaran agar pesanan segera dikirim.");
        } catch (\Exception $e) {
            return redirect()->route('cart.index')->with('error', 'Gagal memproses pesanan: ' . $e->getMessage());
        }
    }
}
