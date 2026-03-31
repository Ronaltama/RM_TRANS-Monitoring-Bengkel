<?php

namespace App\Http\Controllers\Api\Armada;

use App\Http\Controllers\Controller;
use App\Models\JenisArmada;
use Illuminate\Http\Request;

class JenisArmadaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = JenisArmada::all();
        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_jenis' => 'required|unique:jenis_armada,nama_jenis'
        ]);

        $jenis = JenisArmada::create([
            'nama_jenis' => $request->nama_jenis
        ]);

        return response()->json([
            'message' => 'Jenis armada berhasil ditambahkan',
            'data' => $jenis
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $jenis = JenisArmada::find($id);

        if (!$jenis) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        return response()->json($jenis);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $jenis = JenisArmada::find($id);

        if (!$jenis) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $request->validate([
            'nama_jenis' => 'required|unique:jenis_armada,nama_jenis,' . $id
        ]);

        $jenis->update([
            'nama_jenis' => $request->nama_jenis
        ]);

        return response()->json([
            'message' => 'Jenis armada berhasil diupdate',
            'data' => $jenis
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $jenis = JenisArmada::find($id);

        if (!$jenis) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $jenis->delete();

        return response()->json([
            'message' => 'Jenis armada berhasil dihapus'
        ]);
    }
}
