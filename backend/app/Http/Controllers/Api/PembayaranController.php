<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Sesi_rental;
use Exception;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function index()
    {
        try {
            $pembayaran = Pembayaran::with([
                'sesiRental.pelanggan',
                'sesiRental.perangkat.jenisPerangkat'
            ])->latest()->get();

            return response()->json([
                'status' => true,
                'message' => 'Data Pembayaran berhasil diambil',
                'data' => $pembayaran,
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
                'sesi_id' => 'required|exists:sesi_rentals,id',
            ]);

            $sesiRental = \App\Models\Sesi_rental::find($request->sesi_id);

            if (!$sesiRental) {
                return response()->json([
                    'status' => false,
                    'message' => 'Sesi rental tidak ditemukan.'
                ], 404);
            }

            if ($sesiRental->status !== 'belum_main') {
                return response()->json([
                    'status' => false,
                    'message' => 'Pembayaran hanya dapat dilakukan untuk sesi yang menunggu.'
                ], 422);
            }

            $sudahBayar = Pembayaran::where('sesi_id', $sesiRental->id)
                ->where('status', 'lunas')
                ->exists();

            if ($sudahBayar) {
                return response()->json([
                    'status' => false,
                    'message' => 'Sesi rental ini sudah dibayar.'
                ], 422);
            }

            $pembayaran = Pembayaran::create([
                'sesi_id' => $sesiRental->id,
                'jumlah' => $sesiRental->harga,
                'status' => 'menunggu',
                'waktu_bayar' => null,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Pembayaran cash berhasil dicatat.',
                'data' => $pembayaran
            ], 201);

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
            $pembayaran = Pembayaran::find($id);
            if (! $pembayaran) {
                return response()->json(['status' => false, 'message' => 'data pembayaran tidak ada'], 404);
            }

            $request->validate([
                'sesi_id' => 'required|exists:sesi_rentals,id',
                'jumlah' => 'required|numeric',
                'status' => 'required|string',
                'waktu_bayar' => 'nullable|date',
            ]);

            $pembayaran->sesi_id = $request->sesi_id;
            $pembayaran->jumlah = $request->jumlah;
            $pembayaran->status = $request->status;
            $pembayaran->waktu_bayar = $request->waktu_bayar;
            $pembayaran->save();

            return response()->json([
                'status'  => true,
                'message' => 'data pembayaran berhasil diedit',
                'data'    => $pembayaran,
            ], 200);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function konfirmasi($id)
    {
        try {
            $pembayaran = Pembayaran::find($id);

            if (!$pembayaran) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data pembayaran tidak ditemukan.'
                ], 404);
            }

            if ($pembayaran->status === 'lunas') {
                return response()->json([
                    'status' => false,
                    'message' => 'Pembayaran ini sudah dikonfirmasi.'
                ], 422);
            }

            $pembayaran->status = 'lunas';
            $pembayaran->waktu_bayar = now();
            $pembayaran->save();

            return response()->json([
                'status' => true,
                'message' => 'Pembayaran berhasil dikonfirmasi.',
                'data' => $pembayaran
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
