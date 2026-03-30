<?php

namespace App\Models;

use App\Traits\GeneratesCustomId;
use Illuminate\Database\Eloquent\Model;

class JenisArmada extends Model
{
    use GeneratesCustomId;

    protected static function idPrefix(): string
    {
        return 'JNA';
    }

    protected $table = 'jenis_armada';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'nama_jenis'
    ];

    public function armada()
    {
        return $this->hasMany(Armada::class, 'jenis_armada_id');
    }
}
