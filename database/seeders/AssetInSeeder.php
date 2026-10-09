<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\AssetIn;
use App\Models\AssetSerialNumber;
use App\Models\User;
use Illuminate\Database\Seeder;

class AssetInSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first() ?? User::first();

        // [kode_aset => [qty, tanggal, supplier]]
        $transaksi = [
            'LPT-001' => ['qty' => 5, 'date' => now()->subDays(20), 'supplier' => 'CV Sumber Teknologi'],
            'MON-001' => ['qty' => 8, 'date' => now()->subDays(18), 'supplier' => 'CV Sumber Teknologi'],
            'PRN-001' => ['qty' => 3, 'date' => now()->subDays(15), 'supplier' => 'Toko Komputer Jaya'],
            'ATK-001' => ['qty' => 100, 'date' => now()->subDays(10), 'supplier' => 'Toko ATK Makmur'],
            'ATK-002' => ['qty' => 200, 'date' => now()->subDays(10), 'supplier' => 'Toko ATK Makmur'],
            'FRN-001' => ['qty' => 20, 'date' => now()->subDays(25), 'supplier' => 'Mebel Jati Indah'],
        ];

        foreach ($transaksi as $kode => $data) {
            $asset = Asset::where('code', $kode)->first();

            if (! $asset) {
                continue;
            }

            /** @var AssetIn $assetIn */
            $assetIn = AssetIn::create([
                'asset_id' => $asset->id,
                'qty' => $data['qty'],
                'date' => $data['date'],
                'supplier' => $data['supplier'],
                'notes' => 'Data awal (seeder)',
                'created_by' => $admin->id,
            ]);
            // qty di tabel assets otomatis bertambah lewat AssetInObserver

            if ($asset->has_serial_number) {
                $generated = [];

                for ($i = 1; $i <= $data['qty']; $i++) {
                    $sn = strtoupper($kode) . '-SN-' . str_pad($i, 4, '0', STR_PAD_LEFT);

                    AssetSerialNumber::create([
                        'asset_id' => $asset->id,
                        'serial_number' => $sn,
                        'status' => 'in',
                        'asset_in_id' => $assetIn->id,
                    ]);

                    $generated[] = $sn;
                }

                $this->command->line("  -> {$kode}: " . count($generated) . " SN dibuat (" . implode(', ', $generated) . ')');
            }
        }

        $this->command->info('AssetIn seeder selesai: data aset masuk beserta SN berhasil dibuat.');
    }
}
