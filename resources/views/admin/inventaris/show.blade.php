@extends('layouts.app')

@section('title', 'Detail Inventaris - PLN Icon Plus Kantor Management')
@section('page-title', 'Detail Inventaris')
@section('page-subtitle', 'Informasi lengkap inventaris: ' . $inventaris->nama_barang)

@section('page-actions')
    @php
        $actor = Auth::guard('admin')->user();
        $rowKantorId = $inventaris->lokasi_kantor_id ?? ($inventaris->kantor->id ?? null);
    @endphp
    @if(($actor && $actor->role === 'super_admin') || ($actor && in_array($actor->role, ['admin_regional','staf']) && (int)$actor->kantor_id === (int)$rowKantorId && (int)$actor->bidang_id === (int)($inventaris->bidang_id ?? 0)))
    <a href="{{ route('inventaris.edit', $inventaris->id) }}" class="btn btn-modern btn-primary">
        <i class="fas fa-edit"></i> Edit
    </a>
    @endif
    <button type="button" class="btn btn-modern btn-success" onclick="showBarcodeModal()">
        <i class="fas fa-barcode"></i> Print Barcode
    </button>
    <a href="{{ route('inventaris.index') }}" class="btn btn-modern btn-clear">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <!-- Inventaris Information -->
            <div class="detail-card">
                <div class="detail-header">
                    <h5 class="detail-title">
                        <i class="fas fa-box"></i>
                        Informasi Inventaris
                    </h5>
                </div>
                <div class="detail-body">
                    <div class="detail-grid">
                        <div class="detail-item">
                            <label class="detail-label">Kode Inventaris</label>
                            <div class="detail-value">
                                <span class="badge modern-badge badge-primary">{{ $inventaris->kode_inventaris }}</span>
                            </div>
                        </div>
                        <div class="detail-item full-width">
                            <label class="detail-label">Nama Barang</label>
                            <div class="detail-value">
                                <strong>{{ $inventaris->nama_barang }}</strong>
                            </div>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Kategori</label>
                            <div class="detail-value">
                                <span class="badge modern-badge badge-primary">{{ $inventaris->kategori->nama_kategori }}</span>
                            </div>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Jumlah</label>
                            <div class="detail-value">
                                <span class="badge modern-badge badge-primary">{{ $inventaris->jumlah }}</span>
                            </div>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Kondisi</label>
                            <div class="detail-value">
                                @php
                                $kondisiColors = [
                                    'Baru' => 'success',
                                    'Baik' => 'primary',
                                    'Rusak Ringan' => 'warning',
                                    'Rusak Berat' => 'danger'
                                ];
                                @endphp
                                <span class="badge modern-badge bg-{{ $kondisiColors[$inventaris->kondisi] ?? 'secondary' }}">
                                    {{ $inventaris->kondisi }}
                                </span>
                            </div>
                        </div>
                        @if($inventaris->merk)
                        <div class="detail-item">
                            <label class="detail-label">Merk</label>
                            <div class="detail-value">
                                <strong>{{ $inventaris->merk }}</strong>
                            </div>
                        </div>
                        @endif
                        @if($inventaris->harga)
                        <div class="detail-item">
                            <label class="detail-label">Harga</label>
                            <div class="detail-value">
                                <strong style="color: var(--pln-blue); font-size: 1.1rem;">Rp {{ number_format($inventaris->harga, 0, ',', '.') }}</strong>
                            </div>
                        </div>
                        @endif
                        @if($inventaris->tahun)
                        <div class="detail-item">
                            <label class="detail-label">Tahun</label>
                            <div class="detail-value">
                                <span class="badge modern-badge badge-info">{{ $inventaris->tahun }}</span>
                            </div>
                        </div>
                        @endif
                        @if($inventaris->tanggal_pembelian)
                        <div class="detail-item">
                            <label class="detail-label">Tanggal Pembelian</label>
                            <div class="detail-value">
                                <span class="badge modern-badge badge-info">
                                    {{ \Carbon\Carbon::parse($inventaris->tanggal_pembelian)->format('d/m/Y') }}
                                </span>
                            </div>
                        </div>
                        @endif
                        <div class="detail-item full-width">
                            <label class="detail-label">Lokasi</label>
                            <div class="detail-value">
                                <div>
                                    <strong>{{ $inventaris->ruang->nama_ruang }}</strong>
                                    <br><small class="text-muted">{{ $inventaris->lantai->nomor_lantai ? 'Lantai ' . $inventaris->lantai->nomor_lantai . ' - ' : '' }}{{ $inventaris->lantai->nama_lantai }} - {{ $inventaris->gedung->nama_gedung }}</small>
                                    <br><small class="text-muted">{{ $inventaris->kantor->nama_kantor }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Bidang</label>
                            <div class="detail-value">
                                <div>
                                    <strong>{{ $inventaris->bidang->nama_bidang }}</strong>
                                    @if($inventaris->subBidang)
                                        <br><small class="text-muted">{{ $inventaris->subBidang->nama_sub_bidang }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Tanggal Input</label>
                            <div class="detail-value">
                                <span class="badge modern-badge badge-info">
                                    {{ $inventaris->tanggal_input->format('d/m/Y') }}
                                </span>
                            </div>
                        </div>
                        @if($inventaris->deskripsi)
                        <div class="detail-item full-width">
                            <label class="detail-label">Deskripsi</label>
                            <div class="detail-value">
                                <p style="margin: 0; color: #64748b;">{{ $inventaris->deskripsi }}</p>
                            </div>
                        </div>
                        @endif
                        @if($inventaris->gambar)
                        <div class="detail-item full-width">
                            <label class="detail-label">Gambar</label>
                            <div class="detail-value">
                                <img src="{{ asset($inventaris->gambar) }}" alt="{{ $inventaris->nama_barang }}"
                                    class="img-fluid rounded shadow" style="max-width: 100%; max-height: 300px; object-fit: cover;">
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Barcode Section -->
            <div class="detail-card">
                <div class="detail-header">
                    <h5 class="detail-title">
                        <i class="fas fa-barcode"></i>
                        Barcode Inventaris
                    </h5>
                </div>
                <div class="detail-body">
                    <div class="text-center">
                        <div id="barcode-{{ $inventaris->id }}" class="qr-code-container"></div>
                        <div class="mt-3">
                            <p class="mb-2"><strong>Kode:</strong> {{ $inventaris->kode_inventaris }}</p>
                            <p class="mb-3"><strong>Kantor:</strong> {{ $inventaris->kantor->nama_kantor }}</p>
                            <button type="button" class="btn btn-modern btn-success" onclick="showBarcodeModal()">
                                <i class="fas fa-print"></i> Print Barcode
                            </button>
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
                                        <strong>{{ $inventaris->created_at->format('d/m/Y H:i') }}</strong>
                                        <small>{{ $inventaris->created_at->diffForHumans() }}</small>
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
                                        <strong>{{ $inventaris->updated_at->format('d/m/Y H:i') }}</strong>
                                        <small>{{ $inventaris->updated_at->diffForHumans() }}</small>
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
                        @php
                            $currentUser = Auth::guard('admin')->user();
                            $kantorId = $inventaris->lokasi_kantor_id ?? ($inventaris->kantor->id ?? null);
                        @endphp
                        @if(($currentUser && $currentUser->role === 'super_admin') || ($currentUser && in_array($currentUser->role, ['admin_regional','staf']) && (int)$currentUser->kantor_id === (int)$kantorId && (int)$currentUser->bidang_id === (int)($inventaris->bidang_id ?? 0)))
                        <a href="{{ route('inventaris.edit', $inventaris->id) }}" class="btn btn-modern btn-primary">
                            <i class="fas fa-edit"></i> Edit Inventaris
                        </a>
                        @endif
                        @if($currentUser && $currentUser->role === 'super_admin')
                        <form action="{{ route('inventaris.destroy', $inventaris->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-modern btn-danger" 
                                    onclick="return confirm('Apakah Anda yakin ingin menghapus inventaris ini?')">
                                <i class="fas fa-trash"></i> Hapus Inventaris
                            </button>
                        </form>
                        @endif
                        <a href="{{ route('inventaris.index') }}" class="btn btn-modern btn-clear">
                            <i class="fas fa-list"></i> Kembali ke Daftar
                        </a>
                    </div>
                </div>
            </div>

            <!-- Related Info -->
            @if($inventaris->kategori)
            <div class="detail-card">
                <div class="detail-header">
                    <h5 class="detail-title">
                        <i class="fas fa-link"></i>
                        Informasi Terkait
                    </h5>
                </div>
                <div class="detail-body">
                    <div class="related-info-item">
                        <label class="detail-label">Kategori</label>
                        <div class="detail-value">
                            <span class="badge modern-badge badge-primary">{{ $inventaris->kategori->nama_kategori }}</span>
                        </div>
                    </div>
                    <div class="related-info-item" style="margin-top: 1rem;">
                        <a href="{{ route('kategori-inventaris.show', $inventaris->kategori->id) }}" class="btn btn-modern btn-info btn-sm" style="width: 100%; justify-content: center;">
                            <i class="fas fa-eye"></i> Lihat Detail Kategori
                        </a>
                    </div>
                </div>
            </div>
            @endif
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

    /* QR Code Container */
    .qr-code-container {
        display: inline-block;
        padding: 10px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .qr-code-container canvas {
        display: block;
        max-width: 100%;
        height: auto;
    }

    /* Responsive */
    @media (max-width: 991px) {
        .detail-grid {
            grid-template-columns: 1fr;
        }

        .detail-item.full-width {
            grid-column: 1;
        }
    }
</style>
@endpush

@push('scripts')
<!-- QR Code Library -->
<script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>

<script>
    // Generate QR Code
    document.addEventListener('DOMContentLoaded', function() {
        const barcodeData = '{{ $inventaris->kode_inventaris }}|{{ $inventaris->kantor->nama_kantor }}';
        const container = document.getElementById('barcode-{{ $inventaris->id }}');
        
        if (container) {
            QRCode.toCanvas(container, barcodeData, {
                width: 200,
                margin: 2,
                color: {
                    dark: '#000000',
                    light: '#FFFFFF'
                }
            }, function (error) {
                if (error) {
                    console.error('Error generating QR Code:', error);
                    container.innerHTML = '<p class="text-danger">Gagal generate QR Code</p>';
                }
            });
        }
    });

    // Show Barcode Modal
    function showBarcodeModal() {
        const barcodeData = '{{ $inventaris->kode_inventaris }}|{{ $inventaris->kantor->nama_kantor }}';
        const modal = document.getElementById('barcodePrintModal');
        const printBarcode = document.getElementById('print-barcode');
        
        if (!modal || !printBarcode) {
            console.error('Modal or print barcode element not found');
            return;
        }
        
        // Clear previous QR code
        printBarcode.innerHTML = '';
        
        // Generate new QR code
        QRCode.toCanvas(printBarcode, barcodeData, {
            width: 300,
            margin: 3,
            color: {
                dark: '#000000',
                light: '#FFFFFF'
            }
        }, function (error) {
            if (error) {
                console.error('Error generating QR Code:', error);
                printBarcode.innerHTML = '<p class="text-danger">Gagal generate QR Code</p>';
            } else {
                // Show modal after QR code is generated
                const bsModal = new bootstrap.Modal(modal);
                bsModal.show();
            }
        });
    }

    // Print Barcode
    function printBarcode() {
        window.print();
    }
</script>
@endpush

<!-- Barcode Print Modal -->
<div class="modal fade" id="barcodePrintModal" tabindex="-1" aria-labelledby="barcodePrintModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header" style="background: linear-gradient(135deg, var(--pln-blue) 0%, var(--pln-blue-dark) 100%); border-bottom: none; padding: 1.5rem;">
                <h5 class="modal-title text-white" id="barcodePrintModalLabel" style="font-weight: 700; font-size: 1.25rem;">
                    <i class="fas fa-qrcode me-2"></i> Print Barcode Inventaris
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding: 2rem;">
                <div class="text-center" id="barcode-print-content">
                    <!-- Header Info -->
                    <div class="mb-4">
                        <div class="d-inline-block px-4 py-2 mb-3" style="background: var(--pln-blue-lighter); border-radius: 8px; border: 2px solid var(--pln-blue);">
                            <h5 class="mb-0" style="color: var(--pln-blue); font-weight: 700; font-size: 1.1rem;">
                                <i class="fas fa-building me-2"></i>PLN Icon Plus
                            </h5>
                        </div>
                        <div class="barcode-info-card" style="background: #f8f9fa; border-radius: 10px; padding: 1.5rem; margin: 0 auto; max-width: 500px; border: 1px solid #e9ecef;">
                            <div class="row g-3 text-start">
                                <div class="col-12">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-hashtag me-2" style="color: var(--pln-blue); width: 20px;"></i>
                                        <div>
                                            <small class="text-muted d-block" style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Kode Inventaris</small>
                                            <strong style="color: var(--pln-blue); font-size: 1rem;">{{ $inventaris->kode_inventaris }}</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-box me-2" style="color: var(--pln-blue); width: 20px;"></i>
                                        <div>
                                            <small class="text-muted d-block" style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Nama Barang</small>
                                            <strong style="color: #2d3748; font-size: 1rem;">{{ $inventaris->nama_barang }}</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-map-marker-alt me-2" style="color: var(--pln-blue); width: 20px;"></i>
                                        <div>
                                            <small class="text-muted d-block" style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Kantor</small>
                                            <strong style="color: #2d3748; font-size: 1rem;">{{ $inventaris->kantor->nama_kantor }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- QR Code Container -->
                    <div class="qr-code-wrapper mb-4" style="display: inline-block; padding: 20px; background: white; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border: 2px solid #e9ecef;">
                        <div id="print-barcode"></div>
                    </div>
                    
                    <!-- Barcode Text -->
                    <div class="barcode-text mb-3" style="padding: 0.75rem 1.5rem; background: var(--pln-blue-lighter); border-radius: 8px; display: inline-block;">
                        <code style="color: var(--pln-blue); font-size: 0.9rem; font-weight: 600; letter-spacing: 1px;">
                            {{ $inventaris->kode_inventaris }} | {{ $inventaris->kantor->nama_kantor }}
                        </code>
                    </div>
                    
                    <!-- Instruction -->
                    <div class="instruction-text">
                        <small class="text-muted" style="font-size: 0.875rem;">
                            <i class="fas fa-info-circle me-1"></i>
                            Scan QR Code untuk melihat detail inventaris
                        </small>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="background: #f8f9fa; border-top: 1px solid #e9ecef; padding: 1.25rem 2rem;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="min-width: 100px;">
                    <i class="fas fa-times me-2"></i>Tutup
                </button>
                <button type="button" class="btn btn-success" onclick="printBarcode()" style="min-width: 120px; background: var(--pln-blue); border-color: var(--pln-blue);">
                    <i class="fas fa-print me-2"></i>Print
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    /* Barcode Print Modal Styling */
    #barcode-print-content {
        padding: 1rem;
    }

    .barcode-info-card {
        transition: all 0.2s ease;
    }

    .barcode-info-card:hover {
        box-shadow: 0 4px 12px rgba(33, 97, 140, 0.1);
        transform: translateY(-2px);
    }

    .qr-code-wrapper {
        transition: all 0.2s ease;
    }

    .qr-code-wrapper:hover {
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
    }

    #print-barcode canvas {
        display: block;
        margin: 0 auto;
    }

    /* Print Styles */
    @media print {
        body * {
            visibility: hidden;
        }
        #barcode-print-content, #barcode-print-content * {
            visibility: visible;
        }
        #barcode-print-content {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            width: 100%;
            max-width: 600px;
        }
        .modal-footer, .modal-header {
            display: none;
        }
        .barcode-info-card {
            border: 2px solid #000 !important;
            box-shadow: none !important;
        }
        .qr-code-wrapper {
            border: 2px solid #000 !important;
            box-shadow: none !important;
        }
        .barcode-text {
            border: 1px solid #000 !important;
        }
    }
</style>

@if(session('show_barcode'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            showBarcodeModal();
        }, 500);
    });
</script>
@endif

@endsection

