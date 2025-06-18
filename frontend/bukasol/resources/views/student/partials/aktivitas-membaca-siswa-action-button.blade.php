<div class="container-fluid w-100">
    <div class="d-flex justify-content-center w-100">
        <!-- Edit Button to Trigger Edit Page -->
        <a class="btn btn-sm btn-warning py-2 me-2" href="{{ route('student.aktivitas-membaca-siswa-edit.index', ['id' => $noteId]) }}">
            <i class="fa-solid fa-pen-to-square"></i>
        </a>
        
        <button class="btn btn-sm btn-danger py-2 me-2" onclick="showDeleteConfirmationModal({{ $noteId }})">
            <i class="fa fa-trash"></i>
        </button>
    </div>
</div>
