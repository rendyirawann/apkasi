<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * Penanganan kolom "urut" (urutan tampil) yang konsisten untuk form Data Master.
 *
 * Aturan (sesuai permintaan):
 * - TAMBAH (model null): bila urut KOSONG atau BENTROK dengan data lain -> generate otomatis (max + 1).
 *   Bila diisi & belum dipakai -> hormati input.
 * - UBAH (model ada): bila kosong / tidak diubah -> pertahankan urut lama.
 *   Bila diubah ke nilai yang bentrok dengan baris lain -> generate otomatis (max + 1).
 *
 * Catatan: untuk urut ber-grup (mis. SiteLogo per `grup`), gunakan parameter $scope.
 */
trait HandlesUrut
{
    /**
     * @param  class-string<Model>  $modelClass
     * @param  array<string,mixed>  $scope  kondisi tambahan utk urut ber-grup (kolom => nilai)
     */
    protected function resolveUrut(string $modelClass, Request $request, ?Model $model = null, string $column = 'urut', array $scope = []): int
    {
        $provided = $request->filled($column) ? (int) $request->input($column) : null;

        // UBAH: kosong / tidak diubah -> pertahankan nilai lama; diisi -> hormati (admin boleh menata ulang).
        if ($model) {
            return $provided ?? (int) $model->{$column};
        }

        // TAMBAH: hitung urut terbesar (dalam scope, mis. per-grup) untuk auto-generate.
        $maxQuery = $modelClass::query();
        foreach ($scope as $col => $val) {
            $maxQuery->where($col, $val);
        }
        $max = (int) $maxQuery->max($column);

        // Kosong -> nomor berikutnya.
        if ($provided === null) {
            return $max + 1;
        }

        // Diisi tapi sudah dipakai -> generate urutan yang belum ada (max + 1); kalau bebas -> hormati.
        $clashQuery = $modelClass::query()->where($column, $provided);
        foreach ($scope as $col => $val) {
            $clashQuery->where($col, $val);
        }
        return $clashQuery->exists() ? $max + 1 : $provided;
    }
}
