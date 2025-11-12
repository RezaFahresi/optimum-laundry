@extends('admin.template.main')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="base_url" content="{{ url('admin') }}">
@endsection

@section('main-content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Laporan Keuangan</h1>
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
                            <div class="row">
                                <div class="col-sm-5">
                                    <form action="{{ route('admin.reports.print') }}" method="post">
                                        @csrf
                                        <div class="form-group row">
                                            <label for="tahun" class="col-sm-4 col-form-label">Tahun</label>
                                            <div class="col-sm-6">
                                                <select class="form-control" id="tahun" name="year">
                                                    <option value="" disabled selected>-- Silahkan Pilih Tahun --
                                                    </option>
                                                    @foreach ($years as $year)
                                                        <option value="{{ $year->Tahun }}">{{ $year->Tahun }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="bulan" class="col-sm-4 col-form-label">Bulan</label>
                                            <div class="col-sm-6">
                                                <select class="form-control" id="bulan" name="month">
                                                    <option value="" disabled selected>-- Silahkan Pilih Bulan --
                                                    </option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="tanggal" class="col-sm-4 col-form-label">Tanggal</label>
                                            <div class="col-sm-6">
                                                <input type="date" class="form-control" id="tanggal" name="date"
                                                    placeholder="YYYY-MM-DD">
                                                <small class="form-text text-muted">Isi tanggal jika ingin cetak laporan
                                                    harian.</small>
                                            </div>
                                        </div>

                                        <button type="submit" id="btn-cetak" name="action" value="pdf"
                                            class="mt-3 btn btn-success">Cetak</button>
                                        <button type="submit" id="btn-cetak" name="action" value="excel"
                                            class="mt-3 btn btn-success">Excel</button>
                                    </form>

                                </div>
                            </div>

                            <hr>
                            <div class="mb-4">
                                <label for="chart-year" class="form-label">Pilih Tahun:</label>
                                <select id="chart-year" class="form-control" style="width: 200px">
                                    @foreach ($years as $year)
                                        <option value="{{ $year->Tahun }}">{{ $year->Tahun }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <canvas id="reportChart" height="100"></canvas>
                            <hr>

                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/ajax.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('reportChart').getContext('2d');
        let chart;

        function fetchChartData(year = null) {
            const url = new URL("{{ url('admin/reports/chart') }}", window.location.origin);
            if (year) url.searchParams.append('year', year);

            fetch(url)
                .then(res => res.json())
                .then(data => {
                    if (chart) chart.destroy();

                    chart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                    label: `Pendapatan (${data.year})`,
                                    data: data.income,
                                    backgroundColor: 'rgba(75, 192, 192, 0.6)',
                                    borderColor: 'rgba(75, 192, 192, 1)',
                                    borderWidth: 1
                                },
                                {
                                    label: `Pengeluaran (${data.year})`,
                                    data: data.expense,
                                    backgroundColor: 'rgba(255, 99, 132, 0.6)',
                                    borderColor: 'rgba(255, 99, 132, 1)',
                                    borderWidth: 1
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        callback: function(value) {
                                            return 'Rp ' + value.toLocaleString();
                                        }
                                    }
                                }
                            }
                        }
                    });
                });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const filter = document.getElementById('chart-year');
            filter.addEventListener('change', function() {
                fetchChartData(this.value);
            });
            fetchChartData(); // load default
        });
    </script>
@endsection
