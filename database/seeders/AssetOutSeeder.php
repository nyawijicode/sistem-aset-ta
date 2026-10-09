<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\AssetOut;
use App\Models\AssetSerialNumber;
use App\Models\User;
use Illuminate\Database\Seeder;

class AssetOutSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first() ?? User::first();

        // [kode_aset => [qty_keluar, tanggal, penerima]]
        // LPT-001, MON-001, PRN-001 = aset ber-SN -> SN yang keluar dipilih otomatis dari yang masih tersedia
        // ATK-001, FRN-001 = aset tanpa SN -> cukup kurangi qty
        $transaksi = [
            'LPT-001' => ['qty' => 2, 'date' => now()->subDays(5), 'recipient' => 'Divisi Keuangan'],
            'MON-001' => ['qty' => 3, 'date' => now()->subDays(4), 'recipient' => 'Divisi Marketing'],
            'PRN-001' => ['qty' => 1, 'date' => now()->subDays(3), 'recipient' => 'Divisi IT'],
            'ATK-001' => ['qty' => 30, 'date' => now()->subDays(2), 'recipient' => 'Divisi HRD'],
            'FRN-001' => ['qty' => 5, 'date' => now()->subDays(1), 'recipient' => 'Divisi Operasional'],
        ];

        foreach ($transaksi as $kode => $data) {
            $asset = Asset::where('code', $kode)->first();

            if (! $asset || $asset->qty < $data['qty']) {
                // lewati kalau stok tidak cukup, supaya seeder tidak gagal
                continue;
            }

            /** @var AssetOut $assetOut */
            $assetOut = AssetOut::create([
                'asset_id' => $asset->id,
                'qty' => $data['qty'],
                'date' => $data['date'],
                'recipient' => $data['recipient'],
                'notes' => 'Data awal (seeder)',
                'created_by' => $admin->id,
            ]);
            // qty di tabel assets otomatis berkurang lewat AssetOutObserver

            if ($asset->has_serial_number) {
                $serials = AssetSerialNumber::where('asset_id', $asset->id)
                    ->where('status', 'in')
                    ->limit($data['qty'])
                    ->get();

                AssetSerialNumber::whereIn('id', $serials->pluck('id'))->update([
                    'status' => 'out',
                    'asset_out_id' => $assetOut->id,
                ]);

                $this->command->line("  -> {$kode}: SN keluar (" . $serials->pluck('serial_number')->implode(', ') . ')');
            }
        }

        $this->command->info('AssetOut seeder selesai: data aset keluar berhasil dibuat.');
    }
}
