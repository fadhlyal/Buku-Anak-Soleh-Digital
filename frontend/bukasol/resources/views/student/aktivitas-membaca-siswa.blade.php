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

        <div class="text-center p-0 m-0 pe-1">
            <div class="row align-items-center mb-4">
                <div class="col container position-relative">
                    <h2 class="text-center mb-0">Lembar Aktivitas Membaca</h2>
                </div>
            </div>
        </div>

        <div class="col d-flex justify-content-end align-items-end mt-3 mt-md-0">
            <!-- Export Button -->
            <button type="button" class="btn btn-outline-dark rounded-3 me-2" data-bs-toggle="modal" data-bs-target="#pdfModal">
                <i class="fa-solid fa-file-contract me-1"></i>
                <span class="d-none d-md-inline">Export Aktivitas Membaca</span>
            </button>

            <a class="btn btn-outline-dark rounded-3" href="{{ route('student.aktivitas-membaca-siswa-add.index') }}">
                <i class="fa-solid fa-plus me-1"></i>
                <span class="d-none d-md-inline">Tambah Aktivitas Membaca</span>
            </a>
        </div>

        <div class="text-center table-responsive">
            <table class="table table-bordered table-striped table-sm" id="aktivitasMembacaSiswaTable">
            </table>
        </div>
        @php
            $routes = route('reading-activity.convert-pdf', [ 'id' => $studentId ]);
        @endphp

        @include('partials.filter-pdf')
        @include('student.partials.aktivitas-membaca-siswa-delete')
        @include('student.partials.modal-kode-unik-paraf-orang-tua-reading-activity')
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.min.js"></script>

    <script>
        window.addEventListener("pageshow", function(event) {
            if (event.persisted || performance.getEntriesByType("navigation")[0].type === "back_forward") {
                // Reload the page when navigating forward or back in history
                location.reload();
            }
        });

        $(document).ready(function() {
            $('#aktivitasMembacaSiswaTable').DataTable({
                processing: true,
                serverSide: true,
                paging: true,
                ordering: false,
                ajax: {
                    url: '{{ route('siswa.aktivitas-membaca.fetchData') }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                columns: [{
                        data: 'timeStamp',
                        name: 'timeStamp',
                        title: 'Tanggal',
                        render: function(data, type, row) {
                            if (type === 'display' && data) {
                                const date = new Date(data); // Konversi string ke objek Date
                                const day = String(date.getDate()).padStart(2, '0');
                                const month = String(date.getMonth() + 1).padStart(2, '0'); // Bulan dimulai dari 0
                                const year = date.getFullYear();
                                return `${day}-${month}-${year}`; // Format dd-mm-yyyy
                            }
                            return data; // Untuk mode selain display, kembalikan data asli
                        }
                    },
                    {
                        data: 'bookTitle',
                        name: 'bookTitle',
                        title: 'Judul Buku',
                        render: function (data, type, row) {
                            const maxLength = 75;
                            if (data && data.length > maxLength) {
                                return data.substring(0, maxLength) + '...';
                            }
                            return data;
                        }
                    },
                    {
                        data: 'page',
                        name: 'page',
                        title: 'Halaman'
                    },
                    {
                        data: 'teacherSign',
                        name: 'teacherSign',
                        title: 'Paraf Guru',
                        render: function(data, type, row) {
                            if (type === 'display') {
                                return data ?
                                    '<span class="text-success">Sudah</span>':
                                    '<span class="text-danger">Belum</span>';
                            }
                            return data;
                        }
                    },
                    {
                        data: 'parentSign',
                        name: 'parentSign',
                        title: 'Paraf Orang Tua',
                        render: function(data, type, row) {
                            return `
                                <div class="form-check form-switch">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        ${data ? 'checked' : ''}
                                        onclick="showParentCodeModal(${row.id})">
                                </div>`;
                        }
                    },
                    {
                        data: 'action',
                        name: 'action',
                        title: 'Actions'
                    }
                ],
                language: {
                    paginate: {
                        first: '<i class="bi bi-chevron-double-left container-fluid"></i>',
                        previous: '<i class="bi bi-chevron-left container-fluid"></i>',
                        next: '<i class="bi bi-chevron-right container-fluid"></i>',
                        last: '<i class="bi bi-chevron-double-right container-fluid"></i>'
                    }
                }
            });
        });
    </script>
@endpush
