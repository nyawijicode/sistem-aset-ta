<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\StockOpname;
use App\Models\StockOpnameItem;
use App\Models\User;
use Illuminate\Database\Seeder;

class StockOpnameSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first() ?? User::first();

        // Sesi 1: Opname bulan lalu (ada selisih)
        $opname1 = StockOpname::create([
            'tanggal'    => now()->subMonth()->startOfMonth(),
            'keterangan' => 'Stock opname akhir bulan lalu — ditemukan selisih pada beberapa item',
            'created_by' => $admin->id,
        ]);

        $this->seedItems($opname1, [
            'LPT-001' => ['fisik_offset' => 0],   // sesuai sistem
            'MON-001' => ['fisik_offset' => -1],  // 1 unit hilang
            'PRN-001' => ['fisik_offset' => 0],   // sesuai
            'ATK-001' => ['fisik_offset' => -5],  // 5 rim berkurang
            'ATK-002' => ['fisik_offset' => 2],   // 2 pcs lebih (kemungkinan belum terinput keluar)
            'FRN-001' => ['fisik_offset' => 0],   // sesuai
        ]);

        $this->command->info("Opname 1 selesai: {$opname1->items()->count()} item.");

        // Sesi 2: Opname hari ini / terkini (semua sesuai)
        $opname2 = StockOpname::create([
            'tanggal'    => now(),
            'keterangan' => 'Stock opname rutin bulan ini — semua aset sesuai sistem',
            'created_by' => $admin->id,
        ]);

        $this->seedItems($opname2, [
            'LPT-001' => ['fisik_offset' => 0],
            'MON-001' => ['fisik_offset' => 0],
            'PRN-001' => ['fisik_offset' => 0],
            'ATK-001' => ['fisik_offset' => 0],
            'ATK-002' => ['fisik_offset' => 0],
            'FRN-001' => ['fisik_offset' => 0],
        ]);

        $this->command->info("Opname 2 selesai: {$opname2->items()->count()} item.");
        $this->command->info('StockOpname seeder selesai: 2 sesi opname dibuat.');
    }

    /**
     * Buat items untuk satu sesi opname.
     * fisik_offset = selisih stok fisik vs sistem (bisa + atau -)
     */
    private function seedItems(StockOpname $opname, array $asetMap): void
    {
        foreach ($asetMap as $kode => $config) {
            $asset = Asset::where('code', $kode)->first();

            if (! $asset) {
                $this->command->warn("  Aset {$kode} tidak ditemukan, dilewati.");
                continue;
            }

            $stokSistem = $asset->qty;
            $stokFisik  = max(0, $stokSistem + $config['fisik_offset']); // min 0 agar tidak negatif tidak wajar

            // Boleh negatif kalau offset-nya memang besar
            if ($config['fisik_offset'] < 0 && abs($config['fisik_offset']) > $stokSistem) {
                $stokFisik = 0;
            }

            StockOpnameItem::create([
                'stock_opname_id' => $opname->id,
                'asset_id'        => $asset->id,
                'stok_fisik'      => $stokFisik,
                'stok_sistem'     => $stokSistem,
            ]);
        }
    }
}
