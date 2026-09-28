<?php

namespace Tests\Feature;

use App\Models\Pelanggan;
use App\Models\Pembayaran;
use App\Models\Perangkat;
use App\Models\Jenis_perangkat;
use App\Models\Sesi_rental;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExportDataTest extends TestCase
{
    use RefreshDatabase;

    public function test_pelanggan_export_downloads_excel_file(): void
    {
        $user = User::factory()->create();
        Pelanggan::create(['name' => 'Budi']);

        $response = $this->actingAs($user)->get('/api/pelanggan/export');

        $response->assertOk();
        $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $this->assertStringContainsString('pelanggan.xlsx', $response->headers->get('Content-Disposition'));
    }

    public function test_pembayaran_export_downloads_excel_file(): void
    {
        $user = User::factory()->create();

        $pelanggan = Pelanggan::create(['name' => 'Siti']);
        $jenis = Jenis_perangkat::create([
            'name' => 'PC Gaming',
            'harga_per_jam' => 50000,
        ]);
        $perangkat = Perangkat::create([
            'jenis_id' => $jenis->id,
            'name' => 'PC-01',
            'status' => 'tersedia',
        ]);
        $sesi = Sesi_rental::create([
            'pelanggan_id' => $pelanggan->id,
            'perangkat_id' => $perangkat->id,
            'kode_sesi' => 'SESI-001',
            'durasi' => 2,
            'harga' => 100000,
            'status' => 'selesai',
            'waktu_mulai' => now(),
            'waktu_selesai' => now()->addHours(2),
        ]);
        Pembayaran::create([
            'sesi_id' => $sesi->id,
            'jumlah' => 100000,
            'status' => 'lunas',
            'waktu_bayar' => now(),
        ]);

        $response = $this->actingAs($user)->get('/api/pembayaran/export');

        $response->assertOk();
        $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $this->assertStringContainsString('pembayaran.xlsx', $response->headers->get('Content-Disposition'));
    }

    public function test_pelanggan_gets_unique_auto_generated_member_code(): void
    {
        $pelanggan1 = Pelanggan::create(['name' => 'Andi']);
        $pelanggan2 = Pelanggan::create(['name' => 'Andi']);

        $this->assertNotEmpty($pelanggan1->kode_member);
        $this->assertNotEmpty($pelanggan2->kode_member);
        $this->assertNotSame($pelanggan1->kode_member, $pelanggan2->kode_member);
        $this->assertMatchesRegularExpression('/^MBR-\d{6}$/', $pelanggan1->kode_member);
    }
}
