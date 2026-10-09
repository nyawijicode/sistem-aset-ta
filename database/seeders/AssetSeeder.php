<?php

namespace Database\Seeders;

use App\Models\Asset;
use Illuminate\Database\Seeder;

class AssetSeeder extends Seeder
{
    public function run(): void
    {
        $assets = [
            // Aset dengan Serial Number (barang bernilai tinggi / bisa dilacak per unit)
            [
                'code' => 'LPT-001',
                'name' => 'Laptop ThinkPad T14 i7',
                'category' => 'Elektronik',
                'unit' => 'unit',
                'has_serial_number' => true,
            ],
            [
                'code' => 'MON-001',
                'name' => 'Monitor LG 24 inch',
                'category' => 'Elektronik',
                'unit' => 'unit',
                'has_serial_number' => true,
            ],
            [
                'code' => 'PRN-001',
                'name' => 'Printer Epson L3210',
                'category' => 'Elektronik',
                'unit' => 'unit',
                'has_serial_number' => true,
            ],

            // Aset tanpa Serial Number (barang habis pakai / tidak dilacak per unit)
            [
                'code' => 'ATK-001',
                'name' => 'Kertas HVS A4',
                'category' => 'ATK',
                'unit' => 'rim',
                'has_serial_number' => false,
            ],
            [
                'code' => 'ATK-002',
                'name' => 'Pulpen Standard AE7',
                'category' => 'ATK',
                'unit' => 'pcs',
                'has_serial_number' => false,
            ],
            [
                'code' => 'FRN-001',
                'name' => 'Kursi Kantor',
                'category' => 'Furniture',
                'unit' => 'unit',
                'has_serial_number' => false,
            ],
        ];

        foreach ($assets as $asset) {
            Asset::updateOrCreate(
                ['code' => $asset['code']],
                array_merge($asset, ['qty' => 0]) // qty awal 0, akan bertambah lewat AssetInSeeder
            );
        }

        $this->command->info('Asset seeder selesai: ' . count($assets) . ' aset dibuat.');
    }
}
