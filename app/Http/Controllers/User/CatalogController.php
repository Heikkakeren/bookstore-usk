<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controller untuk menampilkan katalog dan detail buku bagi pengunjung/user.
 * Sesuai Standar Kompetensi USK: Pencarian Buku & Tampilan Katalog Buku.
 */
class CatalogController extends Controller
{
    /**
     * Menampilkan halaman beranda (landing page) toko buku.
     *
     * @return \Illuminate\View\View
     */
    public function landing(): View
    {
        $categories = Category::withCount('books')->orderBy('name')->get();

        $featuredBooks = Book::with(['category', 'stock'])
            ->latest()
            ->take(6)
            ->get();

        $totalBooks = Book::count();
        $totalCategories = Category::count();

        return view('landing', compact('featuredBooks', 'categories', 'totalBooks', 'totalCategories'));
    }

    /**
     * Menampilkan katalog buku dengan fitur pencarian dan filter kategori.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request): View
    {
        $categories = Category::orderBy('name')->get();
        $query = Book::with(['category', 'stock']);

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                    ->orWhere('author', 'like', "%{$searchTerm}%")
                    ->orWhere('publisher', 'like', "%{$searchTerm}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $books = $query->latest()->paginate(10)->withQueryString();

        return view('home', compact('books', 'categories'));
    }

    /**
     * Menampilkan rincian informasi dan spesifikasi detail buku.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\View\View
     */
    public function show(Book $book): View
    {
        $book->load(['category', 'stock']);
        $relatedBooks = Book::with(['category', 'stock'])
            ->where('category_id', $book->category_id)
            ->where('id', '!=', $book->id)
            ->whereHas('stock', function ($q) {
                $q->where('quantity', '>', 0);
            })
            ->take(4)
            ->get();

        return view('user.book-detail', compact('book', 'relatedBooks'));
    }
}
