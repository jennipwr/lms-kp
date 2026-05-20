<!-- Sidebar Start -->
<aside class="left-sidebar">
    <!-- Sidebar scroll -->
    <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
            <a href="{{ url('/') }}" class="text-nowrap logo-img">
                <img src="{{ asset('assets/images/logos/logo-ls-new.png') }}" width="180" alt="Logo" />
            </a>

            <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                <i class="ti ti-x fs-8"></i>
            </div>
        </div>

        <!-- Sidebar navigation -->
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
            <ul id="sidebarnav">

                @auth

                    <!-- ========================= -->
                    <!-- HOME / DASHBOARD -->
                    <!-- ========================= -->
                    <li class="nav-small-cap">
                        <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                        <span class="hide-menu">Home</span>
                    </li>

                    @if (Auth::user()->role_id === 1)
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="{{ route('admin.dashboard') }}" aria-expanded="false">
                                <span>
                                    <i class="ti ti-layout-dashboard"></i>
                                </span>
                                <span class="hide-menu">Dashboard</span>
                            </a>
                        </li>
                    @elseif (Auth::user()->role_id === 2)
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="{{ route('dosen.dashboard') }}" aria-expanded="false">
                                <span>
                                    <i class="ti ti-layout-dashboard"></i>
                                </span>
                                <span class="hide-menu">Dashboard</span>
                            </a>
                        </li>
                    @elseif (Auth::user()->role_id === 3)
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="{{ route('mahasiswa.dashboard') }}" aria-expanded="false">
                                <span>
                                    <i class="ti ti-layout-dashboard"></i>
                                </span>
                                <span class="hide-menu">Dashboard</span>
                            </a>
                        </li>
                    @endif


                    <!-- ========================= -->
                    <!-- ADMIN SIDEBAR -->
                    <!-- ========================= -->
                    @if (Auth::user()->role_id === 1)

                        <!-- Manajemen Akun -->
                        <li class="nav-small-cap">
                            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                            <span class="hide-menu">Manajemen Akun</span>
                        </li>

                        <li class="sidebar-item">
                            <a class="sidebar-link" href="{{ route('admin.list-user') }}" aria-expanded="false">
                                <span>
                                    <i class="bi bi-person-lines-fill"></i>
                                </span>
                                <span class="hide-menu">Lihat Semua Akun</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a class="sidebar-link" href="{{ route('admin.create-user') }}" aria-expanded="false">
                                <span>
                                    <i class="ti ti-user-plus"></i>
                                </span>
                                <span class="hide-menu">Tambah Akun</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a class="sidebar-link" href="{{ route('admin.import-mahasiswa-form') }}" aria-expanded="false">
                                <span>
                                    <i class="ti ti-file-import"></i>
                                </span>
                                <span class="hide-menu">Import Mahasiswa</span>
                            </a>
                        </li>

                        <!-- Manajemen Kelas -->
                        <li class="nav-small-cap">
                            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                            <span class="hide-menu">Manajemen Kelas</span>
                        </li>

                        <li class="sidebar-item">
                            <a class="sidebar-link" href="{{ route('admin.list-kelas') }}" aria-expanded="false">
                                <span>
                                    <i class="ti ti-layout-grid"></i>
                                </span>
                                <span class="hide-menu">Lihat Semua Kelas</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a class="sidebar-link" href="{{ route('admin.create-kelas') }}" aria-expanded="false">
                                <span>
                                    <i class="ti ti-plus"></i>
                                </span>
                                <span class="hide-menu">Tambah Kelas</span>
                            </a>
                        </li>

                        <!-- Manajemen Kuesioner -->
                        <li class="nav-small-cap">
                            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                            <span class="hide-menu">Manajemen Kuesioner</span>
                        </li>

                        <li class="sidebar-item">
                            <a class="sidebar-link" href="{{ route('admin.list-kuesioner') }}" aria-expanded="false">
                                <span>
                                    <i class="ti ti-clipboard-list"></i>
                                </span>
                                <span class="hide-menu">Lihat Semua Kuesioner</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a class="sidebar-link" href="{{ route('admin.create-kuesioner') }}" aria-expanded="false">
                                <span>
                                    <i class="ti ti-clipboard-plus"></i>
                                </span>
                                <span class="hide-menu">Tambah Kuesioner</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a class="sidebar-link" href="{{ route('admin.hasil-kuesioner') }}" aria-expanded="false">
                                <span>
                                    <i class="ti ti-report"></i>
                                </span>
                                <span class="hide-menu">Lihat Hasil Kuesioner</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a class="sidebar-link" href="{{ route('admin.grafik-kuesioner') }}" aria-expanded="false">
                                <span>
                                    <i class="ti ti-chart-bar"></i>
                                </span>
                                <span class="hide-menu">Grafik Kuesioner</span>
                            </a>
                        </li>

                        <!-- Kelompok ML -->
                        <li class="nav-small-cap">
                            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                            <span class="hide-menu">Kelompok ML</span>
                        </li>

                        <li class="sidebar-item">
                            <a class="sidebar-link" href="{{ route('admin.kelompok.index') }}" aria-expanded="false">
                                <span>
                                    <i class="ti ti-users-group"></i>
                                </span>
                                <span class="hide-menu">Lihat Kelompok</span>
                            </a>
                        </li>

                    @endif


                    <!-- ========================= -->
                    <!-- DOSEN SIDEBAR -->
                    <!-- ========================= -->
                    @if (Auth::user()->role_id === 2)

                        <!-- Kelas -->
                        <li class="nav-small-cap">
                            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                            <span class="hide-menu">Kelas</span>
                        </li>

                        <li class="sidebar-item">
                            <a class="sidebar-link" href="{{ route('dosen.lihat-kelas') }}" aria-expanded="false">
                                <span>
                                    <i class="bi bi-person-lines-fill"></i>
                                </span>
                                <span class="hide-menu">Lihat Kelas</span>
                            </a>
                        </li>

                        <!-- Kuesioner -->
                        <li class="nav-small-cap">
                            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                            <span class="hide-menu">Kuesioner</span>
                        </li>

                        <li class="sidebar-item">
                            <a class="sidebar-link" href="{{ route('dosen.hasil-kuesioner') }}" aria-expanded="false">
                                <span>
                                    <i class="ti ti-report"></i>
                                </span>
                                <span class="hide-menu">Lihat Hasil Kuesioner</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a class="sidebar-link" href="{{ route('dosen.grafik-kuesioner') }}" aria-expanded="false">
                                <span>
                                    <i class="ti ti-chart-bar"></i>
                                </span>
                                <span class="hide-menu">Grafik Kuesioner</span>
                            </a>
                        </li>

                        <!-- Kelompok ML -->
                        <li class="nav-small-cap">
                            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                            <span class="hide-menu">Kelompok ML</span>
                        </li>

                        <li class="sidebar-item">
                            <a class="sidebar-link" href="{{ route('dosen.kelompok.index') }}" aria-expanded="false">
                                <span>
                                    <i class="ti ti-users-group"></i>
                                </span>
                                <span class="hide-menu">Lihat Kelompok</span>
                            </a>
                        </li>

                    @endif


                    <!-- ========================= -->
                    <!-- MAHASISWA SIDEBAR -->
                    <!-- ========================= -->
                    @if (Auth::user()->role_id === 3)

                        <!-- Gaya Belajar -->
                        <li class="nav-small-cap">
                            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                            <span class="hide-menu">Gaya Belajar</span>
                        </li>

                        <li class="sidebar-item">
                            <a class="sidebar-link" href="{{ route('mahasiswa.tes-index') }}" aria-expanded="false">
                                <span>
                                    <i class="ti ti-clipboard-list"></i>
                                </span>
                                <span class="hide-menu">Test Gaya Belajar</span>
                            </a>
                        </li>

                        <!-- Kelas -->
                        <li class="nav-small-cap">
         
         
         
         
         
         
         
         
         
         
         
         
         
         
         
         
         
         
         
         
         ;;;;;;;;;;;               <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                            <span class="hide-menu">Kelas</span>
                        </li>

                        <li class="sidebar-item">
                            <a class="sidebar-link" href="{{ route('mahasiswa.lihat-kelas') }}" aria-expanded="false">
                                <span>
                                    <i class="bi bi-person-lines-fill"></i>
                                </span>
                                <span class="hide-menu">Lihat Kelas</span>
                            </a>
                        </li>

                        <!-- Kelompok ML -->
                        <li class="nav-small-cap">
                            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                            <span class="hide-menu">Kelompok ML</span>
                        </li>

                        <li class="sidebar-item">
                            <a class="sidebar-link" href="{{ route('mahasiswa.kelompok.index') }}" aria-expanded="false">
                                <span>
                                    <i class="ti ti-users-group"></i>
                                </span>
                                <span class="hide-menu">Lihat Kelompok</span>
                            </a>
                        </li>

                    @endif

                @endauth

            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll -->
</aside>;<!-- Sidebar End -->