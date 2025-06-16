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

        a.disabled {
            pointer-events: none;
            cursor: default;
        }
    </style>
@endpush

@section('content_3')
    <div class="p-0 m-0">
        <div class="text-center p-0 m-0">
            <h2 class="text-center mb-0">Detail Catatan Aktivitas Harian</h2>
            <h2 class="text-center mb-0">Siswa {{ $activityNote->student->user->name }}</h2>
        </div>
        <div class="d-flex justify-content-center align-items-center">
            <div class="p-4 rounded w-75">
                <form>
                    @csrf

                    <!-- Hari/Tanggal Input -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="hari_tanggal">Hari/Tanggal</label>
                        <input class="form-control rounded-3 border-dark border-2" id="hari_tanggal" name="hari_tanggal" type="text" value="{{ \Carbon\Carbon::parse($activityNote->time_stamp)->locale('id')->translatedFormat('l, d-m-Y') }}" readonly disabled>
                    </div>

                    <!-- Kategori Input -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="kategori">Kategori</label>
                        <input class="form-control rounded-3 border-dark border-2" id="kategori" name="kategori" type="text" value="{{ $activityNote->category }}" readonly disabled>
                    </div>

                    <!-- Aktivitas Input -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="aktivitas">Aktivitas</label>
                        <input class="form-control rounded-3 border-dark border-2" id="aktivitas" name="aktivitas" type="text" value="{{ $activityNote->activity }}" readonly disabled>
                    </div>

                    <!-- Rincian Aktivitas Input -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="rincian_aktivitas">Rincian Aktivitas</label>
                        <textarea class="form-control rounded-3 border-dark border-2" id="rincian_aktivitas" name="rincian_aktivitas" rows="3" readonly disabled>{{ $activityNote->activity_detail }}</textarea>
                    </div>

                    <!-- Pertanyaan Orang Tua Input -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="pertanyaan_orang_tua">Pertanyaan Orang Tua</label>
                        <textarea class="form-control rounded-3 border-dark border-2" id="pertanyaan_orang_tua" name="pertanyaan_orang_tua" rows="3" readonly disabled>{{ $parentQuestion }}</textarea>
                    </div>

                    <!-- Jawaban Guru Input -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="jawaban_guru">Respon Guru</label>
                        <textarea class="form-control rounded-3 border-dark border-2" id="jawaban_guru" name="jawaban_guru" rows="3" readonly disabled>{{ $teacherAnswer }}</textarea>
                    </div>

                    <!-- Paraf Input -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="paraf">Paraf Guru</label>
                        <input class="form-control rounded-3 border-dark border-2" id="paraf" name="paraf" type="text" value="{{ $teacherSign }}" readonly disabled>
                    </div>

                    <!-- Button Placeholder -->
                    <div class="d-flex justify-content-center align-items-stretch pt-3 gap-3">
                        <a class="btn btn-secondary rounded-3 flex-fill d-flex justify-content-center align-items-center text-center px-3 py-2" href="{{ route('teacher.catatan-harian-siswa.index', ['id' => $activityNote->student->id]) }}">
                            Batal
                        </a>
                        @if (is_null($activityNote->parent_question))
                            <a class="btn btn-success rounded-3 flex-fill d-flex justify-content-center align-items-center text-center px-3 py-2 disabled">
                                Jawab Pertanyaan Orang Tua
                            </a>
                        @else
                            <a class="btn btn-success rounded-3 flex-fill d-flex justify-content-center align-items-center text-center px-3 py-2" href="{{ route('teacher.jawaban-pertanyaan-orangtua.index', ['id' => $activityNote->id]) }}">
                                Jawab Pertanyaan Orang Tua
                            </a>
                        @endif
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