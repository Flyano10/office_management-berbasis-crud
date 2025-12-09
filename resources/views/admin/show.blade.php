@extends('layouts.app')

@section('title', 'Detail Admin - PLN Icon Plus Kantor Management')
@section('page-title', 'Detail Admin')
@section('page-subtitle', 'Informasi lengkap admin: ' . $admin->nama_admin)

@section('page-actions')
    @php($actor = Auth::guard('admin')->user())
    @if(($actor && in_array($actor->role, ['super_admin','admin'])) || ($actor && (int)$actor->id === (int)$admin->id))
    <a href="{{ route('admin.edit', $admin->id) }}" class="btn btn-modern btn-primary">
        <i class="fas fa-edit"></i> Edit
    </a>
    @endif
    <a href="{{ route('admin.index') }}" class="btn btn-modern btn-clear">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <!-- Admin Information -->
            <div class="detail-card">
                <div class="detail-header">
                    <h5 class="detail-title">
                        <i class="fas fa-user"></i>
                        Informasi Admin
                    </h5>
                </div>
                <div class="detail-body">
                    <div class="detail-grid">
                        <div class="detail-item">
                            <label class="detail-label">ID Admin</label>
                            <div class="detail-value">
                                <span class="badge modern-badge badge-primary">#{{ $admin->id }}</span>
                            </div>
                        </div>
                        <div class="detail-item full-width">
                            <label class="detail-label">Nama Admin</label>
                            <div class="detail-value">
                                <strong>{{ $admin->nama_admin }}</strong>
                            </div>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Email</label>
                            <div class="detail-value">
                                <strong>{{ $admin->email }}</strong>
                            </div>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Username</label>
                            <div class="detail-value">
                                <span class="badge modern-badge badge-primary">{{ $admin->username }}</span>
                            </div>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Role</label>
                            <div class="detail-value">
                                <span class="badge modern-badge badge-info">{{ ucwords(str_replace('_', ' ', $admin->role)) }}</span>
                            </div>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Status</label>
                            <div class="detail-value">
                                <span class="badge modern-badge badge-{{ $admin->is_active ? 'success' : 'secondary' }}">
                                    {{ $admin->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                </span>
                            </div>
                        </div>
                        @if($admin->bidang)
                        <div class="detail-item">
                            <label class="detail-label">Bidang</label>
                            <div class="detail-value">
                                <strong>{{ $admin->bidang->nama_bidang }}</strong>
                            </div>
                        </div>
                        @endif
                        @if($admin->kantor)
                        <div class="detail-item">
                            <label class="detail-label">Kantor</label>
                            <div class="detail-value">
                                <strong>{{ $admin->kantor->nama_kantor }}</strong>
                            </div>
                        </div>
                        @endif
                        @if($admin->region)
                        <div class="detail-item">
                            <label class="detail-label">Region</label>
                            <div class="detail-value">
                                <strong>{{ $admin->region->nama_region ?? '-' }}</strong>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Activity Information -->
            <div class="detail-card">
                <div class="detail-header">
                    <h5 class="detail-title">
                        <i class="fas fa-clock"></i>
                        Informasi Aktivitas
                    </h5>
                </div>
                <div class="detail-body">
                    <div class="detail-grid">
                        <div class="detail-item">
                            <label class="detail-label">Dibuat</label>
                            <div class="detail-value">
                                <div class="detail-time">
                                    <i class="fas fa-calendar-plus"></i>
                                    <div>
                                        <strong>{{ $admin->created_at->format('d/m/Y H:i') }}</strong>
                                        <small>{{ $admin->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Terakhir Diupdate</label>
                            <div class="detail-value">
                                <div class="detail-time">
                                    <i class="fas fa-calendar-check"></i>
                                    <div>
                                        <strong>{{ $admin->updated_at->format('d/m/Y H:i') }}</strong>
                                        <small>{{ $admin->updated_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Login Terakhir</label>
                            <div class="detail-value">
                                <div class="detail-time">
                                    <i class="fas fa-sign-in-alt"></i>
                                    <div>
                                        @if($admin->last_login)
                                            <strong>{{ $admin->last_login->format('d/m/Y H:i') }}</strong>
                                            <small>{{ $admin->last_login->diffForHumans() }}</small>
                                        @else
                                            <strong style="color: #64748b;">Belum pernah login</strong>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Durasi Akun</label>
                            <div class="detail-value">
                                <div class="detail-time">
                                    <i class="fas fa-calendar-alt"></i>
                                    <div>
                                        <strong>{{ $admin->created_at->diffInDays(now()) }} hari</strong>
                                        <small>Sejak akun dibuat</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="detail-card">
                <div class="detail-header">
                    <h5 class="detail-title">
                        <i class="fas fa-bolt"></i>
                        Aksi Cepat
                    </h5>
                </div>
                <div class="detail-body">
                    <div class="action-buttons-vertical">
                        @if(($actor && in_array($actor->role, ['super_admin','admin'])) || ($actor && (int)$actor->id === (int)$admin->id))
                        <a href="{{ route('admin.edit', $admin->id) }}" class="btn btn-modern btn-primary">
                            <i class="fas fa-edit"></i> Edit Admin
                        </a>
                        @endif
                        @php(
                            $inScopeRegional = ($actor && $actor->role === 'admin_regional' && in_array($admin->role, ['manager_bidang','staf']) && (int)$admin->kantor_id === (int)($actor->kantor_id ?? 0))
                        )
                        @php(
                            $inScopeMB = ($actor && $actor->role === 'manager_bidang' && $admin->role === 'staf' && (int)$admin->kantor_id === (int)($actor->kantor_id ?? 0) && (int)$admin->bidang_id === (int)($actor->bidang_id ?? 0))
                        )
                        @if($admin->role !== 'super_admin' && (($actor && $actor->role === 'super_admin') || ($actor && $actor->role === 'admin_regional' && $inScopeRegional) || ($actor && $actor->role === 'manager_bidang' && $inScopeMB)))
                        <form action="{{ route('admin.toggle-status', $admin->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-modern btn-{{ $admin->is_active ? 'warning' : 'success' }}"
                                    onclick="return confirm('Apakah Anda yakin ingin {{ $admin->is_active ? 'menonaktifkan' : 'mengaktifkan' }} admin ini?')">
                                <i class="fas {{ $admin->is_active ? 'fa-ban' : 'fa-check' }}"></i> 
                                {{ $admin->is_active ? 'Nonaktifkan' : 'Aktifkan' }} Admin
                            </button>
                        </form>
                        <form action="{{ route('admin.destroy', $admin->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-modern btn-danger"
                                    onclick="return confirm('Apakah Anda yakin ingin menghapus admin ini?')">
                                <i class="fas fa-trash"></i> Hapus Admin
                            </button>
                        </form>
                        @endif
                        <a href="{{ route('admin.index') }}" class="btn btn-modern btn-clear">
                            <i class="fas fa-list"></i> Kembali ke Daftar
                        </a>
                    </div>
                </div>
            </div>

            <!-- Role Information -->
            <div class="detail-card">
                <div class="detail-header">
                    <h5 class="detail-title">
                        <i class="fas fa-shield-alt"></i>
                        Informasi Role
                    </h5>
                </div>
                <div class="detail-body">
                    <div class="related-info-item">
                        <label class="detail-label">Role</label>
                        <div class="detail-value">
                            <span class="badge modern-badge badge-info" style="font-size: 0.9375rem; padding: 0.5rem 1rem;">
                                {{ ucwords(str_replace('_', ' ', $admin->role)) }}
                            </span>
                        </div>
                    </div>
                    <div class="related-info-item" style="margin-top: 1rem;">
                        <p style="color: #64748b; font-size: 0.875rem; margin: 0;">
                            Role menentukan hak akses dan wewenang admin dalam sistem.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Security Information -->
            <div class="detail-card">
                <div class="detail-header">
                    <h5 class="detail-title">
                        <i class="fas fa-lock"></i>
                        Informasi Keamanan
                    </h5>
                </div>
                <div class="detail-body">
                    <div class="related-info-item">
                        <label class="detail-label">Password</label>
                        <div class="detail-value">
                            <span class="badge modern-badge badge-secondary">Terenskripsi</span>
                            <small style="display: block; color: #64748b; margin-top: 0.25rem;">Password dienkripsi menggunakan Hash</small>
                        </div>
                    </div>
                    <div class="related-info-item" style="margin-top: 1rem;">
                        <label class="detail-label">Status Akun</label>
                        <div class="detail-value">
                            <span class="badge modern-badge badge-{{ $admin->is_active ? 'success' : 'secondary' }}">
                                {{ $admin->is_active ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
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

    /* Detail Card - Modern Design */
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

    /* Detail Grid - 2 Columns */
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

    .btn-modern.btn-warning {
        background: #ffc107;
        color: #1e293b;
        border: 1px solid #ffc107;
    }

    .btn-modern.btn-warning:hover {
        background: #e0a800;
        border-color: #e0a800;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(255, 193, 7, 0.25);
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
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
    }

    /* Action Buttons Vertical */
    .action-buttons-vertical {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .action-buttons-vertical .btn-modern {
        width: 100%;
        justify-content: center;
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
