@extends('layouts.app')

@section('title', 'Detail Bidang - PLN Icon Plus Kantor Management')
@section('page-title', 'Detail Bidang')
@section('page-subtitle', 'Informasi lengkap bidang: ' . $bidang->nama_bidang)

@section('page-actions')
    @php($actor = Auth::guard('admin')->user())
    @if(($actor && $actor->role === 'super_admin') || ($actor && $actor->role === 'manager_bidang' && (int)$actor->bidang_id === (int)$bidang->id))
    <a href="{{ route('bidang.edit', $bidang->id) }}" class="btn btn-modern btn-primary">
        <i class="fas fa-edit"></i> Edit
    </a>
    @endif
    <a href="{{ route('bidang.index') }}" class="btn btn-modern btn-clear">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
@endsection

@section('content')
<div class="container-fluid">
    @php($actor = Auth::guard('admin')->user())

    <div class="row">
        <div class="col-lg-8">
            <!-- Bidang Information -->
            <div class="detail-card">
                <div class="detail-header">
                    <h5 class="detail-title">
                        <i class="fas fa-sitemap"></i>
                        Informasi Bidang
                    </h5>
                </div>
                <div class="detail-body">
                    <div class="detail-grid">
                        <div class="detail-item">
                            <label class="detail-label">Nama Bidang</label>
                            <div class="detail-value">
                                <strong>{{ $bidang->nama_bidang }}</strong>
                            </div>
                        </div>
                        <div class="detail-item full-width">
                            <label class="detail-label">Deskripsi</label>
                            <div class="detail-value">
                                <p style="margin: 0; color: #64748b;">{{ $bidang->deskripsi ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sub Bidang Information -->
            <div class="detail-card">
                <div class="detail-header">
                    <h5 class="detail-title">
                        <i class="fas fa-list"></i>
                        Sub Bidang ({{ $bidang->subBidang->count() }})
                    </h5>
                </div>
                <div class="detail-body">
                    @if($bidang->subBidang->count() > 0)
                        <div class="sub-bidang-list">
                            @foreach($bidang->subBidang as $sub)
                                <div class="sub-bidang-item">
                                    <div class="sub-bidang-header">
                                        <h6 class="sub-bidang-name">{{ $sub->nama_sub_bidang }}</h6>
                                        @if($sub->kode_sub_bidang)
                                        <span class="badge modern-badge badge-info">{{ $sub->kode_sub_bidang }}</span>
                                        @endif
                                    </div>
                                    @if($sub->deskripsi)
                                    <p class="sub-bidang-desc">{{ $sub->deskripsi }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-content">
                            <i class="fas fa-list"></i>
                            <p>Belum ada sub bidang</p>
                        </div>
                    @endif
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
                                        <strong>{{ $bidang->created_at->format('d/m/Y H:i') }}</strong>
                                        <small>{{ $bidang->created_at->diffForHumans() }}</small>
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
                                        <strong>{{ $bidang->updated_at->format('d/m/Y H:i') }}</strong>
                                        <small>{{ $bidang->updated_at->diffForHumans() }}</small>
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
                        @if(($actor && $actor->role === 'super_admin') || ($actor && $actor->role === 'manager_bidang' && (int)$actor->bidang_id === (int)$bidang->id))
                        <a href="{{ route('bidang.edit', $bidang->id) }}" class="btn btn-modern btn-primary">
                            <i class="fas fa-edit"></i> Edit Bidang
                        </a>
                        @endif
                        @if($actor && $actor->role === 'super_admin')
                        <form action="{{ route('bidang.destroy', $bidang->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-modern btn-danger" 
                                    onclick="return confirm('Apakah Anda yakin ingin menghapus bidang ini?')">
                                <i class="fas fa-trash"></i> Hapus Bidang
                            </button>
                        </form>
                        @endif
                        <a href="{{ route('bidang.index') }}" class="btn btn-modern btn-clear">
                            <i class="fas fa-list"></i> Kembali ke Daftar
                        </a>
                    </div>
                </div>
            </div>

            <!-- Statistics Card -->
            <div class="detail-card">
                <div class="detail-header">
                    <h5 class="detail-title">
                        <i class="fas fa-chart-bar"></i>
                        Statistik Bidang
                    </h5>
                </div>
                <div class="detail-body">
                    <div class="stat-item highlight">
                        <div class="stat-icon" style="background: var(--pln-blue-lighter);">
                            <i class="fas fa-list" style="color: var(--pln-blue);"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-label">Total Sub Bidang</div>
                            <div class="stat-value" style="color: var(--pln-blue);">
                                {{ $bidang->subBidang->count() }}
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

    /* Sub Bidang List */
    .sub-bidang-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .sub-bidang-item {
        padding: 1rem;
        background: #fafbfc;
        border-radius: 8px;
        border: 1px solid rgba(33, 97, 140, 0.1);
    }

    .sub-bidang-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.5rem;
    }

    .sub-bidang-name {
        font-size: 0.9375rem;
        font-weight: 700;
        color: var(--pln-blue);
        margin: 0;
    }

    .sub-bidang-desc {
        font-size: 0.875rem;
        color: var(--text-gray);
        margin: 0;
        line-height: 1.5;
    }

    /* Modern Badges */
    .badge.modern-badge {
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.8125rem;
        letter-spacing: 0.3px;
    }

    .badge.badge-info {
        background: var(--pln-blue-light);
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

    /* Statistics */
    .stat-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        background: #fafbfc;
        border-radius: 10px;
        margin-bottom: 0.75rem;
        transition: all 0.2s ease;
    }

    .stat-item:last-child {
        margin-bottom: 0;
    }

    .stat-item:hover {
        background: #f1f5f9;
        transform: translateX(4px);
    }

    .stat-item.highlight {
        background: white;
        border: 2px solid rgba(33, 97, 140, 0.1);
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .stat-icon i {
        font-size: 1.25rem;
    }

    .stat-content {
        flex: 1;
    }

    .stat-label {
        font-size: 0.8rem;
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.25rem;
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--pln-blue);
    }

    /* Empty State */
    .empty-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1rem;
        padding: 2rem;
    }

    .empty-content i {
        font-size: 3rem;
        color: var(--pln-blue-lighter);
    }

    .empty-content p {
        margin: 0;
        color: var(--text-gray);
        font-size: 0.9375rem;
        font-weight: 500;
    }

    /* Responsive */
    @media (max-width: 991px) {
        .detail-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush
@endsection
