@extends('member.template.main') {{-- Pastikan ini sesuai layoutmu --}}

@section('main-content')
    <div class="container">
        <h4>Halaman Pesanan</h4>
        <p>Silakan isi form di bawah untuk melakukan pemesanan laundry Anda.</p>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('member.orders.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="service">Layanan</label>
                <select name="service" id="service" class="form-control" required>
                    @foreach($services as $service)
                        <option value="{{ $service->id }}">{{ $service->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mt-3">
                <label for="category">Kategori</label>
                <select name="category" id="category" class="form-control" required>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mt-3">
                <label for="quantity">Jumlah Barang</label>
                <input type="number" name="quantity" id="quantity" min="1" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Pesan Sekarang</button>
        </form>
    </div>
@endsection
