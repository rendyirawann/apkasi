<?php

namespace Database\Seeders;

use App\Models\Place;
use Illuminate\Database\Seeder;

class PlaceSeeder extends Seeder
{
    /**
     * Lokasi acara (venue) untuk halaman Peta.
     * Hotel -> tabel `hotels` (HotelSeeder). Destinasi wisata -> tabel `destinasi_wisata` (DestinasiWisataSeeder).
     * Jadi `places` kini hanya menyimpan venue.
     */
    public function run(): void
    {
        // Bersihkan kategori yang sudah pindah ke tabel khusus (idempotent).
        Place::whereIn('category', ['hotel', 'wisata'])->delete();

        $data = [
            [
                'category'    => 'venue',
                'name'        => 'Graha Bhineka Deli Serdang',
                'address'     => 'Kawasan Kantor Bupati Deli Serdang, Lubuk Pakam',
                'description' => 'Welcome Dinner (1 Juli) & Malam Puncak Penobatan Putri Otonomi Indonesia 2026.',
                'image'       => 'https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=600&q=80',
                'lat'         => 3.5048,
                'lng'         => 98.8752,
                'maps_url'    => 'https://maps.google.com/?q=Graha+Bhinneka+Deli+Serdang',
                'sort'        => 1,
            ],
            [
                'category'    => 'venue',
                'name'        => 'IKM Hall (P3UD Deli Serdang)',
                'address'     => 'Komplek P3UD Deli Serdang, Tanjung Morawa',
                'description' => 'Dialog Otonomi Daerah, Women Program (UMKM & Stunting) & Forum Bisnis Daerah (FORBISDA).',
                'image'       => 'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=600&q=80',
                'lat'         => 3.5592,
                'lng'         => 98.7892,
                'maps_url'    => 'https://maps.google.com/?q=P3UD+Deli+Serdang+Tanjung+Morawa',
                'sort'        => 2,
            ],
            [
                'category'    => 'venue',
                'name'        => 'Alun-Alun Deli Serdang',
                'address'     => 'Jalan Karya Jasa, Lubuk Pakam (Depan Kantor Bupati)',
                'description' => 'Titik Start & Finish Fun Walk, panggung hiburan rakyat, doorprize & penanaman pohon.',
                'image'       => 'https://images.unsplash.com/photo-1502082553048-f009c37129b9?auto=format&fit=crop&w=600&q=80',
                'lat'         => 3.5055,
                'lng'         => 98.8741,
                'maps_url'    => 'https://maps.google.com/?q=Alun-Alun+Kabupaten+Deli+Serdang',
                'sort'        => 3,
            ],
        ];

        foreach ($data as $row) {
            Place::updateOrCreate(['name' => $row['name']], array_merge(['is_active' => true], $row));
        }
    }
}
