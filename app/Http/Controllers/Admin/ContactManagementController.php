<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Controller untuk mengelola pesan masuk dari pelanggan/pengunjung (Admin).
 * Sesuai Standar Kompetensi USK: Contact to Admin (Monitoring pesan di Admin).
 */
class ContactManagementController extends Controller
{
    /**
     * Menampilkan daftar seluruh pesan masuk dari pelanggan/pengunjung.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        $contacts = Contact::with('user')->latest()->paginate(15);

        return view('admin.messages.index', compact('contacts'));
    }

    /**
     * Menampilkan detail isi pesan dan menandai pesan sudah dibaca.
     *
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\View\View
     */
    public function show(Contact $contact): View
    {
        if (! $contact->is_read) {
            $contact->update(['is_read' => true]);
        }

        return view('admin.contacts.show', compact('contact'));
    }

    /**
     * Menghapus pesan masuk dari basis data.
     *
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Contact $contact): RedirectResponse
    {
        $contact->delete();

        return redirect()->route('admin.contacts.index')->with('success', 'Pesan berhasil dihapus.');
    }
}
