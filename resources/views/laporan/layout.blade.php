<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>{{ $title ?? 'Laporan' }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 13px;
            padding: 24px;
        }

        h1 {
            font-size: 18px;
            margin-bottom: 4px;
        }

        .subtitle {
            color: #555;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #999;
            padding: 6px 8px;
            text-align: left;
        }

        th {
            background: #f0f0f0;
        }

        .btn-print {
            margin-bottom: 16px;
            padding: 8px 16px;
            background: #2563eb;
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        @media print {
            .btn-print {
                display: none;
            }
        }
    </style>
</head>

<body>
    <button class="btn-print" onclick="window.print()">Cetak / Simpan PDF</button>
    <h1>{{ $title ?? 'Laporan' }}</h1>
    <div class="subtitle">Dicetak pada: {{ now()->format('d/m/Y H:i') }}</div>
    {{ $slot }}
</body>

</html>