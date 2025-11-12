@extends($user->role == \App\Enums\Role::Member ? 'member.template.main' : 'admin.template.main')

@section('main-content')
    <style>
        #map {
            height: 400px;
            width: 100%;
        }
    </style>

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Edit Profil</h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @elseif (session('warning'))
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            {{ session('warning') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @elseif (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('patch')
                                <div class="row">
                                    <div class="col-md-4 text-center">
                                        <img id="profil_preview" class="img-fit img-circle" width="200" height="200"
                                            src="{{ $user->getFileAsset() }}" alt="Foto Profil">
                                        <div class="form-group mt-3">
                                            <label for="foto_profil">Pilih Foto Profil</label>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="foto_profil"
                                                    name="profile_picture" onchange="previewImage();">
                                                <label class="custom-file-label" for="foto_profil">Choose file</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4 text-left">
                                            <a href="{{ route('profile.photo.destroy') }}"
                                                onclick="return confirm('Apakah anda yakin ingin reset foto profil?')"
                                                class="btn btn-danger">Reset Foto</a>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                    </div>
                                    <div class="col-md-7">
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="email" class="form-control" id="email"
                                                value="{{ $user->email }}" disabled>
                                        </div>
                                        <div class="form-group">
                                            <label for="name">@lang('auth.name_label')</label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                id="name" name="name" placeholder="@lang('auth.name_placeholder')"
                                                value="{{ $user->name }}" required>
                                            @error('name')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="jenis_kelamin">Jenis Kelamin</label>
                                            <select class="form-control" id="jenis_kelamin" name="gender" required>
                                                @if ($user->gender == 'L')
                                                    <option value="L" selected>Laki - Laki</>
                                                    <option value="P">Perempuan</option>
                                                @elseif ($user->gender == 'P')
                                                    <option value="P" selected>Perempuan</option>
                                                    <option value="L">Laki - Laki</>
                                                    @else
                                                    <option value="">Pilih Jenis Kelamin</option>
                                                    <option value="L">Laki - Laki</>
                                                    <option value="P">Perempuan</option>
                                                @endif
                                            </select>
                                        </div>
                                        {{-- <div class="form-group">
                                            <label for="alamat">Alamat</label>
                                            <input type="text" class="form-control @error('alamat') is-invalid @enderror"
                                                id="alamat" name="address" placeholder="Masukkan alamat anda"
                                                value="{{ $user->address }}" required>
                                            @error('alamat')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div> --}}
                                        <div class="form-group">
                                            <label for="telp">No Telp</label>
                                            <input type="text" class="form-control @error('telp') is-invalid @enderror"
                                                id="telp" name="phone_number" placeholder="Masukkan no telp"
                                                value="{{ $user->phone_number }}" required>
                                            @error('telp')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <!-- Input Lokasi Sharelok -->
                                        <div class="form-group" id="pickup_location_wrapper">
                                            <label for="pickup_location">Alamat</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="pickup_location"
                                                    name="address" placeholder="Masukkan titik lokasi / sharelok"
                                                    value="{{ $user->address }}">
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-outline-primary"
                                                        data-toggle="modal" data-target="#mapModal">
                                                        Pilih di Peta
                                                    </button>
                                                </div>
                                            </div>
                                            <small class="text-muted">Masukkan alamat lengkap atau pilih lokasi di
                                                peta.</small>
                                        </div>
                                    </div>
                                </div>
                                <button class="btn btn-success" type="submit">Simpan Profil</button>
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

                <div class="col-12">
                    <h3 class="mt-2">Ubah Kata Sandi</h3>
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('profile.password.update') }}" method="POST">
                                @csrf
                                @method('patch')
                                <div class="form-group">
                                    <label for="current-password">Kata Sandi Sekarang</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        id="current-password" name="current_password"
                                        placeholder="Masukkan kata sandi sekarang" required autocomplete="off">
                                    @error('password')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="password">Kata Sandi Baru</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        id="password" name="password" placeholder="@lang('auth.password_placeholder')" required>
                                    @error('password')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="password2">Konfirmasi Kata Sandi Baru</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        id="password2" name="password_confirmation" placeholder="@lang('auth.confirm_password_placeholder')"
                                        required>
                                    @error('password')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <button class="btn btn-success" type="submit">Ubah Kata Sandi</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            bsCustomFileInput.init();
        });
    </script>

    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyACxGvbJboRS4fwCiOoNVhn0wkZ01V59Ss&callback=initMap"></script>

    <script>
        let map, marker, geocoder;

        function initMap() {
            geocoder = new google.maps.Geocoder();

            // Ambil value dari input jika ada
            const inputValue = document.getElementById("pickup_location").value;

            if (inputValue && inputValue.trim() !== "") {
                // Kalau valuenya berupa koordinat (lat,lng), parse langsung
                if (inputValue.match(/^-?\d+(\.\d+)?\s*,\s*-?\d+(\.\d+)?$/)) {
                    const parts = inputValue.split(",");
                    var initialPos = {
                        lat: parseFloat(parts[0]),
                        lng: parseFloat(parts[1])
                    };
                    initMapWithPos(initialPos);
                } else {
                    // Kalau valuenya alamat, coba geocode
                    geocoder.geocode({
                        address: inputValue
                    }, function(results, status) {
                        if (status === google.maps.GeocoderStatus.OK && results[0]) {
                            initMapWithPos(results[0].geometry.location);
                        } else {
                            // fallback ke default Banyuwangi
                            initMapWithPos({
                                lat: -8.2192,
                                lng: 114.3696
                            });
                        }
                    });
                }
            } else {
                // kalau kosong, pakai default
                initMapWithPos({
                    lat: -8.2192,
                    lng: 114.3696
                });
            }
        }

        function initMapWithPos(initialPos) {
            map = new google.maps.Map(document.getElementById("map"), {
                center: initialPos,
                zoom: 13
            });

            marker = new google.maps.Marker({
                position: initialPos,
                map: map,
                draggable: true
            });

            google.maps.event.addListener(marker, "dragend", function() {
                geocodePosition(marker.getPosition());
            });

            map.addListener("click", function(event) {
                marker.setPosition(event.latLng);
                geocodePosition(event.latLng);
            });

            // Hanya isi input kalau masih kosong
            if (!document.getElementById("pickup_location").value.trim()) {
                geocodePosition(initialPos);
            }
        }

        function geocodePosition(pos) {
            geocoder.geocode({
                location: pos
            }, function(results, status) {
                if (status === google.maps.GeocoderStatus.OK && results[0]) {
                    document.getElementById("pickup_location").value = results[0].formatted_address;
                } else {
                    document.getElementById("pickup_location").value = pos.lat() + ", " + pos.lng();
                }
            });
        }
    </script>
@endsection
