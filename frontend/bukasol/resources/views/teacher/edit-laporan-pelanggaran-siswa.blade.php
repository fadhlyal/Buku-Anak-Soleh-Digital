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
            <h2 class="text-center mb-4">Ubah Laporan Pelanggaran {{ $studentName }}</h2>
        </div>
        <div class="d-flex justify-content-center align-items-center">
            <div class="p-4 rounded w-75">
                <form action="{{ route('violation-report.update', ['id' => $reportId]) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <input class="form-control rounded-3 border-dark border-2" id="studentId" name="studentId" type="hidden" value="{{ $studentId }}" readonly>

                    <!-- Hari/Tanggal Input -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="tanggal">Tanggal</label>
                        <input class="form-control rounded-3 border-dark border-2" id="tanggal" name="tanggal" type="date" value="{{ old('tanggal', $violationReport->time_stamp->toDateString()) }}" required>
                    </div>

                    <!-- Pelanggaran Input -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="detail_pelanggaran">Detail Pelanggaran</label>
                        <textarea class="form-control rounded-3 border-dark border-2" id="detail_pelanggaran" name="detail_pelanggaran" rows="3" required>{{ old('detail_pelanggaran', $violationReport->violation_details) }}</textarea>
                    </div>

                    <!-- Konsekuensi Input -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="konsekuensi">Konsekuensi</label>
                        <textarea class="form-control rounded-3 border-dark border-2" id="konsekuensi" name="konsekuensi" rows="3" required>{{ old('konsekuensi', $violationReport->consequence) }}</textarea>
                    </div>

                    <!-- Buttons -->
                    <div class="d-flex justify-content-center align-items-stretch pt-3 gap-3">
                        <a class="btn btn-secondary rounded-3 flex-fill d-flex justify-content-center align-items-center text-center px-3 py-2" href="{{ route('teacher.laporan-pelanggaran-siswa.index', ['id' => $studentId]) }}">Batal</a>
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