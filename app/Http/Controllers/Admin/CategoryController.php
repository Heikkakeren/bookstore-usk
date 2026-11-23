<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

/**
 * Controller untuk mengelola CRUD Kategori Buku (Admin).
 * Sesuai Standar Kompetensi USK: Add/Update/Delete Kategori Buku.
 */
class CategoryController extends Controller
{
    /**
     * Menampilkan daftar seluruh kategori buku dengan paginasi.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        $categories = Category::withCount('books')->latest()->paginate(10);

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Menampilkan formulir tambah kategori buku baru.
     *
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        return view('admin.categories.create');
    }

    /**
     * Menyimpan data kategori buku baru ke basis data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name'],
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.unique' => 'Kategori dengan nama tersebut sudah ada.',
            'name.max' => 'Nama kategori maksimal 255 karakter.',
        ]);

        Category::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    /**
     * Menampilkan formulir edit kategori buku.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\View\View
     */
    public function edit(Category $category): View
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Memperbarui data kategori buku di basis data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name,' . $category->id],
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.unique' => 'Kategori dengan nama tersebut sudah ada.',
            'name.max' => 'Nama kategori maksimal 255 karakter.',
        ]);

        $category->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * Menghapus data kategori buku jika tidak memiliki relasi buku aktif.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Category $category): RedirectResponse
    {
        if ($category->books()->count() > 0) {
            return redirect()->route('admin.categories.index')->with('error', 'Kategori tidak dapat dihapus karena masih memiliki buku.');
        }

        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
