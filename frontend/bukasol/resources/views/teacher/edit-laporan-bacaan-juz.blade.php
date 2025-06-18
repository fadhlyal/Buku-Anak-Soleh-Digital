@extends('teacher.teacher-dashboard')

@push('styles')
    <link href="{{ asset('css/dashboardWithTable.css') }}" rel="stylesheet">

    <style>
        .form-check-input:checked {
            background-color: green !important;
        }

        .form-check-input:focus {
            border-color: green !important;
        }
    </style>
@endpush

@section('content_3')
    <div class="p-0 m-0">
        <div class="text-center p-0 m-0">
            <h2 class="text-center mb-4">Ubah Setoran Hafalan Juz {{ $juzNumber }} Siswa {{ $studentName }}</h2>
        </div>
        <div class="d-flex justify-content-center align-items-center">
            <div class="p-4 rounded w-75">
                <form action="{{ route('juz-report.update', ['id' => $reportId]) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <input class="form-control rounded-3 border-dark border-2" id="studentId" name="studentId" type="hidden" value="{{ $studentId }}" readonly>
                    <input class="form-control rounded-3 border-dark border-2" id="juz" name="juz" type="hidden" value="{{ $juzNumber }}" readonly>

                    <!-- Hari/Tanggal Input -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="tanggal">Tanggal</label>
                        <input class="form-control rounded-3 border-dark border-2" id="tanggal" name="tanggal" type="date" value="{{ old('tanggal', $juzReport->time_stamp->toDateString()) }}" required>
                    </div>

                    <!-- Surah Input -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="surah">Surah</label>
                        <div class="input-group mb-3" id="select2container" required>
                            <select class="form-select rounded-3 border-dark border-2" id="surah" name="surah">
                                <option value="" disabled {{ old('surah', $juzReport->surah_name) ? '' : 'selected' }}>Pilih Surat</option>

                                @php
                                    $surahList = [];

                                    if ($juzNumber == 1) {
                                        $surahList = [
                                            "Al-Fatihah",
                                            "Al-Baqarah"
                                        ];
                                    } elseif ($juzNumber == 29) {
                                        $surahList = [
                                            "Al-Mulk", "Al-Qalam", "Al-Haqqah", "Al-Ma'arij", "Nuh", "Al-Jinn", "Al-Muzzammil",
                                            "Al-Muddatstsir", "Al-Qiyamah", "Al-Insan", "Al-Mursalat"
                                        ];
                                    } elseif ($juzNumber == 30) {
                                        $surahList = [
                                            "An-Naba'", "An-Nazi'at", "Abasa", "At-Takwir", "Al-Infitar", "Al-Muthaffifin", "Al-Insyiqaq",
                                            "Al-Buruj", "At-Tariq", "Al-A'la", "Al-Ghasyiyah", "Al-Fajr", "Al-Balad", "Asy-Syams", "Al-Lail",
                                            "Ad-Duha", "Al-Insyirah", "At-Tin", "Al-'Alaq", "Al-Qadr", "Al-Bayyinah", "Az-Zalzalah",
                                            "Al-'Adiyat", "Al-Qari'ah", "At-Takatsur", "Al-'Asr", "Al-Humazah", "Al-Fil", "Quraisy",
                                            "Al-Ma'un", "Al-Kautsar", "Al-Kafirun", "An-Nasr", "Al-Lahab", "Al-Ikhlas", "Al-Falaq", "An-Nas"
                                        ];
                                    }
                                @endphp

                                @foreach ($surahList as $surah)
                                    <option value="{{ $surah }}" {{ old('surah', $juzReport->surah_name) === $surah ? 'selected' : '' }}>
                                        {{ $surah }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Ayat Input -->
                    @php
                        $halaman = explode('-', $juzReport->surah_ayat ?? '');
                    @endphp

                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="ayat">Ayat</label>
                        <input class="form-control rounded-3 border-dark border-2 mb-2" id="ayat_awal" name="ayat_awal" type="text" value="{{ old('ayat_awal', $halaman[0]) }}">
                    <input class="form-control rounded-3 border-dark border-2" id="ayat_akhir" name="ayat_akhir" type="text" value="{{ old('ayat_akhir', $halaman[1]) }}">
                    </div>

                    <!-- Buttons -->
                    <div class="d-flex justify-content-center align-items-stretch pt-3 gap-3">
                        <a class="btn btn-secondary rounded-3 flex-fill d-flex justify-content-center align-items-center text-center px-3 py-2" href="{{ route('teacher.laporan-bacaan-juz-siswa.index', ['juzNumber' => $juzNumber, 'id' => $studentId]) }}">Batal</a>
                        <button class="btn btn-success rounded-3 flex-fill d-flex justify-content-center align-items-center text-center px-3 py-2" type="submit">Ubah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        window.addEventListener("pageshow", function(event) {
            if (event.persisted || performance.getEntriesByType("navigation")[0].type === "back_forward") {
                // Reload the page when navigating forward or back in history
                location.reload();
            }
        });
    </script>
@endpush