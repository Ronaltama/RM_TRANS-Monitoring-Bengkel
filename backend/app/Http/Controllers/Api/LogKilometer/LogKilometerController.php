<?php

namespace App\Http\Controllers\Api\LogKilometer;

use App\Http\Controllers\Controller;
use App\Models\LogKilometer;
use App\Models\MonitoringArmadaAktif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LogKilometerController extends Controller
{
    public function index()
    {
        $logs = LogKilometer::with('armada')->orderBy('tgl_input', 'desc')->get();
        return response()->json([
            'status' => 'success',
            'data'   => $logs
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'armada_id'   => 'required|exists:armada,id',
            'odometer_km' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Validation error',
                'errors'  => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $monitoring = MonitoringArmadaAktif::where('armada_id', $request->armada_id)
                ->where('status', 'aktif')
                ->first();

            if (!$monitoring) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Armada tidak sedang dimonitoring'
                ], 404);
            }

            if ($request->odometer_km <= $monitoring->last_recorded_km) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Kilometer baru harus lebih besar dari kilometer terakhir (' . $monitoring->last_recorded_km . ' km)'
                ], 400);
            }

            $deltaKm = $request->odometer_km - $monitoring->last_recorded_km;

            $log = LogKilometer::create([
                'armada_id'   => $request->armada_id,
                'odometer_km' => $request->odometer_km,
                'delta_km'    => $deltaKm,
                'tgl_input'   => Carbon::now()->toDateString()
            ]);

            // update
            $monitoring->update([
                'last_recorded_km' => $request->odometer_km
            ]);

            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => 'Kilometer berhasil diupdate',
                'data'    => $log,
                'updated_monitoring' => $monitoring
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal mengupdate kilometer: ' . $e->getMessage()
            ], 500);
        }
    }
}
