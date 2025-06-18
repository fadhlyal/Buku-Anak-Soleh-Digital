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
            <h2 class="text-center mb-4">Ubah Laporan Muhasabah Harian {{ $studentName }}</h2>
        </div>
        <div class="d-flex justify-content-center align-items-center">
            <div class="p-4 rounded w-75">
                <form action="{{ route('muhasabah-report.update', ['id' => $reportId]) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <input class="form-control rounded-3 border-dark border-2" id="studentId" name="studentId" type="hidden" value="{{ $studentId }}" readonly>

                    <!-- Tanggal Input -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="tanggal">Tanggal</label>
                        <input class="form-control rounded-3 border-dark border-2" id="tanggal" name="tanggal" type="date" value="{{ old('tanggal', $muhasabahReport->time_stamp->toDateString()) }}" required>
                    </div>

                    <!-- Mengaji Input -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="mengaji">Mengaji</label>

                        <!-- Surah Input -->
                        <div class="input-group mb-3" id="select2container">
                            <select class="form-select rounded-3 border-dark border-2" id="surah" name="surah">
                                <option value="" {{ old('surah', $muhasabahReport->surah_name) ? '' : 'selected' }}>Pilih Surat</option>

                                 @php
                                    $surahList = [
                                        "Al-Fatihah", "Ali 'Imran", "An-Nisa", "Al-Ma'idah", "Al-An'am", "Al-A'raf", "Al-Anfal",
                                        "At-Taubah", "Yunus", "Hud", "Yusuf", "Ar-Ra'd", "Ibrahim", "Al-Hijr", "An-Nahl", "Al-Isra'", "Al-Kahf", "Maryam", "Ta-Ha",
                                        "Al-Anbiya", "Al-Hajj", "Al-Mu'minun", "An-Nur", "Al-Furqan", "Asy-Syu'ara'", "An-Naml", "Al-Qasas", "Al-'Ankabut", "Ar-Rum",
                                        "Luqman", "As-Sajdah", "Al-Ahzab", "Saba'", "Fatir", "Ya-Sin", "As-Saffat", "Sad", "Az-Zumar", "Ghafir", "Fussilat",
                                        "Asy-Syura", "Az-Zukhruf", "Ad-Dukhan", "Al-Jatsiyah", "Al-Ahqaf", "Muhammad", "Al-Fath", "Al-Hujurat", "Qaf", "Az-Zariyat",
                                        "At-Tur", "An-Najm", "Al-Qamar", "Ar-Rahman", "Al-Waqi'ah", "Al-Hadid", "Al-Mujadilah", "Al-Hasyr", "Al-Mumtahanah",
                                        "As-Saff", "Al-Jumu'ah", "Al-Munafiqun", "At-Taghabun", "At-Talaq", "At-Tahrim", "Al-Mulk", "Al-Qalam", "Al-Haqqah",
                                        "Al-Ma'arij", "Nuh", "Al-Jinn", "Al-Muzzammil", "Al-Muddatstsir", "Al-Qiyamah", "Al-Insan", "Al-Mursalat", "An-Naba'",
                                        "An-Nazi'at", "Abasa", "At-Takwir", "Al-Infitar", "Al-Muthaffifin", "Al-Insyiqaq", "Al-Buruj", "At-Tariq", "Al-A'la",
                                        "Al-Ghasyiyah", "Al-Fajr", "Al-Balad", "Asy-Syams", "Al-Lail", "Ad-Duha", "Al-Insyirah", "At-Tin", "Al-'Alaq", "Al-Qadr",
                                        "Al-Bayyinah", "Az-Zalzalah", "Al-'Adiyat", "Al-Qari'ah", "At-Takatsur", "Al-'Asr", "Al-Humazah", "Al-Fil", "Quraisy",
                                        "Al-Ma'un", "Al-Kautsar", "Al-Kafirun", "An-Nasr", "Al-Lahab", "Al-Ikhlas", "Al-Falaq", "An-Nas"
                                    ];
                                @endphp

                                @foreach ($surahList as $surah)
                                    <option value="{{ $surah }}" {{ old('surah', $muhasabahReport->surah_name) === $surah ? 'selected' : '' }}>{{ $surah }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Halaman Input -->
                        @php
                            $halaman = explode('-', $muhasabahReport->surah_ayat ?? '');
                        @endphp

                        <input class="form-control rounded-3 border-dark border-2 mb-2" id="ayat_awal" name="ayat_awal" type="text" value="{{ old('ayat_awal', $halaman[0]) }}">
                        <input class="form-control rounded-3 border-dark border-2" id="ayat_akhir" name="ayat_akhir" type="text" value="{{ old('ayat_akhir', $halaman[1]) }}">
                    </div>

                    <!-- Shalat Sunnah Input -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="shalat_sunnah">Shalat Sunnah</label>
                        <select class="form-select rounded-3 border-dark border-2" id="shalat_sunnah" name="shalat_sunnah" required>
                            <option value="" disabled {{ old('shalat_sunnah', $muhasabahReport->sunnah_pray) ? '' : 'selected' }}>Apakah Shalat Sunnah?</option>
                            <option value="Sudah" {{ old('shalat_sunnah', $muhasabahReport->sunnah_pray ? 'Sudah' : 'Tidak') === 'Sudah' ? 'selected' : '' }}>Shalat</option>
                            <option value="Tidak" {{ old('shalat_sunnah', $muhasabahReport->sunnah_pray ? 'Sudah' : 'Tidak') === 'Tidak' ? 'selected' : '' }}>Tidak Shalat</option>
                        </select>
                    </div>

                    <!-- Shalat Fardhu Inputs -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Shalat Fardhu</label>
                        <select class="form-select rounded-3 border-dark border-2 mb-2" id="subuh" name="subuh" required>
                            <option value="" disabled {{ old('subuh', $muhasabahReport->subuh_pray) ? '' : 'selected' }}>Apakah Shalat Subuh?</option>
                            <option value="Sudah" {{ old('subuh', $muhasabahReport->subuh_pray ? 'Sudah' : 'Tidak') === 'Sudah' ? 'selected' : '' }}>Shalat</option>
                            <option value="Tidak" {{ old('subuh', $muhasabahReport->subuh_pray ? 'Sudah' : 'Tidak') === 'Tidak' ? 'selected' : '' }}>Tidak Shalat</option>
                        </select>
                        <select class="form-select rounded-3 border-dark border-2 mb-2" id="dzuhur" name="dzuhur" required>
                            <option value="" disabled {{ old('dzuhur', $muhasabahReport->dzuhur_pray) ? '' : 'selected' }}>Apakah Shalat dzuhur?</option>
                            <option value="Sudah" {{ old('dzuhur', $muhasabahReport->dzuhur_pray ? 'Sudah' : 'Tidak') === 'Sudah' ? 'selected' : '' }}>Shalat</option>
                            <option value="Tidak" {{ old('dzuhur', $muhasabahReport->dzuhur_pray ? 'Sudah' : 'Tidak') === 'Tidak' ? 'selected' : '' }}>Tidak Shalat</option>
                        </select>
                        <select class="form-select rounded-3 border-dark border-2 mb-2" id="ashar" name="ashar" required>
                            <option value="" disabled {{ old('ashar', $muhasabahReport->ashar_pray) ? '' : 'selected' }}>Apakah Shalat Ashar?</option>
                            <option value="Sudah" {{ old('ashar', $muhasabahReport->ashar_pray ? 'Sudah' : 'Tidak') === 'Sudah' ? 'selected' : '' }}>Shalat</option>
                            <option value="Tidak" {{ old('ashar', $muhasabahReport->ashar_pray ? 'Sudah' : 'Tidak') === 'Tidak' ? 'selected' : '' }}>Tidak Shalat</option>
                        </select>
                        <select class="form-select rounded-3 border-dark border-2 mb-2" id="maghrib" name="maghrib" required>
                            <option value="" disabled {{ old('maghrib', $muhasabahReport->maghrib_pray) ? '' : 'selected' }}>Apakah Shalat Maghrib?</option>
                            <option value="Sudah" {{ old('maghrib', $muhasabahReport->maghrib_pray ? 'Sudah' : 'Tidak') === 'Sudah' ? 'selected' : '' }}>Shalat</option>
                            <option value="Tidak" {{ old('maghrib', $muhasabahReport->maghrib_pray ? 'Sudah' : 'Tidak') === 'Tidak' ? 'selected' : '' }}>Tidak Shalat</option>
                        </select>
                        <select class="form-select rounded-3 border-dark border-2" id="isya" name="isya" required>
                            <option value="" disabled {{ old('isya', $muhasabahReport->isya_pray) ? '' : 'selected' }}>Apakah Shalat Isya?</option>
                            <option value="Sudah" {{ old('isya', $muhasabahReport->isya_pray ? 'Sudah' : 'Tidak') === 'Sudah' ? 'selected' : '' }}>Shalat</option>
                            <option value="Tidak" {{ old('isya', $muhasabahReport->isya_pray ? 'Sudah' : 'Tidak') === 'Tidak' ? 'selected' : '' }}>Tidak Shalat</option>
                        </select>
                    </div>

                    <!-- Buttons -->
                    <div class="d-flex justify-content-center align-items-stretch pt-3 gap-3">
                        <a class="btn btn-secondary rounded-3 flex-fill d-flex justify-content-center align-items-center text-center px-3 py-2" href="{{ route('student.laporan-muhasabah-siswa-table.index') }}">Batal</a>
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