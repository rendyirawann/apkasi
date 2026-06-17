<?php

namespace Database\Seeders;

use App\Models\Pic;
use Illuminate\Database\Seeder;

class PicLoJabatanSeeder extends Seeder
{
    /**
     * Isi sebutan/jabatan instansi LO (pics.lo_jabatan) otomatis dari nama instansi
     * memakai aturan Pic::defaultJabatanInstansi (Kepala Dinas/Badan, Inspektur, dll).
     * Hanya mengisi yang masih KOSONG -> tidak menimpa penyesuaian manual admin.
     * Jalankan setelah PicLoSeeder.
     */
    public function run(): void
    {
        Pic::whereNull('lo_jabatan')
            ->whereNotNull('lo_instansi')
            ->get()
            ->each(function (Pic $pic) {
                $jabatan = Pic::defaultJabatanInstansi($pic->lo_instansi);
                if ($jabatan) {
                    $pic->lo_jabatan = $jabatan;
                    $pic->save();
                }
            });
    }
}
