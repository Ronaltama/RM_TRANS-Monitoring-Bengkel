<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

trait GeneratesCustomId
{
    /**
     * Boot the trait — otomatis generate ID saat creating.
     */
    public static function bootGeneratesCustomId(): void
    {
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = static::generateCustomId();
            }
        });
    }

    /**
     * Generate ID dengan format: PREFIX-DDMMYYYYXXXX
     * Zona waktu: Asia/Jakarta
     */
    public static function generateCustomId(): string
    {
        $prefix = static::idPrefix();
        $now    = Carbon::now('Asia/Jakarta');
        $date   = $now->format('dmY'); // DDMMYYYY

        // Cari index terakhir hari ini berdasarkan prefix + tanggal
        $like  = "{$prefix}-{$date}%";
        $last  = DB::table((new static())->getTable())
                    ->where('id', 'like', $like)
                    ->orderByDesc('id')
                    ->value('id');

        if ($last) {
            // Ambil 4 digit terakhir sebagai index
            $lastIndex = (int) substr($last, -4);
            $newIndex  = $lastIndex + 1;
        } else {
            $newIndex = 1;
        }

        $index = str_pad($newIndex, 4, '0', STR_PAD_LEFT);

        return "{$prefix}-{$date}{$index}";
    }

    /**
     * Prefix ID tabel — wajib di-override di setiap model.
     * Contoh: return 'ARM';
     */
    abstract protected static function idPrefix(): string;
}
