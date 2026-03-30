<?php

namespace App\Models;

use App\Traits\GeneratesCustomId;
use Illuminate\Database\Eloquent\Model;

class RiwayatPerawatanKomponen extends Model
{
    use GeneratesCustomId;

    protected static function idPrefix(): string
    {
        return 'RWK';
    }

    protected $table = 'riwayat_perawatan_komponen';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'komponen_armada_id',
        'km_saat_selesai',
        'tanggal_selesai',
        'catatan'
    ];

    public function komponen()
    {
        return $this->belongsTo(KomponenArmada::class, 'komponen_armada_id');
    }
}
