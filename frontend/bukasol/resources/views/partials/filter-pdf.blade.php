<!-- Modal -->
<div class="modal fade" id="pdfModal" tabindex="-1" aria-labelledby="pdfModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ $routes }}" method="GET">
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
                            <option value="{{ $m }}">{{ \Carbon\Carbon::create()->locale('id')->month($m)->translatedFormat('F') }}</option>
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