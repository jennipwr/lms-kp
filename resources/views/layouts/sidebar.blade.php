<!-- Sidebar Start -->
    <aside class="left-sidebar">
      <!-- Sidebar scroll-->
      <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
          <a href="./index.html" class="text-nowrap logo-img">
            <img src="{{ asset('assets/images/logos/logo-ls-new.png') }}" width="180" alt="" />
          </a>
          <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
            <i class="ti ti-x fs-8"></i>
          </div>
        </div>
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
          <ul id="sidebarnav">
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">Home</span>
            </li>

            <!--Dashboard-->
                <li class="sidebar-item">
                    @if (Auth::check() && Auth::user()->role_id === 1)
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="{{ route('admin.dashboard') }}" aria-expanded="false">
                            <span>
                                <i class="ti ti-layout-dashboard"></i>
                            </span>
                            <span class="hide-menu">Dashboard</span>
                        </a>
                    </li>
                    @elseif (Auth::check() && Auth::user()->role_id === 2)
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="{{ route('dosen.dashboard') }}" aria-expanded="false">
                            <span>
                                <i class="ti ti-layout-dashboard"></i>
                            </span>
                            <span class="hide-menu">Dashboard</span>
                        </a>
                    </li>
                    @elseif (Auth::check() && Auth::user()->role_id === 3)
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="{{ route('mahasiswa.dashboard') }}" aria-expanded="false">
                            <span>
                                <i class="ti ti-layout-dashboard"></i>
                            </span>
                            <span class="hide-menu">Dashboard</span>
                        </a>
                    </li>
                    @endif   
                </li>
              
              <!--Admin-->
              <!--Manjemen Akun-->
                @if (Auth::user()->role_id === 1)
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
                            <span class="hide-menu">Tambah Akun </span>
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
                    <!--Manjemen Kelas-->
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
                                <i class="ti ti-layout-grid"></i>
                            </span>
                            <span class="hide-menu">Tambah Kelas</span>
                        </a>
                    </li>
                    <!--Manjemen Kuesioner-->
                    <li class="nav-small-cap">
                        <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                        <span class="hide-menu">Manajemen Kuesioner</span>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="{{ route('admin.list-kuesioner') }}" aria-expanded="false">
                            <span>
                                <i class="ti ti-layout-grid"></i>
                            </span>
                            <span class="hide-menu">Lihat Semua Kuesioner</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="{{ route('admin.create-kuesioner') }}" aria-expanded="false">
                            <span>
                                <i class="ti ti-layout-grid"></i>
                            </span>
                            <span class="hide-menu">Tambah Kuesioner</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="{{ route('admin.hasil-kuesioner') }}" aria-expanded="false">
                            <span>
                                <i class="ti ti-layout-grid"></i>
                            </span>
                            <span class="hide-menu">Lihat Hasil Kuesioner</span>
                        </a>
                    </li>
                @endif

                <!--Mahasiswa-->
                @if (Auth::user()->role_id === 3)
                <!--Kuesioner-->
                    <li class="nav-small-cap">
                        <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                        <span class="hide-menu">Gaya Belajar</span>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="{{ route('mahasiswa.tes-index') }}" aria-expanded="false">
                            <span>
                                <i class="bi bi-person-lines-fill"></i>
                            </span>
                            <span class="hide-menu">Test Gaya Belajar</span>
                        </a>
                    </li>

                <!--Kelas-->
                <li class="nav-small-cap">
                        <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
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
                </li>
                @endif

                <!--Dosen-->
                @if (Auth::user()->role_id === 2)
                <!--Kelas-->
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
                </li>
                <!--Kuesioner-->
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Kuesioner</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('dosen.hasil-kuesioner') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-layout-grid"></i>
                        </span>
                        <span class="hide-menu">Lihat Hasil Kuesioner</span>
                    </a>
                </li>
                @endif

            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">UI COMPONENTS</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="./ui-buttons.html" aria-expanded="false">
                <span>
                  <i class="ti ti-article"></i>
                </span>
                <span class="hide-menu">Buttons</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="./ui-alerts.html" aria-expanded="false">
                <span>
                  <i class="ti ti-alert-circle"></i>
                </span>
                <span class="hide-menu">Alerts</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="./ui-card.html" aria-expanded="false">
                <span>
                  <i class="ti ti-cards"></i>
                </span>
                <span class="hide-menu">Card</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="./ui-forms.html" aria-expanded="false">
                <span>
                  <i class="ti ti-file-description"></i>
                </span>
                <span class="hide-menu">Forms</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="./ui-typography.html" aria-expanded="false">
                <span>
                  <i class="ti ti-typography"></i>
                </span>
                <span class="hide-menu">Typography</span>
              </a>
            </li>
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">AUTH</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="./authentication-login.html" aria-expanded="false">
                <span>
                  <i class="ti ti-login"></i>
                </span>
                <span class="hide-menu">Login</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="./authentication-register.html" aria-expanded="false">
                <span>
                  <i class="ti ti-user-plus"></i>
                </span>
                <span class="hide-menu">Register</span>
              </a>
            </li>
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">EXTRA</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="./icon-tabler.html" aria-expanded="false">
                <span>
                  <i class="ti ti-mood-happy"></i>
                </span>
                <span class="hide-menu">Icons</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="./sample-page.html" aria-expanded="false">
                <span>
                  <i class="ti ti-aperture"></i>
                </span>
                <span class="hide-menu">Sample Page</span>
              </a>
            </li>
          </ul>
          <div class="unlimited-access hide-menu bg-light-primary position-relative mb-7 mt-5 rounded">
            <div class="d-flex">
              <div class="unlimited-access-title me-3">
                <h6 class="fw-semibold fs-4 mb-6 text-dark w-85">Upgrade to pro</h6>
                <a href="https://adminmart.com/product/modernize-bootstrap-5-admin-template/" target="_blank" class="btn btn-primary fs-2 fw-semibold lh-sm">Buy Pro</a>
              </div>
              <div class="unlimited-access-img">
                <img src="{{ asset('assets/images/backgrounds/rocket.png') }}" alt="" class="img-fluid">
              </div>
            </div>
          </div>
        </nav>
        <!-- End Sidebar navigation -->
      </div>
      <!-- End Sidebar scroll-->
    </aside>
    <!--  Sidebar End -->