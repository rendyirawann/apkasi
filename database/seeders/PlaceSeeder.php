<?php

namespace Database\Seeders;

use App\Models\Place;
use Illuminate\Database\Seeder;

class PlaceSeeder extends Seeder
{
    /**
     * Data lokasi acara (venue) & hotel rekomendasi untuk halaman "Peta Lokasi & Hotel".
     * Sumber data: halaman Panduan (HomeController@guide).
     *
     * - Koordinat VENUE diambil dari map_embed Google Maps (cukup akurat).
     * - Koordinat HOTEL masih PERKIRAAN (area Kualanamu/Batang Kuis/Beringin).
     *   Pin bisa disempurnakan kapan saja; tombol "Rute" tetap akurat karena
     *   memakai maps_url (query Google Maps asli).
     */
    public function run(): void
    {
        $data = [
            // ===== Lokasi Acara / Venue =====
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

            // ===== Hotel Rekomendasi (sekitar Kualanamu) =====
            [
                'category'    => 'hotel',
                'name'        => 'Prime Plaza Hotel Kualanamu',
                'address'     => 'Jl. Arteri Kualanamu, Tumpatan Nibung, Batang Kuis',
                'description' => '± 10 menit dari Bandara Kualanamu • 20 menit ke Lubuk Pakam.',
                'phone'       => '+62 61-8881-2888',
                'price_range' => 'Bintang 4',
                'rating'      => 4.5,
                'image'       => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=600&q=80',
                'lat'         => 3.6210,
                'lng'         => 98.8480,
                'maps_url'    => 'https://maps.google.com/?q=Prime+Plaza+Hotel+Kualanamu',
                'sort'        => 1,
            ],
            [
                'category'    => 'hotel',
                'name'        => "Thong's Inn Transit Hotel Kualanamu",
                'address'     => 'Jl. Pasar V Kebun Kelapa, Kualanamu, Beringin',
                'description' => '± 8 menit dari Bandara Kualanamu • 15 menit ke Lubuk Pakam. Garden Resort.',
                'phone'       => '+62 61-7956-888',
                'price_range' => 'Bintang 3',
                'rating'      => 4.3,
                'image'       => 'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?auto=format&fit=crop&w=600&q=80',
                'lat'         => 3.6360,
                'lng'         => 98.8790,
                'maps_url'    => "https://maps.google.com/?q=Thongs+Inn+Kualanamu",
                'sort'        => 2,
            ],
            [
                'category'    => 'hotel',
                'name'        => 'Wings Hotel Kualanamu',
                'address'     => 'Jl. Arteri Kualanamu No.9, Komplek Hub Kualanamu',
                'description' => '± 10 menit dari Bandara Kualanamu • 25 menit ke IKM Hall.',
                'phone'       => '+62 61-7956-999',
                'price_range' => 'Bintang 3',
                'rating'      => 4.2,
                'image'       => 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=600&q=80',
                'lat'         => 3.6250,
                'lng'         => 98.8520,
                'maps_url'    => 'https://maps.google.com/?q=Wings+Hotel+Kualanamu',
                'sort'        => 3,
            ],
            [
                'category'    => 'hotel',
                'name'        => "d'Prima Hotel Kualanamu",
                'address'     => 'Stasiun KA Bandara Kualanamu Lantai 2, Beringin',
                'description' => 'Langsung di dalam area Bandara Kualanamu • 25 menit ke Lubuk Pakam.',
                'phone'       => '+62 61-8881-2299',
                'price_range' => 'Bintang 2',
                'rating'      => 4.0,
                'image'       => 'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?auto=format&fit=crop&w=600&q=80',
                'lat'         => 3.6425,
                'lng'         => 98.8855,
                'maps_url'    => "https://maps.google.com/?q=dPrima+Hotel+Kualanamu",
                'sort'        => 4,
            ],
        ];

        foreach ($data as $row) {
            Place::updateOrCreate(
                ['name' => $row['name']],
                array_merge(['is_active' => true], $row)
            );
        }
    }
}
