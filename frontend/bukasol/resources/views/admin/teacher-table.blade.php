@extends('admin.admin-dashboard')

@push('styles')
    <!-- DataTables 2.1.8 -->
    <link href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <style>
        .disabled-link {
            pointer-events: none;
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        #teacherTable thead th {
            text-align: center;
            vertical-align: middle;
        }

        #teacherTable tbody td {
            text-align: left;
            vertical-align: middle;
        }

        #teacherTable tbody td:last-child {
            text-align: center;
        }

        td,
        th {
            white-space: nowrap;
        }

        th:last-child,
        td:last-child {
            width: 1%;
        }
    </style>
@endpush

@section('content_3')
    <div class="p-0 m-0">

        <div class="text-center p-0 m-0 pe-1">
            <div class="row align-items-center mb-4">
                <div class="col container position-relative">
                    <h2 class="text-center mb-0">Akun Guru</h2>
                </div>
            </div>
        </div>

        <div class="col d-flex justify-content-end align-items-end mt-3 mt-md-0">
            <a class="btn btn-outline-dark rounded-3 me-2" href="{{ route('teacher-account.convert-pdf') }}">
                <i class="fa-solid fa-file-contract me-1"></i>
                <span class="d-none d-md-inline">Export Akun Guru</span>
            </a>
            <button class="btn btn-outline-dark rounded-3" data-bs-toggle="modal" data-bs-target="#addTeacherModal">
                <i class="fa-solid fa-plus me-1"></i>
                <span class="d-none d-md-inline">Tambah Akun Guru</span>
            </button>
        </div>

        <div class="text-left table-responsive">
            <table class="table table-bordered table-striped table-sm" id="teacherTable">
            </table>
        </div>

        @include('admin.partials.modal-add-teacher')
        @include('admin.partials.modal-delete-teacher')
    </div>
@endsection

@push('scripts')
    <!-- DataTables 2.1.8 -->
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
            $('#teacherTable').DataTable({
                processing: true,
                serverSide: true,
                paging: true,
                ordering: false,
                ajax: {
                    url: '{{ route('teacher.fetchData') }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                columns: [{
                        data: 'nip',
                        name: 'nip',
                        title: 'NIP',
                        className: 'text-start'
                    },
                    {
                        data: 'name',
                        name: 'name',
                        title: 'Nama',
                        className: 'text-start'
                    },
                    {
                        data: 'className',
                        name: 'className',
                        title: 'Kelas',
                        className: 'text-start'
                    },
                    {
                        data: 'username',
                        name: 'username',
                        title: 'Username',
                        className: 'text-start'
                    },
                    {
                        data: 'password',
                        name: 'password',
                        title: 'Password',
                        className: 'text-start'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        title: 'Action',
                        orderable: false,
                        searchable: false
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
