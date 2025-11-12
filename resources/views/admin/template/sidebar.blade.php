<div class="sidebar">

    <!-- Sidebar Menu -->
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item">
                <a href="{{ route('admin.index') }}"
                    class="nav-link {{ request()->routeIs('admin.index') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p>Dashboard</p>
                </a>
            </li>

            @php
                $user = Auth::user();
            @endphp

            @if ($user->role->value == 1)
                <li class="nav-item">
                    <a href="{{ route('admin.price-lists.index') }}"
                        class="nav-link {{ request()->routeIs('admin.price-lists.index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-list"></i>
                        <p>Daftar Harga</p>
                    </a>
                </li>
                @php
                    $lowStockCount = DB::table('stok')->where('stok', '<=', 10)->count();
                @endphp
                <li class="nav-item">
                    <a href="{{ url('admin/stok') }}"
                        class="nav-link {{ request()->is('admin/stok*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-boxes"></i>
                        <p>
                            Stok Barang
                            @if ($lowStockCount > 0)
                                <span class="right badge badge-danger">{{ $lowStockCount }}</span>
                            @endif
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.transactions.create') }}"
                        class="nav-link {{ request()->routeIs('admin.transactions.create') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-invoice"></i>
                        <p> Pesanan</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('admin/jadwal') }}"
                        class="nav-link {{ request()->is('admin/jadwal*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-clock"></i>
                        <p>Jadwal Rutin</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.transactions.index') }}"
                        class="nav-link {{ request()->routeIs('admin.transactions.index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-history"></i>
                        <p>Riwayat Transaksi</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('admin/log') }}"
                        class="nav-link {{ request()->is('admin/log*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-history"></i>
                        <p>Log</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.members.index') }}"
                        class="nav-link {{ request()->routeIs('admin.members*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Daftar Member</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('admin/user') }}"
                        class="nav-link {{ request()->is('admin/user*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Akun Internal</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.vouchers.index') }}"
                        class="nav-link {{ request()->routeIs('admin.vouchers.index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-star"></i>
                        <p>Voucher</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.complaint-suggestions.index') }}"
                        class="nav-link {{ request()->routeIs('admin.complaint-suggestions.index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-sticky-note"></i>
                        <p>Saran / Komplain</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('admin/pengeluaran') }}"
                        class="nav-link {{ request()->is('admin/pengeluaran*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Pengeluaran</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.reports.index') }}"
                        class="nav-link {{ request()->routeIs('admin.reports.index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>Laporan Keuangan</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('profile.index') }}"
                        class="nav-link {{ request()->routeIs('profile.index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-edit"></i>
                        <p>Edit Profil</p>
                    </a>
                </li>
            @endif

            @if ($user->role->value == 3)
                <li class="nav-item">
                    <a href="{{ route('admin.price-lists.index') }}"
                        class="nav-link {{ request()->routeIs('admin.price-lists.index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-list"></i>
                        <p>Daftar Harga</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.transactions.create') }}"
                        class="nav-link {{ request()->routeIs('admin.transactions.create') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-invoice"></i>
                        <p> Pesanan</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('admin/jadwal') }}"
                        class="nav-link {{ request()->is('admin/jadwal*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-clock"></i>
                        <p>Jadwal Rutin</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.transactions.index') }}"
                        class="nav-link {{ request()->routeIs('admin.transactions.index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-history"></i>
                        <p>Riwayat Transaksi</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('admin/log') }}"
                        class="nav-link {{ request()->is('admin/log*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-history"></i>
                        <p>Log</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.members.index') }}"
                        class="nav-link {{ request()->routeIs('admin.members*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Daftar Member</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('admin/user') }}"
                        class="nav-link {{ request()->is('admin/user*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Akun Internal</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.vouchers.index') }}"
                        class="nav-link {{ request()->routeIs('admin.vouchers.index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-star"></i>
                        <p>Voucher</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('profile.index') }}"
                        class="nav-link {{ request()->routeIs('profile.index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-edit"></i>
                        <p>Edit Profil</p>
                    </a>
                </li>
            @endif

            @if ($user->role->value == 4)
                <li class="nav-item">
                    <a href="{{ url('admin/jadwal') }}"
                        class="nav-link {{ request()->is('admin/jadwal*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-clock"></i>
                        <p>Jadwal Rutin</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.reports.index') }}"
                        class="nav-link {{ request()->routeIs('admin.reports.index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>Laporan Keuangan</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('profile.index') }}"
                        class="nav-link {{ request()->routeIs('profile.index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-edit"></i>
                        <p>Edit Profil</p>
                    </a>
                </li>
            @endif
        </ul>
    </nav>
    <!-- /.sidebar-menu -->
</div>
