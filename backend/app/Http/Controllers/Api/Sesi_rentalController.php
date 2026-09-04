<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sesi_rental;
use Exception;
use Illuminate\Http\Request;

class Sesi_rentalController extends Controller
{
    public function index()
    {
        try {
            $sesirental = Sesi_rental::with([
                'pelanggan',
                'perangkat'
            ])->latest()->get();

            return response()->json([
                'status' => true,
                'message' => 'Data sesi rental berhasil diambil.',
                'data' => $sesirental
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
                'pelanggan_id' => 'required|exists:pelanggans,id',
                'perangkat_id' => 'required|exists:perangkats,id',
                'kode_sesi' => 'required|string|unique:sesi_rentals,kode_sesi',
                'durasi' => 'required|integer',
                'harga' => 'required|numeric',
                'status' => 'required|string',
                'waktu_mulai' => 'required|date',
                'waktu_selesai' => 'nullable|date',
            ]);

            $sesirental = Sesi_rental::create($request->only([
                'pelanggan_id',
                'perangkat_id',
                'kode_sesi',
                'durasi',
                'harga',
                'status',
                'waktu_mulai',
                'waktu_selesai'
            ]));

            return response()->json([
                'status' => true,
                'message' => 'Sesi rental berhasil ditambahkan.',
                'data' => $sesirental->load([
                    'pelanggan',
                    'perangkat'
                ])
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
            $sesirental = Sesi_rental::with([
                'pelanggan',
                'perangkat'
            ])->find($id);

            if (!$sesirental) {
                return response()->json([
                    'status' => false,
                    'message' => 'Sesi rental tidak ditemukan.'
                ], 404);
            }

            return response()->json([
                'status' => true,
                'data' => $sesirental
            ], 200);

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
            $sesirental = Sesi_rental::find($id);

            if (!$sesirental) {
                return response()->json([
                    'status' => false,
                    'message' => 'Sesi rental tidak ditemukan.'
                ], 404);
            }

            $request->validate([
                'pelanggan_id' => 'required|exists:pelanggans,id',
                'perangkat_id' => 'required|exists:perangkats,id',
                'kode_sesi' => 'required|string|unique:sesi_rentals,kode_sesi,' . $id,
                'durasi' => 'required|integer',
                'harga' => 'required|numeric',
                'status' => 'required|string',
                'waktu_mulai' => 'required|date',
                'waktu_selesai' => 'nullable|date',
            ]);

            $sesirental->update($request->only([
                'pelanggan_id',
                'perangkat_id',
                'kode_sesi',
                'durasi',
                'harga',
                'status',
                'waktu_mulai',
                'waktu_selesai'
            ]));

            return response()->json([
                'status' => true,
                'message' => 'Sesi rental berhasil diperbarui.',
                'data' => $sesirental->load([
                    'pelanggan',
                    'perangkat'
                ])
            ], 200);

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
            $sesirental = Sesi_rental::find($id);

            if (!$sesirental) {
                return response()->json([
                    'status' => false,
                    'message' => 'Sesi rental tidak ditemukan.'
                ], 404);
            }

            $sesirental->delete();

            return response()->json([
                'status' => true,
                'message' => 'Sesi rental berhasil dihapus.'
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}