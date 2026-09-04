<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Perangkat;
use Exception;
use Illuminate\Http\Request;

class PerangkatController extends Controller
{
    public function index()
    {
        try {
            $perangkat = Perangkat::with('jenisPerangkat')->latest()->get();

            return response()->json([
                'status' => true,
                'message' => 'Data perangkat berhasil diambil.',
                'data' => $perangkat
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'jenis_id' => 'required|exists:jenis_perangkats,id',
                'name' => 'required|string|unique:perangkats,name',
                'status' => 'required|string',
            ]);

            $perangkat = Perangkat::create($request->only([
                'jenis_id',
                'name',
                'status'
            ]));

            return response()->json([
                'status' => true,
                'message' => 'Perangkat berhasil ditambahkan.',
                'data' => $perangkat->load('jenisPerangkat'),
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $perangkat = Perangkat::with('jenisPerangkat')->find($id);

            if (!$perangkat) {
                return response()->json([
                    'status' => false,
                    'message' => 'Perangkat tidak ditemukan.'
                ], 404);
            }

            return response()->json([
                'status' => true,
                'data' => $perangkat
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $perangkat = Perangkat::find($id);

            if (!$perangkat) {
                return response()->json([
                    'status' => false,
                    'message' => 'Perangkat tidak ditemukan.'
                ], 404);
            }

            $request->validate([
                'jenis_id' => 'required|exists:jenis_perangkats,id',
                'name' => 'required|string|unique:perangkats,name,' . $id,
                'status' => 'required|string',
            ]);

            $perangkat->update($request->only([
                'jenis_id',
                'name',
                'status'
            ]));

            return response()->json([
                'status' => true,
                'message' => 'Perangkat berhasil diperbarui.',
                'data' => $perangkat->load('jenisPerangkat'),
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $perangkat = Perangkat::find($id);

            if (!$perangkat) {
                return response()->json([
                    'status' => false,
                    'message' => 'Perangkat tidak ditemukan.'
                ], 404);
            }

            $perangkat->delete();

            return response()->json([
                'status' => true,
                'message' => 'Perangkat berhasil dihapus.'
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
} 