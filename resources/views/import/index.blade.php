@extends('layouts.app')

@section('title', 'Import Data - PLN Icon Plus Kantor Management')
@section('page-title', 'Import Data')

@section('page-actions')
    <a href="{{ route('import.download-template', ['model' => 'kantor']) }}" class="btn btn-outline-primary">
        <i class="fas fa-download"></i> Download Template
    </a>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-upload"></i>
                    Import Data PLN Icon Plus
                </h5>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Import Form -->
                <form id="importForm" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="model" class="form-label">Pilih Model Data</label>
                                <select class="form-select" id="model" name="model" required>
                                    <option value="">-- Pilih Model --</option>
                                    <option value="kantor">Kantor</option>
                                    <option value="gedung">Gedung</option>
                                    <option value="lantai">Lantai</option>
                                    <option value="ruang">Ruang</option>
                                    <option value="bidang">Bidang</option>
                                    <option value="sub_bidang">Sub Bidang</option>
                                    <option value="okupansi">Okupansi</option>
                                    <option value="kontrak">Kontrak</option>
                                    <option value="realisasi">Realisasi</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="file" class="form-label">Pilih File</label>
                                <input type="file" class="form-control" id="file" name="file" accept=".csv,.xlsx,.xls" required>
                                <div class="form-text">Format yang didukung: CSV, Excel (.xlsx, .xls). Maksimal 10MB.</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-outline-info" id="previewBtn" disabled>
                                    <i class="fas fa-eye"></i> Preview Data
                                </button>
                                <button type="button" class="btn btn-outline-success" id="downloadTemplateBtn" disabled>
                                    <i class="fas fa-download"></i> Download Template
                                </button>
                                <button type="submit" class="btn btn-primary" id="importBtn" disabled>
                                    <i class="fas fa-upload"></i> Import Data
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Preview Section -->
                <div id="previewSection" class="mt-4" style="display: none;">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title mb-0">
                                <i class="fas fa-eye"></i>
                                Preview Data
                            </h6>
                        </div>
                        <div class="card-body">
                            <div id="previewContent"></div>
                        </div>
                    </div>
                </div>

                <!-- Progress Section -->
                <div id="progressSection" class="mt-4" style="display: none;">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title mb-0">
                                <i class="fas fa-spinner fa-spin"></i>
                                Import Progress
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="progress mb-3">
                                <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                            </div>
                            <div id="progressText">Preparing import...</div>
                        </div>
                    </div>
                </div>

                <!-- Results Section -->
                <div id="resultsSection" class="mt-4" style="display: none;">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title mb-0">
                                <i class="fas fa-check-circle"></i>
                                Import Results
                            </h6>
                        </div>
                        <div class="card-body">
                            <div id="resultsContent"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modelSelect = document.getElementById('model');
    const fileInput = document.getElementById('file');
    const previewBtn = document.getElementById('previewBtn');
    const downloadTemplateBtn = document.getElementById('downloadTemplateBtn');
    const importBtn = document.getElementById('importBtn');
    const importForm = document.getElementById('importForm');
    const previewSection = document.getElementById('previewSection');
    const progressSection = document.getElementById('progressSection');
    const resultsSection = document.getElementById('resultsSection');

    // Enable/disable button berdasarkan state form
    function updateButtonStates() {
        const hasModel = modelSelect.value !== '';
        const hasFile = fileInput.files.length > 0;
        
        previewBtn.disabled = !hasModel || !hasFile;
        downloadTemplateBtn.disabled = !hasModel;
        importBtn.disabled = !hasModel || !hasFile;
    }

    modelSelect.addEventListener('change', updateButtonStates);
    fileInput.addEventListener('change', updateButtonStates);

    // Download template
    downloadTemplateBtn.addEventListener('click', function() {
        const model = modelSelect.value;
        if (model) {
            window.location.href = `{{ route('import.download-template') }}?model=${model}`;
        }
    });

    // Preview data
    previewBtn.addEventListener('click', function() {
        const formData = new FormData(importForm);
        formData.append('preview', 'true');

        showProgress('Loading preview...');

        fetch('{{ route("import.process") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            hideProgress();
            
            if (data.success) {
                showPreview(data.data, data.total_rows);
            } else {
                showError(data.message);
            }
        })
        .catch(error => {
            hideProgress();
            showError('Terjadi kesalahan: ' + error.message);
        });
    });

    // Import data
    importForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(importForm);
        
        showProgress('Importing data...');

        fetch('{{ route("import.process") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            hideProgress();
            
            if (data.success) {
                showResults(data.imported, data.failed, data.errors);
            } else {
                showError(data.message);
            }
        })
        .catch(error => {
            hideProgress();
            showError('Terjadi kesalahan: ' + error.message);
        });
    });

    function showProgress(message) {
        progressSection.style.display = 'block';
        document.getElementById('progressText').textContent = message;
        document.querySelector('.progress-bar').style.width = '50%';
    }

    function hideProgress() {
        progressSection.style.display = 'none';
        document.querySelector('.progress-bar').style.width = '0%';
    }

    function showPreview(data, totalRows) {
        previewSection.style.display = 'block';
        
        let html = `<p><strong>Total rows:</strong> ${totalRows}</p>`;
        html += '<p><strong>Preview (first 5 rows):</strong></p>';
        html += '<div class="table-responsive">';
        html += '<table class="table table-sm table-striped">';
        
        if (data.length > 0) {
            // Headers
            html += '<thead><tr>';
            Object.keys(data[0]).forEach(key => {
                html += `<th>${key}</th>`;
            });
            html += '</tr></thead>';
            
            // Data
            html += '<tbody>';
            data.forEach(row => {
                html += '<tr>';
                Object.values(row).forEach(value => {
                    html += `<td>${value}</td>`;
                });
                html += '</tr>';
            });
            html += '</tbody>';
        }
        
        html += '</table></div>';
        
        document.getElementById('previewContent').innerHTML = html;
    }

    function showResults(imported, failed, errors) {
        resultsSection.style.display = 'block';
        
        let html = '<div class="row">';
        html += '<div class="col-md-4"><div class="alert alert-success"><strong>Imported:</strong> ' + imported + '</div></div>';
        html += '<div class="col-md-4"><div class="alert alert-danger"><strong>Failed:</strong> ' + failed + '</div></div>';
        html += '<div class="col-md-4"><div class="alert alert-info"><strong>Total:</strong> ' + (imported + failed) + '</div></div>';
        html += '</div>';
        
        if (Object.keys(errors).length > 0) {
            html += '<h6>Errors:</h6>';
            html += '<div class="table-responsive">';
            html += '<table class="table table-sm table-striped">';
            html += '<thead><tr><th>Row</th><th>Error</th></tr></thead>';
            html += '<tbody>';
            
            Object.entries(errors).forEach(([row, errorList]) => {
                errorList.forEach(error => {
                    html += `<tr><td>${row}</td><td>${error}</td></tr>`;
                });
            });
            
            html += '</tbody></table></div>';
        }
        
        document.getElementById('resultsContent').innerHTML = html;
    }

    function showError(message) {
        alert('Error: ' + message);
    }
});
</script>
@endsection

