<?php

namespace Database\Seeders;

use App\Models\JenisArmada;
use Illuminate\Database\Seeder;

class JenisArmadaSeeder extends Seeder
{
    public function run(): void
    {
        $jenis = [
            'Hino',
            'Isuzu',
            'Mitsubishi',
            'Fighter',
            'Center',
        ];

        foreach ($jenis as $nama) {
            JenisArmada::create(['nama_jenis' => $nama]);
        }
    }
}
