<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Jenis_perangkat;
use Exception;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class JenisPerangkatController extends Controller
{
    public function index()
    {
        try {
            $jenis_perangkat = Jenis_perangkat::latest()->get();
            return response()->json([
                'status' => true,
                'message' => 'Data Jenis Perangkat berhasil diambil',
                'data' => $jenis_perangkat,
            ], 200);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|unique:jenis_perangkats,name',
                'harga_per_jam' => 'required|numeric|min:0',
            ]);

            $jenis_perangkat = Jenis_perangkat::create([
                'name' => $request->name,
                'harga_per_jam' => $request->harga_per_jam,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Data Jenis Perangkat berhasil ditambahkan',
                'data' => $jenis_perangkat,
            ], 201);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $jenis_perangkat = Jenis_perangkat::find($id);
            if (!$jenis_perangkat) {
                return response()->json(['status' => false, 'message' => 'data jenis perangkat tidak ada'], 404);
            }

            $request->validate([
                'name' => [
                    'required',
                    'string',
                    Rule::unique('jenis_perangkats', 'name')
                        ->ignore($jenis_perangkat->id),
                ],
                'harga_per_jam' => 'required|numeric|min:0',
            ]);

            $jenis_perangkat->name = $request->name;
            $jenis_perangkat->harga_per_jam = $request->harga_per_jam;
            $jenis_perangkat->save();

            return response()->json([
                'status' => true,
                'message' => 'data jenis perangkat berhasil diedit',
                'data' => $jenis_perangkat,
            ], 200);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $jenis_perangkat = Jenis_perangkat::find($id);

            if (!$jenis_perangkat) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data jenis perangkat tidak ada.'
                ], 404);
            }

            if ($jenis_perangkat->perangkats()->exists()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Jenis perangkat tidak dapat dihapus karena masih digunakan oleh perangkat.'
                ], 422);
            }

            $jenis_perangkat->delete();

            return response()->json([
                'status' => true,
                'message' => 'Data jenis perangkat berhasil dihapus.'
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
