<x-laporan.layout :title="'Laporan Daftar Aset'">
    {{-- Ringkasan filter aktif --}}
    @php
    $activeFilters = array_filter([
    $filters['category'] ? "Kategori: {$filters['category']}" : null,
    isset($filters['has_serial_number']) && $filters['has_serial_number'] !== '' && $filters['has_serial_number'] !== null
    ? 'SN: ' . (filter_var($filters['has_serial_number'], FILTER_VALIDATE_BOOLEAN) ? 'Punya SN' : 'Tanpa SN')
    : null,
    $filters['stok'] ? 'Stok: ' . ($filters['stok'] === 'tersedia' ? 'Tersedia' : 'Habis') : null,
    ]);
    @endphp
    @if(count($activeFilters))

    @endif

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Nama Aset</th>
                <th>Kategori</th>
                <th>Serial Number</th>
                <th>Stok</th>
                <th>Satuan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $i => $row)
            @php
            $sns = $row->has_serial_number ? $row->serialNumbers->pluck('serial_number') : collect();
            @endphp
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $row->code }}</td>
                <td>{{ $row->name }}</td>
                <td>{{ $row->category ?? '-' }}</td>
                <td>
                    @if($row->has_serial_number && $sns->isNotEmpty())
                    {{ $sns->implode(', ') }}
                    @elseif($row->has_serial_number)
                    <em style="color:#888;">-</em>
                    @else
                    <span style="color:#aaa;">Tidak ada SN</span>
                    @endif
                </td>
                <td>{{ $row->qty }}</td>
                <td>{{ $row->unit }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align:center;color:#888;">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="7" style="text-align:right;font-weight:bold;padding-top:8px;">
                    Total: {{ $data->count() }} aset
                </td>
            </tr>
        </tfoot>
    </table>
</x-laporan.layout>