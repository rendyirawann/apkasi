<?php

namespace App\Http\Controllers\Frontend\Peta;

use App\Http\Controllers\Controller;
use App\Models\Gedung;
use App\Models\Hotel;
use App\Models\Kuliner;
use App\Models\DestinasiWisata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class PetaHotelController extends Controller
{
    /**
     * Halaman "Peta Lokasi, Hotel & Wisata".
     * Marker peta dimuat semua; daftar tempat dimuat paginasi via AJAX (lihat list()).
     */
    public function index()
    {
        $items = $this->resolveItems();

        return view('frontend.peta.hotel.peta-hotel', [
            'markers'     => $items->values(),
            'counts'      => $this->counts($items),
            'center'      => $this->center($items),
            'mapboxToken' => config('services.mapbox.token'),
        ]);
    }

    /**
     * Daftar tempat paginasi (AJAX) — 5 per halaman, difilter kategori + pencarian.
     */
    public function list(Request $request)
    {
        $items = $this->resolveItems();

        // Sanitasi input (pengamanan sederhana — cegah nilai liar):
        // - kategori dibatasi whitelist, default 'all'
        // - query pencarian dipangkas & dibatasi panjangnya
        $cat = (string) $request->get('cat', 'all');
        if (! in_array($cat, ['all', 'lokasi', 'gedung', 'hotel', 'wisata', 'kuliner'], true)) {
            $cat = 'all';
        }
        $q = mb_substr(strtolower(trim((string) $request->get('q', ''))), 0, 100);

        $kota = (string) $request->get('kota', 'all');
        if (! in_array($kota, ['all', 'deli_serdang', 'medan'], true)) {
            $kota = 'all';
        }

        $filtered = $this->filterItems($items, $cat, $q, $kota);

        $perPage  = 5;
        $total    = $filtered->count();
        $lastPage = max(1, (int) ceil($total / $perPage));
        $page     = min(max(1, (int) $request->get('page', 1)), $lastPage);
        $data     = $filtered->slice(($page - 1) * $perPage, $perPage)->values();

        return response()->json([
            'data'      => $data,
            'page'      => $page,
            'last_page' => $lastPage,
            'total'     => $total,
            'from'      => $total ? ($page - 1) * $perPage + 1 : 0,
            'to'        => min($page * $perPage, $total),
            'counts'    => $this->counts($items),
        ]);
    }

    /**
     * Endpoint JSON untuk SPA React (frontend/) via GET /api/places.
     */
    public function json()
    {
        // API peta (SPA React): hanya kirim titik yang punya koordinat.
        $items = $this->resolveItems()->filter(fn ($p) => $p->lat !== null && $p->lng !== null);

        return response()->json([
            'places' => $items->values(),
            'center' => $this->center($items),
        ]);
    }

    private function counts($items): array
    {
        return [
            'all'         => $items->count(),
            'lokasi'      => $items->filter(fn ($p) => $p->is_lokasi_acara)->count(), // tag Lokasi Acara (kategori apa pun)
            'gedung'      => $items->where('category', 'venue')->count(),              // kategori Gedung/Venue
            'hotel'       => $items->where('category', 'hotel')->count(),
            'hotel_ds'    => $items->where('category', 'hotel')->where('kota', 'deli_serdang')->count(),
            'hotel_medan' => $items->where('category', 'hotel')->where('kota', 'medan')->count(),
            'wisata'      => $items->where('category', 'wisata')->count(),
            'kuliner'     => $items->where('category', 'kuliner')->count(),
        ];
    }

    private function filterItems($items, string $cat, string $q, string $kota = 'all')
    {
        return $items->filter(function ($p) use ($cat, $q, $kota) {
            $catOk = $cat === 'all'
                || ($cat === 'lokasi' && $p->is_lokasi_acara)
                || ($cat === 'gedung' && $p->category === 'venue')
                || ($cat === 'hotel'  && $p->category === 'hotel')
                || ($cat === 'wisata' && $p->category === 'wisata')
                || ($cat === 'kuliner' && $p->category === 'kuliner');

            // Sub-filter kota khusus tab Hotel (Deli Serdang / Medan); 'all' = gabung.
            $kotaOk = $kota === 'all' || (($p->kota ?? null) === $kota);

            $qOk = $q === ''
                || str_contains(strtolower($p->name), $q)
                || str_contains(strtolower((string) $p->address), $q);

            return $catOk && $kotaOk && $qOk;
        })->values();
    }

    private function center($items): array
    {
        // Hanya hitung pusat peta dari item yang punya koordinat (abaikan yang null).
        $geo = $items->filter(fn ($p) => $p->lat !== null && $p->lng !== null);
        if ($geo->count()) {
            return [round($geo->avg('lng'), 6), round($geo->avg('lat'), 6)];
        }
        return [98.8645, 3.5503];
    }

    /**
     * Gabungan titik: gedung (gedung) + hotel (hotels) + destinasi (destinasi_wisata),
     * dinormalkan ke bentuk seragam. `is_lokasi_acara` menandai item yang masuk tab
     * Lokasi Acara — sumbernya murni dari tag pada data master (tabel `places` tak dipakai).
     */
    private function resolveItems()
    {
        $out = collect();

        if (Schema::hasTable('gedung')) {
            Gedung::query()->where('is_active', true)->orderBy('urut')->orderBy('id')->get()
                ->each(fn ($g) => $out->push((object) [
                    'id' => 'g' . $g->id, 'category' => 'venue', 'is_lokasi_acara' => (bool) $g->is_lokasi_acara,
                    'kota' => null, 'name' => $g->nama, 'address' => $g->alamat, 'description' => null,
                    'rating' => null, 'image' => $g->image_url, 'lat' => $g->lat !== null ? (float) $g->lat : null, 'lng' => $g->lng !== null ? (float) $g->lng : null,
                    'maps_url' => $g->maps_url, 'rooms' => null, 'wa' => null, 'email' => null, 'harga' => null,
                    'cp' => null, 'jarak' => null, 'harga_mulai' => null, 'kamar' => null,
                ]));
        }

        if (Schema::hasTable('hotels')) {
            Hotel::query()->with('kamar')->where('is_active', true)->orderBy('urut')->orderBy('id')->get()
                ->each(fn ($h) => $out->push((object) [
                    'id' => 'h' . $h->id, 'category' => 'hotel', 'is_lokasi_acara' => (bool) $h->is_lokasi_acara,
                    'kota' => $h->kategori, 'name' => $h->nama, 'address' => $h->alamat, 'description' => null,
                    'rating' => $h->rating, 'image' => $h->image_url, 'lat' => $h->lat !== null ? (float) $h->lat : null, 'lng' => $h->lng !== null ? (float) $h->lng : null,
                    'maps_url' => $h->maps_url, 'rooms' => $h->ketersediaan_kamar, 'wa' => $h->contact_wa, 'email' => $h->contact_email, 'harga' => null,
                    'cp' => $h->contact_person, 'jarak' => $h->jarak, 'harga_mulai' => $h->harga_mulai, 'bintang' => $h->bintang,
                    'kamar' => $h->kamar->map(fn ($k) => ['tipe' => $k->tipe, 'harga' => $k->harga])->values(),
                ]));
        }

        if (Schema::hasTable('destinasi_wisata')) {
            DestinasiWisata::query()->where('is_active', true)->orderBy('urut')->orderBy('id')->get()
                ->each(fn ($d) => $out->push((object) [
                    'id' => 'd' . $d->id, 'category' => 'wisata', 'is_lokasi_acara' => (bool) $d->is_lokasi_acara,
                    'kota' => null, 'name' => $d->nama, 'address' => $d->alamat, 'description' => $d->deskripsi,
                    'rating' => $d->rating, 'image' => $d->thumbnail_url, 'lat' => $d->lat !== null ? (float) $d->lat : null, 'lng' => $d->lng !== null ? (float) $d->lng : null,
                    'maps_url' => $d->maps_url, 'rooms' => null, 'wa' => null, 'email' => null, 'harga' => $d->harga_tiket,
                    'cp' => null, 'jarak' => null, 'harga_mulai' => null, 'kamar' => null,
                ]));
        }

        if (Schema::hasTable('kuliner')) {
            Kuliner::query()->where('is_active', true)->orderBy('urut')->orderBy('id')->get()
                ->each(fn ($k) => $out->push((object) [
                    'id' => 'k' . $k->id, 'category' => 'kuliner', 'is_lokasi_acara' => false,
                    'kota' => null, 'name' => $k->nama, 'address' => $k->alamat, 'description' => null,
                    'rating' => $k->rating, 'image' => $k->image_url, 'lat' => $k->lat !== null ? (float) $k->lat : null, 'lng' => $k->lng !== null ? (float) $k->lng : null,
                    'maps_url' => $k->maps_url, 'rooms' => null, 'wa' => null, 'email' => null, 'harga' => null,
                    'cp' => null, 'jarak' => null, 'harga_mulai' => null, 'kamar' => null, 'jenis' => $k->jenis_kuliner, 'halal' => (bool) $k->halal,
                ]));
        }

        return $out;
    }
}
