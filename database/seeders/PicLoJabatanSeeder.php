<?php

namespace Database\Seeders;

use App\Models\Pic;
use Illuminate\Database\Seeder;

class PicLoJabatanSeeder extends Seeder
{
    /**
     * Isi sebutan/jabatan instansi LO (pics.lo_jabatan = SEBUTAN saja, mis. "Kepala Dinas")
     * otomatis dari nama instansi memakai aturan Pic::defaultJabatanTitle.
     * Hanya mengisi yang masih KOSONG -> tidak menimpa penyesuaian manual admin.
     * Jalankan setelah PicLoSeeder.
     */
    public function run(): void
    {
        Pic::whereNull('lo_jabatan')
            ->whereNotNull('lo_instansi')
            ->get()
            ->each(function (Pic $pic) {
                $jabatan = Pic::defaultJabatanTitle($pic->lo_instansi);
                if ($jabatan) {
                    $pic->lo_jabatan = $jabatan;
                    $pic->save();
                }
            });
    }
}
