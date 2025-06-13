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
            <h2 class="text-center mb-4">Detail Laporan Pelanggaran</h2>
            <h2 class="text-center mb-0">Siswa {{ $violationReport->student->user->name }}</h2>
        </div>
        <div class="d-flex justify-content-center align-items-center">
            <div class="p-4 rounded w-75">
                <form id="detailLaporanPelanggaran">

                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="date">Tanggal</label>
                        <input class="form-control rounded-3 border-dark border-2" id="date" name="date" type="text" value="{{ \Carbon\Carbon::parse($violationReport->time_stamp)->locale('id')->translatedFormat('l, d-m-Y') }}" readonly disabled>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="violation_details">Detail Pelanggaran</label>
                        <textarea class="form-control rounded-3 border-dark border-2" id="violation_details" name="violation_details" rows="3" readonly disabled>{{ $violationReport->violation_details }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="consequence">Konsekuensi</label>
                        <textarea class="form-control rounded-3 border-dark border-2" id="consequence" name="consequence" rows="3" readonly disabled>{{ $violationReport->consequence }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="paraf">Paraf Guru</label>
                        <input class="form-control rounded-3 border-dark border-2" id="paraf" name="paraf" type="text" value="{{ $teacherSign }}" readonly disabled>
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