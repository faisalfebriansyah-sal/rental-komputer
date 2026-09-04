<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use Exception;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function index()
    {
        try {
            $pembayaran = Pembayaran::latest()->get();
            return response()->json([
                'status'  => true,
                'message' => 'Data Pembayaran berhasil diambil',
                'data'    => $pembayaran,
            ], 200);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'sesi_id'  => 'required|string',
                'jumlah' => 'required|numeric',
                'status' => 'required|string',
                'waktu_bayar' => 'nullable|date',
            ]);

            $pembayaran = Pembayaran::create([
                'sesi_id'  => $request->sesi_id,
                'jumlah' => $request->jumlah,
                'status' => $request->status,
                'waktu_bayar' => $request->waktu_bayar,
            ]);

            return response()->json([
                'status'  => true,
                'message' => 'Data Pembayaran berhasil ditambahkan',
                'data'    => $pembayaran,
            ], 201);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
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
                'sesi_id'  => 'required|string',
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
}
