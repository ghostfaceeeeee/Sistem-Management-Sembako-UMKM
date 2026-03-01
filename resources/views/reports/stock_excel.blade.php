<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            table-layout: auto;
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        th, td {
            border: 1px solid #d1d5db;
            padding: 6px 8px;
            vertical-align: top;
            white-space: normal;
            word-wrap: break-word;
        }

        th {
            background: #f3f4f6;
            font-weight: 700;
            text-align: left;
        }

        .w-date { width: 150px; }
        .w-product { width: 240px; }
        .w-category { width: 140px; }
        .w-supplier { width: 220px; }
        .w-type { width: 80px; }
        .w-qty { width: 90px; }
        .w-note { width: 320px; }
    </style>
</head>
<body>
<table>
    <colgroup>
        <col class="w-date">
        <col class="w-product">
        <col class="w-category">
        <col class="w-supplier">
        <col class="w-type">
        <col class="w-qty">
        <col class="w-note">
    </colgroup>
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Produk</th>
            <th>Kategori</th>
            <th>Supplier</th>
            <th>Tipe</th>
            <th>Jumlah</th>
            <th>Catatan</th>
        </tr>
    </thead>
    <tbody>
        @foreach($transactions as $transaction)
            <tr>
                <td>{{ $transaction->created_at->format('Y-m-d H:i:s') }}</td>
                <td>{{ $transaction->product->nama_barang ?? '-' }}</td>
                <td>{{ $transaction->product->category->nama ?? '-' }}</td>
                <td>{{ $transaction->product->supplier->nama_supplier ?? '-' }}</td>
                <td>{{ $transaction->type }}</td>
                <td>{{ $transaction->quantity }}</td>
                <td>{{ $transaction->note ?: '-' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
</body>
</html>
