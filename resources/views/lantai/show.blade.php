@extends('layouts.app')

@section('title', 'Detail Lantai - PLN Icon Plus Kantor Management')
@section('page-title', 'Detail Lantai')
@section('page-subtitle', 'Informasi lengkap lantai: ' . $lantai->nama_lantai)

@section('page-actions')
    @php($actor = Auth::guard('admin')->user())
    @if(($actor && $actor->role === 'super_admin') || ($actor && in_array($actor->role, ['admin_regional','staf']) && (int)($lantai->gedung->kantor_id ?? 0) === (int)$actor->kantor_id))
    <a href="{{ route('lantai.edit', $lantai->id) }}" class="btn btn-modern btn-primary">
        <i class="fas fa-edit"></i> Edit
    </a>
    @endif
    <a href="{{ route('lantai.index') }}" class="btn btn-modern btn-clear">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
@endsection

@section('content')
<div class="container-fluid">
    @php($actor = Auth::guard('admin')->user())

    <div class="row">
        <div class="col-lg-8">
            <!-- Lantai Information -->
            <div class="detail-card">
                <div class="detail-header">
                    <h5 class="detail-title">
                        <i class="fas fa-layer-group"></i>
                        Informasi Lantai
                    </h5>
                </div>
                <div class="detail-body">
                    <div class="detail-grid">
                        <div class="detail-item">
                            <label class="detail-label">Nama Lantai</label>
                            <div class="detail-value">
                                <strong>{{ $lantai->nama_lantai }}</strong>
                            </div>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Nomor Lantai</label>
                            <div class="detail-value">
                                <span class="badge modern-badge badge-lantai">{{ $lantai->nomor_lantai }}</span>
                            </div>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Gedung</label>
                            <div class="detail-value">
                                <span class="badge modern-badge badge-gedung">{{ $lantai->gedung->nama_gedung ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Kantor</label>
                            <div class="detail-value">{{ $lantai->gedung->kantor->nama_kantor ?? 'N/A' }}</div>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Kota</label>
                            <div class="detail-value">{{ $lantai->gedung->kantor->kota->nama_kota ?? 'N/A' }}</div>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Provinsi</label>
                            <div class="detail-value">{{ $lantai->gedung->kantor->kota->provinsi->nama_provinsi ?? 'N/A' }}</div>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Total Ruangan</label>
                            <div class="detail-value">
                                <span class="badge modern-badge badge-success">
                                    {{ $lantai->ruang->count() }} Ruang
                                </span>
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
                                        <strong>{{ $lantai->created_at->format('d/m/Y H:i') }}</strong>
                                        <small>{{ $lantai->created_at->diffForHumans() }}</small>
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
                                        <strong>{{ $lantai->updated_at->format('d/m/Y H:i') }}</strong>
                                        <small>{{ $lantai->updated_at->diffForHumans() }}</small>
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
                        @if(($actor && $actor->role === 'super_admin') || ($actor && in_array($actor->role, ['admin_regional','staf']) && (int)($lantai->gedung->kantor_id ?? 0) === (int)$actor->kantor_id))
                        <a href="{{ route('lantai.edit', $lantai->id) }}" class="btn btn-modern btn-primary">
                            <i class="fas fa-edit"></i> Edit Lantai
                        </a>
                        @endif
                        @if($actor && $actor->role === 'super_admin')
                        <form action="{{ route('lantai.destroy', $lantai->id) }}" method="POST"
                              onsubmit="return confirm('Yakin ingin menghapus lantai ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-modern btn-danger w-100">
                                <i class="fas fa-trash"></i> Hapus Lantai
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Related Information -->
            @if($lantai->ruang->count() > 0)
            <div class="detail-card">
                <div class="detail-header">
                    <h5 class="detail-title">
                        <i class="fas fa-door-open"></i>
                        Ruangan
                    </h5>
                </div>
                <div class="detail-body">
                    <div class="ruang-list">
                        @foreach($lantai->ruang as $ruang)
                        <div class="ruang-item">
                            <div class="ruang-info">
                                <strong>{{ $ruang->nama_ruang }}</strong>
                                <small>{{ $ruang->kode_ruang ?? 'N/A' }}</small>
                            </div>
                            <a href="{{ route('ruang.show', $ruang->id) }}" class="btn btn-modern btn-info btn-sm">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

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
        margin-bottom: 1.5rem;
    }

    .detail-item {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .detail-label {
        font-size: 0.75rem;
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

    .badge.badge-success {
        background: #d4edda;
        color: #155724;
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

    .btn-modern.btn-sm {
        padding: 0.4rem 0.65rem;
        font-size: 0.75rem;
        min-width: 2.25rem;
        height: 2.25rem;
    }

    /* Ruang List */
    .ruang-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .ruang-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.875rem;
        background: var(--pln-blue-bg);
        border-radius: 8px;
        border: 1px solid var(--gray-border);
        transition: all 0.2s ease;
    }

    .ruang-item:hover {
        background: var(--pln-blue-lighter);
        border-color: var(--pln-blue);
        transform: translateX(4px);
    }

    .ruang-info {
        flex: 1;
    }

    .ruang-info strong {
        display: block;
        color: var(--pln-blue);
        font-size: 0.9375rem;
        margin-bottom: 0.25rem;
    }

    .ruang-info small {
        display: block;
        color: var(--text-gray);
        font-size: 0.8rem;
    }

    /* Responsive */
    @media (max-width: 991px) {
        .detail-grid {
            grid-template-columns: 1fr;
            gap: 1.25rem;
        }

        .detail-body {
            padding: 1.25rem;
        }
    }
</style>
@endsection
