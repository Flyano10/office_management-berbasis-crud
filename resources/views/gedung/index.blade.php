@extends('layouts.app')

@section('title', 'Data Gedung - PLN Icon Plus Kantor Management')
@section('page-title', 'Data Gedung')
@section('page-subtitle', 'Kelola data gedung kantor PLN Icon Plus')

@section('page-actions')
    <a href="{{ route('gedung.create') }}" class="btn btn-modern btn-primary">
        <i class="fas fa-plus"></i> Tambah Gedung
    </a>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Main Content Card -->
    <div class="row">
        <div class="col-12">
            <div class="gedung-card">
                <div class="gedung-header">
                    <h5 class="gedung-title">
                        <i class="fas fa-home"></i>
                        Daftar Gedung PLN Icon Plus
                    </h5>
                </div>
                <div class="gedung-body">
                    <!-- Filter Panel -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="filter-card">
                                <div class="filter-header">
                                    <h6 class="filter-title">
                                        <i class="fas fa-filter"></i>
                                        Filter Data Gedung
                                    </h6>
                                </div>
                                <div class="filter-body">
                                    <form id="filter-form" method="GET">
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label">Status Gedung</label>
                                                <select class="form-select modern-select" name="status_gedung" id="status-filter">
                                                    <option value="">Semua Status</option>
                                                    <option value="Aktif" {{ request('status_gedung') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                                    <option value="Tidak Aktif" {{ request('status_gedung') == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Kantor</label>
                                                <select class="form-select modern-select" name="kantor" id="kantor-filter">
                                                    <option value="">Semua Kantor</option>
                                                    @foreach($kantor ?? [] as $k)
                                                        <option value="{{ $k->id }}" {{ request('kantor') == $k->id ? 'selected' : '' }}>
                                                            {{ $k->kode_kantor }} - {{ $k->nama_kantor }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Aksi</label>
                                                <div class="d-grid gap-2">
                                                    <button type="submit" class="btn btn-modern btn-filter">
                                                        <i class="fas fa-search"></i> Filter
                                                    </button>
                                                    <a href="{{ route('gedung.index') }}" class="btn btn-modern btn-clear">
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
                                        <i class="fas fa-check-circle"></i>
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

                    <div class="table-responsive">
                        <table class="table modern-table">
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" id="select-all" class="form-check-input modern-checkbox">
                                    </th>
                                    <th>#</th>
                                    <th>Nama Gedung</th>
                                    <th>Kantor</th>
                                    <th>Alamat</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($gedung as $g)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="form-check-input modern-checkbox item-checkbox" value="{{ $g->id }}">
                                    </td>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="gedung-info">
                                            <strong>{{ $g->nama_gedung }}</strong>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="kantor-info">
                                            <span class="badge modern-badge badge-kantor">{{ $g->kantor->kode_kantor }}</span>
                                            <br><small class="text-muted">{{ $g->kantor->nama_kantor }}</small>
                                        </div>
                                    </td>
                                    <td class="alamat-cell">{{ Str::limit($g->alamat_gedung, 50) }}</td>
                                    <td>
                                        <span class="badge modern-badge {{ $g->status_gedung == 'aktif' ? 'badge-success' : 'badge-danger' }}">
                                            {{ ucfirst($g->status_gedung) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('gedung.show', $g->id) }}" class="btn btn-modern btn-info btn-sm" title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('gedung.edit', $g->id) }}" class="btn btn-modern btn-warning btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('gedung.destroy', $g->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-modern btn-danger btn-sm" 
                                                        onclick="return confirm('Apakah Anda yakin ingin menghapus gedung ini?')" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted empty-state">
                                        <div class="empty-content">
                                            <i class="fas fa-info-circle"></i>
                                            <p>Belum ada data gedung. Silakan tambah data gedung baru.</p>
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

        if (confirm(`Apakah Anda yakin ingin menghapus ${ids.length} gedung yang dipilih?`)) {
            // Create form for bulk delete
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("bulk.delete", "gedung") }}';
            
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
        form.action = '{{ route("bulk.export", "gedung") }}';
        
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
        form.action = '{{ route("bulk.export", "gedung") }}';
        
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

// Search function
function searchGedung() {
    const searchInput = document.querySelector('.main-content .search-input');
    if (!searchInput) return;
    
    const searchTerm = searchInput.value.toLowerCase().trim();
    const tableRows = document.querySelectorAll('.modern-table tbody tr');
    
    tableRows.forEach(row => {
        const text = row.textContent.toLowerCase();
        if (text.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Add search input event listener
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('.main-content .search-input');
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            searchGedung();
        });
    }
});
</script>
@endsection

@push('styles')
<style>
    /* Header Actions */
    .header-actions {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .search-container {
        position: relative;
        display: flex;
        align-items: center;
    }

    .search-input {
        padding: 0.75rem 1rem;
        border: 2px solid #e2e8f0;
        border-radius: 0.75rem;
        background: white;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        width: 300px;
    }

    .search-input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .search-btn {
        position: absolute;
        right: 0.5rem;
        background: #3b82f6;
        color: white;
        border: none;
        border-radius: 0.5rem;
        padding: 0.5rem;
        transition: all 0.3s ease;
    }

    .search-btn:hover {
        background: #2563eb;
        transform: scale(1.05);
    }

    /* Gedung Card */
    .gedung-card {
        background: white;
        border-radius: 1.5rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }

    .gedung-header {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        padding: 1.5rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .gedung-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .gedung-title i {
        color: #10b981;
    }

    .gedung-body {
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
        color: #10b981;
    }

    .filter-body {
        padding: 1.5rem;
    }

    .modern-select {
        border: 2px solid #e2e8f0;
        border-radius: 0.75rem;
        padding: 0.75rem 1rem;
        background: white;
        transition: all 0.3s ease;
    }

    .modern-select:focus {
        border-color: #10b981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
    }

    /* Modern Buttons */
    .btn-modern {
        border-radius: 0.75rem;
        padding: 0.75rem 1.5rem;
        font-weight: 500;
        transition: all 0.3s ease;
        border: 2px solid transparent;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-modern.btn-primary {
        background: linear-gradient(135deg, #10b981, #34d399);
        color: white;
        border-color: #10b981;
    }

    .btn-modern.btn-primary:hover {
        background: linear-gradient(135deg, #059669, #10b981);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .btn-modern.btn-filter {
        background: linear-gradient(135deg, #10b981, #34d399);
        color: white;
        border-color: #10b981;
    }

    .btn-modern.btn-filter:hover {
        background: linear-gradient(135deg, #059669, #10b981);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .btn-modern.btn-clear {
        background: linear-gradient(135deg, #64748b, #94a3b8);
        color: white;
        border-color: #64748b;
    }

    .btn-modern.btn-clear:hover {
        background: linear-gradient(135deg, #475569, #64748b);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(100, 116, 139, 0.3);
    }

    .btn-modern.btn-info {
        background: linear-gradient(135deg, #06b6d4, #22d3ee);
        color: white;
        border-color: #06b6d4;
    }

    .btn-modern.btn-info:hover {
        background: linear-gradient(135deg, #0891b2, #06b6d4);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(6, 182, 212, 0.3);
    }

    .btn-modern.btn-warning {
        background: linear-gradient(135deg, #f59e0b, #fbbf24);
        color: white;
        border-color: #f59e0b;
    }

    .btn-modern.btn-warning:hover {
        background: linear-gradient(135deg, #d97706, #f59e0b);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
    }

    .btn-modern.btn-danger {
        background: linear-gradient(135deg, #ef4444, #f87171);
        color: white;
        border-color: #ef4444;
    }

    .btn-modern.btn-danger:hover {
        background: linear-gradient(135deg, #dc2626, #ef4444);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    .btn-modern.btn-success {
        background: linear-gradient(135deg, #10b981, #34d399);
        color: white;
        border-color: #10b981;
    }

    .btn-modern.btn-success:hover {
        background: linear-gradient(135deg, #059669, #10b981);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    /* Bulk Actions */
    .bulk-actions-card {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        border: 1px solid #10b981;
        border-radius: 1rem;
        padding: 1rem;
    }

    .bulk-actions-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .bulk-info {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #065f46;
        font-weight: 600;
    }

    .bulk-info i {
        color: #10b981;
    }

    .bulk-actions {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    /* Modern Table */
    .modern-table {
        margin: 0;
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .modern-table thead th {
        background: #f8fafc;
        border: none;
        padding: 1rem;
        font-weight: 600;
        color: #1e293b;
        border-bottom: 2px solid #e2e8f0;
    }

    .modern-table tbody td {
        padding: 1rem;
        border: none;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .modern-table tbody tr:hover {
        background: #f8fafc;
    }

    .modern-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Modern Checkbox */
    .modern-checkbox {
        width: 1.2rem;
        height: 1.2rem;
        border-radius: 0.375rem;
        border: 2px solid #d1d5db;
        transition: all 0.3s ease;
    }

    .modern-checkbox:checked {
        background: #10b981;
        border-color: #10b981;
    }

    .modern-checkbox:focus {
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
    }

    /* Modern Badges */
    .modern-badge {
        padding: 0.5rem 1rem;
        border-radius: 2rem;
        font-weight: 500;
        font-size: 0.8rem;
    }

    .badge-kantor {
        background: linear-gradient(135deg, #3b82f6, #60a5fa);
        color: white;
    }

    .badge-success {
        background: linear-gradient(135deg, #10b981, #34d399);
        color: white;
    }

    .badge-danger {
        background: linear-gradient(135deg, #ef4444, #f87171);
        color: white;
    }

    /* Table Content */
    .gedung-info strong {
        color: #1e293b;
        font-weight: 600;
    }

    .kantor-info strong {
        color: #1e293b;
        font-weight: 600;
    }

    .alamat-cell {
        max-width: 200px;
        word-wrap: break-word;
    }

    .action-buttons {
        display: flex;
        gap: 0.25rem;
        flex-wrap: wrap;
    }

    .action-buttons .btn-modern {
        padding: 0.5rem;
        min-width: 2.5rem;
        height: 2.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Empty State */
    .empty-state {
        padding: 3rem 1rem;
    }

    .empty-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1rem;
    }

    .empty-content i {
        font-size: 3rem;
        color: #94a3b8;
    }

    .empty-content p {
        margin: 0;
        color: #64748b;
        font-size: 1.1rem;
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .header-actions {
            flex-direction: column;
            align-items: stretch;
        }

        .search-input {
            width: 100%;
        }

        .bulk-actions-content {
            flex-direction: column;
            align-items: stretch;
        }

        .bulk-actions {
            justify-content: center;
        }

        .action-buttons {
            justify-content: center;
        }

        .modern-table {
            font-size: 0.9rem;
        }

        .modern-table th,
        .modern-table td {
            padding: 0.75rem 0.5rem;
        }

        .modern-table th:nth-child(3),
        .modern-table td:nth-child(3) {
            display: none;
        }

        .modern-table th:nth-child(4),
        .modern-table td:nth-child(4) {
            display: none;
        }
    }

    @media (max-width: 576px) {
        .modern-table th:nth-child(2),
        .modern-table td:nth-child(2) {
            display: none;
        }

        .action-buttons .btn-modern {
            padding: 0.25rem;
            min-width: 2rem;
            height: 2rem;
            font-size: 0.8rem;
        }
    }
</style>
@endpush
