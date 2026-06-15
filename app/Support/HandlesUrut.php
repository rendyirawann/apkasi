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

        $maxQuery = $modelClass::query();
        foreach ($scope as $col => $val) {
            $maxQuery->where($col, $val);
        }
        $max = (int) $maxQuery->max($column);

        // Tidak diisi -> create: lanjut nomor berikutnya; update: pertahankan nilai lama.
        if ($provided === null) {
            return $model ? (int) $model->{$column} : $max + 1;
        }

        // Update tanpa mengubah urut -> pertahankan.
        if ($model && $provided === (int) $model->{$column}) {
            return $provided;
        }

        // Cek bentrok dengan baris lain (kecuali dirinya sendiri saat update).
        $clashQuery = $modelClass::query()->where($column, $provided);
        foreach ($scope as $col => $val) {
            $clashQuery->where($col, $val);
        }
        if ($model) {
            $clashQuery->where($model->getKeyName(), '!=', $model->getKey());
        }

        // Sudah ada -> generate urutan yang belum dipakai (max + 1).
        return $clashQuery->exists() ? $max + 1 : $provided;
    }
}
