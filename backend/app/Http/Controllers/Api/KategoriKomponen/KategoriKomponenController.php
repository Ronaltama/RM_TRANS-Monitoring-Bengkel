<?php

namespace App\Http\Controllers\Api\KategoriKomponen;

use App\Http\Controllers\Controller;
use App\Models\CategoryComponen;
use Illuminate\Http\Request;

class KategoriKomponenController extends Controller
{
    //  GET ALL
    public function index()
    {
        $data = CategoryComponen::get();

        return response()->json([
            'message' => 'Berhasil ambil data',
            'data' => $data
        ]);
    }

    //  CREATE
    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100'
        ]);

        $data = CategoryComponen::create([
            'nama_kategori' => $request->nama_kategori
        ]);

        return response()->json([
            'message' => 'Kategori berhasil dibuat',
            'data' => $data
        ], 201);
    }

    //  DELETE
    public function destroy($id)
    {
        $data = CategoryComponen::find($id);

        if (!$data) {
            return response()->json([
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $data->delete();

        return response()->json([
            'message' => 'Kategori berhasil dihapus'
        ]);
    }
}
