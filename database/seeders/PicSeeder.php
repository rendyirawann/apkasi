<?php

namespace Database\Seeders;

use App\Models\Pic;
use Illuminate\Database\Seeder;

class PicSeeder extends Seeder
{
    /**
     * PIC kegiatan APKASI Ke-26 per provinsi (provinsi_id = kode BPS, lihat WilayahProvinsiSeeder).
     *
     * Sumber: data Excel "Nama & No HP PIC per Provinsi" (revisi terbaru, 37 PIC).
     * Catatan:
     * - DKI Jakarta (31) tidak tercantum PIC → tidak di-seed.
     * - Papua (94): nomor 082282255745 ditranskrip apa adanya sesuai sumber.
     */
    public function run(): void
    {
        $data = [
            ['urut' => 1,  'provinsi_id' => 11, 'nama' => 'Tazzara Finsa Ayuningtyas, SE',   'no_hp' => '085382314026'],
            ['urut' => 2,  'provinsi_id' => 12, 'nama' => 'Tri Arista Handayani, S. Kom',     'no_hp' => '081297909170'],
            ['urut' => 3,  'provinsi_id' => 13, 'nama' => 'Khairul Basri, S.T.',              'no_hp' => '081263835550'],
            ['urut' => 4,  'provinsi_id' => 14, 'nama' => 'Kevin Andika GM, S.H.',            'no_hp' => '082164063009'],
            ['urut' => 5,  'provinsi_id' => 21, 'nama' => 'Pandy Syahputra',                  'no_hp' => '082210159815'],
            ['urut' => 6,  'provinsi_id' => 15, 'nama' => 'Ripawandi',                        'no_hp' => '082362759343'],
            ['urut' => 7,  'provinsi_id' => 16, 'nama' => 'Khairul Imam Barus',               'no_hp' => '082363622032'],
            ['urut' => 8,  'provinsi_id' => 19, 'nama' => 'Pandy Syahputra',                  'no_hp' => '082210159815'],
            ['urut' => 9,  'provinsi_id' => 17, 'nama' => 'Khairul Basri, S.T.',              'no_hp' => '081263835550'],
            ['urut' => 10, 'provinsi_id' => 18, 'nama' => 'Ripawandi',                        'no_hp' => '0895339521638'],
            ['urut' => 11, 'provinsi_id' => 32, 'nama' => 'Siti Rahimah Rezeki',              'no_hp' => '082294382062'],
            ['urut' => 12, 'provinsi_id' => 36, 'nama' => 'Pandy Syahputra',                  'no_hp' => '082210159815'],
            ['urut' => 13, 'provinsi_id' => 33, 'nama' => 'Muhammad Nazli Harahap, ST',       'no_hp' => '082294079138'],
            ['urut' => 14, 'provinsi_id' => 34, 'nama' => 'Ricki Ahmadi, Spd',                'no_hp' => '082240738711'],
            ['urut' => 15, 'provinsi_id' => 35, 'nama' => 'Ego Apriando',                     'no_hp' => '082160548246'],
            ['urut' => 16, 'provinsi_id' => 51, 'nama' => 'Desty Aprilia, SE',               'no_hp' => '082281010962'],
            ['urut' => 17, 'provinsi_id' => 52, 'nama' => 'Rahmat',                           'no_hp' => '081396288055'],
            ['urut' => 18, 'provinsi_id' => 53, 'nama' => 'Imam Dores Permana Barus, A.Md',   'no_hp' => '089688830026'],
            ['urut' => 19, 'provinsi_id' => 61, 'nama' => 'Desty Aprilia, SE',               'no_hp' => '082281010962'],
            ['urut' => 20, 'provinsi_id' => 62, 'nama' => 'Rahmat',                           'no_hp' => '081396288055'],
            ['urut' => 21, 'provinsi_id' => 63, 'nama' => 'Marsuman Sihotang',               'no_hp' => '081370389531'],
            ['urut' => 22, 'provinsi_id' => 64, 'nama' => 'Bagus Guntoro',                    'no_hp' => '081269621465'],
            ['urut' => 23, 'provinsi_id' => 65, 'nama' => 'Riki Ahmadi, Spd',                 'no_hp' => '082240738711'],
            ['urut' => 24, 'provinsi_id' => 71, 'nama' => 'Wisnu Jaya Mahendra',              'no_hp' => '082274668097'],
            ['urut' => 25, 'provinsi_id' => 75, 'nama' => 'Sabar Yogi Aritonang',            'no_hp' => '085363221416'],
            ['urut' => 26, 'provinsi_id' => 72, 'nama' => 'Bagus Guntoro',                    'no_hp' => '081269621465'],
            ['urut' => 27, 'provinsi_id' => 76, 'nama' => 'Riki Ahmadi, Spd',                 'no_hp' => '082240738711'],
            ['urut' => 28, 'provinsi_id' => 73, 'nama' => 'Hendra Gunawan Saragih',          'no_hp' => '085763776670'],
            ['urut' => 29, 'provinsi_id' => 74, 'nama' => 'Sabar Yogi Aritonang',            'no_hp' => '085363221416'],
            ['urut' => 30, 'provinsi_id' => 81, 'nama' => 'Wisnu Jaya Mahendra',              'no_hp' => '082274668097'],
            ['urut' => 31, 'provinsi_id' => 82, 'nama' => 'Khairul Imam Barus',               'no_hp' => '082363622032'],
            ['urut' => 32, 'provinsi_id' => 94, 'nama' => 'Kevin Andika GM, S.H.',            'no_hp' => '082282255745'],
            ['urut' => 33, 'provinsi_id' => 91, 'nama' => 'Irvan Rinaldi',                    'no_hp' => '082277843821'],
            ['urut' => 34, 'provinsi_id' => 95, 'nama' => 'Irvan Rinaldi',                    'no_hp' => '082277843821'],
            ['urut' => 35, 'provinsi_id' => 96, 'nama' => 'Irvan Rinaldi',                    'no_hp' => '082277843821'],
            ['urut' => 36, 'provinsi_id' => 97, 'nama' => 'Marsuman Sihotang',               'no_hp' => '081370389531'],
            ['urut' => 37, 'provinsi_id' => 92, 'nama' => 'Pandy Syahputra',                  'no_hp' => '082210159815'],
        ];

        foreach ($data as $row) {
            Pic::updateOrCreate(
                ['provinsi_id' => $row['provinsi_id']],
                array_merge(['is_active' => true], $row)
            );
        }
    }
}
