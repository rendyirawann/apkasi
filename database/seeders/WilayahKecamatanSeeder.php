<?php

namespace Database\Seeders;

use App\Models\WilayahKecamatan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WilayahKecamatanSeeder extends Seeder
{
    /**
     * Impor master kecamatan resmi BPS dari database/sql/wilayah_kecamatan.sql
     * (id kode BPS, wilayah_kabupaten_id, nama). Nama di-trim (sumber ada spasi depan).
     * Idempotent: dilewati bila tabel sudah berisi.
     */
    public function run(): void
    {
        if (WilayahKecamatan::count() > 0) {
            return;
        }

        $path = database_path('sql/wilayah_kecamatan.sql');
        if (! is_file($path)) {
            $this->command->warn("File master kecamatan tidak ditemukan: {$path}");
            return;
        }

        $content = file_get_contents($path);
        preg_match_all(
            "/INSERT INTO `wilayah_kecamatan` VALUES \(\s*(\d+)\s*,\s*(\d+)\s*,\s*'([^']*)'/i",
            $content,
            $matches,
            PREG_SET_ORDER
        );

        $now  = now();
        $rows = [];
        foreach ($matches as $m) {
            $rows[] = [
                'id'                   => (int) $m[1],
                'wilayah_kabupaten_id' => (int) $m[2],
                'nama'                 => trim($m[3]),
                'created_at'           => $now,
                'updated_at'           => $now,
            ];
        }

        foreach (array_chunk($rows, 1000) as $chunk) {
            DB::table('wilayah_kecamatan')->insert($chunk);
        }

        $this->command->info('Impor ' . count($rows) . ' kecamatan (master BPS).');
    }
}
