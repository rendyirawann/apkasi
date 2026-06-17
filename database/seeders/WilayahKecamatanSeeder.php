<?php

namespace Database\Seeders;

use App\Models\WilayahKecamatan;
use Illuminate\Database\Seeder;

class WilayahKecamatanSeeder extends Seeder
{
    /**
     * Master 22 kecamatan Kabupaten Deli Serdang (untuk dropdown LO pada admin PIC).
     * Nama tanpa prefix "Kec." (prefix ditambahkan saat ditampilkan / pada opsi dropdown).
     * Idempotent: firstOrCreate by nama.
     */
    public function run(): void
    {
        $kecamatan = [
            'Bangun Purba', 'Batang Kuis', 'Beringin', 'Biru-Biru', 'Deli Tua',
            'Galang', 'Gunung Meriah', 'Hamparan Perak', 'Kutalimbaru', 'Labuhan Deli',
            'Lubuk Pakam', 'Namorambe', 'Pagar Merbau', 'Pancur Batu', 'Pantai Labu',
            'Patumbak', 'Percut Sei Tuan', 'Sibolangit', 'STM Hilir', 'STM Hulu',
            'Sunggal', 'Tanjung Morawa',
        ];

        foreach ($kecamatan as $i => $nama) {
            WilayahKecamatan::firstOrCreate(
                ['nama' => $nama],
                ['urut' => $i + 1, 'is_active' => true]
            );
        }
    }
}
