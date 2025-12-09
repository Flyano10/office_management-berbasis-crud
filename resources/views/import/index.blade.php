@extends('layouts.app')

@section('title', 'Data Management Center - PLN Icon Plus Kantor Management')
@section('page-title', 'Data Management Center')
@section('page-subtitle', 'Kelola data kantor PLN Icon Plus')

@section('content')
<div class="container-fluid">
    <!-- Tab Navigation -->
    <div class="management-tabs-wrapper">
        <ul class="nav nav-tabs management-tabs" id="managementTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="import-tab" data-bs-toggle="tab" data-bs-target="#import" type="button" role="tab">
                    <i class="fas fa-file-import"></i>
                    <span>Import Data</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="export-tab" data-bs-toggle="tab" data-bs-target="#export" type="button" role="tab">
                    <i class="fas fa-file-export"></i>
                    <span>Export Data</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="backup-tab" data-bs-toggle="tab" data-bs-target="#backup" type="button" role="tab">
                    <i class="fas fa-database"></i>
                    <span>Backup & Restore</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="reports-tab" data-bs-toggle="tab" data-bs-target="#reports" type="button" role="tab">
                    <i class="fas fa-file-pdf"></i>
                    <span>Generate Reports</span>
                </button>
            </li>
        </ul>
                    </div>

    <!-- Tab Content -->
    <div class="tab-content management-tab-content" id="managementTabsContent">
        <!-- IMPORT TAB -->
        <div class="tab-pane fade show active" id="import" role="tabpanel">
            <div class="management-card">
                <div class="management-card-header">
                    <h5 class="management-card-title">
                        <i class="fas fa-file-import"></i>
                        Import Data Massal
                    </h5>
                    <p class="management-card-subtitle">Upload file CSV atau Excel untuk import data dalam jumlah besar</p>
                    </div>
                <div class="management-card-body">
                    <!-- Alert Messages -->
                    <div id="importAlerts"></div>

                <!-- Import Form -->
                <form id="importForm" enctype="multipart/form-data">
                    @csrf
                        <div class="row g-3">
                        <div class="col-md-6">
                                <label for="import_model" class="form-label modern-label">Pilih Model Data <span class="text-danger">*</span></label>
                                <select class="form-select modern-select" id="import_model" name="model" required>
                                    <option value="">Pilih Model</option>
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
                                <div class="form-text">Pilih jenis data yang akan diimport</div>
                        </div>
                        <div class="col-md-6">
                                <label for="import_file" class="form-label modern-label">Pilih File <span class="text-danger">*</span></label>
                                <input type="file" class="form-control modern-input" id="import_file" name="file" accept=".csv,.xlsx,.xls" required>
                                <div class="form-text">Format: CSV, Excel (.xlsx, .xls). Maksimal 10MB</div>
                        </div>
                    </div>

                        <div class="row mt-4">
                        <div class="col-12">
                                <div class="action-buttons">
                                    <button type="button" class="btn btn-modern btn-info" id="downloadTemplateBtn" disabled>
                                        <i class="fas fa-download"></i> Download Template
                                    </button>
                                    <button type="button" class="btn btn-modern btn-secondary" id="previewBtn" disabled>
                                    <i class="fas fa-eye"></i> Preview Data
                                </button>
                                    <button type="submit" class="btn btn-modern btn-primary" id="importBtn" disabled>
                                    <i class="fas fa-upload"></i> Import Data
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Preview Section -->
                    <div id="previewSection" style="display: none;">
                        <div class="preview-container">
                            <div class="preview-header">
                                <h6><i class="fas fa-eye"></i> Preview Data</h6>
                                <button type="button" class="btn-close-preview" onclick="closePreview()">
                                    <i class="fas fa-times"></i>
                                </button>
                        </div>
                            <div class="preview-body" id="previewContent"></div>
                    </div>
                </div>

                <!-- Progress Section -->
                    <div id="progressSection" style="display: none;">
                        <div class="progress-container">
                            <div class="progress-header">
                                <i class="fas fa-spinner fa-spin"></i> Import Progress
                        </div>
                            <div class="progress-body">
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%"></div>
                            </div>
                                <div id="progressText" class="progress-text">Preparing import...</div>
                        </div>
                    </div>
                </div>

                <!-- Results Section -->
                    <div id="resultsSection" style="display: none;">
                        <div class="results-container">
                            <div class="results-header">
                                <i class="fas fa-check-circle"></i> Import Results
                            </div>
                            <div class="results-body" id="resultsContent"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- EXPORT TAB -->
        <div class="tab-pane fade" id="export" role="tabpanel">
            <div class="management-card">
                <div class="management-card-header">
                    <h5 class="management-card-title">
                        <i class="fas fa-file-export"></i>
                        Export Data
                    </h5>
                    <p class="management-card-subtitle">Download data dalam format CSV atau Excel untuk backup atau analisis</p>
                </div>
                <div class="management-card-body">
                    <form id="exportForm">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="export_model" class="form-label modern-label">Pilih Model Data <span class="text-danger">*</span></label>
                                <select class="form-select modern-select" id="export_model" name="model" required>
                                    <option value="">Pilih Model</option>
                                    <option value="kantor">Kantor</option>
                                    <option value="gedung">Gedung</option>
                                    <option value="lantai">Lantai</option>
                                    <option value="ruang">Ruang</option>
                                    <option value="bidang">Bidang</option>
                                    <option value="sub_bidang">Sub Bidang</option>
                                    <option value="okupansi">Okupansi</option>
                                    <option value="kontrak">Kontrak</option>
                                    <option value="realisasi">Realisasi</option>
                                    <option value="inventaris">Inventaris</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="export_format" class="form-label modern-label">Format Export <span class="text-danger">*</span></label>
                                <select class="form-select modern-select" id="export_format" name="format" required>
                                    <option value="">Pilih Format</option>
                                    <option value="csv">CSV</option>
                                    <option value="excel">Excel (.xlsx)</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="action-buttons">
                                    <button type="submit" class="btn btn-modern btn-success" id="exportBtn" disabled>
                                        <i class="fas fa-download"></i> Export Data
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="export-info-box">
                        <i class="fas fa-info-circle"></i>
                        <div>
                            <strong>Informasi Export:</strong>
                            <ul>
                                <li>Export akan mengunduh semua data dari model yang dipilih</li>
                                <li>File akan langsung terdownload ke komputer Anda</li>
                                <li>Data yang di-export dapat digunakan untuk backup atau analisis</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- BACKUP & RESTORE TAB -->
        <div class="tab-pane fade" id="backup" role="tabpanel">
            <div class="row">
                <div class="col-lg-6">
                    <div class="management-card">
                        <div class="management-card-header">
                            <h5 class="management-card-title">
                                <i class="fas fa-save"></i>
                                Create Backup
                            </h5>
                            <p class="management-card-subtitle">Backup seluruh database aplikasi</p>
                        </div>
                        <div class="management-card-body">
                            <div class="backup-info">
                                <i class="fas fa-shield-alt"></i>
                                <div>
                                    <h6>Database Backup</h6>
                                    <p>Backup akan menyimpan semua data termasuk: Kantor, Gedung, Lantai, Ruang, Bidang, Sub Bidang, Okupansi, Kontrak, Realisasi, dan Inventaris.</p>
                                </div>
                            </div>

                            <button type="button" class="btn btn-modern btn-primary w-100" id="createBackupBtn">
                                <i class="fas fa-database"></i> Create Database Backup
                            </button>

                            <div id="backupProgress" style="display: none;" class="mt-3">
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" style="width: 100%"></div>
                                </div>
                                <p class="text-center mt-2"><i class="fas fa-spinner fa-spin"></i> Creating backup...</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="management-card">
                        <div class="management-card-header">
                            <h5 class="management-card-title">
                                <i class="fas fa-undo"></i>
                                Restore Backup
                            </h5>
                            <p class="management-card-subtitle">Restore database dari file backup</p>
                        </div>
                        <div class="management-card-body">
                            <form id="restoreForm" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label for="backup_file" class="form-label modern-label">Pilih File Backup <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control modern-input" id="backup_file" name="backup_file" accept=".sql" required>
                                    <div class="form-text">Format: SQL file (.sql)</div>
                                </div>

                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <strong>Peringatan:</strong> Restore akan menimpa data yang ada saat ini. Pastikan Anda sudah membuat backup terlebih dahulu!
                                </div>

                                <button type="submit" class="btn btn-modern btn-danger w-100" id="restoreBtn" disabled>
                                    <i class="fas fa-undo"></i> Restore Database
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Backup History -->
            <div class="management-card mt-4">
                <div class="management-card-header">
                    <h5 class="management-card-title">
                        <i class="fas fa-history"></i>
                        Backup History
                    </h5>
                    <p class="management-card-subtitle">Riwayat backup yang telah dibuat</p>
                </div>
                <div class="management-card-body">
                    <div class="table-responsive">
                        <table class="table modern-table">
                            <thead>
                                <tr>
                                    <th>Timestamp</th>
                                    <th>File Name</th>
                                    <th>Size</th>
                                    <th>Created By</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="backupHistoryTable">
                                <tr>
                                    <td colspan="5" class="text-center text-muted">
                                        <i class="fas fa-inbox"></i> No backup history available
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- REPORTS TAB -->
        <div class="tab-pane fade" id="reports" role="tabpanel">
            <div class="management-card">
                <div class="management-card-header">
                    <h5 class="management-card-title">
                        <i class="fas fa-file-pdf"></i>
                        Generate Reports
                    </h5>
                    <p class="management-card-subtitle">Generate laporan dalam format PDF atau Excel</p>
                </div>
                <div class="management-card-body">
                    <div class="row g-4">
                        <!-- Report Card 1 -->
                        <div class="col-md-6 col-lg-4">
                            <div class="report-card">
                                <div class="report-icon">
                                    <i class="fas fa-building"></i>
                                </div>
                                <h6>Laporan Inventaris</h6>
                                <p>Laporan lengkap inventaris per kantor</p>
                                <div class="report-actions">
                                    <button class="btn btn-sm btn-modern btn-danger" onclick="generateReport('inventaris', 'pdf')">
                                        <i class="fas fa-file-pdf"></i> PDF
                                    </button>
                                    <button class="btn btn-sm btn-modern btn-success" onclick="generateReport('inventaris', 'excel')">
                                        <i class="fas fa-file-excel"></i> Excel
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Report Card 2 -->
                        <div class="col-md-6 col-lg-4">
                            <div class="report-card">
                                <div class="report-icon">
                                    <i class="fas fa-file-contract"></i>
                                </div>
                                <h6>Laporan Kontrak</h6>
                                <p>Laporan kontrak yang akan expired</p>
                                <div class="report-actions">
                                    <button class="btn btn-sm btn-modern btn-danger" onclick="generateReport('kontrak', 'pdf')">
                                        <i class="fas fa-file-pdf"></i> PDF
                                    </button>
                                    <button class="btn btn-sm btn-modern btn-success" onclick="generateReport('kontrak', 'excel')">
                                        <i class="fas fa-file-excel"></i> Excel
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Report Card 3 -->
                        <div class="col-md-6 col-lg-4">
                            <div class="report-card">
                                <div class="report-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <h6>Laporan Okupansi</h6>
                                <p>Laporan okupansi per bidang</p>
                                <div class="report-actions">
                                    <button class="btn btn-sm btn-modern btn-danger" onclick="generateReport('okupansi', 'pdf')">
                                        <i class="fas fa-file-pdf"></i> PDF
                                    </button>
                                    <button class="btn btn-sm btn-modern btn-success" onclick="generateReport('okupansi', 'excel')">
                                        <i class="fas fa-file-excel"></i> Excel
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Report Card 4 -->
                        <div class="col-md-6 col-lg-4">
                            <div class="report-card">
                                <div class="report-icon">
                                    <i class="fas fa-chart-bar"></i>
                                </div>
                                <h6>Summary Report</h6>
                                <p>Ringkasan data keseluruhan</p>
                                <div class="report-actions">
                                    <button class="btn btn-sm btn-modern btn-danger" onclick="generateReport('summary', 'pdf')">
                                        <i class="fas fa-file-pdf"></i> PDF
                                    </button>
                                    <button class="btn btn-sm btn-modern btn-success" onclick="generateReport('summary', 'excel')">
                                        <i class="fas fa-file-excel"></i> Excel
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Report Card 5 -->
                        <div class="col-md-6 col-lg-4">
                            <div class="report-card">
                                <div class="report-icon">
                                    <i class="fas fa-door-open"></i>
                                </div>
                                <h6>Laporan Ruang</h6>
                                <p>Laporan status dan kapasitas ruang</p>
                                <div class="report-actions">
                                    <button class="btn btn-sm btn-modern btn-danger" onclick="generateReport('ruang', 'pdf')">
                                        <i class="fas fa-file-pdf"></i> PDF
                                    </button>
                                    <button class="btn btn-sm btn-modern btn-success" onclick="generateReport('ruang', 'excel')">
                                        <i class="fas fa-file-excel"></i> Excel
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Report Card 6 -->
                        <div class="col-md-6 col-lg-4">
                            <div class="report-card">
                                <div class="report-icon">
                                    <i class="fas fa-money-bill-wave"></i>
                                </div>
                                <h6>Laporan Realisasi</h6>
                                <p>Laporan realisasi kompensasi</p>
                                <div class="report-actions">
                                    <button class="btn btn-sm btn-modern btn-danger" onclick="generateReport('realisasi', 'pdf')">
                                        <i class="fas fa-file-pdf"></i> PDF
                                    </button>
                                    <button class="btn btn-sm btn-modern btn-success" onclick="generateReport('realisasi', 'excel')">
                                        <i class="fas fa-file-excel"></i> Excel
                                    </button>
                                </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    :root {
        --pln-blue: #21618C;
        --pln-blue-dark: #1A4D73;
        --pln-blue-light: #2E86AB;
        --pln-blue-lighter: #E8F4F8;
        --pln-blue-bg: #F5FAFC;
        --text-dark: #1A1A1A;
        --text-gray: #6C757D;
    }

    /* Tabs */
    .management-tabs-wrapper {
        background: white;
        border-radius: 12px 12px 0 0;
        box-shadow: 0 2px 8px rgba(33, 97, 140, 0.1);
        border: 1px solid rgba(33, 97, 140, 0.15);
        border-bottom: none;
        padding: 0;
        margin-bottom: 0;
    }

    .management-tabs {
        border-bottom: 2px solid var(--pln-blue);
        padding: 0.5rem 1.5rem 0;
    }

    .management-tabs .nav-link {
        border: none;
        color: var(--text-gray);
        font-weight: 600;
        padding: 1rem 1.5rem;
        border-radius: 8px 8px 0 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
        background: transparent;
    }

    .management-tabs .nav-link i {
        font-size: 1.125rem;
    }

    .management-tabs .nav-link:hover {
        color: var(--pln-blue);
        background: var(--pln-blue-bg);
    }

    .management-tabs .nav-link.active {
        color: var(--pln-blue);
        background: var(--pln-blue-lighter);
        border-bottom: 3px solid var(--pln-blue);
    }

    .management-tab-content {
        background: white;
        border-radius: 0 0 12px 12px;
        box-shadow: 0 2px 8px rgba(33, 97, 140, 0.1);
        border: 1px solid rgba(33, 97, 140, 0.15);
        border-top: none;
    }

    .tab-pane {
        padding: 2rem;
    }

    /* Management Card */
    .management-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(33, 97, 140, 0.08);
        border: 1px solid rgba(33, 97, 140, 0.1);
        margin-bottom: 1.5rem;
    }

    .management-card-header {
        padding: 1.5rem;
        border-bottom: 2px solid var(--pln-blue-lighter);
    }

    .management-card-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--pln-blue);
        margin: 0 0 0.5rem 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .management-card-subtitle {
        color: var(--text-gray);
        font-size: 0.9375rem;
        margin: 0;
    }

    .management-card-body {
        padding: 2rem;
    }

    /* Modern Form Elements */
    .modern-label {
        font-weight: 600;
        color: var(--text-dark);
        font-size: 0.9375rem;
        margin-bottom: 0.5rem;
    }

    .modern-select,
    .modern-input {
        border-radius: 10px;
        border: 2px solid rgba(33, 97, 140, 0.2);
        padding: 0.75rem 1rem;
        font-size: 0.9375rem;
        transition: all 0.2s ease;
    }

    .modern-select:focus,
    .modern-input:focus {
        border-color: var(--pln-blue);
        box-shadow: 0 0 0 3px rgba(33, 97, 140, 0.1);
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .btn-modern {
        border-radius: 10px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        transition: all 0.2s ease;
        border: 1px solid transparent;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9375rem;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }

    .btn-modern:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(33, 97, 140, 0.2);
    }

    .btn-modern.btn-primary {
        background: var(--pln-blue);
        color: white;
        border-color: var(--pln-blue);
    }

    .btn-modern.btn-primary:hover:not(:disabled) {
        background: var(--pln-blue-dark);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(33, 97, 140, 0.25);
    }

    .btn-modern.btn-success {
        background: #28a745;
        color: white;
    }

    .btn-modern.btn-success:hover:not(:disabled) {
        background: #218838;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(40, 167, 69, 0.25);
    }

    .btn-modern.btn-info {
        background: var(--pln-blue-light);
        color: white;
    }

    .btn-modern.btn-info:hover:not(:disabled) {
        background: var(--pln-blue);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(46, 134, 171, 0.25);
    }

    .btn-modern.btn-secondary {
        background: #6c757d;
        color: white;
    }

    .btn-modern.btn-secondary:hover:not(:disabled) {
        background: #5a6268;
        transform: translateY(-1px);
    }

    .btn-modern.btn-danger {
        background: #dc3545;
        color: white;
    }

    .btn-modern.btn-danger:hover:not(:disabled) {
        background: #c82333;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(220, 53, 69, 0.25);
    }

    .btn-modern:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    /* Preview Container */
    .preview-container,
    .progress-container,
    .results-container {
        background: var(--pln-blue-bg);
        border-radius: 10px;
        border: 1px solid rgba(33, 97, 140, 0.15);
        margin-top: 1.5rem;
        overflow: hidden;
    }

    .preview-header,
    .progress-header,
    .results-header {
        background: var(--pln-blue-lighter);
        padding: 1rem 1.5rem;
        border-bottom: 1px solid rgba(33, 97, 140, 0.2);
        font-weight: 700;
        color: var(--pln-blue);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .preview-body,
    .progress-body,
    .results-body {
        padding: 1.5rem;
    }

    .btn-close-preview {
        background: none;
        border: none;
        color: var(--text-gray);
        cursor: pointer;
        padding: 0.25rem 0.5rem;
        border-radius: 5px;
        transition: all 0.2s ease;
    }

    .btn-close-preview:hover {
        background: white;
        color: var(--pln-blue);
    }

    .progress-text {
        text-align: center;
        color: var(--text-gray);
        margin-top: 1rem;
        font-weight: 600;
    }

    /* Export Info Box */
    .export-info-box {
        background: var(--pln-blue-bg);
        border-left: 4px solid var(--pln-blue);
        padding: 1.25rem;
        border-radius: 8px;
        margin-top: 2rem;
        display: flex;
        gap: 1rem;
    }

    .export-info-box i {
        color: var(--pln-blue);
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    .export-info-box ul {
        margin: 0.5rem 0 0 0;
        padding-left: 1.25rem;
    }

    .export-info-box li {
        color: var(--text-gray);
        font-size: 0.9375rem;
        margin-bottom: 0.25rem;
    }

    /* Backup Info */
    .backup-info {
        background: var(--pln-blue-bg);
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        display: flex;
        gap: 1rem;
    }

    .backup-info i {
        color: var(--pln-blue);
        font-size: 2rem;
        flex-shrink: 0;
    }

    .backup-info h6 {
        color: var(--pln-blue);
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .backup-info p {
        color: var(--text-gray);
        margin: 0;
        font-size: 0.9375rem;
    }

    /* Modern Table */
    .modern-table {
        margin: 0;
    }

    .modern-table thead th {
        background: var(--pln-blue-lighter);
        color: var(--pln-blue);
        font-weight: 700;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 1rem;
        border: none;
    }

    .modern-table tbody td {
        padding: 1rem;
        vertical-align: middle;
        color: var(--text-dark);
        border-bottom: 1px solid rgba(33, 97, 140, 0.1);
    }

    .modern-table tbody tr:hover {
        background: var(--pln-blue-bg);
    }

    /* Report Cards */
    .report-card {
        background: white;
        border: 2px solid rgba(33, 97, 140, 0.15);
        border-radius: 12px;
        padding: 1.5rem;
        text-align: center;
        transition: all 0.3s ease;
    }

    .report-card:hover {
        border-color: var(--pln-blue);
        box-shadow: 0 4px 16px rgba(33, 97, 140, 0.15);
        transform: translateY(-4px);
    }

    .report-icon {
        width: 64px;
        height: 64px;
        background: var(--pln-blue-lighter);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
    }

    .report-icon i {
        font-size: 1.75rem;
        color: var(--pln-blue);
    }

    .report-card h6 {
        font-weight: 700;
        color: var(--pln-blue);
        margin-bottom: 0.5rem;
    }

    .report-card p {
        color: var(--text-gray);
        font-size: 0.875rem;
        margin-bottom: 1rem;
    }

    .report-actions {
        display: flex;
        gap: 0.5rem;
        justify-content: center;
    }

    /* Alert Styles */
    .alert {
        border-radius: 10px;
        border: none;
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
        border-left: 4px solid #28a745;
    }

    .alert-danger {
        background: #f8d7da;
        color: #721c24;
        border-left: 4px solid #dc3545;
    }

    .alert-warning {
        background: #fff3cd;
        color: #856404;
        border-left: 4px solid #ffc107;
    }

    .alert-info {
        background: var(--pln-blue-lighter);
        color: var(--pln-blue-dark);
        border-left: 4px solid var(--pln-blue);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .management-tabs .nav-link span {
            display: none;
        }

        .management-tabs .nav-link {
            padding: 0.75rem 1rem;
        }

        .tab-pane {
            padding: 1rem;
        }

        .management-card-body {
            padding: 1.25rem;
        }

        .action-buttons {
            flex-direction: column;
        }

        .action-buttons .btn-modern {
            width: 100%;
            justify-content: center;
        }

        .report-actions {
            flex-direction: column;
        }

        .report-actions .btn {
            width: 100%;
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ===== IMPORT TAB =====
    const importForm = document.getElementById('importForm');
    const importModel = document.getElementById('import_model');
    const importFile = document.getElementById('import_file');
    const downloadTemplateBtn = document.getElementById('downloadTemplateBtn');
    const previewBtn = document.getElementById('previewBtn');
    const importBtn = document.getElementById('importBtn');

    function updateImportButtons() {
        const hasModel = importModel.value !== '';
        const hasFile = importFile.files.length > 0;
        
        downloadTemplateBtn.disabled = !hasModel;
        previewBtn.disabled = !hasModel || !hasFile;
        importBtn.disabled = !hasModel || !hasFile;
    }

    importModel.addEventListener('change', updateImportButtons);
    importFile.addEventListener('change', updateImportButtons);

    downloadTemplateBtn.addEventListener('click', function() {
        const model = importModel.value;
        if (model) {
            window.location.href = `{{ route('import.download-template') }}?model=${model}`;
        }
    });

    previewBtn.addEventListener('click', function() {
        const formData = new FormData(importForm);
        formData.append('preview', 'true');

        showProgress('Loading preview...');

        fetch('{{ route("import.process") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            hideProgress();
            if (data.success) {
                showPreview(data.data, data.total_rows);
            } else {
                showAlert('danger', data.message);
            }
        })
        .catch(error => {
            hideProgress();
            showAlert('danger', 'Error: ' + error.message);
        });
    });

    importForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (!confirm('Apakah Anda yakin ingin mengimport data ini?')) {
            return;
        }
        
        const formData = new FormData(importForm);
        
        showProgress('Importing data...');

        fetch('{{ route("import.process") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            hideProgress();
            if (data.success) {
                showResults(data.imported, data.failed, data.errors);
                showAlert('success', `Berhasil import ${data.imported} data!`);
            } else {
                showAlert('danger', data.message);
            }
        })
        .catch(error => {
            hideProgress();
            showAlert('danger', 'Error: ' + error.message);
        });
    });

    function showProgress(message) {
        document.getElementById('progressSection').style.display = 'block';
        document.getElementById('progressText').textContent = message;
        document.querySelector('#progressSection .progress-bar').style.width = '75%';
    }

    function hideProgress() {
        document.getElementById('progressSection').style.display = 'none';
        document.querySelector('#progressSection .progress-bar').style.width = '0%';
    }

    function showPreview(data, totalRows) {
        document.getElementById('previewSection').style.display = 'block';
        
        let html = `<p><strong>Total rows:</strong> ${totalRows}</p>`;
        html += '<p><strong>Preview (first 5 rows):</strong></p>';
        html += '<div class="table-responsive">';
        html += '<table class="table modern-table table-sm">';
        
        if (data.length > 0) {
            html += '<thead><tr>';
            Object.keys(data[0]).forEach(key => {
                html += `<th>${key}</th>`;
            });
            html += '</tr></thead>';
            
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
        document.getElementById('resultsSection').style.display = 'block';
        
        let html = '<div class="row g-3 mb-3">';
        html += '<div class="col-md-4"><div class="alert alert-success mb-0"><strong>✓ Imported:</strong> ' + imported + '</div></div>';
        html += '<div class="col-md-4"><div class="alert alert-danger mb-0"><strong>✗ Failed:</strong> ' + failed + '</div></div>';
        html += '<div class="col-md-4"><div class="alert alert-info mb-0"><strong>Σ Total:</strong> ' + (imported + failed) + '</div></div>';
        html += '</div>';
        
        if (Object.keys(errors).length > 0) {
            html += '<h6 class="text-danger">Errors:</h6>';
            html += '<div class="table-responsive">';
            html += '<table class="table modern-table table-sm">';
            html += '<thead><tr><th>Row</th><th>Error</th></tr></thead>';
            html += '<tbody>';
            
            Object.entries(errors).forEach(([row, error]) => {
                    html += `<tr><td>${row}</td><td>${error}</td></tr>`;
            });
            
            html += '</tbody></table></div>';
        }
        
        document.getElementById('resultsContent').innerHTML = html;
    }

    window.closePreview = function() {
        document.getElementById('previewSection').style.display = 'none';
    };

    function showAlert(type, message) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.getElementById('importAlerts').appendChild(alertDiv);
        
        setTimeout(() => {
            alertDiv.remove();
        }, 5000);
    }

    // ===== EXPORT TAB =====
    const exportForm = document.getElementById('exportForm');
    const exportModel = document.getElementById('export_model');
    const exportFormat = document.getElementById('export_format');
    const exportBtn = document.getElementById('exportBtn');

    function updateExportButtons() {
        const hasModel = exportModel.value !== '';
        const hasFormat = exportFormat.value !== '';
        exportBtn.disabled = !hasModel || !hasFormat;
    }

    exportModel.addEventListener('change', updateExportButtons);
    exportFormat.addEventListener('change', updateExportButtons);

    exportForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const model = exportModel.value;
        const format = exportFormat.value;
        
        if (model && format) {
            window.location.href = `{{ url('/admin/data-management/export') }}?model=${model}&format=${format}`;
            alert('Export started! File will be downloaded shortly.');
        }
    });

    // ===== BACKUP TAB =====
    const createBackupBtn = document.getElementById('createBackupBtn');
    const restoreForm = document.getElementById('restoreForm');
    const backupFile = document.getElementById('backup_file');
    const restoreBtn = document.getElementById('restoreBtn');

    backupFile.addEventListener('change', function() {
        restoreBtn.disabled = backupFile.files.length === 0;
    });

    createBackupBtn.addEventListener('click', function() {
        if (confirm('Create database backup? This may take a few minutes.')) {
            document.getElementById('backupProgress').style.display = 'block';
            createBackupBtn.disabled = true;
            
            fetch('{{ url("/admin/data-management/backup") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('backupProgress').style.display = 'none';
                createBackupBtn.disabled = false;
                
                if (data.success) {
                    alert('Backup created successfully!');
                    // Download backup file
                    window.location.href = data.download_url;
                } else {
                    alert('Backup failed: ' + data.message);
                }
            })
            .catch(error => {
                document.getElementById('backupProgress').style.display = 'none';
                createBackupBtn.disabled = false;
                alert('Error: ' + error.message);
            });
        }
    });

    restoreForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (!confirm('WARNING: This will replace all current data! Are you sure?')) {
            return;
        }

        const formData = new FormData(restoreForm);
        restoreBtn.disabled = true;
        restoreBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Restoring...';

        fetch('{{ url("/admin/data-management/restore") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            restoreBtn.disabled = false;
            restoreBtn.innerHTML = '<i class="fas fa-undo"></i> Restore Database';
            
            if (data.success) {
                alert('Database restored successfully!');
                location.reload();
            } else {
                alert('Restore failed: ' + data.message);
            }
        })
        .catch(error => {
            restoreBtn.disabled = false;
            restoreBtn.innerHTML = '<i class="fas fa-undo"></i> Restore Database';
            alert('Error: ' + error.message);
        });
    });

    // ===== REPORTS TAB =====
    window.generateReport = function(type, format) {
        if (confirm(`Generate ${type} report in ${format.toUpperCase()} format?`)) {
            window.location.href = `{{ url('/admin/data-management/report') }}?type=${type}&format=${format}`;
            alert('Report generation started! File will be downloaded shortly.');
        }
    };
});
</script>
@endpush
@endsection
