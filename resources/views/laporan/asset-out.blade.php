<x-laporan.layout :title="'Laporan Aset Keluar'">
    {{-- Ringkasan filter aktif --}}
    @php
    $activeFilters = array_filter([
    ($filters['date_from'] ?? null) ? 'Dari: ' . \Carbon\Carbon::parse($filters['date_from'])->format('d/m/Y') : null,
    ($filters['date_to'] ?? null) ? 'Sampai: ' . \Carbon\Carbon::parse($filters['date_to'])->format('d/m/Y') : null,
    ($assetLabel ?? null) ? "Aset: {$assetLabel}" : null,
    ($filters['recipient'] ?? null) ? "Penerima: {$filters['recipient']}" : null,
    ]);
    @endphp
    @if(count($activeFilters))

    @endif

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Kode</th>
                <th>Nama Aset</th>
                <th>Qty</th>
                <th>Serial Number</th>
                <th>Penerima</th>
                <th>Diinput Oleh</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $i => $row)
            @php
            $sns = $row->asset->has_serial_number ? $row->serialNumbers->pluck('serial_number') : collect();
            @endphp
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $row->date->format('d/m/Y') }}</td>
                <td>{{ $row->asset->code }}</td>
                <td>{{ $row->asset->name }}</td>
                <td>{{ $row->qty }}</td>
                <td>
                    @if($row->asset->has_serial_number && $sns->isNotEmpty())
                    {{ $sns->implode(', ') }}
                    @elseif($row->asset->has_serial_number)
                    <em style="color:#888;">-</em>
                    @else
                    <span style="color:#aaa;">-</span>
                    @endif
                </td>
                <td>{{ $row->recipient ?? '-' }}</td>
                <td>{{ $row->creator->name }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align:center;color:#888;">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" style="text-align:right;font-weight:bold;padding-top:8px;">Total keluar:</td>
                <td style="font-weight:bold;">{{ $data->sum('qty') }}</td>
                <td colspan="3"></td>
            </tr>
        </tfoot>
    </table>
</x-laporan.layout>