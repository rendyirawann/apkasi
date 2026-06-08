<?php

namespace Database\Seeders;

use App\Models\Rental;
use Illuminate\Database\Seeder;

class RentalSeeder extends Seeder
{
    /**
     * Perusahaan rental mobil Deli Serdang/Medan (nama & alamat dari dokumen).
     * CATATAN: jumlah unit per mobil di bawah masih CONTOH (dokumen hanya memberi
     * daftar armada premium & total sementara 272 unit, tanpa rincian per-rental).
     * Silakan sesuaikan via menu admin.
     */
    public function run(): void
    {
        $data = [
            [
                'nama'   => 'PT. Seribu Nusantara Rental',
                'alamat' => 'Jl. Karya Budi No.1A, Pangkalan Masyhur, Kec. Medan Johor, Kota Medan, Sumatera Utara 20143',
                'lat' => 3.5292, 'lng' => 98.6726, 'maps_url' => 'https://maps.google.com/?q=PT.+Seribu+Nusantara+Rental+Medan+Johor',
                'mobil'  => [
                    ['Toyota Innova Reborn', 20], ['Toyota Innova Zenix', 15],
                    ['Toyota Fortuner', 8], ['Toyota Hiace Commuter', 6], ['Toyota Alphard', 2],
                ],
            ],
            [
                'nama'   => 'PT. Sanobar Gunajaya Car Rental',
                'alamat' => 'Jl. Brigjend Katamso No. 222D, Medan',
                'lat' => 3.5618, 'lng' => 98.6905, 'maps_url' => 'https://maps.google.com/?q=Sanobar+Gunajaya+Car+Rental+Brigjend+Katamso+Medan',
                'mobil'  => [
                    ['Toyota Innova Reborn', 25], ['Toyota Fortuner', 10],
                    ['Toyota Alphard', 3], ['Toyota Vellfire', 2], ['Toyota Hiace Premium', 5],
                ],
            ],
            [
                'nama'   => 'PT. Stasiun Rental Mobil',
                'alamat' => 'Jl. Pancasila No. 11A, Batang Kuis, Deli Serdang',
                'lat' => 3.5861, 'lng' => 98.7943, 'maps_url' => 'https://maps.google.com/?q=Stasiun+Rental+Mobil+Batang+Kuis+Deli+Serdang',
                'mobil'  => [
                    ['Toyota Innova Reborn', 30], ['Toyota Innova Zenix', 20],
                    ['Toyota Fortuner', 12], ['Toyota Hiace Commuter', 8], ['Mitsubishi Pajero', 6],
                ],
            ],
        ];

        foreach ($data as $i => $row) {
            $mobil = $row['mobil'];
            unset($row['mobil']);

            $rental = Rental::updateOrCreate(
                ['nama' => $row['nama']],
                array_merge(['is_active' => true, 'urut' => $i + 1], $row)
            );

            $rental->mobil()->delete();
            foreach ($mobil as $j => $m) {
                $rental->mobil()->create(['nama_mobil' => $m[0], 'jumlah_unit' => $m[1], 'urut' => $j + 1]);
            }
        }
    }
}
