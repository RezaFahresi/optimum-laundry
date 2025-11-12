@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Buat Transaksi Baru</h1>
    <form action="{{ route('member.transactions.store') }}" method="POST">
        @csrf
        <!-- Form input seperti jenis layanan, berat, dll -->
        <div class="mb-3">
            <label for="layanan" class="form-label">Layanan</label>
            <select name="layanan_id" id="layanan" class="form-control">
                <!-- opsi layanan -->
            </select>
        </div>
        <div class="mb-3">
            <label for="berat" class="form-label">Berat (kg)</label>
            <input type="number" name="berat" id="berat" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Simpan Transaksi</button>
    </form>
</div>
@endsection