@push('styles')
    <style>
        .navbar-nav .nav-item .d-flex {
            position: initial;
            left: initial;
        }

        .navigation-button {
            width: 100px;
        }
        
        header {
            align-items: center;
            background-color: #527900;
            min-height: 80px;
            max-height: 80px;
        }

        #ProfilePhoto {
            width: 40px;
            height: 40px;
            object-fit: cover;
        }

        #profileDropdown .ms-2.me-2 {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 100dvw;
        }

        .dropdown-menu .dropdown-item:hover {
            background-color: grey;
            color: white;
        }

        .dropdown-menu li {
            position: relative;
        }

        /* Submenu styling for Laporan Bacaan Juz */
        .dropdown-menu .dropdown-submenu {
            position: relative;
        }

        .dropdown-menu .dropdown-submenu .dropdown-menu {
            position: absolute;
            top: 0;
            left: 100%;
            display: none;
            margin-top: 0;
        }

        /* Display sub-submenu on hover */
        .dropdown-menu .dropdown-submenu:hover>.dropdown-menu {
            display: block;
        }

        .nav-item {
            display: block;
        }

        /* Desktop view - hide accordion, show dropdowns */
        @media (min-width: 576px) {

            .accordion-menu,
            .mobile-profile-section {
                display: none;
            }
        }

        /* Mobile view - hide dropdowns, show accordion and profile section */
        @media (max-width: 576px) {

            .dropdown-menu,
            .dropdown,
            .nav-item {
                display: none;
            }

            .accordion-menu,
            .mobile-profile-section {
                display: block;
                width: 100%;
            }

            .accordion-button {
                width: 100%;
                text-align: left;
            }

            .accordion-collapse {
                width: 100%;
            }

            .nav-item {
                width: 100%;
            }
        }
    </style>
@endpush

<div class="position-fixed top-0 start-0 w-100 p-0 m-0" style="z-index: 3; background-color: saddlebrown">
    <header class="navbar navbar-expand-md navbar-light border-bottom mb-0 mx-0 px-3 pt-3">
        <div class="container-fluid p-0 m-0 h-100" style="min-width: 100%; align-items: center">
            <a class="navbar-brand p-0 m-0 border-0" href="{{ route('dashboard.index') }}">
                <h4 class="poppins-bold p-0 m-0 text-white">SD AR-RAFI</h4>
            </a>

            <button class="navbar-toggler p-0 m-0" data-bs-toggle="collapse" data-bs-target="#navbarContent" type="button" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon p-0 m-0"></span>
            </button>

            <div class="collapse navbar-collapse p-0 m-0" id="navbarContent" style="align-items: center">
                <ul class="navbar-nav ms-auto w-100" style="justify-content: center">
                    @if (isset($role) && $role == 'Admin')
                        <!-- Admin Navbar for Desktop View -->
                        <li class="nav-item px-2 mb-2" id="FirstItem">
                            <a class="btn btn-light navigation-button fw-medium" href="{{ route('admin.student-table.index') }}">
                                Siswa
                            </a>
                        </li>
                        <li class="nav-item px-2 mb-2">
                            <a class="btn btn-light navigation-button fw-medium" href="{{ route('admin.teacher-table.index') }}">
                                Guru
                            </a>
                        </li>

                        <!-- Accordion Menu for Mobile View -->
                        <div class="accordion accordion-menu mt-3" id="adminAccordion">
                            <!-- Siswa Section -->
                            <div class="accordion-item border-0">
                                <h2 class="accordion-header " id="headingSiswa">
                                    <a class="dropdown-item accordion-item container-fluid p-3 w-100 h-100 border-secondary-subtle rounded-2 my-1 fs-6 bg-success-subtle" type="button" href="{{ route('admin.student-table.index') }}">
                                        Siswa
                                    </a>
                                </h2>
                            </div>

                            <!-- Guru Section -->
                            <div class="accordion-item border-0">
                                <h2 class="accordion-header" id="headingGuru">
                                    <a class="dropdown-item accordion-item container-fluid p-3 w-100 h-100 border-secondary-subtle rounded-1 my-1 fs-6 bg-success-subtle" type="button" href="{{ route('admin.teacher-table.index') }}">
                                        Guru
                                    </a>
                                </h2>
                            </div>
                        </div>
                    @elseif (isset($role) && $role == 'Teacher')
                        @php
                            $teacher = auth()->user()->teacher;
                            $className = $teacher->class_name;
                        @endphp
                        <!-- Dropdown Menu for Desktop View -->
                        <li class="dropdown px-2 mb-2" id="FirstItem">
                            <button class="nav-item btn btn-light navigation-button fw-medium" id="laporanDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                Laporan
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="laporanDropdown">
                                <li><a class="dropdown-item" href="{{ route('teacher.laporan-muhasabah-siswa-table.index') }}">Laporan Muhasabah Siswa</a></li>
                                <li><a class="dropdown-item" href="{{ route('teacher.laporan-pelanggaran-siswa-table.index') }}">Laporan Pelanggaran Siswa</a></li>
                                <li class="dropdown-submenu">
                                    <button class="dropdown-item submenu-toggle">Laporan Bacaan Juz &raquo;</button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="{{ route('teacher.laporan-bacaan-juz-siswa-table.index', ['juzNumber' => 1]) }}">Laporan Bacaan Juz 1 Siswa</a></li>
                                        <li><a class="dropdown-item" href="{{ route('teacher.laporan-bacaan-juz-siswa-table.index', ['juzNumber' => 29]) }}">Laporan Bacaan Juz 29 Siswa</a></li>
                                        <li><a class="dropdown-item" href="{{ route('teacher.laporan-bacaan-juz-siswa-table.index', ['juzNumber' => 30]) }}">Laporan Bacaan Juz 30 Siswa</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </li>

                        <li class="dropdown px-2 mb-2">
                            <button class="nav-item btn btn-light navigation-button fw-medium" id="nilaiDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                Nilai
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="nilaiDropdown">
                                <li><a class="dropdown-item" href="{{ route('teacher.nilai-uji-gerakan-siswa-table.index') }}">Nilai Uji Gerakan Siswa</a></li>
                                <li><a class="dropdown-item" href="{{ route('teacher.nilai-uji-bacaan-siswa-table.index') }}">Nilai Uji Bacaan Siswa</a></li>
                            </ul>
                        </li>

                        <li class="dropdown px-2 mb-2">
                            <button class="nav-item btn btn-light navigation-button fw-medium" id="aktivitasDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                Aktivitas
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="aktivitasDropdown">
                                <li><a class="dropdown-item" href="{{ route('teacher.catatan-harian-siswa-table.index') }}">Catatan Harian Siswa</a></li>
                                <li><a class="dropdown-item" href="{{ route('teacher.aktivitas-membaca-siswa-table.index') }}">Aktivitas Membaca Siswa</a></li>
                            </ul>
                        </li>

                        <!-- Accordion Menu for Mobile View -->
                        <div class="accordion accordion-menu mt-3" id="teacherAccordion">
                            <!-- Laporan Section -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingLaporan">
                                    <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#collapseLaporan" type="button" aria-expanded="false" aria-controls="collapseLaporan">
                                        Laporan
                                    </button>
                                </h2>
                                <div class="accordion-collapse collapse" id="collapseLaporan" aria-labelledby="headingLaporan">
                                    <div class="accordion-body">
                                        <a class="dropdown-item accordion-item container-fluid p-3 w-100 h-100 border-secondary-subtle rounded-0 fs-6 bg-success-subtle" href="{{ route('teacher.laporan-muhasabah-siswa-table.index') }}">Laporan Muhasabah Siswa</a>
                                        <a class="dropdown-item accordion-item container-fluid p-3 w-100 h-100 border-secondary-subtle rounded-0 fs-6 bg-success-subtle" href="{{ route('teacher.laporan-pelanggaran-siswa-table.index') }}">Laporan Pelanggaran Siswa</a>
                                        <div class="accordion" id="juzAccordion">
                                            <div class="accordion-item rounded-0">
                                                <h2 class="accordion-header rounded-0" id="headingJuz">
                                                    <button class="accordion-button collapsed rounded-0 fs-6 ps-3 ms-0" data-bs-toggle="collapse" data-bs-target="#collapseJuz" type="button" aria-expanded="false" aria-controls="collapseJuz">
                                                        Laporan Bacaan Juz
                                                    </button>
                                                </h2>
                                                <div class="accordion-collapse collapse" id="collapseJuz" aria-labelledby="headingJuz">
                                                    <div class="accordion-body">
                                                        <a class="dropdown-item accordion-item container-fluid p-3 w-100 h-100 border-secondary-subtle rounded-0 fs-6 bg-success-subtle" href="{{ route('teacher.laporan-bacaan-juz-siswa-table.index', ['juzNumber' => 1]) }}">Laporan Bacaan Juz 1 Siswa</a>
                                                        <a class="dropdown-item accordion-item container-fluid p-3 w-100 h-100 border-secondary-subtle rounded-0 fs-6 bg-success-subtle" href="{{ route('teacher.laporan-bacaan-juz-siswa-table.index', ['juzNumber' => 29]) }}">Laporan Bacaan Juz 29 Siswa</a>
                                                        <a class="dropdown-item accordion-item container-fluid p-3 w-100 h-100 border-secondary-subtle rounded-0 fs-6 bg-success-subtle" href="{{ route('teacher.laporan-bacaan-juz-siswa-table.index', ['juzNumber' => 30]) }}">Laporan Bacaan Juz 30 Siswa</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Nilai Section -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingNilai">
                                    <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#collapseNilai" type="button" aria-expanded="false" aria-controls="collapseNilai">
                                        Nilai
                                    </button>
                                </h2>
                                <div class="accordion-collapse collapse" id="collapseNilai" aria-labelledby="headingNilai">
                                    <div class="accordion-body">
                                        <a class="dropdown-item accordion-item container-fluid p-3 w-100 h-100 border-secondary-subtle rounded-0 fs-6 bg-success-subtle" href="{{ route('teacher.nilai-uji-gerakan-siswa-table.index') }}">Nilai Uji Gerakan Siswa</a>
                                        <a class="dropdown-item accordion-item container-fluid p-3 w-100 h-100 border-secondary-subtle rounded-0 fs-6 bg-success-subtle" href="{{ route('teacher.nilai-uji-bacaan-siswa-table.index') }}">Nilai Uji Bacaan Siswa</a>
                                    </div>
                                </div>
                            </div>

                            <!-- Aktivitas Section -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingAktivitas">
                                    <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#collapseAktivitas" type="button" aria-expanded="false" aria-controls="collapseAktivitas">
                                        Aktivitas
                                    </button>
                                </h2>
                                <div class="accordion-collapse collapse" id="collapseAktivitas" aria-labelledby="headingAktivitas">
                                    <div class="accordion-body">
                                        <a class="dropdown-item accordion-item container-fluid p-3 w-100 h-100 border-secondary-subtle rounded-0 fs-6 bg-success-subtle" href="{{ route('teacher.catatan-harian-siswa-table.index') }}">Catatan Harian Siswa</a>
                                        <a class="dropdown-item accordion-item container-fluid p-3 w-100 h-100 border-secondary-subtle rounded-0 fs-6 bg-success-subtle" href="{{ route('teacher.aktivitas-membaca-siswa-table.index') }}">Aktivitas Membaca Siswa</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif (isset($role) && $role == 'Student')
                        <!-- Dropdown Menu for Desktop View -->
                        <li class="dropdown px-2 mb-2" id="FirstItem">
                            <button class="nav-item btn btn-light navigation-button fw-medium" id="laporanDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                Laporan
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="laporanDropdown">
                                <li><a class="dropdown-item" href="{{ route('student.laporan-muhasabah-siswa-table.index') }}">Laporan Muhasabah Siswa</a></li>
                                <li><a class="dropdown-item" href="{{ route('student.laporan-pelanggaran-siswa-table.index') }}">Laporan Pelanggaran Siswa</a></li>
                                <li class="dropdown-submenu">
                                    <button class="dropdown-item submenu-toggle">Laporan Bacaan Juz &raquo;</button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="{{ route('student.laporan-juz-siswa-table.index', ['juzNumber' => 1]) }}">Laporan Bacaan Juz 1 Siswa</a></li>
                                        <li><a class="dropdown-item" href="{{ route('student.laporan-juz-siswa-table.index', ['juzNumber' => 29]) }}">Laporan Bacaan Juz 29 Siswa</a></li>
                                        <li><a class="dropdown-item" href="{{ route('student.laporan-juz-siswa-table.index', ['juzNumber' => 30]) }}">Laporan Bacaan Juz 30 Siswa</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </li>

                        <li class="dropdown px-2 mb-2">
                            <button class="nav-item btn btn-light navigation-button fw-medium" id="nilaiDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                Nilai
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="nilaiDropdown">
                                <li><a class="dropdown-item" href="{{ route('student.nilai-uji-gerakan-siswa-table.index') }}">Nilai Uji Gerakan Siswa</a></li>
                                <li><a class="dropdown-item" href="{{ route('student.nilai-uji-bacaan-siswa-table.index') }}">Nilai Uji Bacaan Siswa</a></li>
                            </ul>
                        </li>

                        <li class="dropdown px-2 mb-2">
                            <button class="nav-item btn btn-light navigation-button fw-medium" id="aktivitasDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                Aktivitas
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="aktivitasDropdown">
                                <li><a class="dropdown-item" href="{{ route('student.catatan-harian-siswa-table.index') }}">Catatan Harian Siswa</a></li>
                                <li><a class="dropdown-item" href="{{ route('student.aktivitas-membaca-siswa-table.index') }}">Aktivitas Membaca Siswa</a></li>
                            </ul>
                        </li>

                        <!-- Accordion Menu for Mobile View -->
                        <div class="accordion accordion-menu mt-3" id="teacherAccordion">
                            <!-- Laporan Section -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingLaporan">
                                    <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#collapseLaporan" type="button" aria-expanded="false" aria-controls="collapseLaporan">
                                        Laporan
                                    </button>
                                </h2>
                                <div class="accordion-collapse collapse" id="collapseLaporan" aria-labelledby="headingLaporan">
                                    <div class="accordion-body">
                                        <a class="dropdown-item accordion-item container-fluid p-3 w-100 h-100 border-secondary-subtle rounded-0 fs-6 bg-success-subtle" href="{{ route('student.laporan-muhasabah-siswa-table.index') }}">Laporan Muhasabah Siswa</a>
                                        <a class="dropdown-item accordion-item container-fluid p-3 w-100 h-100 border-secondary-subtle rounded-0 fs-6 bg-success-subtle" href="{{ route('student.laporan-pelanggaran-siswa-table.index') }}">Laporan Pelanggaran Siswa</a>
                                        <div class="accordion" id="juzAccordion">
                                            <div class="accordion-item rounded-0">
                                                <h2 class="accordion-header rounded-0" id="headingJuz">
                                                    <button class="accordion-button collapsed rounded-0 fs-6 ps-3 ms-0" data-bs-toggle="collapse" data-bs-target="#collapseJuz" type="button" aria-expanded="false" aria-controls="collapseJuz">
                                                        Laporan Bacaan Juz
                                                    </button>
                                                </h2>
                                                <div class="accordion-collapse collapse" id="collapseJuz" aria-labelledby="headingJuz">
                                                    <div class="accordion-body">
                                                        <a class="dropdown-item accordion-item container-fluid p-3 w-100 h-100 border-secondary-subtle rounded-0 fs-6 bg-success-subtle" href="{{ route('student.laporan-juz-siswa-table.index', ['juzNumber' => 1]) }}">Laporan Bacaan Juz 1 Siswa</a>
                                                        <a class="dropdown-item accordion-item container-fluid p-3 w-100 h-100 border-secondary-subtle rounded-0 fs-6 bg-success-subtle" href="{{ route('student.laporan-juz-siswa-table.index', ['juzNumber' => 29]) }}">Laporan Bacaan Juz 29 Siswa</a>
                                                        <a class="dropdown-item accordion-item container-fluid p-3 w-100 h-100 border-secondary-subtle rounded-0 fs-6 bg-success-subtle" href="{{ route('student.laporan-juz-siswa-table.index', ['juzNumber' => 30]) }}">Laporan Bacaan Juz 30 Siswa</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Nilai Section -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingNilai">
                                    <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#collapseNilai" type="button" aria-expanded="false" aria-controls="collapseNilai">
                                        Nilai
                                    </button>
                                </h2>
                                <div class="accordion-collapse collapse" id="collapseNilai" aria-labelledby="headingNilai">
                                    <div class="accordion-body">
                                        <a class="dropdown-item accordion-item container-fluid p-3 w-100 h-100 border-secondary-subtle rounded-0 fs-6 bg-success-subtle" href="{{ route('student.nilai-uji-gerakan-siswa-table.index') }}">Nilai Uji Gerakan Siswa</a>
                                        <a class="dropdown-item accordion-item container-fluid p-3 w-100 h-100 border-secondary-subtle rounded-0 fs-6 bg-success-subtle" href="{{ route('student.nilai-uji-bacaan-siswa-table.index') }}">Nilai Uji Bacaan Siswa</a>
                                    </div>
                                </div>
                            </div>

                            <!-- Aktivitas Section -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingAktivitas">
                                    <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#collapseAktivitas" type="button" aria-expanded="false" aria-controls="collapseAktivitas">
                                        Aktivitas
                                    </button>
                                </h2>
                                <div class="accordion-collapse collapse" id="collapseAktivitas" aria-labelledby="headingAktivitas">
                                    <div class="accordion-body">
                                        <a class="dropdown-item accordion-item container-fluid p-3 w-100 h-100 border-secondary-subtle rounded-0 fs-6 bg-success-subtle" href="{{ route('student.catatan-harian-siswa-table.index') }}">Catatan Harian Siswa</a>
                                        <a class="dropdown-item accordion-item container-fluid p-3 w-100 h-100 border-secondary-subtle rounded-0 fs-6 bg-success-subtle" href="{{ route('student.aktivitas-membaca-siswa-table.index') }}">Aktivitas Membaca Siswa</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </ul>

                <!-- Profile Section for Desktop -->
                <ul class="navbar-nav ms-auto mt-0 d-none d-md-flex">
                    <li class="nav-item dropdown">
                        <a class="nav-link d-flex align-items-center text-white fw-medium" id="profileDropdown" data-bs-toggle="dropdown" role="button" aria-expanded="false">
                            <img id="ProfilePhoto" src="https://cdn-icons-png.flaticon.com/512/149/149071.png" alt="Profile Photo">
                            <span class="ms-3">—</span>
                            <span class="ms-2 me-2">{{ isset($name) ? $name : 'NAME' }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end text-start ms-1" aria-labelledby="profileDropdown">
                            <li class="ms-1 me-3">
                                @if (isset($role) && $role == 'Admin')
                                    <a class="dropdown-item p-1 m-0 my-0 ms-1" href="{{ route('change-password.index') }}">Ganti Password</a>
                                @endif
                                <div class="dropdown-divider border-0 bg-white p-0 m-0 ms-1"></div>
                                <form class="p-0 m-0" method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button class=" p-1 m-0 my-0 dropdown-item ms-1" type="submit">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>

                <!-- Profile Section for Mobile as an Accordion -->
                <div class="accordion mobile-profile-section d-md-none mt-3">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingProfile">
                            <button class="accordion-button collapsed fw-medium" data-bs-toggle="collapse" data-bs-target="#collapseProfile" type="button" aria-expanded="false" aria-controls="collapseProfile">
                                <img id="ProfilePhoto" src="https://cdn-icons-png.flaticon.com/512/149/149071.png" alt="Profile Photo">
                                <span class="ms-3">—</span>
                                <span class="ms-2 me-2">{{ isset($name) ? $name : 'NAME' }}</span>
                            </button>
                        </h2>
                        <div class="accordion-collapse collapse" id="collapseProfile" aria-labelledby="headingProfile">
                            <div class="accordion-body">
                                @if (isset($role) && $role == 'Admin')
                                    <a class="btn bg-danger-subtle w-100" href="{{ route('change-password.index') }}">Ganti Password</a>
                                @endif
                                <div class="dropdown-divider border-0 bg-white p-0 m-0 pt-2"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button class="btn bg-danger-subtle w-100" type="submit">Logout</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
</div>

@push('scripts')
@endpush
