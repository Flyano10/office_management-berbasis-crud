@extends('layouts.app')

@section('title', 'Data Lantai - PLN Icon Plus Kantor Management')
@section('page-title', 'Data Lantai')
@section('page-subtitle', 'Kelola data lantai kantor PLN Icon Plus')

@section('page-actions')
    <a href="{{ route('lantai.create') }}" class="btn btn-modern btn-primary">
        <i class="fas fa-plus"></i> Tambah Lantai
    </a>
@endsection

@section('content')
<!-- Lantai Content -->
<div class="container-fluid">
    <!-- Main Content Card -->
    <div class="row">
        <div class="col-12">
            <div class="lantai-card">
                <div class="lantai-header">
                    <h6 class="lantai-title">
                        <i class="fas fa-layer-group"></i>
                        Daftar Lantai PLN Icon Plus
                    </h6>
                </div>
                <div class="lantai-body">
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

                    <!-- Filter Panel -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="filter-card">
                                <div class="filter-header">
                                    <h6 class="filter-title">
                                        <i class="fas fa-filter"></i>
                                        Filter Data Lantai
                                    </h6>
                                </div>
                                <div class="filter-body">
                                    <form id="filter-form" method="GET">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label class="form-label">Gedung</label>
                                                <select class="modern-select" name="gedung" id="gedung-filter">
                                                    <option value="">Semua Gedung</option>
                                                    @foreach($gedung ?? [] as $g)
                                                        <option value="{{ $g->id }}" {{ request('gedung') == $g->id ? 'selected' : '' }}>
                                                            {{ $g->nama_gedung }} - {{ $g->kantor->nama_kantor ?? 'N/A' }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Nomor Lantai</label>
                                                <input type="number" class="form-control" name="nomor_lantai" value="{{ request('nomor_lantai') }}" placeholder="Contoh: 10" min="0">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Aksi</label>
                                                <div class="d-flex gap-2">
                                                    <button type="submit" class="btn btn-modern btn-filter">
                                                        <i class="fas fa-search"></i> Filter
                                                    </button>
                                                    <a href="{{ route('lantai.index') }}" class="btn btn-modern btn-clear">
                                                        <i class="fas fa-times"></i> Clear
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bulk Operations Panel -->
                    <div class="row mb-4" id="bulk-actions-panel" style="display: none;">
                        <div class="col-12">
                            <div class="bulk-actions-card">
                                <div class="bulk-actions-content">
                                    <div class="bulk-info">
                                        <i class="fas fa-info-circle"></i>
                                        <span id="selected-count">0 item dipilih</span>
                                    </div>
                                    <div class="bulk-actions">
                                        <button type="button" class="btn btn-modern btn-danger" id="bulk-delete-btn">
                                            <i class="fas fa-trash"></i> Hapus Terpilih
                                        </button>
                                        <button type="button" class="btn btn-modern btn-success" id="bulk-export-csv-btn">
                                            <i class="fas fa-file-csv"></i> Export CSV
                                        </button>
                                        <button type="button" class="btn btn-modern btn-primary" id="bulk-export-excel-btn">
                                            <i class="fas fa-file-excel"></i> Export Excel
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Data Table -->
                    <div class="table-responsive">
                        <table class="modern-table">
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" id="select-all" class="modern-checkbox">
                                    </th>
                                    <th>#</th>
                                    <th>Nama Lantai</th>
                                    <th>Nomor Lantai</th>
                                    <th>Gedung</th>
                                    <th>Kantor</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($lantai as $index => $l)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="modern-checkbox item-checkbox" value="{{ $l->id }}">
                                    </td>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <div class="lantai-info">
                                            <strong>{{ $l->nama_lantai }}</strong>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="modern-badge badge-lantai">{{ $l->nomor_lantai }}</span>
                                    </td>
                                    <td>
                                        <span class="modern-badge badge-gedung">{{ $l->gedung->nama_gedung ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <div class="kantor-info">
                                            <small>{{ $l->gedung->kantor->nama_kantor ?? 'N/A' }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('lantai.show', $l->id) }}" class="btn btn-modern btn-info" title="Lihat">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('lantai.edit', $l->id) }}" class="btn btn-modern btn-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('lantai.destroy', $l->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus lantai ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-modern btn-danger" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">
                                        <div class="empty-state">
                                            <div class="empty-content">
                                                <i class="fas fa-layer-group fa-3x mb-3"></i>
                                                <h5>Belum ada data lantai</h5>
                                                <p>Mulai dengan menambahkan lantai pertama</p>
                                                <a href="{{ route('lantai.create') }}" class="btn btn-modern btn-primary">
                                                    <i class="fas fa-plus"></i> Tambah Lantai Pertama
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('select-all');
    const itemCheckboxes = document.querySelectorAll('.item-checkbox');
    const bulkActionsPanel = document.getElementById('bulk-actions-panel');
    const selectedCountSpan = document.getElementById('selected-count');
    const bulkDeleteBtn = document.getElementById('bulk-delete-btn');
    const bulkExportCsvBtn = document.getElementById('bulk-export-csv-btn');
    const bulkExportExcelBtn = document.getElementById('bulk-export-excel-btn');

    // Select All functionality
    selectAllCheckbox.addEventListener('change', function() {
        itemCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateBulkActionsPanel();
    });

    // Individual checkbox change
    itemCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateBulkActionsPanel();
            updateSelectAllCheckbox();
        });
    });

    function updateBulkActionsPanel() {
        const checkedBoxes = document.querySelectorAll('.item-checkbox:checked');
        const count = checkedBoxes.length;
        
        if (count > 0) {
            bulkActionsPanel.style.display = 'block';
            selectedCountSpan.textContent = `${count} item dipilih`;
        } else {
            bulkActionsPanel.style.display = 'none';
        }
    }

    function updateSelectAllCheckbox() {
        const checkedBoxes = document.querySelectorAll('.item-checkbox:checked');
        const totalBoxes = itemCheckboxes.length;
        
        if (checkedBoxes.length === 0) {
            selectAllCheckbox.indeterminate = false;
            selectAllCheckbox.checked = false;
        } else if (checkedBoxes.length === totalBoxes) {
            selectAllCheckbox.indeterminate = false;
            selectAllCheckbox.checked = true;
        } else {
            selectAllCheckbox.indeterminate = true;
        }
    }

    // Bulk Delete
    bulkDeleteBtn.addEventListener('click', function() {
        const checkedBoxes = document.querySelectorAll('.item-checkbox:checked');
        const ids = Array.from(checkedBoxes).map(cb => cb.value);
        
        if (ids.length === 0) {
            alert('Pilih minimal 1 item untuk dihapus');
            return;
        }

        if (confirm(`Apakah Anda yakin ingin menghapus ${ids.length} lantai yang dipilih?`)) {
            // Create form for bulk delete
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("bulk.delete", "lantai") }}';
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            
        // Create multiple hidden inputs for each ID
        ids.forEach(id => {
            const idsInput = document.createElement('input');
            idsInput.type = 'hidden';
            idsInput.name = 'ids[]';
            idsInput.value = id;
            form.appendChild(idsInput);
        });
        
        form.appendChild(csrfToken);
        document.body.appendChild(form);
        form.submit();
        }
    });

    // Bulk Export CSV
    bulkExportCsvBtn.addEventListener('click', function() {
        const checkedBoxes = document.querySelectorAll('.item-checkbox:checked');
        const ids = Array.from(checkedBoxes).map(cb => cb.value);
        
        if (ids.length === 0) {
            alert('Pilih minimal 1 item untuk diexport');
            return;
        }

        // Create form for bulk export CSV
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("bulk.export", "lantai") }}';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        // Create multiple hidden inputs for each ID
        ids.forEach(id => {
            const idsInput = document.createElement('input');
            idsInput.type = 'hidden';
            idsInput.name = 'ids[]';
            idsInput.value = id;
            form.appendChild(idsInput);
        });
        
        const formatInput = document.createElement('input');
        formatInput.type = 'hidden';
        formatInput.name = 'format';
        formatInput.value = 'csv';
        
        form.appendChild(csrfToken);
        form.appendChild(formatInput);
        document.body.appendChild(form);
        form.submit();
    });

    // Bulk Export Excel
    bulkExportExcelBtn.addEventListener('click', function() {
        const checkedBoxes = document.querySelectorAll('.item-checkbox:checked');
        const ids = Array.from(checkedBoxes).map(cb => cb.value);
        
        if (ids.length === 0) {
            alert('Pilih minimal 1 item untuk diexport');
            return;
        }

        // Create form for bulk export Excel
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("bulk.export", "lantai") }}';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        // Create multiple hidden inputs for each ID
        ids.forEach(id => {
            const idsInput = document.createElement('input');
            idsInput.type = 'hidden';
            idsInput.name = 'ids[]';
            idsInput.value = id;
            form.appendChild(idsInput);
        });
        
        const formatInput = document.createElement('input');
        formatInput.type = 'hidden';
        formatInput.name = 'format';
        formatInput.value = 'excel';
        
        form.appendChild(csrfToken);
        form.appendChild(formatInput);
        document.body.appendChild(form);
        form.submit();
    });
});
</script>

@push('styles')
<style>
    /* Lantai Card */
    .lantai-card {
        background: white;
        border-radius: 1.5rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }

    .lantai-header {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        padding: 1.5rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .lantai-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .lantai-title i {
        color: #3b82f6; /* Blue theme for Lantai */
    }

    .lantai-body {
        padding: 1.5rem;
    }

    /* Filter Card */
    .filter-card {
        background: white;
        border-radius: 1.5rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }

    .filter-header {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        padding: 1.5rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .filter-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1e293b;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .filter-title i {
        color: #3b82f6; /* Blue theme for Lantai */
    }

    .filter-body {
        padding: 1.5rem;
    }

    .modern-select {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid #d1d5db;
        border-radius: 0.75rem;
        background: white;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .modern-select:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    /* Modern Buttons */
    .btn-modern {
        padding: 0.5rem 1rem;
        border-radius: 0.75rem;
        border: none;
        font-weight: 500;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
    }

    .btn-modern.btn-primary {
        background: linear-gradient(135deg, #3b82f6, #60a5fa);
        color: white;
    }

    .btn-modern.btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
        color: white;
    }

    .btn-modern.btn-filter {
        background: linear-gradient(135deg, #3b82f6, #60a5fa);
        color: white;
    }

    .btn-modern.btn-filter:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
    }

    .btn-modern.btn-clear {
        background: linear-gradient(135deg, #6b7280, #9ca3af);
        color: white;
    }

    .btn-modern.btn-clear:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(107, 114, 128, 0.4);
    }

    .btn-modern.btn-info {
        background: linear-gradient(135deg, #06b6d4, #22d3ee);
        color: white;
    }

    .btn-modern.btn-info:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(6, 182, 212, 0.4);
    }

    .btn-modern.btn-warning {
        background: linear-gradient(135deg, #f59e0b, #fbbf24);
        color: white;
    }

    .btn-modern.btn-warning:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
    }

    .btn-modern.btn-danger {
        background: linear-gradient(135deg, #ef4444, #f87171);
        color: white;
    }

    .btn-modern.btn-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
    }

    .btn-modern.btn-success {
        background: linear-gradient(135deg, #10b981, #34d399);
        color: white;
    }

    .btn-modern.btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
    }

    /* Bulk Actions Card */
    .bulk-actions-card {
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        border: 1px solid #f59e0b;
        border-radius: 1rem;
        padding: 1rem;
    }

    .bulk-actions-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
    }

    .bulk-info {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #92400e;
        font-weight: 500;
    }

    .bulk-actions {
        display: flex;
        gap: 0.5rem;
    }

    /* Modern Table */
    .modern-table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .modern-table thead {
        background: linear-gradient(135deg, #3b82f6, #60a5fa);
    }

    .modern-table thead th {
        padding: 1rem;
        text-align: left;
        font-weight: 600;
        color: white;
        border: none;
    }

    .modern-table tbody tr {
        border-bottom: 1px solid #f1f5f9;
        transition: all 0.3s ease;
    }

    .modern-table tbody tr:hover {
        background: #f8fafc;
    }

    .modern-table tbody td {
        padding: 1rem;
        border: none;
        vertical-align: middle;
    }

    .modern-checkbox {
        width: 1.25rem;
        height: 1.25rem;
        border-radius: 0.375rem;
        border: 2px solid #d1d5db;
        background: white;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .modern-checkbox:checked {
        background: #3b82f6;
        border-color: #3b82f6;
    }

    .modern-badge {
        padding: 0.375rem 0.75rem;
        border-radius: 0.5rem;
        font-size: 0.8rem;
        font-weight: 500;
        color: white;
    }

    .badge-lantai {
        background: linear-gradient(135deg, #3b82f6, #60a5fa);
    }

    .badge-gedung {
        background: linear-gradient(135deg, #10b981, #34d399);
    }

    .lantai-info {
        font-weight: 600;
        color: #1e293b;
    }

    .kantor-info {
        color: #64748b;
        font-size: 0.9rem;
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .action-buttons .btn-modern {
        padding: 0.375rem 0.75rem;
        font-size: 0.8rem;
    }

    /* Empty State */
    .empty-state {
        padding: 3rem 1rem;
        text-align: center;
    }

    .empty-content {
        color: #64748b;
    }

    .empty-content i {
        color: #94a3b8;
    }

    .empty-content h5 {
        color: #374151;
        margin: 1rem 0 0.5rem 0;
    }

    .empty-content p {
        margin-bottom: 1.5rem;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .lantai-body {
            padding: 1rem;
        }

        .filter-body {
            padding: 1rem;
        }

        .bulk-actions-content {
            flex-direction: column;
            align-items: stretch;
        }

        .bulk-actions {
            justify-content: center;
            flex-wrap: wrap;
        }

        .modern-table {
            font-size: 0.9rem;
        }

        .modern-table thead th,
        .modern-table tbody td {
            padding: 0.75rem 0.5rem;
        }

        .action-buttons {
            flex-direction: column;
            gap: 0.25rem;
        }

        .action-buttons .btn-modern {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }
    }

    @media (max-width: 576px) {
        .modern-table thead th:nth-child(2),
        .modern-table tbody td:nth-child(2) {
            display: none;
        }

        .modern-table thead th:nth-child(5),
        .modern-table tbody td:nth-child(5) {
            display: none;
        }
    }
</style>
@endpush
@endsection
