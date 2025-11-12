<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    <script defer src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script defer src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <link href="https://fonts.googleapis.com/css2?family=Nunito&display=swap" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
</head>

<body style="padding-top: 56px;">

    <nav class="navbar navbar-expand-sm navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="">Optimum Laundry</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive"
                aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="btn btn-success" href="{{ url('login') }}">@lang('landing.loginOrRegister')</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <header class="py-5 position-relative">
        <div class="background-blur"></div>
        <div class="container h-100">
            <div class="row h-100 align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-3 text-white mt-5 mb-2 with-border">@lang('Selamat Datang Di Laundry Optimum')</h1>
                    <p class="lead mb-5 text-white text-center">@lang('landing.tagline')</p>
                </div>
            </div>
        </div>
    </header>

    <section class="p-5 text-center">
        <h3>@lang('landing.why')</h3>
    </section>

    <section class="kelebihan bg-blue text-white">
        <div class="container p-5">
            <div class="row">
                <div class="col-lg-6">
                    <h4>Peralatan Lengkap dan Canggih</h4>
                    <p>Laundry kami menggunakan peralatan yang cukup lengkap dan canggih. Peralatan kami memungkinkan
                        baju tidak perlu dijemur dan mengurangi debu pada baju</p>
                </div>
                <div class="col-lg-6">
                    <img class="img-fluid d-none d-lg-block" src="{{ asset('img/landing/alat.png') }}" alt="">
                </div>
            </div>
        </div>
    </section>

    <section class="kelebihan bg-blue text-white">
        <div class="container p-5">
            <div class="row">
                <div class="col-lg-6">
                    <img class="img-fluid d-none d-lg-block" src="{{ asset('img/landing/tipebaju.png') }}"
                        alt="">
                </div>
                <div class="col-lg-6">
                    <h4>Segala Tipe Pakaian</h4>
                    <p>Laundry kami menerima segala tipe pakaian mulai dari baju, celana, jas, dan selimut.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="kelebihan bg-blue text-white">
        <div class="container p-5">
            <div class="row">
                <div class="col-lg-6">
                    <h4>Pegawai Profesional</h4>
                    <p>Laundry kami terdiri dari pegawai-pegawai yang profesional yang mampu bekerja dalam tim dengan
                        cukup baik dan handal di bidangnya sehingga membuat laundry kami minim kesalahan</p>
                </div>
                <div class="col-lg-6">
                    <img class="img-fluid d-none d-lg-block" src="{{ asset('img/landing/pegawai.png') }}"
                        alt="">
                </div>
            </div>
        </div>
    </section>

    <section class="text-center p-5">
        <h3>Apa saja yang bisa kami laundry?</h3>
    </section>

    <section class="bg-blue p-5 text-center">
        <div class="container">
            <div class="row flex-row flex-nowrap kategori">
                <div class="col-4 mb-2">
                    <div class="card">
                        <img src="{{ asset('img/landing/Baju.jpg') }}" class="card-img-top" alt="">
                        <div class="card-body d-none d-lg-block">
                            <p class="card-text">Baju</p>
                        </div>
                    </div>
                </div>
                <div class="col-4 mb-2">
                    <div class="card">
                        <img src="{{ asset('img/landing/Celana.jpg') }}" class="card-img-top" alt="">
                        <div class="card-body d-none d-lg-block">
                            <p class="card-text">Celana</p>
                        </div>
                    </div>
                </div>
                <div class="col-4 mb-2">
                    <div class="card">
                        <img src="{{ asset('img/landing/Jas.jpg') }}" class="card-img-top" alt="">
                        <div class="card-body d-none d-lg-block">
                            <p class="card-text">Jas</p>
                        </div>
                    </div>
                </div>
                <div class="col-4 mb-2">
                    <div class="card">
                        <img src="{{ asset('img/landing/Selimut.jpg') }}" class="card-img-top" alt="">
                        <div class="card-body d-none d-lg-block">
                            <p class="card-text">Selimut</p>
                        </div>
                    </div>
                </div>
                <div class="col-4 mb-2">
                    <div class="card">
                        <img src="{{ asset('img/landing/Shoes.jpg') }}" class="card-img-top" alt="">
                        <div class="card-body d-none d-lg-block">
                            <p class="card-text">Shoes & Bag Treatment</p>
                        </div>
                    </div>
                </div>
                <div class="col-4 mb-2">
                    <div class="card">
                        <img src="{{ asset('img/landing/Sofa.jpg') }}" class="card-img-top" alt="">
                        <div class="card-body d-none d-lg-block">
                            <p class="card-text">Sofa & Springbed</p>
                        </div>
                    </div>
                </div>
                <div class="col-4 mb-2">
                    <div class="card">
                        <img src="{{ asset('img/landing/Baby.jpg') }}" class="card-img-top" alt="">
                        <div class="card-body d-none d-lg-block">
                            <p class="card-text">Baby Equipment</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="text-center p-5">
        <h3>Temukan kami!</h3>
    </section>

    <section class="text-white bg-blue">
        <div class="container p-5">
            <div class="row">
                <div class="col-md-6 mb-4 mb-sm-0">
                    <h5>Alamat</h5>
                    <p>Taman Baru, Kec. Banyuwangi, Kabupaten Banyuwangi, Jawa Timur</p>
                    <br>
                    <h5>Kontak</h5>
                    <p>OptimumLaundry@gmail.com</p>
                    <p>(0361)123456</p>
                    <p>085337370777</p>
                </div>
                <div class="col-md-6">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3948.7843236669464!2d114.3622566!3d-8.2244317!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd15b4b494b5415%3A0xd0fcc574da45c1a7!2sOptimum%20Laundry%2CDry%20%26%20Wet%20Cleaning!5e0!3m2!1sid!2sid!4v1742561246275!5m2!1sid!2sid"
                        width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-light text-dark p-5">
        <div class="container">
            <h3 class="text-center mb-4">Rekomendasi Laundry Terdekat</h3>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Laundry Express Banyuwangi</h5>
                            <p class="card-text">Jl. S.Parman No.20, Banyuwangi</p>
                            <a href="https://maps.google.com/?q=Laundry Express Banyuwangi"
                                class="btn btn-primary btn-sm" target="_blank">Lihat di Google Maps</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">QuickWash Laundry</h5>
                            <p class="card-text">Jl. Ikan Paus No.5, Banyuwangi</p>
                            <a href="https://maps.google.com/?q=QuickWash Laundry Banyuwangi"
                                class="btn btn-primary btn-sm" target="_blank">Lihat di Google Maps</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Smart Laundry Center</h5>
                            <p class="card-text">Jl. Ahmad Yani No.112, Banyuwangi</p>
                            <a href="https://maps.google.com/?q=Smart Laundry Center Banyuwangi"
                                class="btn btn-primary btn-sm" target="_blank">Lihat di Google Maps</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="py-4" style="background-color: #0d47a1; color: #ffffff; border-top: 3px solid #1565c0;">
    <div class="container text-center">
        <p class="mb-1 fw-bold">
            &copy; {{ date('Y') }} Optimum Laundry | Developed by Tim Pengembang Politeknik Negeri Banyuwangi
        </p>
        <a href="{{ url('developer-bio') }}" 
           class="text-warning text-decoration-none fw-semibold">
            <i class="fa-solid fa-id-card me-1"></i> Biodata Pengembang
        </a>
    </div>
</footer>


    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $.ajax({
                url: "{{ url('runSchedules') }}",
                type: "GET",
                success: function(response) {
                    console.log("Schedules berhasil dijalankan:", response);
                },
                error: function(xhr) {
                    console.error("Gagal menjalankan schedules:", xhr.responseText);
                }
            });
        });
    </script>

</body>

</html>