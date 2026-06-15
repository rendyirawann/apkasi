<?php

namespace Database\Seeders;

use App\Models\Kuliner;
use Illuminate\Database\Seeder;

class KulinerSeeder extends Seeder
{
    /**
     * Daftar kuliner (rumah makan / restoran) di Medan & Kabupaten Deli Serdang.
     * lat/lng + rating + alamat: hasil riset web + verifikasi (sebagian perkiraan area; maps_url query tetap akurat).
     * updateOrCreate by nama (tidak menghapus data lain) supaya aman di-seed ulang.
     */
    public function run(): void
    {
        $data = [
            [
                'nama' => 'RM Sehati', 'jenis_kuliner' => 'Nusantara', 'rating' => 4.7,
                'alamat' => 'Jl. Tengku Amir Hamzah No.88, Helvetia Timur, Kec. Medan Helvetia, Kota Medan, Sumatera Utara 20114',
                'lat' => 3.6095900, 'lng' => 98.6580610,
            ],
            [
                'nama' => 'RM Sempurna', 'jenis_kuliner' => 'Nusantara', 'rating' => null,
                'alamat' => 'Jl. Jamin Ginting No.82, Bandar Baru, Kec. Sibolangit, Kab. Deli Serdang, Sumatera Utara 20356',
                'lat' => 3.2715000, 'lng' => 98.5460000, // perkiraan area Bandar Baru, Sibolangit
            ],
            [
                'nama' => 'Garuda Hillpark', 'jenis_kuliner' => 'Nusantara', 'rating' => 3.9,
                'alamat' => 'Greenhill City, Jl. Jamin Ginting KM.45, Sikeben, Kec. Sibolangit, Kab. Deli Serdang, Sumatera Utara 20357',
                'lat' => 3.2834630, 'lng' => 98.5565780,
            ],
            [
                'nama' => 'Pondok Rame', 'jenis_kuliner' => 'Nusantara', 'rating' => null,
                'alamat' => 'Jl. Medan - Lubuk Pakam No.1 (depan Kantor Bupati), Tanjung Garbus I, Kec. Lubuk Pakam, Kab. Deli Serdang, Sumatera Utara 20512',
                'lat' => 3.5508000, 'lng' => 98.8664000, // perkiraan (depan Kantor Bupati Deli Serdang)
            ],
            [
                'nama' => 'RM Padang Raya', 'jenis_kuliner' => 'Nusantara', 'rating' => 4.4,
                'alamat' => 'Jl. Jenderal Besar A.H. Nasution No.84, Pangkalan Masyhur, Kec. Medan Johor, Kota Medan, Sumatera Utara 20146',
                'lat' => 3.5406160, 'lng' => 98.6695400,
            ],
        ];

        foreach ($data as $i => $row) {
            $row['maps_url'] = 'https://www.google.com/maps/search/?api=1&query=' . urlencode($row['nama'] . ', Sumatera Utara');
            Kuliner::updateOrCreate(
                ['nama' => $row['nama']],
                array_merge(['is_active' => true, 'halal' => true, 'urut' => $i + 1], $row)
            );
        }
    }
}
