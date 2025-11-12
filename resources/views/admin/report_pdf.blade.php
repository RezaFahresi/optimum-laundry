<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-size: 12px;
        }

        h1,
        h3 {
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 6px;
            text-align: left;
        }

        ul {
            margin: 0;
            padding-left: 15px;
        }
    </style>
</head>

<body>

    <header class="text-center mb-4">
        <div class="row">
            <div class="col-4">
                <h1>{{ config('app.name') }}</h1>
            </div>
            <div class="col-4">
                @if (!empty($dateInput))
                    <h3>Laporan Transaksi Tanggal {{ \Carbon\Carbon::parse($dateInput)->translatedFormat('d F Y') }}
                    </h3>
                @else
                    @php
                        $monthName = \Carbon\Carbon::create()->month($monthInput)->translatedFormat('F');
                    @endphp
                    <h3>Laporan Transaksi Bulan {{ $monthName }} Tahun {{ $yearInput }}</h3>
                @endif
            </div>
            <div class="col-4"></div>
        </div>
    </header>

    <hr>

    <main>
        <p><strong>Banyak Transaksi:</strong> {{ $transactionsCount }} transaksi</p>
        <p><strong>Total Pendapatan:</strong> Rp {{ number_format($revenue, 0, ',', '.') }}</p>

        <table class="table table-bordered mt-4">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tanggal</th>
                    <th>Total</th>
                    <th>Detail Transaksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($transactions as $trx)
                    <tr>
                        <td>{{ $trx->id }}</td>
                        <td>{{ \Carbon\Carbon::parse($trx->created_at)->format('d-m-Y') }}</td>
                        <td>Rp {{ number_format($trx->total, 0, ',', '.') }}</td>
                        <td>
                            <ul>
                                @foreach ($trx->transaction_details as $detail)
                                    @php
                                        $itemName = $detail->price_list->item->name ?? 'Item';
                                        $categoryName = $detail->price_list->category->name ?? 'Kategori';
                                        $serviceName = $detail->price_list->service->name ?? 'Layanan';
                                    @endphp
                                    <li>
                                        {{ $itemName }} ({{ $categoryName }}/{{ $serviceName }})<br>
                                        Qty: {{ $detail->quantity }},
                                        Harga: Rp {{ number_format($detail->price, 0, ',', '.') }},
                                        Subtotal: Rp {{ number_format($detail->sub_total, 0, ',', '.') }}
                                    </li>
                                @endforeach
                            </ul>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">Tidak ada data transaksi</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <hr>

        <p><strong>Banyak Pengeluaran:</strong> {{ $pengeluaranCount }} data</p>
        <p><strong>Total Pengeluaran:</strong> Rp {{ number_format($expense, 0, ',', '.') }}</p>

        <table class="table table-bordered mt-4">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tanggal</th>
                    <th>Judul</th>
                    <th>Jumlah</th>
                    <th>Deskripsi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pengeluaran as $p)
                    <tr>
                        <td>{{ $p->idpengeluaran }}</td>
                        <td>{{ \Carbon\Carbon::parse($p->tanggal)->format('d-m-Y') }}</td>
                        <td>{{ $p->judul }}</td>
                        <td>Rp {{ number_format($p->jumlah, 0, ',', '.') }}</td>
                        <td>{{ $p->deskripsi }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada data pengeluaran</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <hr>
        <h4><strong>Total Laba/Rugi:
                @if ($profit >= 0)
                    <span style="color: green;">Rp {{ number_format($profit, 0, ',', '.') }}</span>
                @else
                    <span style="color: red;">(Rp {{ number_format(abs($profit), 0, ',', '.') }})</span>
                @endif
            </strong></h4>
    </main>


    <hr>

    <footer class="text-end">
        <span class="text-muted small">Dicetak pada Banyuwangi,
            {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</span>
    </footer>

</body>

</html>
