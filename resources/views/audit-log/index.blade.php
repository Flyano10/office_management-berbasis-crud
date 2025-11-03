@extends('layouts.app')

@section('title', 'Audit Log - PLN Icon Plus Kantor Management')
@section('page-title', 'Audit Log')
@section('page-subtitle', 'Riwayat aktivitas sistem dan perubahan data')

@section('page-actions')
    <div class="header-actions d-flex gap-2">
        <a href="{{ route('audit-log.export', request()->query()) }}" class="btn btn-outline-primary">
            <i class="fas fa-download me-2"></i>Export CSV
        </a>
        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#resetModal">
            <i class="fas fa-trash-alt me-2"></i>Reset Audit Log
        </button>
    </div>
@endsection

@section('content')
<!-- Audit Log Content -->
<div class="container-fluid">

    <!-- Simple Filter Panel -->
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
                    <form method="GET" action="{{ route('audit-log.index') }}" class="row g-2 align-items-end">
                        <div class="col-md-5">
                            <label for="search" class="form-label">Cari</label>
                            <input type="text" class="form-control" id="search" name="search"
                                   value="{{ request('search') }}" placeholder="Cari aktivitas...">
                        </div>
                        <div class="col-md-3">
                            <label for="action" class="form-label">Aksi</label>
                            <select class="form-select" id="action" name="action">
                                <option value="">Semua Aksi</option>
                                @foreach($actions as $action)
                                    <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                                        {{ ucfirst($action) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="user_id" class="form-label">User</label>
                            <select class="form-select" id="user_id" name="user_id">
                                <option value="">Semua User</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ (string)request('user_id') === (string)$user->id ? 'selected' : '' }}>
                                        {{ $user->nama_admin }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 d-flex justify-content-end gap-2 mt-2">
                            <a href="{{ route('audit-log.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-rotate-left me-1"></i>Reset
                            </a>
                            <button type="submit" class="btn btn-outline-primary">
                                <i class="fas fa-filter me-1"></i>Filter
                            </button>
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
                    <h6 class="audit-title">
                        <i class="fas fa-history"></i>
                        Audit Log Records
                    </h6>
                </div>
                <div class="audit-body">
                    
                    
                    @if($auditLogs->count() > 0)
                        <div class="table-responsive">
                            <table class="modern-table">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tanggal & Waktu</th>
                                        <th>User</th>
                                        <th>Aksi</th>
                                        <th>Deskripsi</th>
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
                                                <span class="modern-badge">{{ $log->formatted_action }}</span>
                                            </td>
                                            <td>
                                                <div class="audit-description">
                                                    <div class="description-text">
                                                        {{ Str::limit($log->description, 150) }}
                                                    </div>
                                                    @if($log->changes_summary)
                                                        <div class="changes-summary">
                                                            <small class="text-muted">{{ Str::limit($log->changes_summary, 100) }}</small>
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="pagination-container">
                            <div class="pagination-info">
                                Menampilkan {{ $auditLogs->firstItem() }} - {{ $auditLogs->lastItem() }} 
                                dari {{ $auditLogs->total() }} log
                            </div>
                            <div class="pagination-links">
                                {{ $auditLogs->appends(request()->query())->links('pagination.audit-log') }}
                            </div>
                        </div>
                    @else
                        <div class="empty-state">
                            <div class="empty-content">
                                <i class="fas fa-history fa-3x mb-3"></i>
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

@push('styles')
<style>
    /* Card Audit */
    .audit-card {
        background: white;
        border-radius: 1.5rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }

    .audit-header {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        padding: 1.5rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .audit-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1e293b;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .audit-title i {
        color: #3b82f6;
    }

    .audit-body {
        padding: 1.5rem;
    }

    /* Tabel Modern */
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
        height: auto;
        min-height: 70px;
    }

    .modern-table tbody tr:hover {
        background: #f8fafc;
    }

    .modern-table tbody td {
        padding: 1rem;
        border: none;
        vertical-align: middle;
    }

    /* Badge Modern */
    .modern-badge {
        padding: 0.375rem 0.75rem;
        border-radius: 0.5rem;
        font-size: 0.8rem;
        font-weight: 500;
        color: white;
    }

    .badge-success {
        background: linear-gradient(135deg, #10b981, #34d399);
    }

    .badge-warning {
        background: linear-gradient(135deg, #f59e0b, #fbbf24);
    }

    .badge-danger {
        background: linear-gradient(135deg, #ef4444, #f87171);
    }

    .badge-info {
        background: linear-gradient(135deg, #06b6d4, #22d3ee);
    }

    .badge-secondary {
        background: linear-gradient(135deg, #6b7280, #9ca3af);
    }

    .badge-primary {
        background: linear-gradient(135deg, #3b82f6, #60a5fa);
    }

    /* Konten Audit */
    .audit-date {
        font-weight: 600;
        color: #1e293b;
    }

    .audit-user {
        font-weight: 600;
        color: #1e293b;
    }

    .audit-model {
        font-weight: 600;
        color: #1e293b;
    }

    .audit-description {
        color: #64748b;
        max-width: 400px;
        word-wrap: break-word;
    }

    .description-text {
        font-weight: 500;
        color: #1e293b;
        line-height: 1.5;
        margin-bottom: 0.5rem;
    }

    .changes-summary {
        background: #f8fafc;
        padding: 0.5rem;
        border-radius: 0.5rem;
        border-left: 3px solid #3b82f6;
        font-size: 0.85rem;
        margin-top: 0.5rem;
        color: #64748b;
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .action-buttons .btn-modern {
        padding: 0.375rem 0.75rem;
        font-size: 0.8rem;
    }

    /* State Kosong */
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

    /* Styling Pagination */
    .pagination-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 2rem;
        padding: 1rem 0;
        border-top: 1px solid #e2e8f0;
    }

    .pagination-info {
        color: #64748b;
        font-size: 0.9rem;
    }

    .pagination-links {
        display: flex;
        align-items: center;
    }

    /* Styling pagination custom */
    .pagination-links nav {
        display: flex;
        align-items: center;
    }

    .pagination-links nav span,
    .pagination-links nav a {
        display: inline-flex;
        align-items: center;
        padding: 0.5rem 0.75rem;
        margin: 0 0.125rem;
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        text-decoration: none;
        transition: all 0.3s ease;
        font-size: 0.9rem;
        min-width: 2.5rem;
        justify-content: center;
    }

    .pagination-links nav a {
        color: #64748b;
        background: white;
    }

    .pagination-links nav a:hover {
        background: #f8fafc;
        border-color: #3b82f6;
        color: #3b82f6;
    }

    .pagination-links nav span[aria-current="page"] span {
        background: linear-gradient(135deg, #3b82f6, #60a5fa);
        border-color: #3b82f6;
        color: white;
    }

    .pagination-links nav span[aria-disabled="true"] span {
        background: #f8fafc;
        color: #94a3b8;
        cursor: not-allowed;
    }

    /* Pastikan tinggi baris tabel konsisten */
    .modern-table tbody tr td {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .modern-table tbody tr td:last-child {
        white-space: normal;
        overflow: visible;
        text-overflow: initial;
    }

    /* Desain Responsive */
    @media (max-width: 768px) {
        .audit-body {
            padding: 1rem;
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

        .pagination-container {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }
    }

    @media (max-width: 576px) {
        .modern-table {
            font-size: 0.8rem;
        }

        .modern-table thead th,
        .modern-table tbody td {
            padding: 0.5rem;
        }

        .pagination-links .pagination {
            flex-wrap: wrap;
            justify-content: center;
        }
    }
</style>
@endpush

<!-- Reset Confirmation Modal -->
<div class="modal fade" id="resetModal" tabindex="-1" aria-labelledby="resetModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="resetModalLabel">
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
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Batal
                </button>
                <form action="{{ route('audit-log.reset') }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash-alt"></i> Ya, Reset Audit Log
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
