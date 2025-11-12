@extends('member.template.main')

@section('css')
    <link href="{{ asset('vendor/datatables-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/datatables-responsive/css/responsive.bootstrap4.min.css') }}" rel="stylesheet">
@endsection

@section('main-content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Detail Transaksi</h1>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h3>ID Transaksi: {{ $transaction->id }}</h3>
                            <hr>
                            <table id="tbl-detail" class="table dataTable dt-responsive nowrap" style="width:100%">
                                <thead class="thead-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Barang</th>
                                        <th>Kategori</th>
                                        <th>Servis</th>
                                        <th>Banyak</th>
                                        <th>Harga</th>
                                        <th>Sub Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($transactions as $detail)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ optional($detail->price_list->item)->name }}</td>
                                            <td>{{ optional($detail->price_list->category)->name }}</td>
                                            <td>{{ optional($detail->price_list->service)->name }}</td>
                                            <td>{{ $detail->quantity }}</td>
                                            <td>{{ $detail->getFormattedPrice() }}</td>
                                            <td>{{ $detail->getFormattedSubTotal() }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @if ($transaction->bukti_pembayaran)
                                <hr>
                                <h5>Bukti Pembayaran:</h5>
                                @if (Str::endsWith($transaction->bukti_pembayaran, ['.jpg', '.jpeg', '.png', '.JPG', '.JPEG', '.PNG']))
                                    <img src="{{ asset($transaction->bukti_pembayaran) }}" alt="Bukti Pembayaran"
                                        class="img-fluid" style="max-width: 400px;">
                                @elseif (Str::endsWith($transaction->bukti_pembayaran, '.pdf'))
                                    <a href="{{ asset($transaction->bukti_pembayaran) }}" target="_blank"
                                        class="btn btn-primary">
                                        Lihat Bukti Pembayaran (PDF)
                                    </a>
                                @else
                                    <p class="text-muted">Format file tidak dikenali.</p>
                                @endif
                            @endif

                            <hr>
                            <h5>Tipe Servis: {{ optional($transaction->service_type)->name }}</h5>
                            <h5>Biaya Servis: {{ $transaction->getFormattedServiceCost() }}</h5>
                            <h5>Potongan: {{ $transaction->discount }}</h5>
                            <hr>
                            <h4>Total Biaya: {{ $transaction->getFormattedTotal() }}</h4>
                            <h4>Dibayar: {{ $transaction->getFormattedPaymentAmount() }}</h4>

                            <hr>
                            <h4>Status: <span class="badge bg-info">{{ $transaction->status->name }}</span></h4>

                            @if ($transaction->status && strtolower($transaction->status->name) === 'selesai')
                                <hr>
                                <button id="btnKomplain" class="btn btn-warning mb-3">📝 Komplain Pengembalian</button>

                                <div id="formKomplain" style="display: none;">
                                    <form action="{{ url('member/complaint/' . $transaction->id) }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf

                                        <div class="mb-3">
                                            <label for="keluhan" class="form-label">Keluhan</label>
                                            <textarea name="keluhan" id="keluhan" class="form-control" rows="3" required></textarea>
                                        </div>

                                        <table class="table table-bordered" id="tblFoto">
                                            <thead>
                                                <tr>
                                                    <th>Foto Bukti</th>
                                                    <th style="width: 120px;">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><input type="file" name="foto[]" class="form-control"
                                                            accept="image/*" required></td>
                                                    <td><button type="button"
                                                            class="btn btn-success btnTambah">Tambah</button></td>
                                                </tr>
                                            </tbody>
                                        </table>

                                        <button type="submit" class="btn btn-primary">Kirim Komplain</button>
                                    </form>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#tbl-detail').DataTable({
                "searching": false,
                "bPaginate": false,
                "bLengthChange": false,
                "bFilter": false,
                "bInfo": false
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            // Tampilkan form ketika tombol komplain diklik
            $('#btnKomplain').click(function() {
                $('#formKomplain').slideToggle();
            });

            // Tambah baris foto baru
            $(document).on('click', '.btnTambah', function() {
                let newRow = `
            <tr>
                <td><input type="file" name="foto[]" class="form-control" accept="image/*" required></td>
                <td><button type="button" class="btn btn-danger btnHapus">Hapus</button></td>
            </tr>
        `;
                $('#tblFoto tbody').append(newRow);
            });

            // Hapus baris foto
            $(document).on('click', '.btnHapus', function() {
                $(this).closest('tr').remove();
            });
        });
    </script>
@endsection
