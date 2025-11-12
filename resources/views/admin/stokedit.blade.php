@extends('admin.template.main')

@section('main-content')
    <div class="container mt-4">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Edit Stok Deterjen</h5> 
            </div>
            <div class="card-body">
                <form action="{{ url('admin/stokupdate/' . $stok->idstok) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Nama Deterjen</label>
                        <input type="text" name="nama" class="form-control" value="{{ $stok->nama }}" required>
                    </div>
                    <div class="form-group">
                        <label>Stok</label>
                        <input type="number" name="stok" class="form-control" value="{{ $stok->stok }}" required
                            min="0">
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Update</button>
                    <a href="{{ url('admin/stok') }}" class="btn btn-secondary mt-3">Batal</a>
                </form>
            </div>
        </div>
    </div>
@endsection
