@extends('student.student-dashboard')

@push('styles')
    <link href="{{ asset('css/dashboardWithTable.css') }}" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .form-check-input:checked {
            background-color: green !important;
        }

        .form-check-input:focus {
            border-color: green !important;
        }
    </style>

    <style>
        .select2-container--default .select2-selection--single {
            border: 2px solid black;
            border-radius: 7px;
            height: 125%;
            align-content: center;
            padding-left: 7px
        }
    </style>
@endpush

@section('content_3')
    <div class="p-0 m-0">
        <div class="text-center p-0 m-0">
            <h2 class="text-center mb-4">Tambah Hafalan Juz {{ $juzNumber }}</h2>
        </div>
        <div class="d-flex justify-content-center align-items-center">
            <div class="p-4 rounded w-75">
                <form action="{{ route('juz-report.store') }}" method="POST">
                    @csrf

                    <input class="form-control rounded-3 border-dark border-2" id="studentId" name="studentId" type="hidden" value="{{ $studentId }}" readonly>
                    <input class="form-control rounded-3 border-dark border-2" id="juz" name="juz" type="hidden" value="{{ $juzNumber }}" readonly>

                    <!-- Nama Input -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="nama">Nama</label>
                        <input class="form-control rounded-3 border-dark border-2" id="nama" name="nama" type="text" value="{{ $studentName }}" readonly disabled>
                    </div>

                    <!-- Tanggal Input -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="tanggal">Tanggal</label>
                        <input class="form-control rounded-3 border-dark border-2" id="tanggal" name="tanggal" type="date" value="{{ $today }}" required>
                    </div>

                    <!-- Surah Input -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="surah">Surah</label>
                        <div class="input-group" id="select2container" required>
                            <select class="form-select rounded-3 border-dark border-2" id="surah" name="surah">
                                <option value="" disabled selected>Pilih Surat</option>

                                <!-- Options dynamically generated based on the Juz -->
                                @if ($juzNumber == 1)
                                    <option value="Al-Fatihah">Al-Fatihah</option>
                                    <option value="Al-Baqarah">Al-Baqarah</option>
                                    <!-- Add more Surahs as needed -->
                                @elseif ($juzNumber == 29)
                                    <option value="Al-Mulk">Al-Mulk</option>
                                    <option value="Al-Qalam">Al-Qalam</option>
                                    <option value="Al-Haqqah">Al-Haqqah</option>
                                    <option value="Al-Ma'arij">Al-Ma'arij</option>
                                    <option value="Nuh">Nuh</option>
                                    <option value="Al-Jinn">Al-Jinn</option>
                                    <option value="Al-Muzzammil">Al-Muzzammil</option>
                                    <option value="Al-Muddatstsir">Al-Muddatstsir</option>
                                    <option value="Al-Qiyamah">Al-Qiyamah</option>
                                    <option value="Al-Insan">Al-Insan</option>
                                    <option value="Al-Mursalat">Al-Mursalat</option>
                                    <!-- Add more Surahs as needed -->
                                @elseif ($juzNumber == 30)
                                    <option value="An-Naba'">An-Naba'</option>
                                    <option value="An-Nazi'at">An-Nazi'at</option>
                                    <option value="Abasa">Abasa</option>
                                    <option value="At-Takwir">At-Takwir</option>
                                    <option value="Al-Infitar">Al-Infitar</option>
                                    <option value="Al-Muthaffifin">Al-Muthaffifin</option>
                                    <option value="Al-Insyiqaq">Al-Insyiqaq</option>
                                    <option value="Al-Buruj">Al-Buruj</option>
                                    <option value="At-Tariq">At-Tariq</option>
                                    <option value="Al-A'la">Al-A'la</option>
                                    <option value="Al-Ghasyiyah">Al-Ghasyiyah</option>
                                    <option value="Al-Fajr">Al-Fajr</option>
                                    <option value="Al-Balad">Al-Balad</option>
                                    <option value="Asy-Syams">Asy-Syams</option>
                                    <option value="Al-Lail">Al-Lail</option>
                                    <option value="Ad-Duha">Ad-Duha</option>
                                    <option value="Al-Insyirah">Al-Insyirah</option>
                                    <option value="At-Tin">At-Tin</option>
                                    <option value="Al-'Alaq">Al-'Alaq</option>
                                    <option value="Al-Qadr">Al-Qadr</option>
                                    <option value="Al-Bayyinah">Al-Bayyinah</option>
                                    <option value="Az-Zalzalah">Az-Zalzalah</option>
                                    <option value="Al-'Adiyat">Al-'Adiyat</option>
                                    <option value="Al-Qari'ah">Al-Qari'ah</option>
                                    <option value="At-Takatsur">At-Takatsur</option>
                                    <option value="Al-'Asr">Al-'Asr</option>
                                    <option value="Al-Humazah">Al-Humazah</option>
                                    <option value="Al-Fil">Al-Fil</option>
                                    <option value="Quraisy">Quraisy</option>
                                    <option value="Al-Ma'un">Al-Ma'un</option>
                                    <option value="Al-Kautsar">Al-Kautsar</option>
                                    <option value="Al-Kafirun">Al-Kafirun</option>
                                    <option value="An-Nasr">An-Nasr</option>
                                    <option value="Al-Lahab">Al-Lahab</option>
                                    <option value="Al-Ikhlas">Al-Ikhlas</option>
                                    <option value="Al-Falaq">Al-Falaq</option>
                                    <option value="An-Nas">An-Nas</option>
                                    <!-- Add more Surahs as needed -->
                                @endif
                            </select>
                        </div>
                    </div>

                    <!-- Ayat Input -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="ayat">Ayat</label>
                        <input class="form-control rounded-3 border-dark border-2 mb-2" id="ayat_awal" name="ayat_awal" type="text" placeholder="Ayat Awal Bacaan Surah..." required>
                        <input class="form-control rounded-3 border-dark border-2" id="ayat_akhir" name="ayat_akhir" type="text" placeholder="Ayat Akhir Bacaan Surah..." required>
                    </div>

                    <!-- Reset and Submit Buttons -->
                    <div class="d-flex justify-content-center align-items-stretch pt-3 gap-3">
                        <a class="btn btn-secondary rounded-3 flex-fill d-flex justify-content-center align-items-center text-center px-3 py-2" href="{{ route('teacher.laporan-bacaan-juz-siswa.index', ['juzNumber' => $juzNumber, 'id' => $studentId]) }}">Batal</a>
                        <button class="btn btn-success rounded-3 flex-fill d-flex justify-content-center align-items-center text-center px-3 py-2" type="submit">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $(document).ready(function() {
            $('#surah').select2({
                placeholder: 'Pilih Surah',
                allowClear: true,
                width: '100%',
                // dropdownCssClass: "",
                selectionCssClass: "",
            });
        });
    </script>
@endpush

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