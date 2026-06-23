<?php

namespace Database\Seeders;

use App\Models\Rental;
use Illuminate\Database\Seeder;

class RentalNagaHitamSeeder extends Seeder
{
    /**
     * Seeder AMAN untuk deploy di server: HANYA menambah PT Naga Hitam Rentcar bila belum ada.
     * - TIDAK menimpa rental lain, TIDAK menghapus apa pun (berbeda dari RentalSeeder yang authoritative).
     * - Idempotent: jika PT Naga Hitam sudah ada, seeder dilewati (perubahan admin tetap aman).
     * Pakai ini di server, JANGAN RentalSeeder, agar data yang sudah diubah admin tidak tertimpa.
     */
    public function run(): void
    {
        if (Rental::where('nama', 'PT Naga Hitam Rentcar')->exists()) {
            return;
        }

        $rental = Rental::create([
            'nama'      => 'PT Naga Hitam Rentcar',
            'alamat'    => 'Jl. Brigjend Katamso Gg. Kenangan No.46, Kp. Baru, Kec. Medan Maimun, Kota Medan, Sumatera Utara 20158',
            'deskripsi' => 'www.nagahitamrentcar.co.id',
            'logo'      => 'logos/nagahitam.png',
            'kontak_wa' => null,
            'telepon'   => null,
            'lat'       => 3.5625,
            'lng'       => 98.6884,
            'maps_url'  => 'https://www.google.com/maps/search/?api=1&query=' . urlencode('PT Naga Hitam Rentcar, Sumatera Utara'),
            'urut'      => (int) Rental::max('urut') + 1,
            'is_active' => true,
        ]);

        $mobil = ['City Car', 'All New Innova Zenix', 'Fortuner / Pajero Sport', 'Toyota Hiace', 'Sedan Premium (Mercedes-Benz)'];
        foreach ($mobil as $i => $m) {
            $rental->mobil()->create(['nama_mobil' => $m, 'jumlah_unit' => null, 'urut' => $i + 1]);
        }

        $kontak = [['Indra', '081370631286'], ['Moses', '081368048363'], ['Dimas', '085765499827']];
        foreach ($kontak as $i => [$nama, $hp]) {
            $rental->kontak()->create(['nama' => $nama, 'no_hp' => $hp, 'urut' => $i + 1]);
        }
    }
}
