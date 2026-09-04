<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Jenis_perangkat;
use Exception;
use Illuminate\Http\Request;

class JenisPerangkatController extends Controller
{
    public function index()
    {
        try {
            $jenis_perangkat = Jenis_perangkat::latest()->get();
            return response()->json([
                'status'  => true,
                'message' => 'Data Jenis Perangkat berhasil diambil',
                'data'    => $jenis_perangkat,
            ], 200);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name'  => 'required|string',
                'harga_per_jam' => 'nullable|string',
            ]);

            $jenis_perangkat = Jenis_perangkat::create([
                'name'  => $request->name,
                'harga_per_jam' => $request->harga_per_jam,
            ]);

            return response()->json([
                'status'  => true,
                'message' => 'Data Jenis Perangkat berhasil ditambahkan',
                'data'    => $jenis_perangkat,
            ], 201);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

     public function update(Request $request, $id)
    {
        try {
            $jenis_perangkat = Jenis_perangkat::find($id);
            if (! $jenis_perangkat) {
                return response()->json(['status' => false, 'message' => 'data jenis perangkat tidak ada'], 404);
            }

            $request->validate([
                'name'  => 'required|string',
                'harga_per_jam' => 'nullable|string',
            ]);

            $jenis_perangkat->name = $request->name;
            $jenis_perangkat->harga_per_jam = $request->harga_per_jam;
            $jenis_perangkat->save();

            return response()->json([
                'status'  => true,
                'message' => 'data jenis perangkat berhasil diedit',
                'data'    => $jenis_perangkat,
            ], 200);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
