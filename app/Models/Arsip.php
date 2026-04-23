<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Arsip extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'arsips';

    protected $fillable = [
        'no_urut',
        'kode_klasifikasi',
        'nomor_arsip',
        'nomor_berkas',
        'uraian',
        'kurun_waktu',
        'uraian_informasi',
        'jumlah_item',
        'tanggal_arsip',
        'tingkat_perkembangan',
        'keterangan',
        'lokasi',
        'boks',
        'created_by',
        'updated_by',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public static function uraianList(): array
    {
        return self::whereNotNull('uraian')
            ->distinct()
            ->orderBy('uraian')
            ->pluck('uraian')
            ->toArray();
    }

    public static function kodeList(): array
    {
        return self::whereNotNull('kode_klasifikasi')
            ->distinct()
            ->orderBy('kode_klasifikasi')
            ->pluck('kode_klasifikasi')
            ->toArray();
    }

    public static function tahunList(): array
    {
        return self::whereNotNull('kurun_waktu')
            ->distinct()
            ->orderBy('kurun_waktu')
            ->pluck('kurun_waktu')
            ->toArray();
    }
}
