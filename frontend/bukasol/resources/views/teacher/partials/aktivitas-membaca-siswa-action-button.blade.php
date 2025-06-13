<div class="container-fluid w-100">
    <div class="d-flex justify-content-center w-100">
        <!-- Approve All Button -->
         @php
            $isDisabled = $teacherSign; // if teacherSign is true, disable the button
        @endphp

        <a class="btn btn-sm btn-warning py-2 me-2 text-white" href="{{ route('read-activity.teacher-sign-all', ['id' => $studentId]) }}"
        onclick="event.preventDefault(); document.getElementById('approve-form-{{ $studentId }}').submit();"
        style="{{ $isDisabled ? 'pointer-events: none; opacity: 0.5; cursor: not-allowed;' : '' }}">
            <i class="fa fa-check"></i>
        </a>

        <form id="approve-form-{{ $studentId }}" method="POST" action="{{ route('read-activity.teacher-sign-all', ['id' => $studentId]) }}" class="d-none">
            @csrf
            @method('PUT')
        </form>
        
        <!-- Detail Button to Trigger Detail Modal -->
        <a class="btn btn-sm btn-primary py-2 me-2" href="{{ route('teacher.aktivitas-membaca-siswa.index', ['id' => $studentId]) }}">
            <i class="fa fa-eye"></i>
        </a>

       <!-- Export Button -->
        <button type="button" class="btn btn-sm btn-success py-2 me-2" data-bs-toggle="modal" data-bs-target="#pdfModal">
            <i class="fa fa-file-export"></i>
        </button>
    </div>
</div>

@php
    $routes = route('reading-activity.convert-pdf', ['id' => $studentId]);
@endphp

@include('partials.filter-pdf')