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

<!-- Modal -->
<div class="modal fade" id="pdfModal" tabindex="-1" aria-labelledby="pdfModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('reading-activity.convert-pdf', ['id' => $studentId]) }}" method="GET">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pdfModalLabel">Pilih Bulan dan Tahun</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="month" class="form-label">Bulan</label>
                    <select name="month" id="month" class="form-control" required>
                        @foreach (range(1, 12) as $m)
                            <option value="{{ $m }}">{{ \Carbon\Carbon::create()->month($m)->format('F') }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="year" class="form-label">Tahun</label>
                    <select name="year" id="year" class="form-control" required>
                        @for ($y = date('Y'); $y >= 2020; $y--)
                            <option value="{{ $y }}">{{ $y }}</option>
                        @endfor
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Download</button>
            </div>
        </div>
    </form>
  </div>
</div>