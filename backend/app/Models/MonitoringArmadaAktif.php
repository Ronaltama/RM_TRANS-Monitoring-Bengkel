<?php

namespace App\Models;

use App\Traits\GeneratesCustomId;
use Illuminate\Database\Eloquent\Model;

class MonitoringArmadaAktif extends Model
{
    use GeneratesCustomId;

    protected static function idPrefix(): string
    {
        return 'MON';
    }

    protected $table = 'monitoring_armada_aktif';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'armada_id',
        'last_recorded_km',
        'status',
        'spedo_status',
        'tanggal_mulai_monitoring',
        'keterangan'
    ];

    public function armada()
    {
        return $this->belongsTo(Armada::class, 'armada_id');
    }

    public function komponen()
    {
        return $this->hasMany(KomponenArmada::class, 'monitoring_armada_id');
    }
}
