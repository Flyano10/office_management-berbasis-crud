@extends('layouts.app')

@section('title', 'Detail Audit Log - PLN Icon Plus Kantor Management')
@section('page-title', 'Detail Audit Log')
@section('page-subtitle', 'Informasi lengkap aktivitas: ' . Str::limit($auditLog->description, 50))

@section('page-actions')
    <a href="{{ route('audit-log.index') }}" class="btn btn-modern btn-clear">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <!-- Basic Information -->
            <div class="detail-card">
                <div class="detail-header">
                    <h5 class="detail-title">
                        <i class="fas fa-info-circle"></i>
                        Informasi Dasar
                    </h5>
                </div>
                <div class="detail-body">
                    <div class="detail-grid">
                        <div class="detail-item">
                            <label class="detail-label">Tanggal & Waktu</label>
                            <div class="detail-value">
                                <div class="detail-time">
                                    <i class="fas fa-calendar"></i>
                                    <div>
                                        <strong>{{ $auditLog->created_at->format('d/m/Y H:i:s') }}</strong>
                                        <small>{{ $auditLog->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">User</label>
                            <div class="detail-value">
                                <strong>{{ $auditLog->user_name ?? 'System' }}</strong>
                                @if($auditLog->user_id)
                                    <br>
                                    <small class="text-muted">ID: {{ $auditLog->user_id }}</small>
                                @endif
                            </div>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Aksi</label>
                            <div class="detail-value">
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
                                    $color = $actionColors[$auditLog->action] ?? 'secondary';
                                @endphp
                                <span class="badge modern-badge badge-{{ $color }}">{{ $auditLog->formatted_action }}</span>
                            </div>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Model</label>
                            <div class="detail-value">
                                @if($auditLog->model_type)
                                    <span class="badge modern-badge badge-info">{{ $auditLog->formatted_model }}</span>
                                    @if($auditLog->model_name)
                                        <br>
                                        <small class="text-muted">{{ $auditLog->model_name }}</small>
                                    @endif
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </div>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">IP Address</label>
                            <div class="detail-value">
                                <strong>{{ $auditLog->ip_address }}</strong>
                            </div>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">URL</label>
                            <div class="detail-value">
                                <a href="{{ $auditLog->url }}" target="_blank" style="color: var(--pln-blue); text-decoration: none;">
                                    {{ Str::limit($auditLog->url, 50) }}
                                    <i class="fas fa-external-link-alt"></i>
                                </a>
                            </div>
                        </div>
                        <div class="detail-item full-width">
                            <label class="detail-label">Deskripsi</label>
                            <div class="detail-value">
                                <p style="margin: 0; color: var(--text-dark);">{{ $auditLog->description }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Changes Information -->
            @if($auditLog->old_values || $auditLog->new_values)
            <div class="detail-card">
                <div class="detail-header">
                    <h5 class="detail-title">
                        <i class="fas fa-exchange-alt"></i>
                        Perubahan Data
                    </h5>
                </div>
                <div class="detail-body">
                    @if($auditLog->changes_summary)
                        <div class="alert alert-info" style="background: var(--pln-blue-lighter); border-left: 4px solid var(--pln-blue); color: var(--pln-blue-dark); padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
                            <i class="fas fa-info-circle"></i>
                            <strong>Ringkasan Perubahan:</strong> {{ $auditLog->changes_summary }}
                        </div>
                    @endif

                    <div class="row g-3">
                        @if($auditLog->old_values)
                        <div class="col-md-6">
                            <div class="changes-box changes-old">
                                <h6 style="color: #dc3545; font-weight: 700; margin-bottom: 0.75rem;">
                                    <i class="fas fa-arrow-left"></i> Nilai Sebelum
                                </h6>
                                <div class="changes-content">
                                    <pre>{{ json_encode($auditLog->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if($auditLog->new_values)
                        <div class="col-md-6">
                            <div class="changes-box changes-new">
                                <h6 style="color: #28a745; font-weight: 700; margin-bottom: 0.75rem;">
                                    <i class="fas fa-arrow-right"></i> Nilai Sesudah
                                </h6>
                                <div class="changes-content">
                                    <pre>{{ json_encode($auditLog->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>
        <div class="col-lg-4">
            <!-- Technical Information -->
            <div class="detail-card">
                <div class="detail-header">
                    <h5 class="detail-title">
                        <i class="fas fa-cog"></i>
                        Informasi Teknis
                    </h5>
                </div>
                <div class="detail-body">
                    <div class="related-info-item">
                        <label class="detail-label">User Agent</label>
                        <div class="detail-value">
                            <small style="color: var(--text-gray); word-break: break-all;">{{ $auditLog->user_agent }}</small>
                        </div>
                    </div>
                    <div class="related-info-item" style="margin-top: 1rem;">
                        <label class="detail-label">Model Type</label>
                        <div class="detail-value">
                            <span class="badge modern-badge badge-secondary">{{ $auditLog->model_type ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="related-info-item" style="margin-top: 1rem;">
                        <label class="detail-label">Model ID</label>
                        <div class="detail-value">
                            <strong>{{ $auditLog->model_id ?? '-' }}</strong>
                        </div>
                    </div>
                    @if($auditLog->changed_fields)
                    <div class="related-info-item" style="margin-top: 1rem;">
                        <label class="detail-label">Changed Fields</label>
                        <div class="detail-value">
                            <div class="d-flex flex-wrap gap-1">
                                @foreach($auditLog->changed_fields as $field)
                                    <span class="badge modern-badge badge-warning">{{ $field }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                    @if($auditLog->metadata)
                    <div class="related-info-item" style="margin-top: 1rem;">
                        <label class="detail-label">Metadata</label>
                        <div class="detail-value">
                            <div class="changes-content">
                                <pre style="font-size: 0.75rem;">{{ json_encode($auditLog->metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Related Logs -->
            <div class="detail-card">
                <div class="detail-header">
                    <h5 class="detail-title">
                        <i class="fas fa-link"></i>
                        Log Terkait
                    </h5>
                </div>
                <div class="detail-body">
                    @if($auditLog->model_type && $auditLog->model_id)
                        <p style="color: var(--text-gray); font-size: 0.875rem; margin-bottom: 1rem;">
                            Log lain untuk model ini:
                        </p>
                        <a href="{{ route('audit-log.index', ['model_type' => $auditLog->model_type, 'model_id' => $auditLog->model_id]) }}" 
                           class="btn btn-modern btn-primary" style="width: 100%; justify-content: center;">
                            <i class="fas fa-filter"></i> Lihat Semua
                        </a>
                    @else
                        <p style="color: var(--text-gray); font-size: 0.875rem; margin: 0;">Tidak ada log terkait</p>
                    @endif
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

    /* Detail Card */
    .detail-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(33, 97, 140, 0.1);
        border: 1px solid rgba(33, 97, 140, 0.15);
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .detail-header {
        background: white;
        padding: 1.25rem 1.75rem;
        border-bottom: 2px solid var(--pln-blue);
    }

    .detail-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--pln-blue);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .detail-title i {
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

    .detail-body {
        padding: 1.75rem;
    }

    /* Detail Grid */
    .detail-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
    }

    .detail-item {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .detail-item.full-width {
        grid-column: 1 / -1;
    }

    .detail-label {
        font-size: 0.8125rem;
        font-weight: 600;
        color: var(--text-gray);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .detail-value {
        font-size: 0.9375rem;
        color: var(--text-dark);
        font-weight: 500;
    }

    .detail-value strong {
        color: var(--pln-blue);
        font-weight: 700;
    }

    /* Detail Time */
    .detail-time {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .detail-time i {
        color: var(--pln-blue);
        font-size: 1.1rem;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--pln-blue-lighter);
        border-radius: 8px;
    }

    .detail-time strong {
        display: block;
        color: #1e293b;
        font-weight: 700;
        font-size: 0.95rem;
    }

    .detail-time small {
        display: block;
        color: #64748b;
        font-size: 0.8rem;
        margin-top: 0.25rem;
    }

    /* Related Info */
    .related-info-item {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
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

    /* Changes Box */
    .changes-box {
        background: var(--pln-blue-bg);
        border-radius: 10px;
        padding: 1.25rem;
        border: 2px solid transparent;
    }

    .changes-old {
        border-left: 4px solid #dc3545;
    }

    .changes-new {
        border-left: 4px solid #28a745;
    }

    .changes-content {
        background: white;
        border-radius: 8px;
        padding: 1rem;
        overflow-x: auto;
    }

    .changes-content pre {
        margin: 0;
        font-size: 0.8125rem;
        line-height: 1.6;
        color: var(--text-dark);
        white-space: pre-wrap;
        word-wrap: break-word;
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

    /* Responsive */
    @media (max-width: 991px) {
        .detail-grid {
            grid-template-columns: 1fr;
        }

        .detail-item.full-width {
            grid-column: 1 / -1;
        }
    }
</style>
@endpush
@endsection
