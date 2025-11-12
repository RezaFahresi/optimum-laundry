<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DeveloperController extends Controller
{
    public function showBio()
    {
        // Data untuk 5 anggota pengembang
        $developers = [
            [
                'id' => 1,
                'fullName' => 'Ishak Hadi Pernama',
                'role' => 'Project Lead & Backend Dev',
                'email' => 'ishakhadipernama@gmail.com',
                'github' => 'https://github.com/ishakper',
                'photo' => 'Ishak.jpg',
                'description' => 'Sebagai Project Lead dan Backend Developer, Ishak bertanggung jawab dalam mengatur arah pengembangan proyek, memastikan integrasi sistem berjalan lancar, serta menangani logika backend menggunakan Laravel.',
            ],
            [
                'id' => 2,
                'fullName' => 'M. Atthalarik Faisal',
                'role' => 'Project Lead & Backend Dev',
                'email' => 'attafaiz22@gmail.com',
                'github' => 'https://github.com/AthaFaizal',
                'photo' => 'Atta.jpg',
                'description' => 'Atthalarik berperan sebagai Project Lead kedua dan Backend Developer. Ia berfokus pada manajemen database, perancangan API, serta memastikan performa dan keamanan sistem aplikasi tetap optimal.',
            ],
            [
                'id' => 3,
                'fullName' => 'Reza Fahresi',
                'role' => 'Database Specialist',
                'email' => 'fahrezireza26@gmail.com',
                'github' => 'https://github.com/RezaFahresi',
                'photo' => 'Reza.jpg',
                'description' => 'Sebagai Database Specialist, Reza bertanggung jawab dalam perancangan struktur database, optimasi query, serta menjaga integritas data agar aplikasi berjalan efisien dan stabil.',
            ],
            [
                'id' => 4,
                'fullName' => 'Aldi Rionaldi Simanullang',
                'role' => 'UI/UX Designer',
                'email' => 'aldysimanullang707@gmail.com',
                'github' => 'https://github.com/aldyymnlnk',
                'photo' => 'Aldi.jpg',
                'description' => 'Aldi berfokus pada pengalaman pengguna (UI/UX). Ia mendesain tampilan antarmuka yang modern, mudah digunakan, dan menarik, agar pengguna merasa nyaman dalam menggunakan aplikasi.',
            ],
            [
                'id' => 5,
                'fullName' => 'Siti Fatimah Ayu Lestari',
                'role' => 'Quality Assurance',
                'email' => 'fatimayu464@gmail.com',
                'github' => 'https://github.com/fatim2304',
                'photo' => 'Fatim.jpg',
                'description' => 'Sebagai Quality Assurance, Siti Fatimah bertugas melakukan pengujian aplikasi untuk memastikan semua fitur berjalan sesuai standar, bebas dari bug, dan siap digunakan oleh pengguna.',
            ],
        ];

        // Kirim array $developers ke view
        return view('developer-bio', compact('developers'));
    }
}
