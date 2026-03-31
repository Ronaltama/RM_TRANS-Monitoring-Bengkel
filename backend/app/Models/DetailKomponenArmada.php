<?php

namespace App\Models;

use App\Traits\GeneratesCustomId;
use Illuminate\Database\Eloquent\Model;

class DetailKomponenArmada extends Model
{
    use GeneratesCustomId;

    protected static function idPrefix(): string
    {
        return 'DKA';
    }

    protected $table = 'detail_komponen_armada';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'komponen_armada_id',
        'nomor_seri',
        'nomor_stamp',
        'jenis_ban',
        'ukuran',
        'merk_tipe',
        'pemasok',
        'harga',
        'tanggal_pemasangan',
        'km_pemasangan',
        'jumlah_liter',
        'tanggal_pelepasan',
        'status_ban_bekas',
        'keterangan'
    ];

    public function komponen()
    {
        return $this->belongsTo(KomponenArmada::class, 'komponen_armada_id');
    }
}
