<x-laporan.layout :title="'Laporan Stock Opname'">
    <div style="margin-bottom: 16px;">
        <table style="border: none; width: auto;">
            <tr>
                <td style="border: none; padding: 2px 8px 2px 0; font-weight: bold; width: 130px;">Tanggal Opname</td>
                <td style="border: none; padding: 2px 0;">: {{ $opname->tanggal->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td style="border: none; padding: 2px 8px 2px 0; font-weight: bold;">Dibuat Oleh</td>
                <td style="border: none; padding: 2px 0;">: {{ $opname->creator->name ?? '-' }}</td>
            </tr>
            @if($opname->keterangan)
            <tr>
                <td style="border: none; padding: 2px 8px 2px 0; font-weight: bold;">Keterangan</td>
                <td style="border: none; padding: 2px 0;">: {{ $opname->keterangan }}</td>
            </tr>
            @endif
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Aset</th>
                <th>Nama Aset</th>
                <th>Kategori</th>
                <th>Satuan</th>
                <th style="text-align: center;">Stok Fisik</th>
                <th style="text-align: center;">Stok Sistem</th>
                <th style="text-align: center;">Selisih</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($opname->items as $i => $item)
            @php
            $selisih = $item->stok_fisik - $item->stok_sistem;
            $selisihColor = $selisih < 0 ? '#dc2626' : ($selisih> 0 ? '#d97706' : '#16a34a');
                $selisihText = ($selisih > 0 ? '+' : '') . $selisih;
                @endphp
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $item->asset->code ?? '-' }}</td>
                    <td>{{ $item->asset->name ?? '-' }}</td>
                    <td>{{ $item->asset->category ?? '-' }}</td>
                    <td>{{ $item->asset->unit ?? '-' }}</td>
                    <td style="text-align: center;">{{ $item->stok_fisik }}</td>
                    <td style="text-align: center;">{{ $item->stok_sistem }}</td>
                    <td style="text-align: center; font-weight: bold; color: {{ $selisihColor }};">
                        {{ $selisihText }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center; color: #888;">Tidak ada data</td>
                </tr>
                @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="8" style="text-align: right; font-weight: bold; padding-top: 8px;">
                    Total: {{ $opname->items->count() }} aset
                </td>
            </tr>
        </tfoot>
    </table>

    <div style="margin-top: 12px; font-size: 11px; color: #555;">
        <strong>Keterangan Selisih:</strong>
        <span style="color: #16a34a; margin-left: 8px;">■ 0 = Sesuai</span>
        <span style="color: #d97706; margin-left: 8px;">■ Positif = Stok fisik lebih banyak</span>
        <span style="color: #dc2626; margin-left: 8px;">■ Negatif = Stok fisik kurang</span>
    </div>
</x-laporan.layout>