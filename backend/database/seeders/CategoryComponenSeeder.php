<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use App\Models\CategoryComponen;

class CategoryComponenSeeder extends Seeder
{
    public function run(): void
    {
        $categories = ['Ban', 'Oli', 'Accu', 'Filter', 'Rem'];

        foreach ($categories as $cat) {
            CategoryComponen::create(['nama_kategori' => $cat]);
        }
    }
}
