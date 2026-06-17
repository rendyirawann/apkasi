<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pic extends Model
{
    protected $fillable = ['provinsi_id', 'nama', 'lo_kecamatan', 'lo_instansi', 'lo_jabatan', 'no_hp', 'urut', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
        'urut'      => 'integer',
    ];

    public function provinsi()
    {
        return $this->belongsTo(WilayahProvinsi::class, 'provinsi_id');
    }

    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }

    /** Sebutan kecamatan: kepala kecamatan = "Camat <nama>" (prefix "Kec." dibuang). */
    public function getLoCamatAttribute(): ?string
    {
        if (! $this->lo_kecamatan) {
            return null;
        }
        $kec = trim(preg_replace('/^\s*Kec\.\s*/i', '', $this->lo_kecamatan));
        return 'Camat Kecamatan ' . $kec;
    }

    /** Sebutan instansi: pakai override lo_jabatan bila diisi, selain itu otomatis dari nama instansi. */
    public function getLoJabatanInstansiAttribute(): ?string
    {
        if (filled($this->lo_jabatan)) {
            return $this->lo_jabatan;
        }
        return self::defaultJabatanInstansi($this->lo_instansi);
    }

    /**
     * Aturan otomatis sebutan kepala instansi (format "Jabatan - Nama Instansi"):
     * - "Inspektorat ..."          -> "Inspektur ..." (kata Inspektorat diganti)
     * - "Dinas ..." / akronim "D.." -> "Kepala Dinas - <instansi>"
     * - "Badan ..." / akronim "B.." -> "Kepala Badan - <instansi>"
     * - lainnya                     -> "Kepala <instansi>"
     */
    public static function defaultJabatanInstansi(?string $instansi): ?string
    {
        $t = trim((string) $instansi);
        if ($t === '') {
            return null;
        }
        if (preg_match('/^Inspektorat\b/i', $t)) {
            return preg_replace('/^Inspektorat/i', 'Inspektur', $t);
        }
        if (preg_match('/^Dinas\b/i', $t) || preg_match('/^D[A-Z]/', $t)) {
            return 'Kepala Dinas - ' . $t;
        }
        if (preg_match('/^Badan\b/i', $t) || preg_match('/^B[A-Za-z]/', $t)) {
            return 'Kepala Badan - ' . $t;
        }
        return 'Kepala ' . $t;
    }
}
