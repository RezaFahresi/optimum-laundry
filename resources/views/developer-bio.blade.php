<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biodata Pengembang Tim Optimum Laundry</title>

    {{-- Bootstrap & FontAwesome --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(180deg, #e3f2fd, #ffffff);
            font-family: 'Poppins', sans-serif;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .content {
            flex: 1;
        }

        .bg-custom-blue {
            background-color: #1a237e;
        }

        .navbar-brand img {
            height: 45px;
            margin-right: 12px;
        }

        h1 {
            color: #1a237e;
            font-weight: 700;
        }

        /* Scrollable developer cards */
        .developer-scroll-wrapper {
            overflow-x: auto;
            padding: 15px;
        }

        .developer-scroll {
            display: flex;
            gap: 25px;
            justify-content: flex-start;
            align-items: stretch;
            padding-bottom: 10px;
            min-width: max-content;
        }

        .developer-card {
            flex: 0 0 auto;
            width: 250px;
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            padding: 20px;
            text-align: center;
        }

        .developer-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }

        .developer-img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #1a237e;
            margin-bottom: 15px;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .developer-img:hover {
            transform: scale(1.07);
        }

        .card-title {
            color: #1a237e;
            font-weight: 600;
            font-size: 18px;
        }

        .card-subtitle {
            color: #6c757d;
            font-size: 14px;
            margin-bottom: 10px;
        }

        footer {
            background-color: #0d47a1;
            color: #ffffff;
            text-align: center;
            padding: 20px 0;
            border-top: 3px solid #1565c0;
        }

        footer a {
            color: #ffeb3b;
            font-weight: 600;
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
            color: #fff176;
        }

        /* scrollbar */
        .developer-scroll-wrapper::-webkit-scrollbar {
            height: 8px;
        }

        .developer-scroll-wrapper::-webkit-scrollbar-thumb {
            background: #90caf9;
            border-radius: 10px;
        }

        .developer-scroll-wrapper::-webkit-scrollbar-thumb:hover {
            background: #42a5f5;
        }

        /* ✨ Modal animation (zoom in + fade) */
        .modal.fade .modal-dialog {
            transform: scale(0.8);
            opacity: 0;
            transition: all 0.3s ease-in-out;
        }

        .modal.fade.show .modal-dialog {
            transform: scale(1);
            opacity: 1;
        }

        /* Fade-in image inside modal */
        .modal-body img {
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
    </style>
</head>
<body>

    {{-- Navbar --}}
    <nav class="navbar navbar-dark bg-custom-blue">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                <img src="{{ asset('img/logo_poliwangi.png') }}" alt="Logo Poliwangi">
                <span class="ms-2 fw-bold">Politeknik Negeri Banyuwangi</span>
            </a>
        </div>
    </nav>

    {{-- Content --}}
    <div class="content">
        <div class="container mt-5">
            <h1 class="text-center mb-4">
                <i class="fa-solid fa-users me-2"></i> Tim Pengembang Kami
            </h1>

            <div class="developer-scroll-wrapper">
                <div class="developer-scroll">
                    @foreach ($developers as $index => $developer)
                        <div class="developer-card">
                            @php
                                $photoPath = isset($developer['photo']) && !empty($developer['photo'])
                                    ? asset('img/developers/' . $developer['photo'])
                                    : asset('img/developers/default.jpg');
                            @endphp

                            {{-- Klik foto buka modal --}}
                            <img src="{{ $photoPath }}" alt="Foto {{ $developer['fullName'] }}" 
                                 class="developer-img"
                                 data-bs-toggle="modal"
                                 data-bs-target="#developerModal{{ $index }}">

                            <h5 class="card-title">{{ $developer['fullName'] }}</h5>
                            <h6 class="card-subtitle">{{ $developer['role'] }}</h6>
                        </div>

                        {{-- Modal Detail Developer --}}
                        <div class="modal fade" id="developerModal{{ $index }}" tabindex="-1" 
                             aria-labelledby="developerModalLabel{{ $index }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow-lg">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title" id="developerModalLabel{{ $index }}">
                                            <i class="fa-solid fa-id-card me-2"></i> 
                                            {{ $developer['fullName'] }}
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body text-center">
                                        <img src="{{ $photoPath }}" alt="Foto {{ $developer['fullName'] }}" 
                                             class="rounded-circle mb-3" 
                                             width="130" height="130">
                                        <h6 class="text-muted mb-2">{{ $developer['role'] }}</h6>
                                        <p class="text-secondary px-3">
                                            {{ $developer['description'] ?? 'Tidak ada deskripsi.' }}
                                        </p>

                                        <hr>

                                        <p class="mb-1">
                                            <i class="fa-solid fa-envelope text-info me-2"></i>
                                            <a href="mailto:{{ $developer['email'] }}">{{ $developer['email'] }}</a>
                                        </p>
                                        <p>
                                            <i class="fa-brands fa-github text-dark me-2"></i>
                                            <a href="{{ $developer['github'] }}" target="_blank">GitHub Profile</a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="text-center mt-5 mb-5">
                <a href="{{ url('/') }}" class="btn btn-lg btn-primary shadow-sm">
                    <i class="fa-solid fa-home me-2"></i> Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <footer>
        <div class="container">
            <p class="mb-0">
                &copy; {{ date('Y') }} <strong>Optimum Laundry</strong> | Developed by 
                <a href="https://poliwangi.ac.id/" target="_blank">Tim Pengembang Politeknik Negeri Banyuwangi</a>
            </p>
        </div>
    </footer>

    {{-- ✅ Gunakan CDN Bootstrap JS agar modal berfungsi --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
