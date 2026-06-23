<?php

namespace Database\Seeders;

use App\Models\Rental;
use Illuminate\Database\Seeder;

class RentalSeeder extends Seeder
{
    /**
     * Daftar rental mobil (dokumen: "Daftar Rental Mobil di Sumatera Utara 2026").
     * - kontak_wa + alamat dari dokumen. Dokumen TIDAK mencantumkan jumlah unit -> jumlah_unit = null.
     * - lat/lng: hasil riset web + verifikasi (sebagian perkiraan); maps_url query tetap akurat utk Rute.
     * AUTHORITATIVE: rental yang tidak ada di daftar ini akan dihapus (re-seed = reset ke data dokumen).
     */
    public function run(): void
    {
        $data = [
            [
                'nama' => 'Seribu Nusantara Rental',
                'alamat' => 'Jl. Karya Budi No.1A, Kel. Pangkalan Masyhur, Kec. Medan Johor, Kota Medan, Sumatera Utara',
                'kontak_wa' => '081267979797', 'lat' => 3.5378143, 'lng' => 98.6696443,
                'mobil' => [
                    'All New Alphard Gen 4', 'Alphard Facelift Gen 3', 'Hiace Premio VIP Luxury', 'Hiace Premio Std',
                    'Hiace Commuter', 'Fortuner / Pajero Sport', 'Zenix Q Capt Seat', 'Zenix G', 'Innova Reborn', 'All New Avanza',
                ],
            ],
            [
                'nama' => 'PT. Pesona Nusantara Rentcar',
                'alamat' => 'Jl. Perwira Utama No.19, Kel. Lalang, Sunggal, Kab. Deli Serdang, Sumatera Utara',
                'kontak_wa' => '082291338205', 'lat' => 3.5754, 'lng' => 98.6143,
                'mobil' => ['Hiace Commuter', 'Fortuner / Pajero Sport', 'Zenix G', 'Innova Reborn', 'Xpander', 'All New Avanza'],
            ],
            [
                'nama' => 'PT. Sanobar Gunajaya',
                'alamat' => 'Jl. Brigjend Katamso No.222, Kel. Kampung Baru, Kec. Medan Maimun, Kota Medan, Sumatera Utara',
                'kontak_wa' => '085260023004', 'lat' => 3.5610486, 'lng' => 98.689215,
                'mobil' => ['Land Cruiser', 'All New Alphard Gen 4', 'Alphard Gen 3 Facelift', 'Zenix G', 'Innova Reborn'],
            ],
            [
                'nama' => 'PT. Sekawan Mandiri Grup',
                'alamat' => 'Jl. Bakaran Batu, Kel. Desa Tumpatan, Kec. Beringin, Kab. Deli Serdang, Sumatera Utara',
                'kontak_wa' => '085274492682', 'lat' => 3.6287, 'lng' => 98.8694,
                'mobil' => ['Innova Reborn', 'Xpander', 'All New Avanza'],
            ],
            [
                'nama' => 'CV Bosque Rent Car',
                'alamat' => 'Jln Medan - Tebingtinggi, Kab. Deli Serdang, Sumatera Utara',
                'kontak_wa' => '081370979431', 'lat' => 3.5504, 'lng' => 98.8645,
                'mobil' => ['Innova Reborn', 'Xpander', 'All New Avanza'],
            ],
            [
                'nama' => 'PT Naga Hitam Rentcar',
                'alamat' => 'Jl. Brigjend Katamso Gg. Kenangan No.46, Kp. Baru, Kec. Medan Maimun, Kota Medan, Sumatera Utara 20158',
                'deskripsi' => 'www.nagahitamrentcar.co.id',
                'logo' => 'logos/nagahitam.png',
                'kontak_wa' => null, // kontak via Contact Person (3 CP) di bawah
                'lat' => 3.5625, 'lng' => 98.6884, // perkiraan ruas Brigjend Katamso, Kampung Baru, Medan Maimun (titik tepat bisa diatur di admin)
                'mobil' => ['City Car', 'All New Innova Zenix', 'Fortuner / Pajero Sport', 'Toyota Hiace', 'Sedan Premium (Mercedes-Benz)'],
                'kontak' => [
                    ['Indra', '081370631286'],
                    ['Moses', '081368048363'],
                    ['Dimas', '085765499827'],
                ],
            ],
        ];

        foreach ($data as $i => $row) {
            $mobil  = $row['mobil'];
            $kontak = $row['kontak'] ?? [];
            unset($row['mobil'], $row['kontak']);
            $row['maps_url'] = 'https://www.google.com/maps/search/?api=1&query=' . urlencode($row['nama'] . ', Sumatera Utara');

            $rental = Rental::updateOrCreate(
                ['nama' => $row['nama']],
                array_merge(['is_active' => true, 'urut' => $i + 1], $row)
            );

            $rental->mobil()->delete();
            foreach ($mobil as $j => $m) {
                // Dokumen tanpa jumlah unit -> jumlah_unit null.
                $rental->mobil()->create(['nama_mobil' => $m, 'jumlah_unit' => null, 'urut' => $j + 1]);
            }

            $rental->kontak()->delete();
            foreach ($kontak as $k => [$nama, $hp]) {
                $rental->kontak()->create(['nama' => $nama, 'no_hp' => $hp, 'urut' => $k + 1]);
            }
        }

        // Authoritative: hapus rental lama yang tidak ada di daftar dokumen (mis. "PT. Stasiun Rental Mobil").
        Rental::whereNotIn('nama', array_column($data, 'nama'))->delete();
    }
}
