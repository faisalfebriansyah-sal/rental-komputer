<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use Exception;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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

    public function export()
    {
        try {
            $pelanggans = Pelanggan::latest()->get();
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('Pelanggan');
            $sheet->fromArray([
                ['ID', 'Nama', 'Dibuat', 'Diperbarui'],
            ], null, 'A1');

            foreach ($pelanggans as $index => $pelanggan) {
                $sheet->fromArray([[
                    $pelanggan->id,
                    $pelanggan->name,
                    $pelanggan->created_at?->format('Y-m-d H:i:s'),
                    $pelanggan->updated_at?->format('Y-m-d H:i:s'),
                ]], null, 'A' . ($index + 2));
            }

            foreach (['A', 'B', 'C', 'D'] as $column) {
                $sheet->getColumnDimension($column)->setAutoSize(true);
            }

            $filePath = tempnam(sys_get_temp_dir(), 'pelanggan_');
            $writer = new Xlsx($spreadsheet);
            $writer->save($filePath);

            return response()->download(
                $filePath,
                'pelanggan.xlsx',
                ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']
            )->deleteFileAfterSend(true);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name'  => 'required|string',
            ]);

            $pelanggan = Pelanggan::create([
                'name'  => $request->name,
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
            ]);

            $pelanggan->name = $request->name;
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
