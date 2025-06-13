// File: resources\views\student\laporan-muhasabah-harian-detail.blade.php
@extends('student.student-dashboard')

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
            <h2 class="text-center mb-4">Detail Laporan Muhasabah Harian</h2>
        </div>
        <div class="d-flex justify-content-center align-items-center">
            <div class="p-4 mb-5 rounded w-50 border border-2">

                <div class="mb-3">
                    <label class="form-label fw-semibold" for="hari_tanggal">Hari/Tanggal</label>
                    <input class="form-control rounded-3 border-dark border-2" id="hari_tanggal" type="text" value="{{ \Carbon\Carbon::parse($muhasabahReport->time_stamp)->locale('id')->translatedFormat('l, d-m-Y') }}" readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold" for="mengaji">Mengaji</label>
                    <input class="form-control rounded-3 border-dark border-2" id="mengaji" type="text" value="{{ $surahName }}" readonly>
                    <input class="form-control rounded-3 border-dark border-2 mt-1" id="ayat" type="text" value="{{ $surahAyat }}" readonly>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Shalat Sunnah</label>
                    <input style="{{ $muhasabahReport->sunnah_pray ? 'background-color: #198754 !important; border: 1px solid #198754 !important;' : 'background-color: #dc3545 !important; border: 1px solid #dc3545 !important;' }}" class="form-control rounded-3 border-dark border-2" type="text" value="{{ $muhasabahReport->sunnah_pray ? 'Shalat' : 'Tidak Shalat' }}" readonly>
                </div>

                <div class="mb-4">
                    <h3 class="fw-semibold text-center">Shalat Fardhu</h3>
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Subuh
                            <span class="badge {{ $muhasabahReport->subuh_pray ? 'bg-success' : 'bg-danger' }} rounded-pill px-3">
                                {{ $muhasabahReport->subuh_pray ? 'Shalat' : 'Tidak Shalat' }}
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Dzuhur
                            <span class="badge {{ $muhasabahReport->dzuhur_pray ? 'bg-success' : 'bg-danger' }} rounded-pill px-3">
                                {{ $muhasabahReport->dzuhur_pray ? 'Shalat' : 'Tidak Shalat' }}
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Ashar
                            <span class="badge {{ $muhasabahReport->ashar_pray ? 'bg-success' : 'bg-danger' }} rounded-pill px-3">
                                {{ $muhasabahReport->ashar_pray ? 'Shalat' : 'Tidak Shalat' }}
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Maghrib
                            <span class="badge {{ $muhasabahReport->maghrib_pray ? 'bg-success' : 'bg-danger' }} rounded-pill px-3">
                                {{ $muhasabahReport->maghrib_pray ? 'Shalat' : 'Tidak Shalat' }}
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Isya
                            <span class="badge {{ $muhasabahReport->isya_pray ? 'bg-success' : 'bg-danger' }} rounded-pill px-3">
                                {{ $muhasabahReport->isya_pray ? 'Shalat' : 'Tidak Shalat' }}
                            </span>
                        </li>
                    </ul>
                </div>

                <div class="mb-4">
                    <h3 class="fw-semibold text-center">Paraf</h3>
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Guru
                            <span class="badge {{ $muhasabahReport->teacher_sign ? 'bg-success' : 'bg-danger' }}  rounded-pill px-3">{{ $muhasabahReport->teacher_sign ? 'Sudah' : 'Belum' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Orang Tua
                            <span class="badge {{ $muhasabahReport->parent_sign ? 'bg-success' : 'bg-danger' }}  rounded-pill px-3">{{ $muhasabahReport->parent_sign ? 'Sudah' : 'Belum' }}</span>
                        </li>
                    </ul>
                </div>
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