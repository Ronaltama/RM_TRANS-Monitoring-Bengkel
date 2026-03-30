<?php

namespace App\Models;

use App\Traits\GeneratesCustomId;
use Illuminate\Database\Eloquent\Model;

class LogKilometer extends Model
{
    use GeneratesCustomId;

    protected static function idPrefix(): string
    {
        return 'LGK';
    }

    protected $table = 'log_kilometer';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'armada_id',
        'odometer_km',
        'delta_km',
        'tgl_input'
    ];

    public function armada()
    {
        return $this->belongsTo(Armada::class, 'armada_id');
    }
}
