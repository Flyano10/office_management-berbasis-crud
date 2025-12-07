@extends('layouts.app')

@section('title', 'Detail Okupansi - PLN Icon Plus Kantor Management')
@section('page-title', 'Detail Okupansi')
@section('page-subtitle', 'Informasi lengkap okupansi ruang: ' . $okupansi->ruang->nama_ruang ?? 'N/A')

@section('page-actions')
    @php($actor = Auth::guard('admin')->user())
    @php($rowKantorId = $okupansi->ruang->lantai->gedung->kantor->id ?? $okupansi->ruang->lantai->gedung->kantor_id ?? null)
    @php($rowBidangId = $okupansi->bidang_id ?? null)
    @if(($actor && $actor->role === 'super_admin') || ($actor && in_array($actor->role, ['admin_regional','staf']) && (int)$actor->kantor_id === (int)$rowKantorId && (int)$actor->bidang_id === (int)$rowBidangId))
    <a href="{{ route('okupansi.edit', $okupansi->id) }}" class="btn btn-modern btn-primary">
        <i class="fas fa-edit"></i> Edit
    </a>
    @endif
    <a href="{{ route('okupansi.index') }}" class="btn btn-modern btn-clear">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
@endsection

@section('content')
<div class="container-fluid">
    @php($actor = Auth::guard('admin')->user())

    <div class="row">
        <div class="col-lg-8">
            <!-- Okupansi Information -->
            <div class="detail-card">
                <div class="detail-header">
                    <h5 class="detail-title">
                        <i class="fas fa-chart-pie"></i>
                        Informasi Okupansi
                    </h5>
                </div>
                <div class="detail-body">
                    <div class="detail-grid">
                        <div class="detail-item">
                            <label class="detail-label">Ruang</label>
                            <div class="detail-value">
                                <strong>{{ $okupansi->ruang->nama_ruang ?? 'N/A' }}</strong>
                            </div>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Tanggal Okupansi</label>
                            <div class="detail-value">
                                <span class="badge modern-badge badge-info">
                                    {{ \Carbon\Carbon::parse($okupansi->tanggal_okupansi)->format('d/m/Y') }}
                                </span>
                            </div>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Bidang</label>
                            <div class="detail-value">
                                <span class="badge modern-badge badge-bidang">{{ $okupansi->bidang->nama_bidang ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Sub Bidang</label>
                            <div class="detail-value">
                                @if($okupansi->subBidang)
                                    <span class="badge modern-badge badge-bidang">{{ $okupansi->subBidang->nama_sub_bidang }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </div>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Jumlah Pegawai Organik</label>
                            <div class="detail-value">
                                <span class="badge modern-badge badge-pegawai">{{ $okupansi->jml_pegawai_organik }}</span>
                            </div>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Jumlah Pegawai TAD</label>
                            <div class="detail-value">
                                <span class="badge modern-badge badge-pegawai">{{ $okupansi->jml_pegawai_tad }}</span>
                            </div>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Jumlah Pegawai Kontrak</label>
                            <div class="detail-value">
                                <span class="badge modern-badge badge-pegawai">{{ $okupansi->jml_pegawai_kontrak ?? 0 }}</span>
                            </div>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Total Pegawai</label>
                            <div class="detail-value">
                                <strong style="color: var(--pln-blue); font-size: 1.1rem;">{{ $okupansi->total_pegawai }}</strong>
                            </div>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Kapasitas Ruang</label>
                            <div class="detail-value">
                                <span class="badge modern-badge badge-kapasitas">{{ $okupansi->ruang->kapasitas ?? 'N/A' }} orang</span>
                            </div>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Persentase Okupansi</label>
                            <div class="detail-value">
                                <span class="badge modern-badge badge-okupansi">
                                    {{ $okupansi->persentase_okupansi }}%
                                </span>
                            </div>
                        </div>
                        @if($okupansi->keterangan)
                        <div class="detail-item full-width">
                            <label class="detail-label">Keterangan</label>
                            <div class="detail-value">
                                <p style="margin: 0; color: #64748b;">{{ $okupansi->keterangan }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

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
                                <strong>{{ $okupansi->ruang->nama_ruang ?? 'N/A' }}</strong>
                            </div>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Kapasitas</label>
                            <div class="detail-value">
                                <span class="badge modern-badge badge-kapasitas">{{ $okupansi->ruang->kapasitas ?? 'N/A' }} orang</span>
                            </div>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Status Ruang</label>
                            <div class="detail-value">
                                <span class="badge modern-badge badge-status">
                                    {{ ucfirst($okupansi->ruang->status_ruang ?? 'N/A') }}
                                </span>
                            </div>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Lantai</label>
                            <div class="detail-value">
                                <span class="badge modern-badge badge-lantai">
                                    {{ $okupansi->ruang->lantai->nama_lantai ?? 'N/A' }} (Lantai {{ $okupansi->ruang->lantai->nomor_lantai ?? 'N/A' }})
                                </span>
                            </div>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Gedung</label>
                            <div class="detail-value">
                                <span class="badge modern-badge badge-gedung">{{ $okupansi->ruang->lantai->gedung->nama_gedung ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Kantor</label>
                            <div class="detail-value">
                                @if($okupansi->ruang && $okupansi->ruang->lantai && $okupansi->ruang->lantai->gedung && $okupansi->ruang->lantai->gedung->kantor)
                                    <span class="badge modern-badge badge-kantor">{{ $okupansi->ruang->lantai->gedung->kantor->kode_kantor }}</span>
                                    <div class="mt-1">
                                        <small>{{ $okupansi->ruang->lantai->gedung->kantor->nama_kantor }}</small>
                                    </div>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="related-info">
                        <a href="{{ route('ruang.show', $okupansi->ruang->id) }}" class="btn btn-modern btn-info btn-sm">
                            <i class="fas fa-eye"></i> Lihat Detail Ruang
                        </a>
                        <a href="{{ route('lantai.show', $okupansi->ruang->lantai->id) }}" class="btn btn-modern btn-info btn-sm">
                            <i class="fas fa-layer-group"></i> Lihat Detail Lantai
                        </a>
                        <a href="{{ route('gedung.show', $okupansi->ruang->lantai->gedung->id) }}" class="btn btn-modern btn-info btn-sm">
                            <i class="fas fa-building"></i> Lihat Detail Gedung
                        </a>
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
                                        <strong>{{ $okupansi->created_at->format('d/m/Y H:i') }}</strong>
                                        <small>{{ $okupansi->created_at->diffForHumans() }}</small>
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
                                        <strong>{{ $okupansi->updated_at->format('d/m/Y H:i') }}</strong>
                                        <small>{{ $okupansi->updated_at->diffForHumans() }}</small>
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
                        @php($rowKantorId = $okupansi->ruang->lantai->gedung->kantor->id ?? $okupansi->ruang->lantai->gedung->kantor_id ?? null)
                        @php($rowBidangId = $okupansi->bidang_id ?? null)
                        @if(($actor && $actor->role === 'super_admin') || ($actor && in_array($actor->role, ['admin_regional','staf']) && (int)$actor->kantor_id === (int)$rowKantorId && (int)$actor->bidang_id === (int)$rowBidangId))
                        <a href="{{ route('okupansi.edit', $okupansi->id) }}" class="btn btn-modern btn-primary">
                            <i class="fas fa-edit"></i> Edit Okupansi
                        </a>
                        <form action="{{ route('okupansi.destroy', $okupansi->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-modern btn-danger" 
                                    onclick="return confirm('Apakah Anda yakin ingin menghapus okupansi ini?')">
                                <i class="fas fa-trash"></i> Hapus Okupansi
                            </button>
                        </form>
                        @endif
                        <a href="{{ route('okupansi.index') }}" class="btn btn-modern btn-clear">
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
                        Statistik Okupansi
                    </h5>
                </div>
                <div class="detail-body">
                    <div class="stat-item">
                        <div class="stat-icon" style="background: var(--pln-blue-lighter);">
                            <i class="fas fa-users" style="color: var(--pln-blue);"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-label">Total Pegawai</div>
                            <div class="stat-value">{{ $okupansi->total_pegawai }}</div>
                        </div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-icon" style="background: var(--pln-blue-lighter);">
                            <i class="fas fa-user-tie" style="color: var(--pln-blue);"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-label">Pegawai Organik</div>
                            <div class="stat-value">{{ $okupansi->jml_pegawai_organik }}</div>
                        </div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-icon" style="background: var(--pln-blue-lighter);">
                            <i class="fas fa-user-check" style="color: var(--pln-blue-light);"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-label">Pegawai TAD</div>
                            <div class="stat-value">{{ $okupansi->jml_pegawai_tad }}</div>
                        </div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-icon" style="background: var(--pln-blue-lighter);">
                            <i class="fas fa-user-clock" style="color: var(--pln-blue-dark);"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-label">Pegawai Kontrak</div>
                            <div class="stat-value">{{ $okupansi->jml_pegawai_kontrak ?? 0 }}</div>
                        </div>
                    </div>
                    <div class="stat-item highlight">
                        <div class="stat-icon" style="background: var(--pln-blue-lighter);">
                            <i class="fas fa-percentage" style="color: var(--pln-blue);"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-label">Okupansi</div>
                            <div class="stat-value" style="color: var(--pln-blue);">
                                {{ $okupansi->persentase_okupansi }}%
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

    /* Badges */
    .badge.modern-badge {
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.8125rem;
        letter-spacing: 0.3px;
    }

    .badge-lantai {
        background: var(--pln-blue);
        color: white;
    }

    .badge-gedung {
        background: var(--pln-blue-light);
        color: white;
    }

    .badge-bidang {
        background: var(--pln-blue-lighter);
        color: var(--pln-blue);
    }

    .badge-kapasitas {
        background: var(--pln-blue-lighter);
        color: var(--pln-blue);
    }

    .badge-pegawai {
        background: var(--pln-blue);
        color: white;
    }

    .badge-okupansi {
        background: var(--pln-blue-light);
        color: white;
    }

    .badge-status {
        background: var(--pln-blue-lighter);
        color: var(--pln-blue);
    }

    .badge.badge-kantor {
        background: var(--pln-blue-lighter);
        color: var(--pln-blue);
    }

    .badge.badge-info {
        background: var(--pln-blue-light);
        color: white;
    }

    /* Related Info */
    .related-info {
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid rgba(33, 97, 140, 0.1);
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
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

    .btn-modern.btn-sm {
        padding: 0.5rem 0.75rem;
        font-size: 0.8rem;
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

    /* Responsive */
    @media (max-width: 991px) {
        .detail-grid {
            grid-template-columns: 1fr;
        }

        .related-info {
            flex-direction: column;
        }

        .related-info .btn-modern {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endpush
@endsection

