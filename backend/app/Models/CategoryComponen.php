<?php

namespace App\Models;

use App\Traits\GeneratesCustomId;
use Illuminate\Database\Eloquent\Model;

class CategoryComponen extends Model
{
    use GeneratesCustomId;

    protected static function idPrefix(): string
    {
        return 'CAT';
    }

    protected $table = 'category_componen';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'nama_kategori',
    ];

    public function komponen()
    {
        return $this->hasMany(KomponenArmada::class, 'kategori_komponen_id');
    }
}
