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
            <div class="row align-items-center mb-4">
                <div class="col container position-relative">
                    <h2 class="text-center mb-0">Lembar Nilai Uji Gerakan</h2>
                    <h2 class="text-center mb-0">Siswa {{ $studentName }}</h2>
                </div>
            </div>
        </div>
        <div class="col d-flex justify-content-end align-items-end mt-3 mt-md-0">
            <a class="btn btn-outline-dark rounded-3" href="{{ route('teacher.nilai-uji-gerakan-siswa-add.index', ['id' => $studentId]) }}">
                <i class="fa-solid fa-plus me-1"></i>
                <span class="d-none d-md-inline">Tambah Nilai Uji Gerakan</span>
            </a>
        </div>
        <div class="text-center table-responsive">
            <table class="table table-bordered table-striped table-sm" id="nilaiUjigerakanSiswaDetailTable">
            </table>
        </div>

        @include('teacher.partials.nilai-uji-gerakan-siswa-detail-delete')
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
            $('#nilaiUjigerakanSiswaDetailTable').DataTable({
                processing: true,
                serverSide: true,
                paging: true,
                ordering: false,
                ajax: {
                    url: '{{ route('nilai_uji_gerakan_detail.fetchData', ['id' => $studentId]) }}',
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
                        data: 'motionCategory',
                        name: 'motionCategory',
                        title: 'Jenis Gerakan'
                    },
                    {
                        data: 'gradeSemester1',
                        name: 'gradeSemester1',
                        title: 'Semester 1'
                    },
                    {
                        data: 'gradeSemester2',
                        name: 'gradeSemester2',
                        title: 'Semester 2'
                    },
                    {
                        data: 'parentSign',
                        name: 'parentSign',
                        title: 'Paraf Orang Tua',
                        render: function(data, type, row) {
                            if (type === 'display') {
                                return data ?
                                    '<span class="text-success">Sudah</span>' :
                                    '<span class="text-danger">Belum</span>';
                            }
                            return data; // Return raw data for non-display types (e.g., export)
                        }
                    },
                    {
                        data: 'teacherSign',
                        name: 'teacherSign',
                        title: 'Paraf Guru',
                        render: function(data, type, row) {
                            return `
                                <div class="form-check form-switch">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        ${data ? 'checked' : ''}
                                        onclick="updateTeacherSign(${row.id}, this.checked)">
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

@push('scripts')
    <script>
        function updateTeacherSign(gradeId) {
            const url = `{{ route('prayer-grade.teacher-sign', ':id') }}`.replace(':id', gradeId);

            fetch(url, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                window.showAlert(data.success, true, '#nilaiUjigerakanSiswaDetailTable');
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating teacher sign.');
            });
        }
    </script>
@endpush
