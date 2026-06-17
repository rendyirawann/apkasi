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

    /** Sebutan kecamatan: "Camat - Kec. <nama>". */
    public function getLoCamatAttribute(): ?string
    {
        if (! $this->lo_kecamatan) {
            return null;
        }
        return 'Camat - ' . trim($this->lo_kecamatan);
    }

    /**
     * Tampilan sebutan instansi = "<sebutan> - <nama instansi>".
     * Sebutan diambil dari lo_jabatan (override admin: SEBUTAN saja) atau otomatis dari nama instansi.
     */
    public function getLoJabatanInstansiAttribute(): ?string
    {
        $instansi = trim((string) $this->lo_instansi);
        if ($instansi === '') {
            return null;
        }
        $title = filled($this->lo_jabatan) ? trim($this->lo_jabatan) : self::defaultJabatanTitle($instansi);
        return $title ? ($title . ' - ' . $instansi) : $instansi;
    }

    /**
     * Sebutan/jabatan kepala instansi (TITLE saja, tanpa nama instansi):
     * - "Inspektorat ..."           -> "Inspektur"
     * - "Dinas ..." / akronim "D.." -> "Kepala Dinas"
     * - "Badan ..." / akronim "B.." -> "Kepala Badan"
     * - lainnya                     -> "Kepala"
     */
    public static function defaultJabatanTitle(?string $instansi): ?string
    {
        $t = trim((string) $instansi);
        if ($t === '') {
            return null;
        }
        if (preg_match('/^Inspektorat\b/i', $t)) {
            return 'Inspektur';
        }
        if (preg_match('/^Dinas\b/i', $t) || preg_match('/^D[A-Z]/', $t)) {
            return 'Kepala Dinas';
        }
        if (preg_match('/^Badan\b/i', $t) || preg_match('/^B[A-Za-z]/', $t)) {
            return 'Kepala Badan';
        }
        return 'Kepala';
    }
}
