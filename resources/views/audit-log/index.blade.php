@extends('layouts.app')

@section('title', 'Audit Log - PLN Icon Plus Kantor Management')
@section('page-title', 'Audit Log')
@section('page-subtitle', 'Riwayat aktivitas sistem dan perubahan data')

@section('page-actions')
    <div class="header-actions d-flex gap-2">
        <a href="{{ route('audit-log.export', request()->query()) }}" class="btn btn-modern btn-success">
            <i class="fas fa-download"></i> Export CSV
        </a>
        <button type="button" class="btn btn-modern btn-danger" data-bs-toggle="modal" data-bs-target="#resetModal">
            <i class="fas fa-trash-alt"></i> Reset Audit Log
        </button>
    </div>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Filter Panel -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="filter-card">
                <div class="filter-header">
                    <h6 class="filter-title">
                        <i class="fas fa-filter"></i>
                        Filter Audit Log
                    </h6>
                </div>
                <div class="filter-body">
                    <form method="GET" action="{{ route('audit-log.index') }}" class="row g-3">
                        <div class="col-md-4">
                            <label for="search" class="form-label modern-label">Cari Aktivitas</label>
                            <input type="text" class="form-control modern-input" id="search" name="search"
                                   value="{{ request('search') }}" placeholder="Cari aktivitas, user, atau deskripsi...">
                        </div>
                        <div class="col-md-3">
                            <label for="action" class="form-label modern-label">Aksi</label>
                            <select class="form-select modern-select" id="action" name="action">
                                <option value="">Semua Aksi</option>
                                @foreach($actions as $action)
                                    <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                                        {{ ucfirst($action) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="user_id" class="form-label modern-label">User</label>
                            <select class="form-select modern-select" id="user_id" name="user_id">
                                <option value="">Semua User</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ (string)request('user_id') === (string)$user->id ? 'selected' : '' }}>
                                        {{ $user->nama_admin }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label modern-label">Aksi</label>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-modern btn-filter">
                                    <i class="fas fa-search"></i> Filter
                                </button>
                                <a href="{{ route('audit-log.index') }}" class="btn btn-modern btn-clear">
                                    <i class="fas fa-times"></i> Clear
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Audit Log Table -->
    <div class="row">
        <div class="col-12">
            <div class="audit-card">
                <div class="audit-header">
                    <h5 class="audit-title">
                        <i class="fas fa-history"></i>
                        Audit Log Records
                    </h5>
                </div>
                <div class="audit-body">
                    @if($auditLogs->count() > 0)
                        <div class="table-responsive" id="tableContainer">
                            <table class="table modern-table">
                                <thead>
                                    <tr>
                                        <th>Tanggal & Waktu</th>
                                        <th>User</th>
                                        <th>Aksi</th>
                                        <th>Model</th>
                                        <th>Deskripsi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($auditLogs as $log)
                                        <tr>
                                            <td>
                                                <div class="audit-date">
                                                    <strong>{{ $log->created_at->format('d/m/Y') }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $log->created_at->format('H:i:s') }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="audit-user">
                                                    <strong>{{ $log->user_name ?? 'System' }}</strong>
                                                    @if($log->user_id)
                                                        <br>
                                                        <small class="text-muted">ID: {{ $log->user_id }}</small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                @php
                                                    $actionColors = [
                                                        'create' => 'success',
                                                        'update' => 'warning',
                                                        'delete' => 'danger',
                                                        'login' => 'info',
                                                        'logout' => 'secondary',
                                                        'view' => 'primary',
                                                        'export' => 'success',
                                                        'import' => 'info',
                                                        'bulk_delete' => 'danger',
                                                        'bulk_export' => 'success'
                                                    ];
                                                    $color = $actionColors[$log->action] ?? 'secondary';
                                                @endphp
                                                <span class="badge modern-badge badge-{{ $color }}">{{ $log->formatted_action }}</span>
                                            </td>
                                            <td>
                                                @if($log->model_type)
                                                    <span class="badge modern-badge badge-info">{{ $log->formatted_model }}</span>
                                                    @if($log->model_name)
                                                        <br>
                                                        <small class="text-muted">{{ Str::limit($log->model_name, 30) }}</small>
                                                    @endif
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="audit-description">
                                                    <div class="description-text">
                                                        {{ Str::limit($log->description, 100) }}
                                                    </div>
                                                    @if($log->changes_summary)
                                                        <div class="changes-summary">
                                                            <small class="text-muted">{{ Str::limit($log->changes_summary, 80) }}</small>
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <a href="{{ route('audit-log.show', $log->id) }}" class="btn btn-modern btn-info btn-sm" title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Scroll Controls -->
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

                        <!-- Pagination -->
                        @if($auditLogs->hasPages())
                        <div class="pagination-container">
                            <div class="pagination-info">
                                Menampilkan {{ $auditLogs->firstItem() }} - {{ $auditLogs->lastItem() }} 
                                dari {{ $auditLogs->total() }} log
                            </div>
                            <div class="pagination-links">
                                {{ $auditLogs->appends(request()->query())->links() }}
                            </div>
                        </div>
                        @endif
                    @else
                        <div class="empty-state">
                            <div class="empty-content">
                                <i class="fas fa-history"></i>
                                <h5>Tidak ada audit log ditemukan</h5>
                                <p>Coba ubah filter atau tunggu aktivitas admin</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reset Confirmation Modal -->
<div class="modal fade" id="resetModal" tabindex="-1" aria-labelledby="resetModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="border-bottom: 2px solid var(--pln-blue);">
                <h5 class="modal-title" id="resetModalLabel" style="color: var(--pln-blue); font-weight: 700;">
                    <i class="fas fa-exclamation-triangle text-warning"></i>
                    Konfirmasi Reset Audit Log
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="fas fa-warning"></i>
                    <strong>Peringatan!</strong> Tindakan ini akan menghapus semua data audit log secara permanen.
                </div>
                <p>Apakah Anda yakin ingin mereset semua audit log? Tindakan ini tidak dapat dibatalkan.</p>
                <div class="bg-light p-3 rounded">
                    <strong>Informasi:</strong>
                    <ul class="mb-0 mt-2">
                        <li>Semua riwayat aktivitas sistem akan dihapus</li>
                        <li>Data yang sudah di-export sebelumnya masih aman</li>
                        <li>Audit log baru akan dimulai dari awal</li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-modern btn-clear" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Batal
                </button>
                <form action="{{ route('audit-log.reset') }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-modern btn-danger">
                        <i class="fas fa-trash-alt"></i> Ya, Reset Audit Log
                    </button>
                </form>
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

    /* Filter Card */
    .filter-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(33, 97, 140, 0.1);
        border: 1px solid rgba(33, 97, 140, 0.15);
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .filter-header {
        background: white;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid rgba(33, 97, 140, 0.1);
    }

    .filter-title {
        font-size: 0.9375rem;
        font-weight: 700;
        color: var(--pln-blue);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .filter-title i {
        color: var(--pln-blue);
        font-size: 1rem;
    }

    .filter-body {
        padding: 1.25rem 1.5rem;
        background: var(--pln-blue-bg);
    }

    .modern-label {
        font-weight: 600;
        color: var(--text-dark);
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
    }

    .modern-input,
    .modern-select {
        border: 2px solid rgba(33, 97, 140, 0.2);
        border-radius: 10px;
        padding: 0.75rem 1rem;
        font-size: 0.9375rem;
        transition: all 0.2s ease;
    }

    .modern-input:focus,
    .modern-select:focus {
        border-color: var(--pln-blue);
        box-shadow: 0 0 0 3px rgba(33, 97, 140, 0.1);
        outline: none;
    }

    /* Audit Card */
    .audit-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(33, 97, 140, 0.1);
        border: 1px solid rgba(33, 97, 140, 0.15);
        overflow: hidden;
    }

    .audit-header {
        background: white;
        padding: 1.25rem 1.75rem;
        border-bottom: 2px solid var(--pln-blue);
    }

    .audit-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--pln-blue);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .audit-title i {
        color: var(--pln-blue);
        font-size: 1.25rem;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--pln-blue-lighter);
        border-radius: 8px;
    }

    .audit-body {
        padding: 1.5rem 1.75rem;
    }

    /* Modern Table */
    .modern-table {
        margin: 0;
        width: 100%;
        border-collapse: collapse;
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
        border-bottom: 2px solid var(--pln-blue);
        white-space: nowrap;
    }

    .modern-table tbody td {
        padding: 1rem;
        border-bottom: 1px solid rgba(33, 97, 140, 0.1);
        vertical-align: middle;
        color: var(--text-dark);
        font-size: 0.9375rem;
    }

    .modern-table tbody tr {
        transition: all 0.15s ease;
        background: white;
    }

    .modern-table tbody tr:hover {
        background: var(--pln-blue-bg);
    }

    .modern-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Audit Content */
    .audit-date strong {
        color: var(--pln-blue);
        font-weight: 700;
    }

    .audit-user strong {
        color: var(--text-dark);
        font-weight: 600;
    }

    .audit-description {
        color: var(--text-gray);
        max-width: 400px;
    }

    .description-text {
        font-weight: 500;
        color: var(--text-dark);
        line-height: 1.5;
        margin-bottom: 0.5rem;
    }

    .changes-summary {
        background: var(--pln-blue-bg);
        padding: 0.5rem 0.75rem;
        border-radius: 8px;
        border-left: 3px solid var(--pln-blue);
        font-size: 0.8125rem;
        margin-top: 0.5rem;
        color: var(--text-gray);
    }

    /* Modern Badges */
    .badge.modern-badge {
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.8125rem;
        letter-spacing: 0.3px;
    }

    .badge.badge-primary {
        background: var(--pln-blue);
        color: white;
    }

    .badge.badge-info {
        background: var(--pln-blue-light);
        color: white;
    }

    .badge.badge-success {
        background: #28a745;
        color: white;
    }

    .badge.badge-warning {
        background: #ffc107;
        color: #1e293b;
    }

    .badge.badge-danger {
        background: #dc3545;
        color: white;
    }

    .badge.badge-secondary {
        background: #6c757d;
        color: white;
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
    }

    .btn-modern.btn-primary:hover {
        background: var(--pln-blue-dark);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(33, 97, 140, 0.25);
    }

    .btn-modern.btn-success {
        background: #28a745;
        color: white;
        border: 1px solid #28a745;
    }

    .btn-modern.btn-success:hover {
        background: #218838;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(40, 167, 69, 0.25);
    }

    .btn-modern.btn-danger {
        background: #dc3545;
        color: white;
        border: 1px solid #dc3545;
    }

    .btn-modern.btn-danger:hover {
        background: #c82333;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(220, 53, 69, 0.25);
    }

    .btn-modern.btn-info {
        background: var(--pln-blue);
        color: white;
        border: 1px solid var(--pln-blue);
    }

    .btn-modern.btn-info:hover {
        background: var(--pln-blue-dark);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(33, 97, 140, 0.25);
    }

    .btn-modern.btn-filter {
        background: var(--pln-blue);
        color: white;
        border: 1px solid var(--pln-blue);
    }

    .btn-modern.btn-filter:hover {
        background: var(--pln-blue-dark);
        transform: translateY(-1px);
    }

    .btn-modern.btn-clear {
        background: white;
        color: #64748b;
        border: 1px solid #e2e8f0;
    }

    .btn-modern.btn-clear:hover {
        background: #f8f9fa;
        color: #475569;
        border-color: #cbd5e0;
        transform: translateY(-1px);
    }

    .btn-modern.btn-sm {
        padding: 0.5rem 0.75rem;
        font-size: 0.8rem;
    }

    /* Scroll Controls */
    .table-scroll-controls {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 1rem;
        padding: 0.75rem 1rem;
        background: var(--pln-blue-bg);
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
        transform: scale(1.1);
    }

    .scroll-btn:disabled {
        opacity: 0.4;
        cursor: not-allowed;
    }

    .scroll-indicator {
        font-size: 0.8rem;
        color: var(--text-gray);
        font-weight: 600;
        min-width: 60px;
        text-align: center;
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

    .empty-content h5 {
        color: var(--text-dark);
        font-weight: 700;
        margin: 0;
    }

    .empty-content p {
        color: var(--text-gray);
        margin: 0;
    }

    /* Pagination */
    .pagination-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 1.5rem;
        padding: 1rem 0;
        border-top: 1px solid rgba(33, 97, 140, 0.1);
    }

    .pagination-info {
        color: var(--text-gray);
        font-size: 0.875rem;
        font-weight: 600;
    }

    .pagination-links .pagination {
        margin: 0;
    }

    .pagination-links .page-link {
        color: var(--pln-blue);
        border-color: rgba(33, 97, 140, 0.2);
        padding: 0.5rem 0.75rem;
    }

    .pagination-links .page-link:hover {
        background: var(--pln-blue-lighter);
        border-color: var(--pln-blue);
    }

    .pagination-links .page-item.active .page-link {
        background: var(--pln-blue);
        border-color: var(--pln-blue);
        color: white;
    }

    /* Table Wrapper */
    .table-wrapper {
        position: relative;
    }

    .table-responsive {
        border-radius: 0;
        overflow-x: auto;
        overflow-y: visible;
        scrollbar-width: none;
        -ms-overflow-style: none;
        scroll-behavior: smooth;
    }

    .table-responsive::-webkit-scrollbar {
        display: none;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .audit-body {
            padding: 1rem;
        }

        .modern-table {
            font-size: 0.875rem;
        }

        .modern-table thead th,
        .modern-table tbody td {
            padding: 0.75rem 0.5rem;
        }

        .pagination-container {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }

        .header-actions {
            flex-direction: column;
            width: 100%;
        }

        .header-actions .btn-modern {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Table Horizontal Scroll with Arrow Buttons
    const tableContainer = document.getElementById('tableContainer');
    const scrollLeftBtn = document.getElementById('scrollLeftBtn');
    const scrollRightBtn = document.getElementById('scrollRightBtn');

    if (tableContainer && scrollLeftBtn && scrollRightBtn) {
        const scrollPosition = document.getElementById('scrollPosition');
        const scrollMax = document.getElementById('scrollMax');

        function updateScrollButtons() {
            const { scrollLeft, scrollWidth, clientWidth } = tableContainer;
            const maxScroll = scrollWidth - clientWidth;
            
            if (scrollPosition && scrollMax) {
                scrollPosition.textContent = Math.round(scrollLeft);
                scrollMax.textContent = Math.round(maxScroll);
            }
            
            scrollLeftBtn.disabled = scrollLeft <= 5;
            scrollRightBtn.disabled = scrollLeft >= maxScroll - 5;
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
});
</script>
@endpush
@endsection
