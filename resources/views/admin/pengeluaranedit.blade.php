@extends('admin.template.main')

@section('main-content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Edit Pengeluaran</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ url('admin/pengeluaran') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ url('admin/pengeluaranupdate', $pengeluaran->idpengeluaran) }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label>Judul</label>
                            <input type="text" name="judul" class="form-control"
                                value="{{ old('judul', $pengeluaran->judul) }}" required>
                        </div>

                        <div class="form-group">
                            <label>Jumlah (Rp)</label>
                            <input type="number" name="jumlah" class="form-control"
                                value="{{ old('jumlah', $pengeluaran->jumlah) }}" required>
                        </div>

                        <div class="form-group">
                            <label>Tanggal</label>
                            <input type="date" name="tanggal" class="form-control"
                                value="{{ old('tanggal', $pengeluaran->tanggal) }}" required>
                        </div>

                        <div class="form-group">
                            <label>Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $pengeluaran->deskripsi) }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        <a href="{{ url('admin/pengeluaran') }}" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
