@extends('member.template.main')

@section('css')
    <link href="{{ asset('vendor/datatables-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/datatables-responsive/css/responsive.bootstrap4.min.css') }}" rel="stylesheet">
    <style>
        #map {
            height: 400px;
            width: 100%;
        }
    </style>
@endsection

@section('main-content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Tambah Pesanan</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
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

                <!-- KIRI: FORM TAMBAH PESANAN -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Tambah Pesanan</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ url('member/transactions-tambah-session') }}" method="post">
                                @csrf
                                <input type="hidden" id="id-member" name="member-id"
                                    value="{{ $memberIdSessionTransaction ?? Auth::user()->id }}">

                                <!-- Barang -->
                                <div class="form-group">
                                    <label for="barang">Barang</label>
                                    <select class="form-control" id="barang" name="item">
                                        @foreach ($items as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Servis -->
                                <div class="form-group">
                                    <label for="servis">Servis</label>
                                    <select class="form-control" id="servis" name="service">
                                        @foreach ($services as $service)
                                            <option value="{{ $service->id }}">{{ $service->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Kategori -->
                                <div class="form-group">
                                    <label for="kategori">Kategori</label>
                                    <select class="form-control" id="kategori" name="category">
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Banyak -->
                                <div class="form-group">
                                    <label for="banyak">Banyak</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <button type="button" class="quantity-left-minus btn btn-danger btn-number"
                                                data-type="minus">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                        </div>
                                        <input type="text" id="quantity" name="quantity"
                                            class="form-control text-center" value="1" min="1" max="100">
                                        <div class="input-group-append">
                                            <button type="button" class="quantity-right-plus btn btn-success btn-number"
                                                data-type="plus">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Opsi Penjemputan -->
                                <div class="form-group">
                                    <label for="pickup_option">Opsi Penjemputan</label>
                                    <select class="form-control" id="pickup_option" name="pickup_option" required>
                                        <option value="">-- Pilih Opsi --</option>
                                        <option value="none">Tidak Perlu Penjemputan</option>
                                        <option value="wa">Penjemputan via WhatsApp</option>
                                        <option value="app">Penjemputan via Website</option>
                                    </select>
                                </div>

                                <!-- Input Lokasi Sharelok -->
                                <div class="form-group d-none" id="pickup_location_wrapper">
                                    <label for="pickup_location">Lokasi Sharelok</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="pickup_location"
                                            name="pickup_location" placeholder="Masukkan titik lokasi / sharelok">
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-outline-primary" data-toggle="modal"
                                                data-target="#mapModal">
                                                Pilih di Peta
                                            </button>
                                        </div>
                                    </div>
                                    <small class="text-muted">Masukkan alamat lengkap atau pilih lokasi di peta.</small>
                                </div>

                                <!-- Link WA Manual -->
                                <div class="form-group d-none" id="pickup_wa_wrapper">
                                    <a href="https://wa.me/6285283305179?text=Halo%20Laundry,%20mohon%20jemput%20laundry%20saya"
                                        target="_blank" id="pickup_wa_link" class="btn btn-success btn-block">
                                        Chat Admin via WhatsApp
                                    </a>
                                </div>

                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary">Tambah Pesanan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- KANAN: TABEL PESANAN -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">Daftar Pesanan</h5>
                        </div>
                        <div class="card-body">
                            <table id="tbl-input-transaksi"
                                class="table table-striped table-bordered dt-responsive nowrap">
                                <thead class="thead-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Barang</th>
                                        <th>Servis</th>
                                        <th>Kategori</th>
                                        <th>Banyak</th>
                                        <th>Harga</th>
                                        <th>Penjemputan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($sessionTransaction) && count($sessionTransaction) > 0)
                                        @foreach ($sessionTransaction as $index => $trx)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $trx['itemName'] }}</td>
                                                <td>{{ $trx['serviceName'] }}</td>
                                                <td>{{ $trx['categoryName'] }}</td>
                                                <td>{{ $trx['quantity'] }}</td>
                                                <td>Rp{{ number_format($trx['subTotal'], 0, ',', '.') }}</td>
                                                <td>{{ $trx['pickup_option'] }}</td>
                                                <td>
                                                    <form
                                                        action="{{ url('member/transactions-hapus-session/' . $index) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Yakin ingin menghapus item ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>

                            @if (isset($sessionTransaction) && count($sessionTransaction) > 0)
                                <button id="btn-bayar" class="btn btn-success btn-block mt-3" data-toggle="modal"
                                    data-target="#paymentsModal">
                                    Bayar
                                </button>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- JADWAL RUTIN -->
                <div class="col-12 mt-4">
                    <div class="card">
                        <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Jadwal Rutin</h5>
                            <button class="btn btn-sm btn-primary ml-auto" data-toggle="modal"
                                data-target="#addScheduleModal">
                                <i class="fas fa-plus"></i> Tambah Jadwal
                            </button>
                        </div>

                        <div class="card-body">
                            <table class="table table-bordered table-striped">
                                <thead class="thead-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Tipe</th>
                                        <th>Jam</th>
                                        <th>Periode</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($schedules as $index => $schedule)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ ucfirst($schedule->type) }}</td>
                                            <td>{{ $schedule->time }}</td>
                                            <td>
                                                {{ $schedule->start_date }}
                                                @if ($schedule->end_date)
                                                    s/d {{ $schedule->end_date }}
                                                @else
                                                    (berjalan)
                                                @endif
                                            </td>
                                            <td>
                                                <span
                                                    class="badge
                            @if ($schedule->status == 'active') badge-success
                            @elseif($schedule->status == 'paused') badge-warning
                            @else badge-danger @endif">
                                                    {{ ucfirst($schedule->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <!-- Tombol Detail -->
                                                <button class="btn btn-info btn-sm" data-toggle="modal"
                                                    data-target="#detailScheduleModal{{ $schedule->id }}">
                                                    <i class="fas fa-eye"></i>
                                                </button>

                                                <!-- Tombol Edit (letakkan di kolom Aksi jika belum ada) -->
                                                <button class="btn btn-warning btn-sm" data-toggle="modal"
                                                    data-target="#editScheduleModal{{ $schedule->id }}">
                                                    <i class="fas fa-edit"></i>
                                                </button>


                                                <!-- Tombol Hapus -->
                                                <form action="{{ url('member/hapusSchedule/' . $schedule->id) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Hapus jadwal ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-danger btn-sm">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>

                                        <!-- Modal Edit Jadwal (selengkap modal detail, tapi editable) -->
                                        <div class="modal fade" id="editScheduleModal{{ $schedule->id }}" tabindex="-1"
                                            role="dialog" aria-labelledby="editScheduleModalLabel{{ $schedule->id }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <form action="{{ url('member/updateSchedule/' . $schedule->id) }}"
                                                        method="POST" class="edit-schedule-form">
                                                        @csrf
                                                        @method('PUT')

                                                        <div class="modal-header bg-warning text-dark">
                                                            <h5 class="modal-title"
                                                                id="editScheduleModalLabel{{ $schedule->id }}">Edit Jadwal
                                                                Rutin</h5>
                                                            <button type="button" class="close text-dark"
                                                                data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>

                                                        <div class="modal-body">
                                                            {{-- Basic meta --}}
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>Tipe Jadwal</label>
                                                                        <select name="type" class="form-control"
                                                                            required>
                                                                            <option value="daily"
                                                                                {{ $schedule->type == 'daily' ? 'selected' : '' }}>
                                                                                Harian</option>
                                                                            <option value="weekly"
                                                                                {{ $schedule->type == 'weekly' ? 'selected' : '' }}>
                                                                                Mingguan</option>
                                                                            <option value="monthly"
                                                                                {{ $schedule->type == 'monthly' ? 'selected' : '' }}>
                                                                                Bulanan</option>
                                                                            <option value="custom"
                                                                                {{ $schedule->type == 'custom' ? 'selected' : '' }}>
                                                                                Kustom</option>
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>Tanggal Mulai</label>
                                                                        <input type="date" name="start_date"
                                                                            class="form-control"
                                                                            value="{{ $schedule->start_date }}" required>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>Tanggal Selesai (opsional)</label>
                                                                        <input type="date" name="end_date"
                                                                            class="form-control"
                                                                            value="{{ $schedule->end_date }}">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>Jam Penjemputan</label>
                                                                        <input type="time" name="time"
                                                                            class="form-control"
                                                                            value="{{ $schedule->time }}" required>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>Opsi Penjemputan</label>
                                                                        <select name="pickup_option" class="form-control"
                                                                            required>
                                                                            <option value="none"
                                                                                {{ $schedule->pickup_option == 'none' ? 'selected' : '' }}>
                                                                                Tidak Perlu Penjemputan</option>
                                                                            <option value="wa"
                                                                                {{ $schedule->pickup_option == 'wa' ? 'selected' : '' }}>
                                                                                Penjemputan via WhatsApp</option>
                                                                            <option value="app"
                                                                                {{ $schedule->pickup_option == 'app' ? 'selected' : '' }}>
                                                                                Penjemputan via Website</option>
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>Status</label>
                                                                        <select name="status" class="form-control"
                                                                            required>
                                                                            <option value="active"
                                                                                {{ $schedule->status == 'active' ? 'selected' : '' }}>
                                                                                Aktif</option>
                                                                            <option value="paused"
                                                                                {{ $schedule->status == 'paused' ? 'selected' : '' }}>
                                                                                Ditunda</option>
                                                                            <option value="cancelled"
                                                                                {{ $schedule->status == 'cancelled' ? 'selected' : '' }}>
                                                                                Dibatalkan</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            {{-- Rules (weekly / monthly / custom) --}}
                                                            @php
                                                                $rulesData = is_string($schedule->rules)
                                                                    ? json_decode($schedule->rules, true)
                                                                    : $schedule->rules ?? [];
                                                                $weeklySelected = $rulesData['weekly'] ?? [];
                                                                $monthlySelected = $rulesData['monthly'] ?? [];
                                                                $customSelected = $rulesData['custom'] ?? [];
                                                            @endphp

                                                            <div class="form-group">
                                                                <label>Aturan Jadwal</label>
                                                                <div id="rulesWrapper-{{ $schedule->id }}">
                                                                    <div id="weeklyRules-{{ $schedule->id }}"
                                                                        style="display: {{ $schedule->type === 'weekly' ? 'block' : 'none' }};">
                                                                        <label>Pilih Hari</label><br>
                                                                        @php $days = ['senin','selasa','rabu','kamis','jumat','sabtu','minggu']; @endphp
                                                                        @foreach ($days as $d)
                                                                            <div class="form-check form-check-inline">
                                                                                <input class="form-check-input"
                                                                                    type="checkbox" name="rules[weekly][]"
                                                                                    value="{{ $d }}"
                                                                                    id="weekly-{{ $schedule->id }}-{{ $d }}"
                                                                                    {{ in_array($d, $weeklySelected) ? 'checked' : '' }}>
                                                                                <label class="form-check-label"
                                                                                    for="weekly-{{ $schedule->id }}-{{ $d }}">{{ ucfirst($d) }}</label>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>

                                                                    <div id="monthlyRules-{{ $schedule->id }}"
                                                                        style="display: {{ $schedule->type === 'monthly' ? 'block' : 'none' }};">
                                                                        <label>Pilih Tanggal (Bulanan)</label>
                                                                        <select class="form-control"
                                                                            name="rules[monthly][]" multiple>
                                                                            @for ($i = 1; $i <= 31; $i++)
                                                                                <option value="{{ $i }}"
                                                                                    {{ in_array($i, $monthlySelected) ? 'selected' : '' }}>
                                                                                    {{ $i }}</option>
                                                                            @endfor
                                                                        </select>
                                                                    </div>

                                                                    <div id="customRules-{{ $schedule->id }}"
                                                                        style="display: {{ $schedule->type === 'custom' ? 'block' : 'none' }};">
                                                                        <label>Pilih Tanggal Khusus (Custom)</label>
                                                                        <div id="customDates-{{ $schedule->id }}">
                                                                            @foreach ($customSelected as $c)
                                                                                @if ($c)
                                                                                    <input type="date"
                                                                                        name="rules[custom][]"
                                                                                        class="form-control mb-2"
                                                                                        value="{{ $c }}">
                                                                                @endif
                                                                            @endforeach
                                                                        </div>
                                                                        <button type="button"
                                                                            class="btn btn-sm btn-outline-info"
                                                                            id="addCustomDateBtn-{{ $schedule->id }}"><i
                                                                                class="fas fa-plus"></i> Tambah
                                                                            Tanggal</button>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            {{-- Order Details (editable, multiple) --}}
                                                            <h6 class="mt-3">Rincian Pesanan</h6>
                                                            <div class="order-items-wrapper"
                                                                id="orderItemsWrapper-{{ $schedule->id }}">
                                                                @php $odIndex = 0; @endphp
                                                                @foreach ($schedule->order_details as $od)
                                                                    <div class="border rounded p-3 mb-3 order-item"
                                                                        data-index="{{ $odIndex }}">
                                                                        <button type="button"
                                                                            class="btn btn-sm btn-danger float-right remove-order-item"
                                                                            title="Hapus item">&times;</button>

                                                                        <div class="form-row">
                                                                            <div class="form-group col-md-3">
                                                                                <label>Barang</label>
                                                                                <select class="form-control item-select"
                                                                                    name="order_details[{{ $odIndex }}][item_id]"
                                                                                    required>
                                                                                    <option value="">-- Pilih Barang
                                                                                        --</option>
                                                                                    @foreach ($items as $item)
                                                                                        <option
                                                                                            value="{{ $item->id }}"
                                                                                            {{ isset($od['item_id']) && $od['item_id'] == $item->id ? 'selected' : '' }}>
                                                                                            {{ $item->name }}
                                                                                        </option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>

                                                                            <div class="form-group col-md-3">
                                                                                <label>Servis</label>
                                                                                <select class="form-control service-select"
                                                                                    name="order_details[{{ $odIndex }}][service_id]"
                                                                                    required>
                                                                                    <option value="">-- Pilih Servis
                                                                                        --</option>
                                                                                    @foreach ($services as $service)
                                                                                        <option
                                                                                            value="{{ $service->id }}"
                                                                                            {{ isset($od['service_id']) && $od['service_id'] == $service->id ? 'selected' : '' }}>
                                                                                            {{ $service->name }}
                                                                                        </option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>

                                                                            <div class="form-group col-md-2">
                                                                                <label>Kategori</label>
                                                                                <select
                                                                                    class="form-control category-select"
                                                                                    name="order_details[{{ $odIndex }}][category_id]"
                                                                                    required>
                                                                                    <option value="">-- Pilih
                                                                                        Kategori --</option>
                                                                                    @foreach ($categories as $category)
                                                                                        <option
                                                                                            value="{{ $category->id }}"
                                                                                            {{ isset($od['category_id']) && $od['category_id'] == $category->id ? 'selected' : '' }}>
                                                                                            {{ $category->name }}
                                                                                        </option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>

                                                                            <div class="form-group col-md-1">
                                                                                <label>Qty</label>
                                                                                <input type="number" min="1"
                                                                                    class="form-control qty-input"
                                                                                    name="order_details[{{ $odIndex }}][quantity]"
                                                                                    value="{{ $od['quantity'] ?? 1 }}">
                                                                            </div>

                                                                            <div class="form-group col-md-3">
                                                                                <label>Subtotal (amount * qty)</label>
                                                                                <input type="text"
                                                                                    class="form-control subtotal-field"
                                                                                    value="Rp{{ number_format($od['subtotal'] ?? 0, 0, ',', '.') }}"
                                                                                    readonly>
                                                                                <input type="hidden"
                                                                                    class="subtotal-hidden"
                                                                                    name="order_details[{{ $odIndex }}][subtotal]"
                                                                                    value="{{ $od['subtotal'] ?? 0 }}">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    @php $odIndex++; @endphp
                                                                @endforeach
                                                            </div>

                                                            <button type="button"
                                                                class="btn btn-sm btn-outline-success mb-3"
                                                                id="addOrderItemBtn-{{ $schedule->id }}">
                                                                <i class="fas fa-plus"></i> Tambah Item
                                                            </button>

                                                            {{-- Service Type --}}
                                                            <div class="form-group">
                                                                <label>Tipe Service</label>
                                                                <select class="form-control service-type-select"
                                                                    name="service_type_id"
                                                                    id="serviceTypeSelect-{{ $schedule->id }}" required>
                                                                    <option value="">-- Pilih Tipe Service --
                                                                    </option>
                                                                    @foreach ($serviceTypes as $st)
                                                                        <option value="{{ $st->id }}"
                                                                            data-cost="{{ $st->cost }}"
                                                                            {{ $schedule->service_type_id == $st->id ? 'selected' : '' }}>
                                                                            {{ $st->name }} -
                                                                            Rp{{ number_format($st->cost, 0, ',', '.') }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            {{-- Voucher --}}
                                                            @if (!empty($vouchers) && count($vouchers) > 0)
                                                                <div class="form-group">
                                                                    <label>Voucher</label>
                                                                    <select class="form-control voucher-select"
                                                                        name="voucher_id"
                                                                        id="voucherSelect-{{ $schedule->id }}">
                                                                        <option value="">-- Tidak Menggunakan Voucher
                                                                            --</option>
                                                                        @foreach ($vouchers as $v)
                                                                            <option value="{{ $v->id }}"
                                                                                data-discount="{{ $v->discount_percentage }}"
                                                                                data-max="{{ $v->max_discount }}"
                                                                                {{ $schedule->voucher_id == $v->id ? 'selected' : '' }}>
                                                                                {{ $v->code }} -
                                                                                {{ $v->discount_percentage }}% (Maks:
                                                                                Rp{{ number_format($v->max_discount, 0, ',', '.') }})
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            @endif

                                                            {{-- Estimasi Total --}}
                                                            <div class="form-group">
                                                                <label>Estimasi Total</label>
                                                                <input type="text"
                                                                    id="totalEstimation-{{ $schedule->id }}"
                                                                    class="form-control"
                                                                    value="Rp{{ number_format($schedule->total_amount ?? 0, 0, ',', '.') }}"
                                                                    readonly>
                                                                <input type="hidden" name="total_amount"
                                                                    id="totalAmountHidden-{{ $schedule->id }}"
                                                                    value="{{ $schedule->total_amount ?? 0 }}">
                                                            </div>
                                                        </div>

                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-primary">Simpan
                                                                Perubahan</button>
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Batal</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <script>
                                            (function() {
                                                const scheduleId = {{ $schedule->id }};
                                                const orderWrapper = document.getElementById("orderItemsWrapper-" + scheduleId);
                                                const addBtn = document.getElementById("addOrderItemBtn-" + scheduleId);
                                                const serviceTypeSelect = document.getElementById("serviceTypeSelect-" + scheduleId);
                                                const voucherSelect = document.getElementById("voucherSelect-" + scheduleId);
                                                const totalField = document.getElementById("totalEstimation-" + scheduleId);
                                                const totalHidden = document.getElementById("totalAmountHidden-" + scheduleId);

                                                // counter global untuk index item baru
                                                let orderItemCounter = orderWrapper.querySelectorAll('.order-item').length;

                                                // fetch price utility
                                                async function fetchPrice(itemId, serviceId, categoryId) {
                                                    try {
                                                        const url = "{{ url('member/get-price') }}" +
                                                            `?item_id=${itemId}&service_id=${serviceId}&category_id=${categoryId}`;
                                                        const res = await fetch(url, {
                                                            headers: {
                                                                'Accept': 'application/json'
                                                            }
                                                        });
                                                        if (!res.ok) throw new Error('HTTP ' + res.status);
                                                        const data = await res.json();
                                                        return parseInt(data.price || 0, 10);
                                                    } catch (err) {
                                                        console.error('fetchPrice err', err);
                                                        return 0;
                                                    }
                                                }

                                                // recalc subtotal per order item
                                                async function recalcOrderItem(orderItemEl) {
                                                    const itemId = orderItemEl.querySelector('.item-select')?.value;
                                                    const serviceId = orderItemEl.querySelector('.service-select')?.value;
                                                    const categoryId = orderItemEl.querySelector('.category-select')?.value;
                                                    const qty = parseInt(orderItemEl.querySelector('.qty-input')?.value || 1, 10);

                                                    if (itemId && serviceId && categoryId) {
                                                        const amount = await fetchPrice(itemId, serviceId, categoryId);
                                                        const subtotal = amount * qty;
                                                        orderItemEl.querySelector('.subtotal-field').value = "Rp" + subtotal.toLocaleString('id-ID');
                                                        orderItemEl.querySelector('.subtotal-hidden').value = subtotal;
                                                        updateTotal();
                                                    }
                                                }

                                                // update total keseluruhan
                                                function updateTotal() {
                                                    let itemsTotal = 0;
                                                    orderWrapper.querySelectorAll('.subtotal-hidden').forEach(inp => {
                                                        itemsTotal += parseInt(inp.value || 0, 10);
                                                    });

                                                    let serviceCost = serviceTypeSelect?.selectedOptions[0]?.dataset.cost ?
                                                        parseInt(serviceTypeSelect.selectedOptions[0].dataset.cost, 10) : 0;

                                                    let discount = 0;
                                                    if (voucherSelect && voucherSelect.value) {
                                                        const perc = parseInt(voucherSelect.selectedOptions[0].dataset.discount || 0, 10);
                                                        const maxDisc = parseInt(voucherSelect.selectedOptions[0].dataset.max || 0, 10);
                                                        discount = Math.min(((itemsTotal + serviceCost) * perc / 100), maxDisc);
                                                    }

                                                    const finalTotal = itemsTotal + serviceCost - discount;
                                                    totalField.value = "Rp" + finalTotal.toLocaleString('id-ID');
                                                    totalHidden.value = finalTotal;
                                                }

                                                // fungsi tambah item
                                                function addOrderItem(itemData = null) {
                                                    const idx = orderItemCounter++;
                                                    const container = document.createElement('div');
                                                    container.className = 'border rounded p-3 mb-3 order-item';
                                                    container.setAttribute('data-index', idx);

                                                    container.innerHTML = `
                                                            <button type="button" class="btn btn-sm btn-danger float-right remove-order-item" title="Hapus item">&times;</button>
                                                            <div class="form-row">
                                                                <div class="form-group col-md-3">
                                                                    <label>Barang</label>
                                                                    <select class="form-control item-select" name="order_details[${idx}][item_id]" required>
                                                                        <option value="">-- Pilih Barang --</option>
                                                                        @foreach ($items as $item)
                                                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="form-group col-md-3">
                                                                    <label>Servis</label>
                                                                    <select class="form-control service-select" name="order_details[${idx}][service_id]" required>
                                                                        <option value="">-- Pilih Servis --</option>
                                                                        @foreach ($services as $service)
                                                                            <option value="{{ $service->id }}">{{ $service->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="form-group col-md-2">
                                                                    <label>Kategori</label>
                                                                    <select class="form-control category-select" name="order_details[${idx}][category_id]" required>
                                                                        <option value="">-- Pilih Kategori --</option>
                                                                        @foreach ($categories as $category)
                                                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="form-group col-md-1">
                                                                    <label>Qty</label>
                                                                    <input type="number" min="1" class="form-control qty-input" name="order_details[${idx}][quantity]" value="1">
                                                                </div>
                                                                <div class="form-group col-md-3">
                                                                    <label>Subtotal</label>
                                                                    <input type="text" class="form-control subtotal-field" value="Rp0" readonly>
                                                                    <input type="hidden" class="subtotal-hidden" name="order_details[${idx}][subtotal]" value="0">
                                                                </div>
                                                            </div>
                                                        `;

                                                    orderWrapper.appendChild(container);

                                                    if (itemData) {
                                                        container.querySelector('.item-select').value = itemData.item_id || '';
                                                        container.querySelector('.service-select').value = itemData.service_id || '';
                                                        container.querySelector('.category-select').value = itemData.category_id || '';
                                                        container.querySelector('.qty-input').value = itemData.quantity || 1;
                                                        recalcOrderItem(container);
                                                    }
                                                }

                                                // event delegasi untuk remove
                                                orderWrapper.addEventListener('click', function(e) {
                                                    if (e.target.closest('.remove-order-item')) {
                                                        const wrapper = e.target.closest('.order-item');
                                                        wrapper.remove();
                                                        updateTotal();
                                                    }
                                                });

                                                // event perubahan item/service/category/qty
                                                orderWrapper.addEventListener('change', function(e) {
                                                    const orderItemEl = e.target.closest('.order-item');
                                                    if (!orderItemEl) return;
                                                    if (e.target.matches('.item-select,.service-select,.category-select,.qty-input')) {
                                                        recalcOrderItem(orderItemEl);
                                                    }
                                                });

                                                // tombol tambah item
                                                addBtn?.addEventListener('click', () => addOrderItem());

                                                // perubahan service type / voucher
                                                serviceTypeSelect?.addEventListener('change', updateTotal);
                                                voucherSelect?.addEventListener('change', updateTotal);

                                                // custom date add button
                                                const addCustomBtn = document.getElementById('addCustomDateBtn-' + scheduleId);
                                                addCustomBtn?.addEventListener('click', () => {
                                                    const container = document.getElementById('customDates-' + scheduleId);
                                                    const input = document.createElement('input');
                                                    input.type = 'date';
                                                    input.name = 'rules[custom][]';
                                                    input.className = 'form-control mb-2';
                                                    container.appendChild(input);
                                                });

                                                // initialize
                                                (function init() {
                                                    orderWrapper.querySelectorAll('.order-item').forEach(el => recalcOrderItem(el));

                                                    const typeSelect = document.querySelector('#editScheduleModal' + scheduleId +
                                                        ' select[name="type"]');
                                                    typeSelect?.addEventListener('change', function() {
                                                        const t = this.value;
                                                        document.getElementById('weeklyRules-' + scheduleId).style.display = t === 'weekly' ?
                                                            'block' : 'none';
                                                        document.getElementById('monthlyRules-' + scheduleId).style.display = t === 'monthly' ?
                                                            'block' : 'none';
                                                        document.getElementById('customRules-' + scheduleId).style.display = t === 'custom' ?
                                                            'block' : 'none';
                                                    });

                                                    setTimeout(updateTotal, 400);
                                                })();

                                            })();
                                        </script>



                                        <!-- Modal Detail Jadwal -->
                                        <div class="modal fade" id="detailScheduleModal{{ $schedule->id }}"
                                            tabindex="-1" role="dialog"
                                            aria-labelledby="detailScheduleModalLabel{{ $schedule->id }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-info text-white">
                                                        <h5 class="modal-title"
                                                            id="detailScheduleModalLabel{{ $schedule->id }}">
                                                            Detail Jadwal Rutin
                                                        </h5>
                                                        <button type="button" class="close text-white"
                                                            data-dismiss="modal" aria-label="Close">
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

                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">Belum ada jadwal rutin</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>


            </div>
        </div>
    </div>


    <!-- Tambah Jadwal Modal -->
    <div class="modal fade" id="addScheduleModal" tabindex="-1" role="dialog" aria-labelledby="addScheduleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="{{ url('member/simpanJadwal') }}" method="POST">
                    @csrf
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="addScheduleModalLabel">Tambah Jadwal Rutin</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <!-- Tipe Jadwal -->
                        <div class="form-group">
                            <label for="type">Tipe Jadwal</label>
                            <select class="form-control" id="type" name="type" required>
                                <option value="daily">Harian</option>
                                <option value="weekly">Mingguan</option>
                                <option value="monthly">Bulanan</option>
                                <option value="custom">Custom</option>
                            </select>
                        </div>

                        <!-- Tanggal Mulai & Akhir -->
                        <div class="form-group">
                            <label for="start_date">Tanggal Mulai</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" required>
                        </div>

                        <div class="form-group">
                            <label for="end_date">Tanggal Selesai (Opsional)</label>
                            <input type="date" class="form-control" id="end_date" name="end_date">
                        </div>

                        <!-- Jam -->
                        <div class="form-group">
                            <label for="time">Jam Penjemputan</label>
                            <input type="time" class="form-control" id="time" name="time" required>
                        </div>

                        <!-- Opsi Penjemputan -->
                        <div class="form-group">
                            <label for="pickup_option">Opsi Penjemputan</label>
                            <select class="form-control" id="pickup_option" name="pickup_option" required>
                                <option value="none">Tidak Perlu Penjemputan</option>
                                <option value="wa">Penjemputan via WhatsApp</option>
                                <option value="app">Penjemputan via Website</option>
                            </select>
                        </div>

                        <!-- Rules -->
                        <div id="rulesWrapper" class="mt-3" style="display:none;">
                            <h6>Aturan Jadwal</h6>
                            <div id="weeklyRules" style="display:none;">
                                <label>Pilih Hari</label><br>
                                @php
                                    $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                                @endphp
                                @foreach ($days as $i => $day)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="rules[weekly][]"
                                            value="{{ strtolower($day) }}" id="day{{ $i }}">
                                        <label class="form-check-label"
                                            for="day{{ $i }}">{{ $day }}</label>
                                    </div>
                                @endforeach
                            </div>

                            <div id="monthlyRules" style="display:none;">
                                <label>Pilih Tanggal</label>
                                <select class="form-control" name="rules[monthly][]" multiple size="5">
                                    @for ($i = 1; $i <= 31; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>

                            <div id="customRules" style="display:none;">
                                <label>Pilih Tanggal Khusus</label>
                                <input type="date" class="form-control mb-2" name="rules[custom][]" multiple>
                                <button type="button" class="btn btn-sm btn-outline-info mt-2" id="addCustomDate">
                                    <i class="fas fa-plus"></i> Tambah Tanggal
                                </button>
                                <div id="customDates"></div>
                            </div>
                        </div>

                        <!-- Input Pesanan -->
                        <h6 class="mt-4">Rincian Pesanan</h6>
                        <div id="orderDetailsWrapper">
                            <div class="border rounded p-3 mb-3 order-item">
                                <div class="form-group">
                                    <label>Barang</label>
                                    <select class="form-control item-select" name="order_details[0][item_id]" required>
                                        <option value="">-- Pilih Barang --</option>
                                        @foreach ($items as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Servis</label>
                                    <select class="form-control service-select" name="order_details[0][service_id]"
                                        required>
                                        <option value="">-- Pilih Servis --</option>
                                        @foreach ($services as $service)
                                            <option value="{{ $service->id }}">{{ $service->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Kategori</label>
                                    <select class="form-control category-select" name="order_details[0][category_id]"
                                        required>
                                        <option value="">-- Pilih Kategori --</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Banyak</label>
                                    <input type="number" class="form-control qty-input"
                                        name="order_details[0][quantity]" value="1" min="1">
                                </div>
                                <div class="form-group">
                                    <label>Subtotal</label>
                                    <input type="text" class="form-control subtotal-field" value="Rp0" readonly>
                                    <input type="hidden" class="subtotal-hidden" name="order_details[0][subtotal]"
                                        value="0">
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-success" id="addOrderItem">
                            <i class="fas fa-plus"></i> Tambah Item
                        </button>

                        <!-- Service Type -->
                        <div class="form-group mt-4">
                            <label for="service_type_id">Tipe Service</label>
                            <select class="form-control" id="service_type_id" name="service_type_id" required>
                                <option value="">-- Pilih Tipe Service --</option>
                                @foreach ($serviceTypes as $type)
                                    <option value="{{ $type->id }}" data-cost="{{ $type->cost }}">
                                        {{ $type->name }} - Rp{{ number_format($type->cost, 0, ',', '.') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Voucher -->
                        @if (!empty($vouchers) && count($vouchers) > 0)
                            <div class="form-group">
                                <label for="voucher_id">Voucher</label>
                                <select class="form-control" name="voucher_id" id="voucher_id">
                                    <option value="">-- Tidak Menggunakan Voucher --</option>
                                    @foreach ($vouchers as $voucher)
                                        <option value="{{ $voucher->id }}"
                                            data-discount="{{ $voucher->discount_percentage }}"
                                            data-max="{{ $voucher->max_discount }}">
                                            {{ $voucher->code }} - {{ $voucher->discount_percentage }}% (Maks:
                                            Rp{{ number_format($voucher->max_discount, 0, ',', '.') }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <!-- Total Estimasi -->
                        <div class="form-group">
                            <label for="total_estimation">Estimasi Total</label>
                            <input type="text" class="form-control" id="total_estimation" value="Rp0" readonly>
                            <input type="hidden" name="total_amount" id="total_amount">
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Jadwal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Map Modal -->
    <div class="modal fade" id="mapModal" tabindex="-1" role="dialog" aria-labelledby="mapModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pilih Lokasi Penjemputan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="map"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Modal -->
    <div class="modal fade" id="paymentsModal" tabindex="-1" role="dialog" aria-labelledby="paymentsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <form action="{{ url('member/transactions-store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="paymentsModalLabel">Pembayaran</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="alert alert-info">
                            <strong>Rekening Tujuan:</strong><br>
                            BUMDes MAKMUR SEJAHTERA<br>
                            Bank BRI: 1234-5678-9012-3456<br>
                            (Konfirmasi setelah transfer dengan mengunggah bukti pembayaran)
                        </div>

                        @if (isset($sessionTransaction) && count($sessionTransaction) > 0)
                            <div class="form-group">
                                <label for="metode_pembayaran">Tipe Service</label>
                                <select class="form-control" name="service_type_id" id="metode_pembayaran" required>
                                    <option value="">-- Pilih Tipe Service --</option>
                                    @foreach ($serviceTypes as $type)
                                        <option value="{{ $type->id }}" data-cost="{{ $type->cost }}">
                                            {{ $type->name }} - Rp{{ number_format($type->cost, 0, ',', '.') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        @if (!empty($vouchers) && count($vouchers) > 0)
                            <div class="form-group">
                                <label for="voucher">Pilih Voucher</label>
                                <select class="form-control" name="voucher_id" id="voucher">
                                    <option value="">-- Tidak Menggunakan Voucher --</option>
                                    @foreach ($vouchers as $voucher)
                                        <option value="{{ $voucher->id }}">
                                            {{ $voucher->code }} - {{ $voucher->discount_percentage }}% (Maks:
                                            Rp{{ number_format($voucher->max_discount, 0, ',', '.') }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div class="form-group">
                            <label for="upload_bukti">Upload Bukti Pembayaran</label>
                            <input type="file" class="form-control-file" id="upload_bukti" name="upload_bukti"
                                accept="image/*,application/pdf" required>
                            <small class="form-text text-muted">Unggah bukti transfer berupa gambar atau PDF (maks
                                2MB).</small>
                        </div>

                        <div class="form-group">
                            <label for="total">Total Bayar</label>
                            <input type="text" class="form-control" id="total" name="total"
                                value="Rp{{ number_format($totalPrice ?? 0, 0, ',', '.') }}" readonly>
                            <input type="hidden" name="payment-amount" id="payment_amount">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Bayar Sekarang</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>

    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyACxGvbJboRS4fwCiOoNVhn0wkZ01V59Ss&callback=initMap"></script>

    <script>
        let map, marker, geocoder;

        function initMap() {
            const initialPos = {
                lat: -8.2192,
                lng: 114.3696
            }; // Banyuwangi
            geocoder = new google.maps.Geocoder();

            map = new google.maps.Map(document.getElementById("map"), {
                center: initialPos,
                zoom: 13
            });

            marker = new google.maps.Marker({
                position: initialPos,
                map: map,
                draggable: true
            });

            google.maps.event.addListener(marker, 'dragend', function() {
                geocodePosition(marker.getPosition());
            });

            map.addListener('click', function(event) {
                marker.setPosition(event.latLng);
                geocodePosition(event.latLng);
            });

            geocodePosition(initialPos);
        }

        function geocodePosition(pos) {
            geocoder.geocode({
                location: pos
            }, function(results, status) {
                if (status === google.maps.GeocoderStatus.OK && results[0]) {
                    document.getElementById('pickup_location').value = results[0].formatted_address;
                } else {
                    document.getElementById('pickup_location').value = pos.lat() + ", " + pos.lng();
                }
            });
        }

        $(document).ready(function() {
            $('#tbl-input-transaksi').DataTable({
                searching: false,
                bPaginate: false,
                bLengthChange: false,
                bFilter: false,
                bInfo: false
            });

            // Quantity buttons
            $('.quantity-right-plus').click(function(e) {
                e.preventDefault();
                let input = $('#quantity');
                input.val(parseInt(input.val()) + 1);
            });
            $('.quantity-left-minus').click(function(e) {
                e.preventDefault();
                let input = $('#quantity');
                if (parseInt(input.val()) > 1) input.val(parseInt(input.val()) - 1);
            });

            // Update total bayar
            const metodeSelect = document.getElementById("metode_pembayaran");
            const totalInput = document.getElementById("total");
            const paymentAmount = document.getElementById("payment_amount");
            const baseTotal = {{ $totalPrice ?? 0 }};
            metodeSelect?.addEventListener("change", function() {
                const cost = parseInt(this.options[this.selectedIndex].getAttribute("data-cost")) || 0;
                const finalTotal = baseTotal + cost;
                totalInput.value = new Intl.NumberFormat("id-ID", {
                    style: "currency",
                    currency: "IDR",
                    minimumFractionDigits: 0
                }).format(finalTotal);
                paymentAmount.value = finalTotal;
            });

            // Pickup option logic
            $('#pickup_option').on('change', function() {
                $('#pickup_location_wrapper').addClass('d-none');
                $('#pickup_wa_wrapper').addClass('d-none');
                if (this.value === "app") $('#pickup_location_wrapper').removeClass('d-none');
                if (this.value === "wa") $('#pickup_wa_wrapper').removeClass('d-none');
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let index = 1;
            const wrapper = document.getElementById("orderDetailsWrapper");

            // tambah item baru
            document.getElementById("addOrderItem").addEventListener("click", function() {
                const newItem = document.querySelector(".order-item").cloneNode(true);

                newItem.querySelectorAll("input, select").forEach(el => {
                    if (el.tagName === "INPUT") {
                        if (el.classList.contains("qty-input")) el.value = 1;
                        else el.value = "";
                    }
                    if (el.tagName === "SELECT") el.selectedIndex = 0;
                    el.name = el.name.replace(/\[\d+\]/, `[${index}]`);
                });

                // reset subtotal
                newItem.querySelector(".subtotal-field").value = "Rp0";
                newItem.querySelector(".subtotal-hidden").value = 0;

                wrapper.appendChild(newItem);
                index++;
            });

            // toggle rules
            const typeSelect = document.getElementById("type");
            const rulesWrapper = document.getElementById("rulesWrapper");
            const weeklyRules = document.getElementById("weeklyRules");
            const monthlyRules = document.getElementById("monthlyRules");
            const customRules = document.getElementById("customRules");

            typeSelect.addEventListener("change", function() {
                rulesWrapper.style.display = "none";
                weeklyRules.style.display = "none";
                monthlyRules.style.display = "none";
                customRules.style.display = "none";

                if (this.value === "weekly") {
                    rulesWrapper.style.display = "block";
                    weeklyRules.style.display = "block";
                } else if (this.value === "monthly") {
                    rulesWrapper.style.display = "block";
                    monthlyRules.style.display = "block";
                } else if (this.value === "custom") {
                    rulesWrapper.style.display = "block";
                    customRules.style.display = "block";
                }
            });

            document.getElementById("addCustomDate").addEventListener("click", function() {
                const container = document.getElementById("customDates");
                const input = document.createElement("input");
                input.type = "date";
                input.classList.add("form-control", "mb-2");
                input.name = "rules[custom][]";
                container.appendChild(input);
            });

            // kalkulasi harga per item (via AJAX get-price)
            async function fetchPrice(itemId, serviceId, categoryId) {
                try {
                    const url = "{{ url('member/get-price') }}" +
                        `?item_id=${itemId}&service_id=${serviceId}&category_id=${categoryId}`;

                    // res didefinisikan di sini
                    const res = await fetch(url, {
                        method: "GET",
                        headers: {
                            "Accept": "application/json"
                        }
                    });

                    if (!res.ok) {
                        throw new Error(`HTTP error! status: ${res.status}`);
                    }

                    const data = await res.json();
                    return data.price;
                } catch (error) {
                    console.error("Fetch price error:", error);
                    return 0;
                }
            }

            // update semua subtotal saat ada perubahan
            wrapper.addEventListener("change", async function(e) {
                if (e.target.matches(".item-select, .service-select, .category-select, .qty-input")) {
                    const orderItem = e.target.closest(".order-item");

                    const itemId = orderItem.querySelector(".item-select").value;
                    const serviceId = orderItem.querySelector(".service-select").value;
                    const categoryId = orderItem.querySelector(".category-select").value;
                    const qty = parseInt(orderItem.querySelector(".qty-input").value) || 1;

                    if (itemId && serviceId && categoryId) {
                        const price = await fetchPrice(itemId, serviceId, categoryId);

                        // hitung subtotal
                        const subtotal = price * qty;

                        orderItem.querySelector(".subtotal-field").value =
                            "Rp" + subtotal.toLocaleString("id-ID");
                        orderItem.querySelector(".subtotal-hidden").value = subtotal;

                        // update total keseluruhan
                        updateTotal();
                    }
                }
            });

            // kalkulasi total keseluruhan
            const serviceTypeSelect = document.getElementById("service_type_id");
            const voucherSelect = document.getElementById("voucher_id");
            const totalField = document.getElementById("total_estimation");
            const hiddenTotal = document.getElementById("total_amount");

            function updateTotal() {
                let itemsTotal = 0;
                document.querySelectorAll(".subtotal-hidden").forEach(input => {
                    itemsTotal += parseInt(input.value) || 0;
                });

                let serviceCost = 0;
                if (serviceTypeSelect && serviceTypeSelect.value) {
                    serviceCost = parseInt(serviceTypeSelect.selectedOptions[0].dataset.cost || 0);
                }

                let discount = 0;
                if (voucherSelect && voucherSelect.value) {
                    const perc = parseInt(voucherSelect.selectedOptions[0].dataset.discount || 0);
                    const maxDisc = parseInt(voucherSelect.selectedOptions[0].dataset.max || 0);
                    discount = Math.min(((itemsTotal + serviceCost) * perc / 100), maxDisc);
                }

                let finalTotal = itemsTotal + serviceCost - discount;
                totalField.value = "Rp" + finalTotal.toLocaleString("id-ID");
                hiddenTotal.value = finalTotal;
            }

            if (serviceTypeSelect) serviceTypeSelect.addEventListener("change", updateTotal);
            if (voucherSelect) voucherSelect.addEventListener("change", updateTotal);
        });
    </script>
@endsection
