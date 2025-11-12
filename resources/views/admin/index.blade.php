@extends('admin.template.main')

@section('css')
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
@endsection

@section('main-content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Selamat Datang Admin, {{ $user->name }}</h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4 col-6">
                    <!-- small box -->
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <p>Jumlah Member</p>

                            <h3>{{ $membersCount }}</h3>
                        </div>
                        <div class="icon">
                            <i class="ion ion-ios-people"></i>
                        </div>
                        <a href="{{ route('admin.members.index') }}" class="small-box-footer">Lihat member <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-md-4 col-6">
                    <!-- small box -->
                    <div class="small-box bg-info">
                        <div class="inner">
                            <p>Jumlah Transaksi</p>

                            <h3>{{ $transactionsCount }}</h3>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <a href="{{ route('admin.transactions.index') }}" class="small-box-footer">Lihat transaksi <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-md-4 col-6">
                    <!-- small box -->
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <p>Jadwal Rutin Hari Ini</p>

                            <h3>{{ count($jadwalHariIni) }}</h3>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <a href="{{ url('admin/jadwal') }}" class="small-box-footer">Lihat Jadwal Rutinan <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>

            @foreach (['error', 'warning', 'success'] as $msg)
                @if (session($msg))
                    <div class="col-12">
                        <div class="alert alert-{{ $msg }} alert-dismissible fade show" role="alert">
                            {{ session($msg) }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                @endif
            @endforeach

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="mb-3">Jadwal Rutin Hari Ini</h3>
                            <table class="table">
                                <thead class="thead-light">
                                    <tr>
                                        <th>No</th>
                                        <th>User</th>
                                        <th>Tipe</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($jadwalHariIni as $schedule)
                                        @php
                                            if ($schedule->last_proccess == date('Y-m-d')) {
                                                $status = 'Sudah Diproses';
                                            } else {
                                                $status = 'Belum Diproses';
                                            }
                                        @endphp
                                        <tr>
                                            <td>{{ $loop->iteration }}.</td>
                                            <td>{{ $schedule->user ? $schedule->user->name : '-' }}</td>
                                            <td>{{ ucfirst($schedule->type) }}</td>
                                            <td>{{ $schedule->start_date }}</td>
                                            <td>{{ $schedule->end_date ?? '-' }}</td>
                                            <td>{{ $status }}</td>
                                            <td>
                                                <!-- Tombol Detail -->
                                                <button type="button" class="btn btn-info btn-sm" data-toggle="modal"
                                                    data-target="#detailScheduleModal{{ $schedule->id }}">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                @if ($schedule->last_proccess != date('Y-m-d'))
                                                    <a href="{{ url('admin/prosesJadwal/' . $schedule->id) }}"
                                                        class="btn btn-success btn-sm" title="Proses Jadwal Rutin"
                                                        onclick="return confirm('Proses jadwal rutin?')"><i
                                                            class="fas fa-check"></i></a>
                                                @endif
                                            </td>
                                        </tr>

                                        <!-- Modal Detail Jadwal -->
                                        <div class="modal fade" id="detailScheduleModal{{ $schedule->id }}" tabindex="-1"
                                            role="dialog" aria-labelledby="detailScheduleModalLabel{{ $schedule->id }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-info text-white">
                                                        <h5 class="modal-title"
                                                            id="detailScheduleModalLabel{{ $schedule->id }}">
                                                            Detail Jadwal Rutin
                                                        </h5>
                                                        <button type="button" class="close text-white" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>

                                                    <div class="modal-body">
                                                        <!-- Tipe Jadwal -->
                                                        <div class="form-group">
                                                            <label for="type">Tipe Jadwal</label>
                                                            <input type="text" class="form-control"
                                                                value="{{ ucfirst($schedule->type) }}" readonly>
                                                        </div>

                                                        <!-- Tanggal Mulai & Akhir -->
                                                        <div class="form-group">
                                                            <label for="start_date">Tanggal Mulai</label>
                                                            <input type="text" class="form-control"
                                                                value="{{ $schedule->start_date }}" readonly>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="end_date">Tanggal Selesai</label>
                                                            <input type="text" class="form-control"
                                                                value="{{ $schedule->end_date ?? 'Berjalan' }}" readonly>
                                                        </div>

                                                        <!-- Jam -->
                                                        <div class="form-group">
                                                            <label for="time">Jam Penjemputan</label>
                                                            <input type="text" class="form-control"
                                                                value="{{ $schedule->time }}" readonly>
                                                        </div>

                                                        <!-- Opsi Penjemputan -->
                                                        <div class="form-group">
                                                            <label for="pickup_option">Opsi Penjemputan</label>
                                                            <input type="text" class="form-control"
                                                                value="@if ($schedule->pickup_option == 'none') Tidak Perlu Penjemputan
                                            @elseif($schedule->pickup_option == 'wa') Penjemputan via WhatsApp
                                            @else Penjemputan via Website @endif"
                                                                readonly>
                                                        </div>

                                                        <!-- Rules (human-friendly) -->
                                                        @php
                                                            // Pastikan $schedule->rules sudah berbentuk array (cast di model)
                                                            $rules = is_string($schedule->rules)
                                                                ? json_decode($schedule->rules, true)
                                                                : $schedule->rules ?? [];
                                                            $typeLabels = [
                                                                'weekly' => 'Mingguan',
                                                                'monthly' => 'Bulanan',
                                                                'custom' => 'Kustom',
                                                            ];
                                                        @endphp

                                                        @if (!empty($rules) && is_array($rules))
                                                            <div class="form-group">
                                                                <label>Aturan Jadwal</label>

                                                                <div>
                                                                    @foreach ($rules as $type => $values)
                                                                        @php
                                                                            // pastikan values adalah array dan ada nilai non-empty
                                                                            if (!is_array($values)) {
                                                                                continue;
                                                                            }
                                                                            $filtered = array_values(
                                                                                array_filter($values, function ($v) {
                                                                                    return !is_null($v) && $v !== '';
                                                                                }),
                                                                            );
                                                                            if (count($filtered) === 0) {
                                                                                continue;
                                                                            }
                                                                            $label =
                                                                                $typeLabels[$type] ?? ucfirst($type);
                                                                        @endphp

                                                                        <div class="mb-2">
                                                                            <strong>{{ $label }}:</strong>
                                                                            <ul class="mb-0">
                                                                                @foreach ($filtered as $val)
                                                                                    @php
                                                                                        // Tentukan cara menampilkan tiap jenis rule
                                                                                        if ($type === 'weekly') {
                                                                                            // contoh: 'senin' => 'Senin'
                                                                                            $display = ucfirst($val);
                                                                                        } elseif ($type === 'monthly') {
                                                                                            // tampilkan "Tanggal X"
                                                                                            $display =
                                                                                                'Tanggal ' .
                                                                                                ltrim(
                                                                                                    (string) $val,
                                                                                                    '0',
                                                                                                );
                                                                                        } elseif ($type === 'custom') {
                                                                                            // coba parse tanggal; jika gagal tampilkan apa adanya
                                                                                            $timestamp = strtotime(
                                                                                                $val,
                                                                                            );
                                                                                            $display = $timestamp
                                                                                                ? date(
                                                                                                    'd M Y',
                                                                                                    $timestamp,
                                                                                                )
                                                                                                : $val;
                                                                                        } else {
                                                                                            $display = $val;
                                                                                        }
                                                                                    @endphp
                                                                                    <li>{{ $display }}</li>
                                                                                @endforeach
                                                                            </ul>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        @endif


                                                        <!-- Rincian Pesanan -->
                                                        <h6 class="mt-4">Rincian Pesanan</h6>
                                                        @foreach ($schedule->order_details as $detail)
                                                            <div class="border rounded p-3 mb-3">
                                                                <div class="form-group">
                                                                    <label>Barang</label>
                                                                    <input type="text" class="form-control"
                                                                        value="{{ $detail['item_name'] ?? '-' }}"
                                                                        readonly>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Servis</label>
                                                                    <input type="text" class="form-control"
                                                                        value="{{ $detail['service_name'] ?? '-' }}"
                                                                        readonly>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Kategori</label>
                                                                    <input type="text" class="form-control"
                                                                        value="{{ $detail['category_name'] ?? '-' }}"
                                                                        readonly>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Banyak</label>
                                                                    <input type="text" class="form-control"
                                                                        value="{{ $detail['quantity'] ?? 0 }}" readonly>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Subtotal</label>
                                                                    <input type="text" class="form-control"
                                                                        value="Rp{{ number_format($detail['subtotal'] ?? 0, 0, ',', '.') }}"
                                                                        readonly>
                                                                </div>
                                                            </div>
                                                        @endforeach


                                                        <!-- Tipe Service -->
                                                        <div class="form-group mt-4">
                                                            <label for="service_type_id">Tipe Service</label>
                                                            <input type="text" class="form-control"
                                                                value="{{ $schedule->serviceType->name }} - Rp{{ number_format($schedule->serviceType->cost, 0, ',', '.') }}"
                                                                readonly>
                                                        </div>

                                                        <!-- Voucher -->
                                                        @if ($schedule->voucher)
                                                            <div class="form-group">
                                                                <label for="voucher_id">Voucher</label>
                                                                <input type="text" class="form-control"
                                                                    value="{{ $schedule->voucher->code }} - {{ $schedule->voucher->discount_percentage }}% (Maks: Rp{{ number_format($schedule->voucher->max_discount, 0, ',', '.') }})"
                                                                    readonly>
                                                            </div>
                                                        @endif

                                                        <!-- Total Estimasi -->
                                                        <div class="form-group">
                                                            <label for="total_estimation">Estimasi Total</label>
                                                            <input type="text" class="form-control"
                                                                value="Rp{{ number_format($schedule->total_amount, 0, ',', '.') }}"
                                                                readonly>
                                                        </div>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Tutup</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>


                    <div class="card">
                        <div class="card-body">
                            <h3 class="mb-3">Transaksi Berjalan (Priority Service): </h3>
                            <table class="table">
                                <thead class="thead-light">
                                    <tr>
                                        <th>ID Transaksi</th>
                                        <th>Tanggal</th>
                                        <th>Nama Member</th>
                                        <th>Status</th>
                                        <th>Biaya Service</th>
                                        <th>Total Harga</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($priorityTransactions as $item)
                                        <tr>
                                            <td>{{ $item->id }}</td>
                                            <td>{{ date('d F Y', strtotime($item->created_at)) }}</td>
                                            <td>{{ $item->member->name }}</td>
                                            <td>
                                                @if ($item->status_id == 3)
                                                    <span class="text-success">Selesai</span>
                                                @else
                                                    @if ($user->role->value == 4)
                                                        @foreach ($statuses as $s)
                                                            @if ($item->status_id == $s->id)
                                                                {{ $s->name }}
                                                            @endif
                                                        @endforeach
                                                    @else
                                                        <input type="hidden" id="csrf_token"
                                                            value="{{ csrf_token() }}">
                                                        <select name="" id="status"
                                                            data-id="{{ $item->id }}"
                                                            data-val="{{ $item->status_id }}" class="select-status">
                                                            @foreach ($statuses as $s)
                                                                @if ($item->status_id == $s->id)
                                                                    <option selected value="{{ $s->id }}">
                                                                        {{ $s->name }}</option>
                                                                @else
                                                                    <option value="{{ $s->id }}">
                                                                        {{ $s->name }}
                                                                    </option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    @endif
                                                @endif
                                            </td>
                                            <td>{{ $item->getFormattedServiceCost() }}</td>
                                            <td>{{ $item->getFormattedTotal() }}</td>
                                            <td>
                                                <a href="#" class="badge badge-info btn-detail" data-toggle="modal"
                                                    data-target="#transactionDetailModal"
                                                    data-id="{{ $item->id }}">Detail</a>
                                                <a href="{{ route('admin.transactions.print.index', ['transaction' => $item->id]) }}"
                                                    class="badge badge-primary" target="_blank">Cetak</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>


                    <div class="card">
                        <div class="card-body">
                            <h3 class="mb-3">Transaksi Berjalan (Regular Service): </h3>
                            <table class="table">
                                <thead class="thead-light">
                                    <tr>
                                        <th>ID Transaksi</th>
                                        <th>Tanggal</th>
                                        <th>Nama Member</th>
                                        <th>Status</th>
                                        <th>Biaya Service</th>
                                        <th>Total Harga</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($recentTransactions as $item)
                                        <tr>
                                            <td>{{ $item->id }}</td>
                                            <td>{{ date('d F Y', strtotime($item->created_at)) }}</td>
                                            <td>{{ $item->member->name }}</td>
                                            <td>
                                                @if ($item->status_id == 3)
                                                    <span class="text-success">Selesai</span>
                                                @else
                                                    @if ($user->role->value == 4)
                                                        @foreach ($statuses as $s)
                                                            @if ($item->status_id == $s->id)
                                                                {{ $s->name }}
                                                            @endif
                                                        @endforeach
                                                    @else
                                                        <input type="hidden" id="csrf_token"
                                                            value="{{ csrf_token() }}">
                                                        <select name="" id="status"
                                                            data-id="{{ $item->id }}"
                                                            data-val="{{ $item->status_id }}" class="select-status">
                                                            @foreach ($statuses as $s)
                                                                @if ($item->status_id == $s->id)
                                                                    <option selected value="{{ $s->id }}">
                                                                        {{ $s->name }}</option>
                                                                @else
                                                                    <option value="{{ $s->id }}">
                                                                        {{ $s->name }}
                                                                    </option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    @endif
                                                @endif
                                            </td>
                                            <td>{{ $item->getFormattedServiceCost() }}</td>
                                            <td>{{ $item->getFormattedTotal() }}</td>
                                            <td>
                                                <a href="#" class="badge badge-info btn-detail" data-toggle="modal"
                                                    data-target="#transactionDetailModal"
                                                    data-id="{{ $item->id }}">Detail</a>
                                                <a href="{{ route('admin.transactions.print.index', ['transaction' => $item->id]) }}"
                                                    class="badge badge-primary" target="_blank">Cetak</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </div>
@endsection

@section('modals')
    <x-admin.modals.transaction-detail-modal />
@endsection

@section('scripts')
    <script src="{{ asset('js/ajax.js') }}"></script>
@endsection
