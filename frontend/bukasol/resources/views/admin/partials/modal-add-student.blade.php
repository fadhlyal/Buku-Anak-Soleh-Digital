<!-- Modal for Add Student Method -->
<div class="modal fade" id="addStudentModal" aria-labelledby="addStudentModalLabel" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addStudentModalLabel">Pilih Metode Menambahkan Akun</h5>
                <button class="btn-close" data-bs-dismiss="modal" type="button" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-center align-items-stretch pt-3 gap-3">
                    <a class="btn btn-outline-primary flex-fill d-flex justify-content-center align-items-center text-center px-3 py-2 disabled-link" onclick="document.getElementById('excelFileInput').click();">
                        <span class="d-none d-md-inline">Import Excel</span>
                    </a>
                    <input type="file" id="excelFileInput" name="file" accept=".xlsx, .xls" style="display: none;" onchange="submitFile()">

                    <a class="btn btn-outline-success flex-fill d-flex justify-content-center align-items-center text-center px-3 py-2" type="button" href="{{ route('admin.student-add.index') }}">Tambah Manual</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function submitFile() {
        const input = document.getElementById('excelFileInput');
        if (input.files.length > 0) {
            const importUrl = `{{ route('student-account.import-excel') }}`;
            const formData = new FormData();
            formData.append('file', input.files[0]);

            fetch(importUrl, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
            })
            .then(response => response.json())
            .then(data => {
                const parentSignConfirmationModal = bootstrap.Modal.getInstance(document.getElementById('addStudentModal'));
                parentSignConfirmationModal.hide();

                if (data.success) {
                    // Show success message
                    window.showAlert(data.success, true, '#studentTable');
                } else if (data.error) {
                    // Show error message
                    window.showAlert(data.error, false, '#studentTable');
                }
            })
            .catch(error => {
                const parentSignConfirmationModal = bootstrap.Modal.getInstance(document.getElementById('addStudentModal'));
                parentSignConfirmationModal.hide();

                window.showAlert('Error uploading file:', error, false);
            });
        } else {
            window.showAlert('Please select an Excel file.', false);
        }
    }
</script>
