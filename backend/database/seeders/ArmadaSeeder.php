<?php

namespace Database\Seeders;

use App\Models\Armada;
use App\Models\JenisArmada;
use Illuminate\Database\Seeder;

class ArmadaSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil semua jenis armada yang sudah di-seed, diindex by nama_jenis
        $jenis = JenisArmada::all()->keyBy('nama_jenis');

        // 5 armada — masing-masing relasi ke jenis yang berbeda
        $armadas = [
            ['nopol' => 'B 1234 ABC', 'jenis' => 'Hino'],
            ['nopol' => 'B 5678 DEF', 'jenis' => 'Isuzu'],
            ['nopol' => 'D 9012 GHI', 'jenis' => 'Mitsubishi'],
            ['nopol' => 'D 3456 JKL', 'jenis' => 'Fighter'],
            ['nopol' => 'E 7890 MNO', 'jenis' => 'Center'],
        ];

        foreach ($armadas as $data) {
            Armada::create([
                'nopol'           => $data['nopol'],
                'jenis_armada_id' => $jenis[$data['jenis']]->id,
            ]);
        }
    }
}
