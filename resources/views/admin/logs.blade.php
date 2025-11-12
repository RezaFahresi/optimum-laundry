@extends('admin.template.main')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="base_url" content="{{ url('admin') }}">
    <link href="{{ asset('vendor/datatables-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/datatables-responsive/css/responsive.bootstrap4.min.css') }}" rel="stylesheet">
@endsection

@section('main-content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Riwayat Log</h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">

                    <div class="card">
                        <div class="card-body">
                            <form action="" method="get">
                                <div class="form-group row">
                                    <label for="tahun" class="col-auto col-form-label">Tahun</label>
                                    <div class="col-auto">
                                        <select class="form-control" id="tahun" name="year">
                                            @foreach ($years as $year)
                                                @if ($year->tahun == $currentYear)
                                                    <option value="{{ $year->Tahun }}" selected>{{ $year->Tahun }}
                                                    </option>
                                                @else
                                                    <option value="{{ $year->Tahun }}">{{ $year->Tahun }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <label for="bulan" class="col-auto col-form-label">Bulan</label>
                                    <div class="col-auto">
                                        <select class="form-control" id="bulan" name="month">
                                            @for ($i = 1; $i <= 12; $i++)
                                                @if ($i == $currentMonth)
                                                    <option value="{{ $i }}" selected>
                                                        {{ $i }}</option>
                                                @else
                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                @endif
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-auto">
                                        <button type="submit" id="btn-filter" class="btn btn-success">Filter</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">

                    {{-- Tabel Transaction Log --}}
                    <div class="card">
                        <div class="card-body">
                            <h4 class="mb-3">Log Transaksi</h4>
                            <table id="tbl-transaksi-priority" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Transaction ID</th>
                                        <th>Changed By</th>
                                        <th>Old Status</th>
                                        <th>New Status</th>
                                        <th>Note</th>
                                        <th>Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($transactionLogs as $log)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $log->transaction_id }}</td>
                                            <td>{{ $log->user->name ?? 'System' }}</td>
                                            <td>{{ $log->old_status }}</td>
                                            <td>{{ $log->new_status }}</td>
                                            <td>{{ $log->note }}</td>
                                            <td>{{ $log->created_at }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">Belum ada log transaksi</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
            <div class="row">
                <div class="col-12">

                    {{-- Tabel Schedule Log --}}
                    <div class="card">
                        <div class="card-body">
                            <h4 class="mb-3">Log Jadwal Rutin</h4>
                            <table id="tbl-transaksi-belum" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Schedule ID</th>
                                        <th>User</th>
                                        <th>Status</th>
                                        <th>Message</th>
                                        <th>Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($scheduleLogs as $log)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $log->schedule_id }}</td>
                                            <td>{{ $log->schedule->user->name ?? '-' }}</td>
                                            <td>{{ $log->status }}</td>
                                            <td>{{ $log->message }}</td>
                                            <td>{{ $log->created_at }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">Belum ada log schedule</td>
                                        </tr>
                                    @endforelse
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
    <script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('js/ajax.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#tbl-transaksi-selesai').DataTable();
            $('#tbl-transaksi-belum').DataTable();
            $('#tbl-transaksi-priority').DataTable();
        });
    </script>
@endsection
