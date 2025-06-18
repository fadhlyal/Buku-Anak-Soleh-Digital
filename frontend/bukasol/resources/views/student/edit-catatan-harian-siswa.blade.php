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
            <h2 class="text-center mb-4">Ubah Catatan Harian {{ $studentName }}</h2>
        </div>
        <div class="d-flex justify-content-center align-items-center">
            <div class="p-4 rounded w-75">
                <form action="{{ route('activity-notes.update', ['id' => $noteId]) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <input class="form-control rounded-3 border-dark border-2" id="studentId" name="studentId" type="hidden" value="{{ $studentId }}" readonly>

                    <!-- Hari/Tanggal Input -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="hari_tanggal">Hari/Tanggal</label>
                        <input class="form-control rounded-3 border-dark border-2" id="hari_tanggal" name="hari_tanggal" type="date" value="{{ old('hari_tanggal', $activityNote->time_stamp->toDateString()) }}" required>
                    </div>

                    <!-- Kategori Input -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="kategori">Kategori</label>
                        <select class="form-select rounded-3 border-dark border-2" id="kategori" name="kategori" onchange="handleKategoriChange()" required>
                            <option value="" disabled {{ old('kategori', $activityNote->category) ? '' : 'selected' }}>Pilih Kategori</option>
                            <option value="Aktivitas Harian" {{ old('kategori', $activityNote->category) === 'Aktivitas Harian' ? 'selected' : '' }}>Aktivitas Harian</option>
                            <option value="Prestasi" {{ old('kategori', $activityNote->category) === 'Prestasi' ? 'selected' : '' }}>Prestasi</option>
                            <option value="Ekstrakurikuler" {{ old('kategori', $activityNote->category) === 'Ekstrakurikuler' ? 'selected' : '' }}>Ekstrakurikuler</option>
                        </select>
                    </div>

                    <!-- Detail Input -->
                    <div id="kategori-fields">
                        @if ($activityNote->category === 'Aktivitas Harian')
                            <div class="mb-3">
                                <label class="form-label fw-semibold" for="aktivitas">Aktivitas</label>
                                <input class="form-control rounded-3 border-dark border-2" id="aktivitas" name="aktivitas" type="text" value="{{ old('aktivitas', $activityNote->activity) }}" placeholder="Masukkan Aktivitas" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold" for="rincian_aktivitas">Rincian Aktivitas</label>
                                <textarea class="form-control rounded-3 border-dark border-2" id="rincian_aktivitas" name="rincian_aktivitas" rows="3" required>{{ old('rincian_aktivitas', $activityNote->activity_detail) }}</textarea>
                            </div>
                        @elseif ($activityNote->category === 'Prestasi')
                            <div class="mb-3">
                                <label class="form-label fw-semibold" for="aktivitas">Nama Perlombaan</label>
                                <input class="form-control rounded-3 border-dark border-2" id="aktivitas" name="aktivitas" type="text" value="{{ old('aktivitas', $activityNote->activity) }}" placeholder="Masukkan Nama Perlombaan" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold" for="rincian_aktivitas">Rincian Kegiatan Lomba</label>
                                <textarea class="form-control rounded-3 border-dark border-2" id="rincian_aktivitas" name="rincian_aktivitas" rows="3" required>{{ old('rincian_aktivitas', $activityNote->activity_detail) }}</textarea>
                            </div>
                        @elseif ($activityNote->category === 'Ekstrakurikuler')
                            <div class="mb-3">
                                <label class="form-label fw-semibold" for="aktivitas">Nama Ekstrakurikuler</label>
                                <input class="form-control rounded-3 border-dark border-2" id="aktivitas" name="aktivitas" type="text" value="{{ old('aktivitas', $activityNote->activity) }}" placeholder="Masukkan Nama Ekstrakurikuler" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold" for="rincian_aktivitas">Rincian Ekstrakurikuler</label>
                                <textarea class="form-control rounded-3 border-dark border-2" id="rincian_aktivitas" name="rincian_aktivitas" rows="3" required>{{ old('rincian_aktivitas', $activityNote->activity_detail) }}</textarea>
                            </div>
                        @endif
                    </div>

                    <!-- Pertanyaan Orang Tua Input -->
                    @php
                        $isEditable = is_null($activityNote->teacher_answer);
                    @endphp

                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="pertanyaan_orang_tua">Pertanyaan Orang Tua</label>
                        <textarea class="form-control rounded-3 border-dark border-2" id="pertanyaan_orang_tua" name="pertanyaan_orang_tua" rows="3" {{ $isEditable ? '' : 'readonly' }}>{{ old('pertanyaan_orang_tua', $activityNote->parent_question) }}</textarea>
                    </div>

                    <!-- Buttons -->
                    <div class="d-flex justify-content-center align-items-stretch pt-3 gap-3">
                        <a class="btn btn-secondary rounded-3 flex-fill d-flex justify-content-center align-items-center text-center px-3 py-2" href="{{ route('student.catatan-harian-siswa-table.index') }}">Batal</a>
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

        function handleKategoriChange() {
            const selectedKategori = document.getElementById("kategori").value;
            const fieldContainer = document.getElementById("kategori-fields");

            let html = "";

            if (selectedKategori === "Aktivitas Harian") {
                html = `
                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="aktivitas">Aktivitas</label>
                        <input class="form-control rounded-3 border-dark border-2" id="aktivitas" name="aktivitas" type="text" value="{{ old('aktivitas', $activityNote->activity) }}" placeholder="Masukkan Aktivitas" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="rincian_aktivitas">Rincian Aktivitas</label>
                        <textarea class="form-control rounded-3 border-dark border-2" id="rincian_aktivitas" name="rincian_aktivitas" rows="3" required>{{ old('rincian_aktivitas', $activityNote->activity_detail) }}</textarea>
                    </div>
                `;
            } else if (selectedKategori === "Prestasi") {
                html = `
                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="aktivitas">Nama Perlombaan</label>
                        <input class="form-control rounded-3 border-dark border-2" id="aktivitas" name="aktivitas" type="text" value="{{ old('aktivitas', $activityNote->activity) }}" placeholder="Masukkan Nama Perlombaan" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="rincian_aktivitas">Rincian Kegiatan Lomba</label>
                        <textarea class="form-control rounded-3 border-dark border-2" id="rincian_aktivitas" name="rincian_aktivitas" rows="3" required>{{ old('rincian_aktivitas', $activityNote->activity_detail) }}</textarea>
                    </div>
                `;
            } else if (selectedKategori === "Ekstrakurikuler") {
                html = `
                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="aktivitas">Nama Ekstrakurikuler</label>
                        <input class="form-control rounded-3 border-dark border-2" id="aktivitas" name="aktivitas" type="text" value="{{ old('aktivitas', $activityNote->activity) }}" placeholder="Masukkan Nama Ekstrakurikuler" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="rincian_aktivitas">Rincian Ekstrakurikuler</label>
                        <textarea class="form-control rounded-3 border-dark border-2" id="rincian_aktivitas" name="rincian_aktivitas" rows="3" required>{{ old('rincian_aktivitas', $activityNote->activity_detail) }}</textarea>
                    </div>
                `;
            }

            fieldContainer.innerHTML = html;
        }
    </script>
@endpush