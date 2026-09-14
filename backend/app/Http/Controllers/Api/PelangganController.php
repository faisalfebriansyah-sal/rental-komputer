<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use Exception;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    public function index()
    {
        try {
            $pelanggan = Pelanggan::latest()->get();
            return response()->json([
                'status'  => true,
                'message' => 'Data Pelanggan berhasil diambil',
                'data'    => $pelanggan,
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
                'no_hp' => 'required|string|unique:pelanggans',
            ]);

            $pelanggan = Pelanggan::create([
                'name'  => $request->name,
                'no_hp' => $request->no_hp,
            ]);

            return response()->json([
                'status'  => true,
                'message' => 'Data Pelanggan berhasil ditambahkan',
                'data'    => $pelanggan,
            ], 201);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

     public function update(Request $request, $id)
    {
        try {
            $pelanggan = Pelanggan::find($id);
            if (! $pelanggan) {
                return response()->json(['status' => false, 'message' => 'data pelanggan tidak ada'], 404);
            }

            $request->validate([
                'name'  => 'required|string',
                'no_hp' => 'required|string|unique:pelanggans,no_hp,' . $id . ',id',
            ]);

            $pelanggan->name = $request->name;
            $pelanggan->no_hp = $request->no_hp;
            $pelanggan->save();

            return response()->json([
                'status'  => true,
                'message' => 'data pelanggan berhasil diedit',
                'data'    => $pelanggan,
            ], 200);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $pelanggan = Pelanggan::find($id);

            if (!$pelanggan) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data pelanggan tidak ada'
                ], 404);
            }

            if ($pelanggan->sesiRentals()->exists()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Pelanggan tidak dapat dihapus karena memiliki riwayat rental'
                ], 409);
            }

            $pelanggan->delete();

            return response()->json([
                'status' => true,
                'message' => 'Data pelanggan berhasil dihapus'
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
