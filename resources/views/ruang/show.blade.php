@extends('layouts.app')

@section('title', 'Detail Ruang - PLN Icon Plus Kantor Management')
@section('page-title', 'Detail Ruang')
@section('page-subtitle', 'Informasi lengkap ruang: ' . $ruang->nama_ruang)

@section('page-actions')
    @php($actor = Auth::guard('admin')->user())
    @php($rowKantorId = $ruang->lantai->gedung->kantor->id ?? $ruang->lantai->gedung->kantor_id ?? null)
    @php($rowBidangId = $ruang->bidang_id ?? null)
    @if(($actor && $actor->role === 'super_admin') || ($actor && in_array($actor->role, ['admin_regional','staf']) && (int)$actor->kantor_id === (int)$rowKantorId && (int)$actor->bidang_id === (int)$rowBidangId))
    <a href="{{ route('ruang.edit', $ruang->id) }}" class="btn btn-modern btn-primary">
        <i class="fas fa-edit"></i> Edit
    </a>
    @endif
    <a href="{{ route('ruang.index') }}" class="btn btn-modern btn-clear">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
@endsection

@section('content')
<div class="container-fluid">
    @php($actor = Auth::guard('admin')->user())

    <div class="row">
        <div class="col-lg-8">
            <!-- Ruang Information -->
            <div class="detail-card">
                <div class="detail-header">
                    <h5 class="detail-title">
                        <i class="fas fa-door-open"></i>
                        Informasi Ruang
                    </h5>
                </div>
                <div class="detail-body">
                    <div class="detail-grid">
                        <div class="detail-item">
                            <label class="detail-label">Nama Ruang</label>
                            <div class="detail-value">
                                <strong>{{ $ruang->nama_ruang }}</strong>
                            </div>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Kapasitas</label>
                            <div class="detail-value">
                                <span class="badge modern-badge badge-kapasitas">{{ $ruang->kapasitas }} orang</span>
                            </div>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Status Ruang</label>
                            <div class="detail-value">
                                <span class="badge modern-badge {{ $ruang->status_ruang == 'tersedia' ? 'badge-success' : ($ruang->status_ruang == 'terisi' ? 'badge-warning' : 'badge-danger') }}">
                                    {{ ucfirst($ruang->status_ruang) }}
                                </span>
                            </div>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Lantai</label>
                            <div class="detail-value">
                                <span class="badge modern-badge badge-lantai">
                                    {{ $ruang->lantai->nama_lantai ?? 'N/A' }} (Lantai {{ $ruang->lantai->nomor_lantai ?? 'N/A' }})
                                </span>
                            </div>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Gedung</label>
                            <div class="detail-value">
                                <span class="badge modern-badge badge-gedung">{{ $ruang->lantai->gedung->nama_gedung ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Kantor</label>
                            <div class="detail-value">
                                @if($ruang->lantai && $ruang->lantai->gedung && $ruang->lantai->gedung->kantor)
                                    <span class="badge modern-badge badge-kantor">{{ $ruang->lantai->gedung->kantor->kode_kantor }}</span>
                                    <div class="mt-1">
                                        <small>{{ $ruang->lantai->gedung->kantor->nama_kantor }}</small>
                                    </div>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </div>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Bidang</label>
                            <div class="detail-value">
                                <span class="badge modern-badge badge-bidang">{{ $ruang->bidang->nama_bidang ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Sub Bidang</label>
                            <div class="detail-value">
                                @if($ruang->subBidang)
                                    <span class="badge modern-badge badge-bidang">{{ $ruang->subBidang->nama_sub_bidang }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </div>
                        </div>
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
                                        <strong>{{ $ruang->created_at->format('d/m/Y H:i') }}</strong>
                                        <small>{{ $ruang->created_at->diffForHumans() }}</small>
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
                                        <strong>{{ $ruang->updated_at->format('d/m/Y H:i') }}</strong>
                                        <small>{{ $ruang->updated_at->diffForHumans() }}</small>
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
                        @php($rowKantorId = $ruang->lantai->gedung->kantor->id ?? $ruang->lantai->gedung->kantor_id ?? null)
                        @php($rowBidangId = $ruang->bidang_id ?? null)
                        @if(($actor && $actor->role === 'super_admin') || ($actor && in_array($actor->role, ['admin_regional','staf']) && (int)$actor->kantor_id === (int)$rowKantorId && (int)$actor->bidang_id === (int)$rowBidangId))
                        <a href="{{ route('ruang.edit', $ruang->id) }}" class="btn btn-modern btn-primary">
                            <i class="fas fa-edit"></i> Edit Ruang
                        </a>
                        @endif
                        @if($actor && $actor->role === 'super_admin')
                        <form action="{{ route('ruang.destroy', $ruang->id) }}" method="POST"
                              onsubmit="return confirm('Yakin ingin menghapus ruang ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-modern btn-danger w-100">
                                <i class="fas fa-trash"></i> Hapus Ruang
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Related Information -->
            @if($ruang->lantai)
            <div class="detail-card">
                <div class="detail-header">
                    <h5 class="detail-title">
                        <i class="fas fa-layer-group"></i>
                        Informasi Lantai
                    </h5>
                </div>
                <div class="detail-body">
                    <div class="related-info">
                        <div class="related-item">
                            <label class="related-label">Nama Lantai</label>
                            <div class="related-value">
                                <a href="{{ route('lantai.show', $ruang->lantai->id) }}" class="related-link">
                                    {{ $ruang->lantai->nama_lantai }}
                                </a>
                            </div>
                        </div>
                        <div class="related-item">
                            <label class="related-label">Nomor Lantai</label>
                            <div class="related-value">
                                <span class="badge modern-badge badge-lantai">{{ $ruang->lantai->nomor_lantai }}</span>
                            </div>
                        </div>
                        <div class="related-item">
                            <label class="related-label">Gedung</label>
                            <div class="related-value">
                                @if($ruang->lantai->gedung)
                                    <a href="{{ route('gedung.show', $ruang->lantai->gedung->id) }}" class="related-link">
                                        {{ $ruang->lantai->gedung->nama_gedung }}
                                    </a>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

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

    /* Badges */
    .badge.modern-badge {
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.8125rem;
        letter-spacing: 0.3px;
    }

    .badge.badge-lantai {
        background: var(--pln-blue-lighter);
        color: var(--pln-blue);
    }

    .badge.badge-gedung {
        background: var(--pln-blue-lighter);
        color: var(--pln-blue);
    }

    .badge.badge-bidang {
        background: var(--pln-blue-lighter);
        color: var(--pln-blue);
    }

    .badge.badge-kantor {
        background: var(--pln-blue-lighter);
        color: var(--pln-blue);
    }

    .badge.badge-kapasitas {
        background: #fff3cd;
        color: #856404;
    }

    .badge.badge-success {
        background: #d4edda;
        color: #155724;
    }

    .badge.badge-warning {
        background: #fff3cd;
        color: #856404;
    }

    .badge.badge-danger {
        background: #f8d7da;
        color: #721c24;
    }

    /* Detail Time */
    .detail-time {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
    }

    .detail-time i {
        color: var(--pln-blue);
        margin-top: 0.25rem;
        flex-shrink: 0;
    }

    .detail-time strong {
        display: block;
        color: var(--text-dark);
        margin-bottom: 0.25rem;
    }

    .detail-time small {
        display: block;
        color: var(--text-gray);
        font-size: 0.8rem;
    }

    /* Action Buttons Vertical */
    .action-buttons-vertical {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    /* Buttons */
    .btn-modern {
        border-radius: 10px;
        padding: 0.625rem 1.25rem;
        font-weight: 600;
        transition: all 0.2s ease;
        border: 1px solid transparent;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
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

    /* Related Information */
    .related-info {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .related-item {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .related-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .related-value {
        font-size: 0.95rem;
        color: #1e293b;
        font-weight: 500;
    }

    .related-link {
        color: var(--pln-blue);
        text-decoration: none;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .related-link:hover {
        color: var(--pln-blue-dark);
        text-decoration: underline;
    }

    /* Responsive */
    @media (max-width: 991px) {
        .detail-grid {
            grid-template-columns: 1fr;
        }

        .detail-card {
            margin-bottom: 1rem;
        }
    }
</style>
@endpush
