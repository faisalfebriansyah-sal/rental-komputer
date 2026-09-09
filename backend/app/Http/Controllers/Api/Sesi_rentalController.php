<?php

namespace App\Http\Controllers\Api;

use App\Events\RentalUpdated;
use App\Http\Controllers\Controller;
use App\Models\Sesi_rental;
use App\Models\Perangkat;
use App\Models\Pembayaran;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Sesi_rentalController extends Controller
{
     public function index()
     {
          try {
               $sesirental = Sesi_rental::with([
                    'pelanggan',
                    'perangkat.jenisPerangkat'
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
                    'durasi' => 'required|integer|min:1',
               ]);

               // Cari perangkat
               $perangkat = Perangkat::with('jenisPerangkat')
                    ->find($request->perangkat_id);

               if (!$perangkat) {
                    return response()->json([
                         'status' => false,
                         'message' => 'Perangkat tidak ditemukan.'
                    ], 404);
               }

               // Cek status perangkat
               if ($perangkat->status === 'digunakan') {
                    return response()->json([
                         'status' => false,
                         'message' => 'Perangkat sedang digunakan.'
                    ], 422);
               }

               if ($perangkat->status === 'maintenance') {
                    return response()->json([
                         'status' => false,
                         'message' => 'Perangkat sedang maintenance.'
                    ], 422);
               }

               // Pastikan jenis perangkat tersedia
               if (!$perangkat->jenisPerangkat) {
                    return response()->json([
                         'status' => false,
                         'message' => 'Jenis perangkat tidak ditemukan.'
                    ], 422);
               }

               // Ambil harga per jam
               $hargaPerJam = $perangkat->jenisPerangkat->harga_per_jam;

               // Hitung total harga
               $harga = $hargaPerJam * $request->durasi;

               // Generate kode rental
               do {
                    $kodeSesi = (string) random_int(100000, 999999);
               } while (Sesi_rental::where('kode_sesi', $kodeSesi)->exists());

               // Buat sesi rental
               $sesirental = Sesi_rental::create([
                    'pelanggan_id' => $request->pelanggan_id,
                    'perangkat_id' => $request->perangkat_id,
                    'kode_sesi' => $kodeSesi,
                    'durasi' => $request->durasi,
                    'harga' => $harga,
                    'status' => 'belum_main',
                    'waktu_mulai' => null,
                    'waktu_selesai' => null,
               ]);

               event(new RentalUpdated($sesirental));

               return response()->json([
                    'status' => true,
                    'message' => 'Sesi rental berhasil ditambahkan.',
                    'data' => $sesirental->load([
                         'pelanggan',
                         'perangkat.jenisPerangkat'
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
                    'perangkat.jenisPerangkat'
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

     // Fungsi memverifikasi kode sesi rental

     // Fungsi memverifikasi kode sesi rental
     public function verifyCode(Request $request)
     {
          try {
               $request->validate([
                    'kode_sesi' => 'required|string',
               ]);

               $kodeSesi = strtoupper(trim($request->kode_sesi));

               $sesirental = Sesi_rental::with([
                    'pelanggan',
                    'perangkat.jenisPerangkat'
               ])->where('kode_sesi', $kodeSesi)->first();

               if (!$sesirental) {
                    return response()->json([
                         'status' => false,
                         'message' => 'Kode rental tidak ditemukan.'
                    ], 404);
               }

               // Cek apakah pembayaran untuk sesi ini sudah lunas
               $pembayaran = Pembayaran::where('sesi_id', $sesirental->id)
                    ->where('status', 'lunas')    
                    ->first();

               if (!$pembayaran) {
                    return response()->json([
                         'status' => false,
                         'message' => 'Pembayaran belum dikonfirmasi. Silakan lakukan pembayaran terlebih dahulu.'
                    ], 422);
               }

               return response()->json([
                    'status' => true,
                    'message' => 'Kode rental valid dan pembayaran sudah dikonfirmasi.',
                    'data' => $sesirental
               ], 200);

          } catch (Exception $e) {
               return response()->json([
                    'status' => false,
                    'message' => $e->getMessage()
               ], 500);
          }
     }

     // Fungsi update status menjadi aktif, waktu mulai, waktu selesai

     public function startSession($id)
     {
          try {
               $sesirental = Sesi_rental::find($id);

               if (!$sesirental) {
                    return response()->json([
                         'status' => false,
                         'message' => 'Sesi rental tidak ditemukan.'
                    ], 404);
               }

               if ($sesirental->status !== 'belum_main') {
                    return response()->json([
                         'status' => false,
                         'message' => 'Sesi rental tidak dapat dimulai.'
                    ], 422);
               }

               $perangkat = Perangkat::find($sesirental->perangkat_id);

               if (!$perangkat) {
                    return response()->json([
                         'status' => false,
                         'message' => 'Perangkat tidak ditemukan.'
                    ], 404);
               }

               // Cek apakah PC sedang digunakan
               if ($perangkat->status === 'digunakan') {
                    return response()->json([
                         'status' => false,
                         'message' => 'Perangkat sedang digunakan.'
                    ], 422);
               }

               // Cek apakah PC sedang maintenance
               if ($perangkat->status === 'maintenance') {
                    return response()->json([
                         'status' => false,
                         'message' => 'Perangkat sedang maintenance.'
                    ], 422);
               }

               $waktuMulai = now();

               $waktuSelesai = $waktuMulai->copy()->addMinutes(
                    $sesirental->durasi * 60
               );

               // Aktifkan sesi rental
               $sesirental->update([
                    'status' => 'sedang_main',
                    'waktu_mulai' => $waktuMulai,
                    'waktu_selesai' => $waktuSelesai,
               ]);

               // Tandai PC sedang digunakan
               $perangkat->update([
                    'status' => 'digunakan',
               ]);

               event(new RentalUpdated($sesirental));

               return response()->json([
                    'status' => true,
                    'message' => 'Sesi rental berhasil dimulai.',
                    'data' => $sesirental->load([
                         'pelanggan',
                         'perangkat.jenisPerangkat'
                    ])
               ], 200);

          } catch (Exception $e) {
               return response()->json([
                    'status' => false,
                    'message' => $e->getMessage()
               ], 500);
          }
     }


     // Fungsi update status menjadi selesai dan waktu selesai
     public function finishSession($id)
     {
          try {
               $sesirental = Sesi_rental::find($id);

               if (!$sesirental) {
                    return response()->json([
                         'status' => false,
                         'message' => 'Sesi rental tidak ditemukan.'
                    ], 404);
               }

               if ($sesirental->status !== 'sedang_main') {
                    return response()->json([
                         'status' => false,
                         'message' => 'Sesi rental tidak sedang aktif.'
                    ], 422);
               }

               $perangkat = Perangkat::find($sesirental->perangkat_id);

               // Selesaikan sesi rental
               $sesirental->update([
                    'status' => 'selesai',
                    'waktu_selesai' => now(),
               ]);

               // Kembalikan PC menjadi tersedia
               if ($perangkat) {
                    $perangkat->update([
                         'status' => 'tersedia',
                    ]);
               }

               event(new RentalUpdated($sesirental));

               return response()->json([
                    'status' => true,
                    'message' => 'Sesi rental telah selesai.',
                    'data' => $sesirental->load([
                         'pelanggan',
                         'perangkat.jenisPerangkat'
                    ])
               ], 200);

          } catch (Exception $e) {
               return response()->json([
                    'status' => false,
                    'message' => $e->getMessage()
               ], 500);
          }
     }

     // fungsi mendapatkan sesi rental yang sedang barjalan 
     // agar tidak kembali ke halaman masukan kode
     public function getRentalSession($id)
     {
          try {
               $sesirental = Sesi_rental::with([
                    'pelanggan',
                    'perangkat.jenisPerangkat'
               ])->find($id);

               if (!$sesirental) {
                    return response()->json([
                         'status' => false,
                         'message' => 'Sesi rental tidak ditemukan.'
                    ], 404);
               }

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

     // fungsi mendapatkan data sesi yang sedang aktif atau digunakan
     public function getActiveSession($perangkatId)
     {
          try {
               $sesirental = Sesi_rental::with([
                    'pelanggan',
                    'perangkat.jenisPerangkat'
               ])
                    ->where('perangkat_id', $perangkatId)
                    ->where('status', 'sedang_main')
                    ->first();

               return response()->json([
                    'status' => true,
                    'message' => $sesirental
                         ? 'Sesi aktif ditemukan.'
                         : 'Tidak ada sesi aktif.',
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
                    'durasi' => 'required|integer',
                    'harga' => 'required|numeric',
                    'status' => 'required|string',
                    'waktu_mulai' => 'required|date',
                    'waktu_selesai' => 'nullable|date',
               ]);

               $sesirental->update($request->only([
                    'pelanggan_id',
                    'perangkat_id',
                    'durasi',
                    'harga',
                    'status',
                    'waktu_mulai',
                    'waktu_selesai'
               ]));

               event(new RentalUpdated($sesirental));

               return response()->json([
                    'status' => true,
                    'message' => 'Sesi rental berhasil diperbarui.',
                    'data' => $sesirental->load([
                         'pelanggan',
                         'perangkat.jenisPerangkat'
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

               event(new RentalUpdated());

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