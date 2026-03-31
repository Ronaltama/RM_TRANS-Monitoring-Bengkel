<?php

namespace App\Models;

use App\Traits\GeneratesCustomId;
use Illuminate\Database\Eloquent\Model;

class KomponenArmada extends Model
{
    use GeneratesCustomId;

    protected static function idPrefix(): string
    {
        return 'KMP';
    }

    protected $table = 'komponen_armada';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'monitoring_armada_id',
        'kategori_komponen_id',
        'nama_komponen',
        'tipe_pelacakan',
        'target_km',
        'target_tanggal',
        'target_hari',
        'km_terakhir_perawatan',
        'tanggal_terakhir_perawatan',
        'status',
        'has_identity',
    ];

    public function monitoring()
    {
        return $this->belongsTo(MonitoringArmadaAktif::class, 'monitoring_armada_id');
    }

    public function kategori()
    {
        return $this->belongsTo(CategoryComponen::class, 'kategori_komponen_id');
    }

    public function detail()
    {
        return $this->hasMany(DetailKomponenArmada::class, 'komponen_armada_id');
    }

    public function riwayat()
    {
        return $this->hasMany(RiwayatPerawatanKomponen::class, 'komponen_armada_id');
    }
}
