@extends('layouts.app')

@section('title', 'Detail Audit Log')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Detail Audit Log</h2>
            <p class="text-muted mb-0">Informasi lengkap aktivitas: {{ $auditLog->description }}</p>
        </div>
        <a href="{{ route('audit-log.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Basic Information -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>Informasi Dasar
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Tanggal & Waktu</label>
                                <p class="h6 mb-0">{{ $auditLog->created_at->format('d/m/Y H:i:s') }}</p>
                                <small class="text-muted">{{ $auditLog->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">User</label>
                                <p class="h6 mb-0">{{ $auditLog->user_name ?? 'System' }}</p>
                                @if($auditLog->user_id)
                                    <small class="text-muted">ID: {{ $auditLog->user_id }}</small>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Aksi</label>
                                <p class="mb-0">
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
                                    <span class="badge bg-{{ $color }} fs-6">{{ $auditLog->formatted_action }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Model</label>
                                <p class="mb-0">
                                    @if($auditLog->model_type)
                                        <strong>{{ $auditLog->formatted_model }}</strong>
                                        @if($auditLog->model_name)
                                            <br>
                                            <small class="text-muted">{{ $auditLog->model_name }}</small>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">IP Address</label>
                                <p class="mb-0">{{ $auditLog->ip_address }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">URL</label>
                                <p class="mb-0">
                                    <a href="{{ $auditLog->url }}" target="_blank" class="text-decoration-none">
                                        {{ $auditLog->url }}
                                        <i class="fas fa-external-link-alt ms-1"></i>
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">Deskripsi</label>
                        <p class="mb-0">{{ $auditLog->description }}</p>
                    </div>
                </div>
            </div>

            <!-- Changes Information -->
            @if($auditLog->old_values || $auditLog->new_values)
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-exchange-alt me-2"></i>Perubahan Data
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($auditLog->changes_summary)
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Ringkasan Perubahan:</strong> {{ $auditLog->changes_summary }}
                            </div>
                        @endif

                        <div class="row">
                            @if($auditLog->old_values)
                                <div class="col-md-6">
                                    <h6 class="text-danger">Nilai Sebelum</h6>
                                    <div class="bg-light p-3 rounded">
                                        <pre class="mb-0 small">{{ json_encode($auditLog->old_values, JSON_PRETTY_PRINT) }}</pre>
                                    </div>
                                </div>
                            @endif
                            @if($auditLog->new_values)
                                <div class="col-md-6">
                                    <h6 class="text-success">Nilai Sesudah</h6>
                                    <div class="bg-light p-3 rounded">
                                        <pre class="mb-0 small">{{ json_encode($auditLog->new_values, JSON_PRETTY_PRINT) }}</pre>
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
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-cog me-2"></i>Informasi Teknis
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label text-muted">User Agent</label>
                        <p class="mb-0 small">{{ $auditLog->user_agent }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">Model Type</label>
                        <p class="mb-0">{{ $auditLog->model_type ?? '-' }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">Model ID</label>
                        <p class="mb-0">{{ $auditLog->model_id ?? '-' }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">Changed Fields</label>
                        @if($auditLog->changed_fields)
                            <div class="d-flex flex-wrap gap-1">
                                @foreach($auditLog->changed_fields as $field)
                                    <span class="badge bg-warning">{{ $field }}</span>
                                @endforeach
                            </div>
                        @else
                            <p class="mb-0 text-muted">-</p>
                        @endif
                    </div>
                    @if($auditLog->metadata)
                        <div class="mb-3">
                            <label class="form-label text-muted">Metadata</label>
                            <div class="bg-light p-3 rounded">
                                <pre class="mb-0 small">{{ json_encode($auditLog->metadata, JSON_PRETTY_PRINT) }}</pre>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Related Logs -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-link me-2"></i>Log Terkait
                    </h5>
                </div>
                <div class="card-body">
                    @if($auditLog->model_type && $auditLog->model_id)
                        <p class="small text-muted mb-2">Log lain untuk model ini:</p>
                        <a href="{{ route('audit-log.index', ['model_type' => $auditLog->model_type, 'model_id' => $auditLog->model_id]) }}" 
                           class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-filter me-1"></i>Lihat Semua
                        </a>
                    @else
                        <p class="text-muted small">Tidak ada log terkait</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


