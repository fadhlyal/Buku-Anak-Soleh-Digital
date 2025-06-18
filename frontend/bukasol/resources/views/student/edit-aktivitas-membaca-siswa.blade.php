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
            <h2 class="text-center mb-4">Ubah Aktivitas Membaca {{ $studentName }}</h2>
        </div>
        <div class="d-flex justify-content-center align-items-center">
            <div class="p-4 rounded w-75">
                <form action="{{ route('reading-activity.update', ['id' => $noteId]) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <input class="form-control rounded-3 border-dark border-2" id="studentId" name="studentId" type="hidden" value="{{ $studentId }}" readonly>

                    <!-- Hari/Tanggal Input -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="tanggal">Hari/Tanggal</label>
                        <input class="form-control rounded-3 border-dark border-2" id="tanggal" name="tanggal" type="date" value="{{ old('tanggal', $readActivity->time_stamp->toDateString()) }}" required>
                    </div>

                    <!-- Judul Buku Input -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="judul_buku">Judul Buku</label>
                        <input class="form-control rounded-3 border-dark border-2" id="judul_buku" name="judul_buku" type="text" value="{{ old('judul_buku', $readActivity->book_title) }}" required>
                    </div>

                    <!-- Halaman Input -->
                     @php
                        $halaman = explode('-', $readActivity->page);
                    @endphp

                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="halaman">Halaman</label>
                        <input class="form-control rounded-3 border-dark border-2 mb-2" id="halaman_awal" name="halaman_awal" type="text" value="{{ old('halaman_awal', $halaman[0]) }}" required>
                        <input class="form-control rounded-3 border-dark border-2" id="halaman_akhir" name="halaman_akhir" type="text" value="{{ old('halaman_akhir', $halaman[1]) }}" required>
                    </div>

                    <!-- Buttons -->
                    <div class="d-flex justify-content-center align-items-stretch pt-3 gap-3">
                        <a class="btn btn-secondary rounded-3 flex-fill d-flex justify-content-center align-items-center text-center px-3 py-2" href="{{ route('student.aktivitas-membaca-siswa-table.index') }}">Batal</a>
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