<?php

namespace Database\Seeders;

use App\Models\Gedung;
use Illuminate\Database\Seeder;

class GedungSeeder extends Seeder
{
    /**
     * Gedung / venue di Kabupaten Deli Serdang. Koordinat di sekitar kompleks
     * pemerintahan Lubuk Pakam (Jl. Negara) + P3UD Tanjung Morawa. `maps_url`
     * memakai query nama agar navigasi Google Maps tetap akurat.
     * is_lokasi_acara: Alun-Alun, Graha Bhineka, DPRD, IKM Hall (Kantor Bupati: tidak).
     */
    public function run(): void
    {
        $rows = [
            [
                'nama'            => 'IKM Hall (P3UD Deli Serdang)',
                'alamat'          => 'Komplek P3UD Deli Serdang, Jl. Medan – Tebing Tinggi, Tanjung Morawa',
                'lat'             => 3.5592000,
                'lng'             => 98.7892000,
                'maps_url'        => 'https://maps.google.com/?q=P3UD+Deli+Serdang+Tanjung+Morawa',
                'image'           => 'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=600&q=80',
                'is_lokasi_acara' => true,
                'urut'            => 1,
            ],
            [
                'nama'            => 'Graha Bhineka Perkasa Jaya',
                'alamat'          => 'Jl. Negara, Lubuk Pakam, Kabupaten Deli Serdang',
                'lat'             => 3.5048000,
                'lng'             => 98.8752000,
                'maps_url'        => 'https://maps.google.com/?q=Graha+Bhinneka+Perkasa+Jaya+Deli+Serdang',
                'image'           => 'https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=600&q=80',
                'is_lokasi_acara' => true,
                'urut'            => 2,
            ],
            [
                'nama'            => 'DPRD Kabupaten Deli Serdang',
                'alamat'          => 'Jl. Negara No.3, Lubuk Pakam, Kabupaten Deli Serdang',
                'lat'             => 3.5520000,
                'lng'             => 98.8700000,
                'maps_url'        => 'https://maps.google.com/?q=DPRD+Kabupaten+Deli+Serdang',
                'image'           => 'https://images.unsplash.com/photo-1582719508461-905c673771fd?auto=format&fit=crop&w=600&q=80',
                'is_lokasi_acara' => true,
                'urut'            => 3,
            ],
            [
                'nama'            => 'Kantor Bupati Deli Serdang',
                'alamat'          => 'Jl. Negara No.1, Petapahan, Lubuk Pakam, Kabupaten Deli Serdang',
                'lat'             => 3.5507070,
                'lng'             => 98.8664410,
                'maps_url'        => 'https://maps.google.com/?q=Kantor+Bupati+Deli+Serdang',
                'image'           => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=600&q=80',
                'is_lokasi_acara' => false,
                'urut'            => 4,
            ],
            [
                'nama'            => 'Alun-Alun Kabupaten Deli Serdang',
                'alamat'          => 'Jl. Tengku Fachrudin, Tanjung Garbus I, Lubuk Pakam (depan Kantor Bupati)',
                'lat'             => 3.5055000,
                'lng'             => 98.8741000,
                'maps_url'        => 'https://maps.google.com/?q=Alun-Alun+Kabupaten+Deli+Serdang',
                'image'           => 'https://images.unsplash.com/photo-1502082553048-f009c37129b9?auto=format&fit=crop&w=600&q=80',
                'is_lokasi_acara' => true,
                'urut'            => 5,
            ],
        ];

        foreach ($rows as $row) {
            Gedung::updateOrCreate(['nama' => $row['nama']], array_merge(['is_active' => true], $row));
        }
    }
}
