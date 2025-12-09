@extends('layouts.app')

@section('title', 'Data Kategori Inventaris - PLN Icon Plus Kantor Management')
@section('page-title', 'Data Kategori Inventaris')
@section('page-subtitle', 'Kelola data kategori inventaris PLN Icon Plus')

@section('page-actions')
    <a href="{{ route('kategori-inventaris.create') }}" class="btn btn-modern btn-primary">
        <i class="fas fa-plus"></i> Tambah Kategori Inventaris
    </a>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Main Content Card -->
    <div class="row">
        <div class="col-12">
            <div class="kontrak-card">
                <div class="kontrak-header">
                    <h5 class="kontrak-title">
                        <i class="fas fa-tags"></i>
                        Daftar Kategori Inventaris PLN Icon Plus
                    </h5>
                </div>
                <div class="kontrak-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Bulk Operations Panel -->
                    @if((Auth::guard('admin')->user()->role ?? '') === 'super_admin')
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
                    @endif

                    <div class="table-wrapper">
                        <div class="table-responsive" id="tableContainer">
                            <table class="table modern-table">
                            <thead>
                                <tr>
                                    @if((Auth::guard('admin')->user()->role ?? '') === 'super_admin')
                                    <th>
                                        <input type="checkbox" id="select-all" class="form-check-input modern-checkbox">
                                    </th>
                                    @endif
                                    <th>#</th>
                                    <th>Nama Kategori</th>
                                    <th>Deskripsi</th>
                                    <th>Jumlah Inventaris</th>
                                    <th>Dibuat</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($kategori as $item)
                                <tr>
                                    @if((Auth::guard('admin')->user()->role ?? '') === 'super_admin')
                                    <td>
                                        <input type="checkbox" class="form-check-input modern-checkbox item-checkbox" value="{{ $item->id }}">
                                    </td>
                                    @endif
                                    <td>{{ $loop->iteration + ($kategori->currentPage() - 1) * $kategori->perPage() }}</td>
                                    <td>
                                        <strong>{{ $item->nama_kategori }}</strong>
                                    </td>
                                    <td>
                                        @if($item->deskripsi)
                                            <small style="color: #64748b;">{{ Str::limit($item->deskripsi, 50) }}</small>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge modern-badge badge-primary">{{ $item->inventaris->count() }}</span>
                                    </td>
                                    <td>
                                        <small style="color: #64748b;">{{ $item->created_at->format('d/m/Y') }}</small>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('kategori-inventaris.show', $item->id) }}" class="btn btn-modern btn-info btn-sm" title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('kategori-inventaris.edit', $item->id) }}" class="btn btn-modern btn-warning btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('kategori-inventaris.destroy', $item->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-modern btn-danger btn-sm" 
                                                        onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="{{ (Auth::guard('admin')->user()->role ?? '') === 'super_admin' ? '7' : '6' }}" class="text-center text-muted empty-state">
                                        <div class="empty-content">
                                            <i class="fas fa-tags"></i>
                                            <p>Belum ada data kategori inventaris. Silakan tambah data kategori baru.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        </div>
                        <!-- Scroll Controls Below Table -->
                        <div class="table-scroll-controls">
                            <button class="scroll-btn scroll-left" id="scrollLeftBtn" aria-label="Scroll left">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <div class="scroll-indicator">
                                <span id="scrollPosition">0</span> / <span id="scrollMax">0</span>
                            </div>
                            <button class="scroll-btn scroll-right" id="scrollRightBtn" aria-label="Scroll right">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>

                    @if($kategori->hasPages())
                        <div class="d-flex justify-content-center mt-3">
                            {{ $kategori->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Table Horizontal Scroll with Arrow Buttons
    const tableContainer = document.getElementById('tableContainer');
    const scrollLeftBtn = document.getElementById('scrollLeftBtn');
    const scrollRightBtn = document.getElementById('scrollRightBtn');

    // Initialize scroll buttons
    if (tableContainer && scrollLeftBtn && scrollRightBtn) {
        const scrollPosition = document.getElementById('scrollPosition');
        const scrollMax = document.getElementById('scrollMax');

        function updateScrollButtons() {
            if (!tableContainer) return;
            const { scrollLeft, scrollWidth, clientWidth } = tableContainer;
            const maxScroll = scrollWidth - clientWidth;
            
            if (scrollPosition && scrollMax) {
                scrollPosition.textContent = Math.round(scrollLeft);
                scrollMax.textContent = Math.round(maxScroll);
            }
            
            if (scrollLeft > 5) {
                scrollLeftBtn.disabled = false;
            } else {
                scrollLeftBtn.disabled = true;
            }
            
            if (scrollLeft < maxScroll - 5) {
                scrollRightBtn.disabled = false;
            } else {
                scrollRightBtn.disabled = true;
            }
        }

        setTimeout(updateScrollButtons, 100);
        tableContainer.addEventListener('scroll', updateScrollButtons);
        window.addEventListener('resize', updateScrollButtons);

        scrollLeftBtn.addEventListener('click', function() {
            if (!this.disabled) {
                tableContainer.scrollBy({
                    left: -300,
                    behavior: 'smooth'
                });
            }
        });

        scrollRightBtn.addEventListener('click', function() {
            if (!this.disabled) {
                tableContainer.scrollBy({
                    left: 300,
                    behavior: 'smooth'
                });
            }
        });
    }

    // Bulk Operations
    const selectAllCheckbox = document.getElementById('select-all');
    const itemCheckboxes = document.querySelectorAll('.item-checkbox');
    const bulkActionsPanel = document.getElementById('bulk-actions-panel');
    const selectedCountSpan = document.getElementById('selected-count');
    const bulkDeleteBtn = document.getElementById('bulk-delete-btn');
    const bulkExportCsvBtn = document.getElementById('bulk-export-csv-btn');
    const bulkExportExcelBtn = document.getElementById('bulk-export-excel-btn');

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            itemCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateBulkActionsPanel();
        });
    }

    itemCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateBulkActionsPanel();
            updateSelectAllCheckbox();
        });
    });

    function updateBulkActionsPanel() {
        const checkedBoxes = document.querySelectorAll('.item-checkbox:checked');
        const count = checkedBoxes.length;
        
        if (bulkActionsPanel) {
            if (count > 0) {
                bulkActionsPanel.style.display = 'block';
                if (selectedCountSpan) selectedCountSpan.textContent = `${count} item dipilih`;
            } else {
                bulkActionsPanel.style.display = 'none';
            }
        }
    }

    function updateSelectAllCheckbox() {
        if (!selectAllCheckbox) return;
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

    // Hapus Bulk
    if (bulkDeleteBtn) {
        bulkDeleteBtn.addEventListener('click', function() {
            const checkedBoxes = document.querySelectorAll('.item-checkbox:checked');
            const ids = Array.from(checkedBoxes).map(cb => cb.value);
            
            if (ids.length === 0) {
                window.Toast?.warning('Pilih minimal 1 item untuk dihapus') || alert('Pilih minimal 1 item untuk dihapus');
                return;
            }

            if (confirm(`Apakah Anda yakin ingin menghapus ${ids.length} kategori inventaris yang dipilih?`)) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("bulk.delete", "kategori_inventaris") }}';
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                
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
    }

    // Export Bulk CSV
    if (bulkExportCsvBtn) {
        bulkExportCsvBtn.addEventListener('click', function() {
            const checkedBoxes = document.querySelectorAll('.item-checkbox:checked');
            const ids = Array.from(checkedBoxes).map(cb => cb.value);
            
            if (ids.length === 0) {
                alert('Pilih minimal 1 item untuk diexport');
                return;
            }

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("bulk.export", "kategori_inventaris") }}';
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            
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
    }

    // Export Bulk Excel
    if (bulkExportExcelBtn) {
        bulkExportExcelBtn.addEventListener('click', function() {
            const checkedBoxes = document.querySelectorAll('.item-checkbox:checked');
            const ids = Array.from(checkedBoxes).map(cb => cb.value);
            
            if (ids.length === 0) {
                alert('Pilih minimal 1 item untuk diexport');
                return;
            }

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("bulk.export", "kategori_inventaris") }}';
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            
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
    }
});
</script>

@push('styles')
<style>
    :root {
        --pln-blue: #21618C;
        --pln-blue-dark: #1A4D73;
        --pln-blue-light: #2E86AB;
        --pln-blue-lighter: #E8F4F8;
        --pln-blue-bg: #F5FAFC;
        --white: #FFFFFF;
        --gray-light: #F8F9FA;
        --gray-border: #E0E0E0;
        --text-dark: #1A1A1A;
        --text-gray: #6C757D;
        --success-color: #28A745;
        --danger-color: #DC3545;
    }

    /* Card Kontrak */
    .kontrak-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(33, 97, 140, 0.1);
        border: 1px solid rgba(33, 97, 140, 0.15);
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .kontrak-header {
        background: white;
        padding: 1.25rem 1.75rem;
        border-bottom: 2px solid var(--pln-blue);
    }

    .kontrak-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--pln-blue);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .kontrak-title i {
        color: var(--pln-blue);
        font-size: 1.5rem;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--pln-blue-lighter);
        border-radius: 10px;
    }

    .kontrak-body {
        padding: 1.5rem 1.75rem;
    }

    /* Button Modern */
    .btn-modern {
        border-radius: 10px;
        padding: 0.625rem 1.25rem;
        font-weight: 600;
        transition: all 0.2s ease;
        border: 1px solid transparent;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        cursor: pointer;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }

    .btn-modern:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(33, 97, 140, 0.2);
    }

    .btn-modern.btn-primary {
        background: var(--pln-blue);
        color: white;
        border: 1px solid var(--pln-blue);
        box-shadow: 0 2px 6px rgba(33, 97, 140, 0.15);
    }

    .btn-modern.btn-primary:hover {
        background: var(--pln-blue-dark);
        border-color: var(--pln-blue-dark);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(33, 97, 140, 0.25);
    }

    .btn-modern.btn-info {
        background: var(--pln-blue);
        color: white;
        border: 1px solid var(--pln-blue);
    }

    .btn-modern.btn-info:hover {
        background: var(--pln-blue-dark);
        border-color: var(--pln-blue-dark);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(33, 97, 140, 0.25);
    }

    .btn-modern.btn-warning {
        background: var(--pln-blue);
        color: white;
        border: 1px solid var(--pln-blue);
    }

    .btn-modern.btn-warning:hover {
        background: var(--pln-blue-dark);
        border-color: var(--pln-blue-dark);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(33, 97, 140, 0.25);
    }

    .btn-modern.btn-danger {
        background: #dc3545;
        color: white;
        border: 1px solid #dc3545;
    }

    .btn-modern.btn-danger:hover {
        background: #c82333;
        border-color: #c82333;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(220, 53, 69, 0.25);
    }

    .btn-modern.btn-success {
        background: #28a745;
        color: white;
        border: 1px solid #28a745;
    }

    .btn-modern.btn-success:hover {
        background: #218838;
        border-color: #218838;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(40, 167, 69, 0.25);
    }

    /* Aksi Bulk */
    .bulk-actions-card {
        background: var(--pln-blue-lighter);
        border: 2px solid var(--pln-blue);
        border-radius: 10px;
        padding: 1rem 1.25rem;
        box-shadow: 0 2px 8px rgba(33, 97, 140, 0.15);
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
        color: var(--pln-blue-dark);
        font-weight: 600;
    }

    .bulk-info i {
        color: var(--pln-blue);
    }

    .bulk-actions {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
        align-items: center;
    }

    .bulk-actions .btn-modern {
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        border: 1px solid transparent;
    }

    .bulk-actions .btn-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    /* Table Wrapper with Scroll Controls */
    .table-wrapper {
        position: relative;
        margin: 1.5rem 0;
    }

    .table-responsive {
        border-radius: 0;
        overflow-x: auto;
        overflow-y: visible;
        scrollbar-width: none;
        -ms-overflow-style: none;
        scroll-behavior: smooth;
        touch-action: pan-x pinch-zoom;
    }

    .table-responsive::-webkit-scrollbar {
        display: none;
    }

    /* Scroll Controls Below Table */
    .table-scroll-controls {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 1rem;
        padding: 0.75rem 1rem;
        background: #fafbfc;
        border-top: 1px solid rgba(33, 97, 140, 0.1);
        margin-top: 0;
    }

    .scroll-btn {
        background: white;
        color: var(--pln-blue);
        border: 2px solid var(--pln-blue);
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 2px 6px rgba(33, 97, 140, 0.15);
        transition: all 0.2s ease;
    }

    .scroll-btn:hover:not(:disabled) {
        background: var(--pln-blue-lighter);
        border-color: var(--pln-blue-dark);
        box-shadow: 0 4px 10px rgba(33, 97, 140, 0.25);
        transform: scale(1.1);
    }

    .scroll-btn:active:not(:disabled) {
        transform: scale(0.95);
    }

    .scroll-btn:disabled {
        opacity: 0.4;
        cursor: not-allowed;
    }

    .scroll-btn i {
        font-size: 0.85rem;
        color: var(--pln-blue);
    }

    .scroll-indicator {
        font-size: 0.8rem;
        color: #64748b;
        font-weight: 600;
        min-width: 60px;
        text-align: center;
    }

    /* Tabel Modern */
    .modern-table {
        margin: 0;
        border-radius: 0;
        overflow: hidden;
        box-shadow: none;
        width: 100%;
        min-width: 800px;
    }

    .modern-table thead th {
        background: white;
        border: none;
        padding: 1rem;
        font-weight: 700;
        color: var(--pln-blue);
        border-bottom: 2px solid var(--pln-blue);
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        white-space: nowrap;
    }

    .modern-table tbody td {
        padding: 1rem;
        border: none;
        border-bottom: 1px solid var(--gray-border);
        vertical-align: middle;
        font-size: 0.875rem;
    }

    .modern-table tbody tr {
        transition: all 0.15s ease;
        background: white;
    }

    .modern-table tbody tr:hover {
        background: var(--pln-blue-lighter);
    }

    .modern-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Modern Checkbox */
    .modern-checkbox {
        width: 1.2rem;
        height: 1.2rem;
        border-radius: 4px;
        border: 2px solid rgba(33, 97, 140, 0.3);
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .modern-checkbox:checked {
        background: var(--pln-blue);
        border-color: var(--pln-blue);
    }

    .modern-checkbox:focus {
        box-shadow: 0 0 0 3px rgba(33, 97, 140, 0.15);
        outline: none;
    }

    /* Modern Badges */
    .modern-badge {
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.8125rem;
        letter-spacing: 0.3px;
    }

    .badge-primary {
        background: var(--pln-blue);
        color: white;
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
        align-items: center;
    }

    .action-buttons .btn-modern {
        padding: 0.5rem 0.75rem;
        min-width: 2.5rem;
        height: 2.5rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        font-size: 0.8rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        transition: all 0.2s ease;
    }

    .action-buttons .btn-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    .action-buttons .btn-modern.btn-sm {
        padding: 0.4rem 0.65rem;
        min-width: 2.25rem;
        height: 2.25rem;
        font-size: 0.75rem;
    }

    /* Empty State */
    .empty-state {
        padding: 4rem 2rem;
    }

    .empty-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1rem;
    }

    .empty-content i {
        font-size: 3.5rem;
        color: var(--pln-blue-lighter);
    }

    .empty-content p {
        margin: 0;
        color: var(--text-gray);
        font-size: 1rem;
        font-weight: 500;
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
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

        .table-scroll-controls {
            padding: 0.5rem;
            gap: 0.75rem;
        }
        
        .scroll-btn {
            width: 32px;
            height: 32px;
        }
        
        .scroll-indicator {
            font-size: 0.75rem;
            min-width: 50px;
        }
        
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        .table-responsive::-webkit-scrollbar {
            display: block;
            height: 4px;
        }
        
        .table-responsive::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        
        .table-responsive::-webkit-scrollbar-thumb {
            background: var(--pln-blue);
            border-radius: 2px;
        }
    }

    @media (max-width: 576px) {
        .action-buttons .btn-modern {
            padding: 0.25rem;
            min-width: 2rem;
            height: 2rem;
            font-size: 0.8rem;
        }
    }
</style>
@endpush
@endsection
