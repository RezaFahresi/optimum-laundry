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
            @if (!empty($notifications))
                <div class="alert alert-info">
                    <h5 class="mb-2"><i class="fa fa-bell"></i> Jadwal Hari Ini
                        ({{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }})</h5>
                    <ul class="mb-0">
                        @foreach ($notifications as $notif)
                            <li>
                                <strong>{{ $notif['user'] }}</strong>
                                <br>
                                <small class="text-white"><i class="fa fa-map-marker-alt"></i>
                                    {{ $notif['address'] }}</small>
                                <br>
                                {{ $notif['type'] }} @if ($notif['time'])
                                    pukul {{ $notif['time'] }}
                                @endif
                                <br>
                                <em>{{ $notif['details'] }}</em>
                                <br>
                                <strong>Total:</strong> Rp {{ $notif['total'] }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card text-white bg-primary">
                        <div class="card-body">
                            <h5 class="card-title">Harian</h5>
                            <p class="card-text">{{ $totals['daily'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card text-white bg-success">
                        <div class="card-body">
                            <h5 class="card-title">Mingguan</h5>
                            <p class="card-text">{{ $totals['weekly'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card text-white bg-warning">
                        <div class="card-body">
                            <h5 class="card-title">Bulanan</h5>
                            <p class="card-text">{{ $totals['monthly'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card text-white bg-danger">
                        <div class="card-body">
                            <h5 class="card-title">Custom</h5>
                            <p class="card-text">{{ $totals['custom'] }}</p>
                        </div>
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


            <div id="printable-area" class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-dark d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Jadwal Rutin</h5>
                    <button class="btn btn-sm btn-success ml-auto no-print" title="Print"
                        onclick="printDiv('printable-area')">
                        <i class="fas fa-print"></i> Print
                    </button>
                </div>
                <form method="GET" action="{{ url('admin/jadwal') }}" class="mb-3">
                    <div class="row g-2 align-items-end">
                        <div class="col-md-3">
                            <label>Hari</label>
                            <select name="hari" class="form-control">
                                <option value="">-- Semua Hari --</option>
                                @foreach (['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'] as $h)
                                    <option value="{{ $h }}" {{ request('hari') == $h ? 'selected' : '' }}>
                                        {{ ucfirst($h) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label>Nama Customer</label>
                            <input type="text" name="nama" class="form-control" value="{{ request('nama') }}">
                        </div>

                        <div class="col-md-3">
                            <label>Wilayah</label>
                            <input type="text" name="wilayah" class="form-control" value="{{ request('wilayah') }}">
                        </div>

                        <div class="col-md-3">
                            <button class="btn btn-primary w-100">
                                <i class="fas fa-search"></i> Filter
                            </button>
                        </div>
                    </div>
                </form>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>No</th>
                                    <th>User</th>
                                    <th>Tipe</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Status</th>
                                    <th class="no-print">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($schedules as $schedule)
                                    <tr>
                                        <td>{{ $loop->iteration }}.</td>
                                        <td>{{ $schedule->user ? $schedule->user->name : '-' }}</td>
                                        <td>{{ ucfirst($schedule->type) }}</td>
                                        <td>{{ $schedule->start_date }}</td>
                                        <td>{{ $schedule->end_date ?? '-' }}</td>
                                        <td>{{ ucfirst($schedule->status) }}</td>
                                        <td class="no-print">
                                            <!-- Tombol Detail -->
                                            <button type="button" class="btn btn-info btn-sm" data-toggle="modal"
                                                data-target="#detailScheduleModal{{ $schedule->id }}">
                                                <i class="fas fa-eye"></i>
                                            </button> 

                                            @if ($user->role->value == 1 || $user->role->value == 3)
                                                <!-- Tombol Edit (letakkan di kolom Aksi jika belum ada) -->
                                                <button class="btn btn-warning btn-sm" data-toggle="modal"
                                                    data-target="#editScheduleModal{{ $schedule->id }}">
                                                    <i class="fas fa-edit"></i>
                                                </button>


                                                <!-- Tombol Hapus -->
                                                <form action="{{ url('admin/hapusJadwal/' . $schedule->id) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Hapus jadwal ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-danger btn-sm">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>


                                                <!-- Modal Edit Jadwal (selengkap modal detail, tapi editable) -->
                                                <div class="modal fade" id="editScheduleModal{{ $schedule->id }}"
                                                    tabindex="-1" role="dialog"
                                                    aria-labelledby="editScheduleModalLabel{{ $schedule->id }}"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog modal-lg" role="document">
                                                        <div class="modal-content">
                                                            <form
                                                                action="{{ url('admin/updateJadwal/' . $schedule->id) }}"
                                                                method="POST" class="edit-schedule-form">
                                                                @csrf
                                                                @method('PUT')

                                                                <div class="modal-header bg-warning text-dark">
                                                                    <h5 class="modal-title"
                                                                        id="editScheduleModalLabel{{ $schedule->id }}">
                                                                        Edit
                                                                        Jadwal
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
                                                                                <select name="type"
                                                                                    class="form-control" required>
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
                                                                                    value="{{ $schedule->start_date }}"
                                                                                    required>
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
                                                                                    value="{{ $schedule->time }}"
                                                                                    required>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>Opsi Penjemputan</label>
                                                                                <select name="pickup_option"
                                                                                    class="form-control" required>
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
                                                                                <select name="status"
                                                                                    class="form-control" required>
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
                                                                                    <div
                                                                                        class="form-check form-check-inline">
                                                                                        <input class="form-check-input"
                                                                                            type="checkbox"
                                                                                            name="rules[weekly][]"
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
                                                                                        <option
                                                                                            value="{{ $i }}"
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
                                                                                        <select
                                                                                            class="form-control item-select"
                                                                                            name="order_details[{{ $odIndex }}][item_id]"
                                                                                            required>
                                                                                            <option value="">-- Pilih
                                                                                                Barang
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
                                                                                        <select
                                                                                            class="form-control service-select"
                                                                                            name="order_details[{{ $odIndex }}][service_id]"
                                                                                            required>
                                                                                            <option value="">-- Pilih
                                                                                                Servis
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
                                                                                        <input type="number"
                                                                                            min="1"
                                                                                            class="form-control qty-input"
                                                                                            name="order_details[{{ $odIndex }}][quantity]"
                                                                                            value="{{ $od['quantity'] ?? 1 }}">
                                                                                    </div>

                                                                                    <div class="form-group col-md-3">
                                                                                        <label>Subtotal (amount *
                                                                                            qty)</label>
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
                                                                            id="serviceTypeSelect-{{ $schedule->id }}"
                                                                            required>
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
                                                                                <option value="">-- Tidak Menggunakan
                                                                                    Voucher
                                                                                    --</option>
                                                                                @foreach ($vouchers as $v)
                                                                                    <option value="{{ $v->id }}"
                                                                                        data-discount="{{ $v->discount_percentage }}"
                                                                                        data-max="{{ $v->max_discount }}"
                                                                                        {{ $schedule->voucher_id == $v->id ? 'selected' : '' }}>
                                                                                        {{ $v->code }} -
                                                                                        {{ $v->discount_percentage }}%
                                                                                        (Maks:
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
                                                                const url = "{{ url('admin/getPrice') }}" +
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

                                                    })
                                                    ();
                                                </script>
                                            @endif
                                        </td>
                                    </tr>

                                    <!-- Modal Detail Jadwal -->
                                    <div class="modal fade" id="detailScheduleModal{{ $schedule->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="detailScheduleModalLabel{{ $schedule->id }}"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <!-- Header -->
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

                                                <!-- Body -->
                                                <div class="modal-body">

                                                    <!-- Informasi Utama -->
                                                    <h6 class="text-primary mb-3">Informasi Jadwal</h6>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="border rounded p-3 mb-3 bg-light">
                                                                <p class="mb-1"><strong>Tipe Jadwal</strong></p>
                                                                <p class="text-muted">{{ ucfirst($schedule->type) }}</p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="border rounded p-3 mb-3 bg-light">
                                                                <p class="mb-1"><strong>Tanggal Mulai</strong></p>
                                                                <p class="text-muted">{{ $schedule->start_date }}</p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="border rounded p-3 mb-3 bg-light">
                                                                <p class="mb-1"><strong>Tanggal Selesai</strong></p>
                                                                <p class="text-muted">
                                                                    {{ $schedule->end_date ?? 'Berjalan' }}</p>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="border rounded p-3 mb-3 bg-light">
                                                                <p class="mb-1"><strong>Jam Penjemputan</strong></p>
                                                                <p class="text-muted">{{ $schedule->time }}</p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="border rounded p-3 mb-3 bg-light">
                                                                <p class="mb-1"><strong>Opsi Penjemputan</strong></p>
                                                                <p class="text-muted">
                                                                    @if ($schedule->pickup_option == 'none')
                                                                        Tidak Perlu Penjemputan
                                                                    @elseif($schedule->pickup_option == 'wa')
                                                                        Penjemputan via WhatsApp
                                                                    @else
                                                                        Penjemputan via Website
                                                                    @endif
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="border rounded p-3 mb-3 bg-light">
                                                                <p class="mb-1"><strong>Status</strong></p>
                                                                <p class="text-muted">{{ ucfirst($schedule->status) }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <!-- Aturan Jadwal -->
                                                    @if (!empty($rules) && is_array($rules))
                                                        <h6 class="text-primary mt-4 mb-3">Aturan Jadwal</h6>
                                                        <div class="border rounded p-3 mb-3 bg-light">
                                                            @foreach ($rules as $type => $values)
                                                                @php
                                                                    $filtered = array_values(
                                                                        array_filter(
                                                                            $values,
                                                                            fn($v) => $v !== null && $v !== '',
                                                                        ),
                                                                    );
                                                                    if (count($filtered) === 0) {
                                                                        continue;
                                                                    }
                                                                    $label = $typeLabels[$type] ?? ucfirst($type);
                                                                @endphp
                                                                <div class="mb-2">
                                                                    <strong>{{ $label }}:</strong>
                                                                    <ul class="mb-0 pl-3">
                                                                        @foreach ($filtered as $val)
                                                                            @php
                                                                                if ($type === 'weekly') {
                                                                                    $display = ucfirst($val);
                                                                                } elseif ($type === 'monthly') {
                                                                                    $display =
                                                                                        'Tanggal ' .
                                                                                        ltrim((string) $val, '0');
                                                                                } elseif ($type === 'custom') {
                                                                                    $timestamp = strtotime($val);
                                                                                    $display = $timestamp
                                                                                        ? date('d M Y', $timestamp)
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
                                                    @endif

                                                    <!-- Rincian Pesanan -->
                                                    <h6 class="text-primary mt-4 mb-3">Rincian Pesanan</h6>
                                                    <div class="row">
                                                        @foreach ($schedule->order_details as $detail)
                                                            <div class="col-md-6">
                                                                <div class="border rounded p-3 mb-3 bg-light">
                                                                    <p class="mb-1"><strong>Barang:</strong>
                                                                        {{ $detail['item_name'] ?? '-' }}</p>
                                                                    <p class="mb-1"><strong>Servis:</strong>
                                                                        {{ $detail['service_name'] ?? '-' }}</p>
                                                                    <p class="mb-1"><strong>Kategori:</strong>
                                                                        {{ $detail['category_name'] ?? '-' }}</p>
                                                                    <p class="mb-1"><strong>Banyak:</strong>
                                                                        {{ $detail['quantity'] ?? 0 }}</p>
                                                                    <p class="mb-0"><strong>Subtotal:</strong>
                                                                        Rp{{ number_format($detail['subtotal'] ?? 0, 0, ',', '.') }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>

                                                    <!-- Service & Voucher -->
                                                    <h6 class="text-primary mt-4 mb-3">Biaya & Voucher</h6>
                                                    <div class="border rounded p-3 mb-3 bg-light">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <p class="mb-1"><strong>Tipe Service</strong></p>
                                                                <p class="text-muted">
                                                                    {{ $schedule->serviceType->name }} -
                                                                    Rp{{ number_format($schedule->serviceType->cost, 0, ',', '.') }}
                                                                </p>
                                                            </div>
                                                            @if ($schedule->voucher)
                                                                <div class="col-md-6">
                                                                    <p class="mb-1"><strong>Voucher</strong></p>
                                                                    <p class="text-muted">
                                                                        {{ $schedule->voucher->code }} -
                                                                        {{ $schedule->voucher->discount_percentage }}%
                                                                        (Maks:
                                                                        Rp{{ number_format($schedule->voucher->max_discount, 0, ',', '.') }})
                                                                    </p>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <!-- Total -->
                                                    {{-- <div class="mt-3">
                                                        <h5 class="text-success">Total Estimasi:
                                                            Rp{{ number_format($schedule->total_amount, 0, ',', '.') }}
                                                        </h5>
                                                    </div>

                                                     --}}

                                                    @php
                                                        $hariIni = now()->locale('id')->translatedFormat('l');
                                                        $tanggalHariIni = now()->format('Y-m-d');

                                                        // Pastikan rules berupa array
                                                        $rules = is_array($schedule->rules)
                                                            ? $schedule->rules
                                                            : json_decode($schedule->rules ?? '[]', true);

                                                        $isTodayIncluded = false;

                                                        if ($schedule->type === 'daily') {
                                                            $isTodayIncluded = true;
                                                        } elseif (
                                                            $schedule->type === 'weekly' &&
                                                            in_array(strtolower($hariIni), $rules['weekly'] ?? [])
                                                        ) {
                                                            $isTodayIncluded = true;
                                                        } elseif (
                                                            $schedule->type === 'monthly' &&
                                                            in_array(now()->format('d'), $rules['monthly'] ?? [])
                                                        ) {
                                                            $isTodayIncluded = true;
                                                        } elseif (
                                                            $schedule->type === 'custom' &&
                                                            in_array($tanggalHariIni, $rules['custom'] ?? [])
                                                        ) {
                                                            $isTodayIncluded = true;
                                                        }

                                                        // Cek apakah sudah dibuat transaksi hari ini
                                                        $alreadyPaid = \App\Models\Transaction::where(
                                                            'member_id',
                                                            $schedule->user_id,
                                                        )
                                                            ->whereDate('created_at', $tanggalHariIni)
                                                            ->where('service_type_id', $schedule->service_type_id)
                                                            ->exists();
                                                    @endphp



                                                    <div class="mt-3 d-flex justify-content-between align-items-center">
                                                        <h5 class="text-success mb-0">
                                                            Total Estimasi:
                                                            Rp{{ number_format($schedule->total_amount, 0, ',', '.') }}
                                                        </h5>

                                                        @if ($isTodayIncluded)
                                                            @if ($alreadyPaid)
                                                                <button class="btn btn-secondary" disabled>
                                                                    <i class="fas fa-check-circle"></i> Sudah Dibayar
                                                                </button>
                                                            @else
                                                                <a href="{{ url('admin/jadwalbayar', $schedule->id) }}"
                                                                    class="btn btn-success">
                                                                    <i class="fas fa-money-bill-wave"></i> Bayar
                                                                </a>
                                                            @endif
                                                        @endif
                                                    </div>

                                                </div>

                                                <!-- Footer -->
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
            </div>

        </div><!-- /.container-fluid -->
    </div>
@endsection
