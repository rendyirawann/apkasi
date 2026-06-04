<?php

namespace App\Http\Controllers\Frontend\Peta;

use App\Http\Controllers\Controller;
use App\Models\Place;
use Illuminate\Support\Facades\Schema;

class PetaHotelController extends Controller
{
    /**
     * Halaman "Peta Lokasi & Hotel".
     * Ambil data dari tabel places kalau sudah ada; kalau belum, pakai contoh
     * supaya halaman tetap jalan sebelum migrate/seed.
     */
    public function index()
    {
        $places = $this->resolvePlaces();

        // Titik tengah peta (rata-rata koordinat). Default: kompleks Pemkab Deli Serdang.
        $center = [98.8645, 3.5503]; // [lng, lat]
        if ($places->count()) {
            $center = [
                round($places->avg('lng'), 6),
                round($places->avg('lat'), 6),
            ];
        }

        return view('frontend.peta.hotel.peta-hotel', [
            'places'       => $places->values(),
            'center'       => $center,
            'mapboxToken'  => config('services.mapbox.token'),
        ]);
    }

    /**
     * Endpoint JSON untuk dikonsumsi SPA React (frontend/) lewat GET /api/places.
     * Mengembalikan daftar tempat + titik tengah peta.
     */
    public function json()
    {
        $places = $this->resolvePlaces();

        $center = [98.8645, 3.5503]; // [lng, lat] default: kompleks Pemkab Deli Serdang
        if ($places->count()) {
            $center = [
                round($places->avg('lng'), 6),
                round($places->avg('lat'), 6),
            ];
        }

        return response()->json([
            'places' => $places->values(),
            'center' => $center,
        ]);
    }

    /**
     * Sumber data: DB dulu, fallback ke contoh.
     */
    private function resolvePlaces()
    {
        if (Schema::hasTable('places')) {
            $rows = Place::query()
                ->where('is_active', true)
                ->whereIn('category', ['venue', 'hotel'])
                ->orderByRaw("FIELD(category,'venue','hotel')")
                ->orderBy('sort')
                ->get([
                    'id', 'category', 'name', 'address', 'description',
                    'phone', 'price_range', 'rating', 'image', 'lat', 'lng', 'maps_url',
                ]);

            if ($rows->count()) {
                return $rows;
            }
        }

        // Fallback contoh (koordinat perkiraan, ganti dengan data asli).
        return collect($this->sampleData())->map(function ($p, $i) {
            return (object) array_merge(['id' => $i + 1], $p);
        });
    }

    private function sampleData(): array
    {
        return [
            [
                'category' => 'venue', 'name' => 'Graha Bhineka',
                'address' => 'Kompleks Pemkab Deli Serdang, Lubuk Pakam',
                'description' => 'Welcome Dinner & Malam Grand Final POI 2026',
                'phone' => null, 'price_range' => null, 'rating' => null,
                'image' => null, 'lat' => 3.5511, 'lng' => 98.8650, 'maps_url' => null,
            ],
            [
                'category' => 'venue', 'name' => 'IKM Hall',
                'address' => 'Kompleks Pemkab Deli Serdang, Lubuk Pakam',
                'description' => 'Dialog Otonomi, Women Program & FORBISDA',
                'phone' => null, 'price_range' => null, 'rating' => null,
                'image' => null, 'lat' => 3.5498, 'lng' => 98.8639, 'maps_url' => null,
            ],
            [
                'category' => 'venue', 'name' => 'Alun-Alun Deli Serdang',
                'address' => 'Lubuk Pakam, Deli Serdang',
                'description' => 'Start & Finish Fun Walk 2026',
                'phone' => null, 'price_range' => null, 'rating' => null,
                'image' => null, 'lat' => 3.5505, 'lng' => 98.8662, 'maps_url' => null,
            ],
            [
                'category' => 'hotel', 'name' => 'Hotel Contoh 1 (ganti)',
                'address' => 'Jl. contoh, Lubuk Pakam',
                'description' => null, 'phone' => '0812-xxxx',
                'price_range' => 'Rp350rb', 'rating' => 4.3,
                'image' => null, 'lat' => 3.5560, 'lng' => 98.8710, 'maps_url' => null,
            ],
            [
                'category' => 'hotel', 'name' => 'Hotel Contoh 2 (ganti)',
                'address' => 'Jl. contoh, dekat Kualanamu',
                'description' => null, 'phone' => '0813-xxxx',
                'price_range' => 'Rp500rb', 'rating' => 4.5,
                'image' => null, 'lat' => 3.6300, 'lng' => 98.8800, 'maps_url' => null,
            ],
        ];
    }
}
