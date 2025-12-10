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
    <button type="button" class="btn btn-modern btn-success" onclick="window.showBarcodeModal && window.showBarcodeModal()">
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
                        <div id="barcode-{{ $inventaris->id }}" class="qr-code-container">
                            <div class="qr-loading">
                                <i class="fas fa-spinner fa-spin"></i>
                                <p class="mt-2 text-muted">Memuat QR Code...</p>
                            </div>
                        </div>
                        <div class="mt-3">
                            <p class="mb-2"><strong>Kode:</strong> {{ $inventaris->kode_inventaris }}</p>
                            <p class="mb-3"><strong>Kantor:</strong> {{ $inventaris->kantor->nama_kantor }}</p>
                            <button type="button" class="btn btn-modern btn-success" onclick="window.showBarcodeModal && window.showBarcodeModal()">
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
        display: inline-flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 20px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        min-height: 250px;
        min-width: 250px;
    }

    .qr-code-container canvas {
        display: block;
        max-width: 100%;
        height: auto;
    }

    .qr-loading {
        text-align: center;
        color: var(--pln-blue);
    }

    .qr-loading i {
        font-size: 2rem;
        color: var(--pln-blue);
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
<!-- QR Code Data - Berisi detail lengkap inventaris -->
@php
$qrCodeData = "INVENTARIS PLN ICON PLUS\n";
$qrCodeData .= "========================\n";
$qrCodeData .= "Kode: " . $inventaris->kode_inventaris . "\n";
$qrCodeData .= "Nama Barang: " . $inventaris->nama_barang . "\n";
if($inventaris->merk) {
    $qrCodeData .= "Merk: " . $inventaris->merk . "\n";
}
if($inventaris->harga) {
    $qrCodeData .= "Harga: Rp " . number_format($inventaris->harga, 0, ',', '.') . "\n";
}
$qrCodeData .= "Kategori: " . $inventaris->kategori->nama_kategori . "\n";
$qrCodeData .= "Kondisi: " . $inventaris->kondisi . "\n";
$qrCodeData .= "Jumlah: " . $inventaris->jumlah . " unit\n";
if($inventaris->tahun) {
    $qrCodeData .= "Tahun: " . $inventaris->tahun . "\n";
}
if($inventaris->tanggal_pembelian) {
    $qrCodeData .= "Tanggal Pembelian: " . \Carbon\Carbon::parse($inventaris->tanggal_pembelian)->format('d/m/Y') . "\n";
}
$qrCodeData .= "Lokasi: " . $inventaris->ruang->nama_ruang . ", " . $inventaris->lantai->nama_lantai . ", " . $inventaris->gedung->nama_gedung . "\n";
$qrCodeData .= "Kantor: " . $inventaris->kantor->nama_kantor . "\n";
$qrCodeData .= "Bidang: " . $inventaris->bidang->nama_bidang . "\n";
if($inventaris->subBidang) {
    $qrCodeData .= "Sub Bidang: " . $inventaris->subBidang->nama_sub_bidang . "\n";
}
if($inventaris->deskripsi) {
    $qrCodeData .= "Deskripsi: " . $inventaris->deskripsi . "\n";
}
$qrCodeData .= "========================\n";
$qrCodeData .= "PLN ICON PLUS";
@endphp
<!-- QR Code Library with fallback -->
<script>
    // Track if QRCode is loaded
    window.qrcodeLoaded = false;
    window.qrcodeReadyCallback = null;
    
    // Function to check if QRCode is available
    function checkQRCodeLoaded() {
        if (typeof QRCode !== 'undefined') {
            window.qrcodeLoaded = true;
            console.log('QRCode library loaded successfully');
            // Call callback if exists
            if (window.qrcodeReadyCallback) {
                window.qrcodeReadyCallback();
                window.qrcodeReadyCallback = null;
            }
            return true;
        }
        return false;
    }
    
    // Show error message if QRCode cannot be loaded
    function showQRCodeError() {
        const container = document.getElementById('barcode-{{ $inventaris->id }}');
        if (container) {
            const barcodeData = @json($qrCodeData);
            // Try using online QR code API as last resort
            const qrApiUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=350x350&data=' + encodeURIComponent(barcodeData);
            container.innerHTML = '<div class="text-center">' +
                '<img src="' + qrApiUrl + '" alt="QR Code" class="img-fluid" style="max-width: 200px;" onerror="this.parentElement.innerHTML=\'<div class=\\\'alert alert-warning\\\'><i class=\\\'fas fa-exclamation-triangle\\\'></i> QR Code tidak dapat dimuat. Pastikan koneksi internet aktif atau refresh halaman.</div>\'">' +
                '<p class="mt-2 text-muted small">Menggunakan QR Code API</p>' +
                '</div>';
        }
    }
    
    // Fallback loader for QRCode library - MUST be defined before script tag
    window.loadQRCodeFallback = function() {
        if (typeof QRCode === 'undefined' && !window.qrcodeLoaded) {
            console.log('Trying to load QRCode from unpkg...');
            const script = document.createElement('script');
            script.src = 'https://unpkg.com/qrcode@1.5.3/build/qrcode.min.js';
            script.onerror = function() {
                console.error('Failed to load QRCode from unpkg, trying cdnjs...');
                const script2 = document.createElement('script');
                script2.src = 'https://cdnjs.cloudflare.com/ajax/libs/qrcode/1.5.3/qrcode.min.js';
                script2.onerror = function() {
                    console.error('All QRCode CDN sources failed');
                    window.qrcodeLoaded = false;
                    showQRCodeError();
                };
                script2.onload = function() {
                    console.log('QRCode loaded from cdnjs');
                    window.qrcodeLoaded = checkQRCodeLoaded();
                    // Trigger generateQRCode if it exists
                    if (window.qrcodeLoaded) {
                        setTimeout(function() {
                            if (typeof generateQRCode === 'function') {
                                generateQRCode();
                            } else if (window.qrcodeReadyCallback) {
                                window.qrcodeReadyCallback();
                            }
                        }, 100);
                    }
                };
                document.head.appendChild(script2);
            };
            script.onload = function() {
                console.log('QRCode loaded from unpkg');
                window.qrcodeLoaded = checkQRCodeLoaded();
                // Trigger generateQRCode if it exists
                if (window.qrcodeLoaded) {
                    setTimeout(function() {
                        if (typeof generateQRCode === 'function') {
                            generateQRCode();
                        } else if (window.qrcodeReadyCallback) {
                            window.qrcodeReadyCallback();
                        }
                    }, 100);
                }
            };
            document.head.appendChild(script);
        }
    };
</script>
<script>
    // Load QRCode library with proper error handling
    (function() {
        const script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js';
        script.async = true;
        
        script.onload = function() {
            checkQRCodeLoaded();
        };
        
        script.onerror = function() {
            console.error('Failed to load QRCode from jsdelivr, trying alternative...');
            if (typeof window.loadQRCodeFallback === 'function') {
                window.loadQRCodeFallback();
            } else {
                console.error('loadQRCodeFallback not available, will retry...');
                setTimeout(function() {
                    if (typeof window.loadQRCodeFallback === 'function') {
                        window.loadQRCodeFallback();
                    }
                }, 100);
            }
        };
        
        document.head.appendChild(script);
    })();
</script>
<script>

    // Make functions global
    window.showBarcodeModal = function() {
        const barcodeData = @json($qrCodeData);
        const modal = document.getElementById('barcodePrintModal');
        const printBarcode = document.getElementById('print-barcode');
        
        if (!modal) {
            console.error('Modal element not found');
            return;
        }
        
        if (!printBarcode) {
            console.error('Print barcode element not found');
            return;
        }
        
        // Clear previous QR code
        printBarcode.innerHTML = '';
        
        // Check if QRCode library is loaded
        if (typeof QRCode !== 'undefined' && window.qrcodeLoaded) {
            // Use library to generate QR code
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
                    // Fallback to API
                    useQRCodeAPI(printBarcode, barcodeData, modal);
                } else {
                    // Show modal after QR code is generated
                    showModal(modal);
                }
            });
        } else {
            // Library not loaded, use API fallback
            console.log('QRCode library not available, using API fallback');
            useQRCodeAPI(printBarcode, barcodeData, modal);
        }
    };
    
    // Function to use QR Code API as fallback
    function useQRCodeAPI(container, data, modal) {
        const qrApiUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=350x350&data=' + encodeURIComponent(data);
        const img = document.createElement('img');
        img.src = qrApiUrl;
        img.alt = 'QR Code';
        img.className = 'img-fluid';
        img.style.maxWidth = '100%';
        img.onerror = function() {
            if (container) {
                container.innerHTML = '<p class="text-danger">Gagal memuat QR Code. Pastikan koneksi internet aktif.</p>';
            }
        };
        img.onload = function() {
            if (container) {
                // Clear container first
                container.innerHTML = '';
                container.appendChild(img);
            }
            // Show modal after image is loaded (if modal provided)
            if (modal) {
                showModal(modal);
            }
        };
    }
    
    // Function to show modal
    function showModal(modal) {
        try {
            const bsModal = new bootstrap.Modal(modal);
            bsModal.show();
        } catch (e) {
            // Fallback if Bootstrap Modal not available
            modal.style.display = 'block';
            modal.classList.add('show');
            document.body.classList.add('modal-open');
        }
    }

    // Print Barcode
    window.printBarcode = function() {
        // Ensure QR code is loaded before printing
        const printBarcode = document.getElementById('print-barcode');
        if (printBarcode && printBarcode.children.length === 0) {
            // QR code not generated yet, generate it first
            const barcodeData = @json($qrCodeData);
            if (typeof QRCode !== 'undefined' && window.qrcodeLoaded) {
                QRCode.toCanvas(printBarcode, barcodeData, {
                    width: 350,
                    margin: 2,
                    color: {
                        dark: '#000000',
                        light: '#FFFFFF'
                    }
                }, function (error) {
                    if (error) {
                        // Use API fallback
                        useQRCodeAPI(printBarcode, barcodeData, null);
                    }
                    // Wait a bit then print
                    setTimeout(function() {
                        window.print();
                    }, 500);
                });
            } else {
                // Use API fallback
                useQRCodeAPI(printBarcode, barcodeData, null);
                setTimeout(function() {
                    window.print();
                }, 500);
            }
        } else {
            // QR code already loaded, print immediately
            window.print();
        }
    };

    // Generate QR Code on page load
    let qrCodeRetryCount = 0;
    const maxRetries = 50; // Maximum 5 seconds (50 * 100ms)
    
    function generateQRCode() {
        const container = document.getElementById('barcode-{{ $inventaris->id }}');
        
        if (!container) {
            console.error('QR Code container not found');
            return;
        }
        
        // Check if QRCode library is loaded
        if (typeof QRCode === 'undefined' || !window.qrcodeLoaded) {
            qrCodeRetryCount++;
            
            if (qrCodeRetryCount >= maxRetries) {
                // Max retries reached, use API fallback
                console.log('QRCode library failed to load after ' + maxRetries + ' attempts, using API fallback');
                if (qrCodeRetryCount === maxRetries) {
                    // Try fallback CDN loader once more
                    if (typeof window.loadQRCodeFallback === 'function') {
                        window.loadQRCodeFallback();
                        setTimeout(generateQRCode, 500);
                        return;
                    }
                }
                // If still not loaded, use API fallback
                const barcodeData = @json($qrCodeData);
                const qrApiUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=350x350&data=' + encodeURIComponent(barcodeData);
                container.innerHTML = '<div class="text-center">' +
                    '<img src="' + qrApiUrl + '" alt="QR Code" class="img-fluid" style="max-width: 200px;" onerror="this.parentElement.innerHTML=\'<div class=\\\'alert alert-warning\\\'><i class=\\\'fas fa-exclamation-triangle\\\'></i> QR Code tidak dapat dimuat.</div>\'">' +
                    '<p class="mt-2 text-muted small">Menggunakan QR Code API</p>' +
                    '</div>';
                return;
            }
            
            // Retry after 100ms
            setTimeout(generateQRCode, 100);
            return;
        }

        // Library is loaded, generate QR code
        const barcodeData = @json($qrCodeData);
        
        // Clear loading indicator
        container.innerHTML = '';
        
        // Generate QR Code directly to container
        try {
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
                    // Fallback to API
                    const qrApiUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=350x350&data=' + encodeURIComponent(barcodeData);
                    container.innerHTML = '<div class="text-center">' +
                        '<img src="' + qrApiUrl + '" alt="QR Code" class="img-fluid" style="max-width: 200px;">' +
                        '<p class="mt-2 text-muted small">Menggunakan QR Code API</p>' +
                        '</div>';
                } else {
                    console.log('QR Code generated successfully');
                }
            });
        } catch (e) {
            console.error('Exception generating QR Code:', e);
            // Fallback to API
            const qrApiUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=350x350&data=' + encodeURIComponent(barcodeData);
            container.innerHTML = '<div class="text-center">' +
                '<img src="' + qrApiUrl + '" alt="QR Code" class="img-fluid" style="max-width: 200px;">' +
                '<p class="mt-2 text-muted small">Menggunakan QR Code API</p>' +
                '</div>';
        }
    }

    // Initialize when DOM is ready and library is loaded
    function initializeQRCode() {
        // Set callback for when library loads
        window.qrcodeReadyCallback = function() {
            if (typeof generateQRCode === 'function') {
                generateQRCode();
            }
        };
        
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                // Wait a bit for external scripts to load
                setTimeout(function() {
                    if (typeof QRCode !== 'undefined' || window.qrcodeLoaded) {
                        generateQRCode();
                    } else {
                        // Library not loaded yet, start retry mechanism
                        generateQRCode();
                    }
                }, 500);
            });
        } else {
            // DOM already loaded
            setTimeout(function() {
                if (typeof QRCode !== 'undefined' || window.qrcodeLoaded) {
                    generateQRCode();
                } else {
                    // Library not loaded yet, start retry mechanism
                    generateQRCode();
                }
            }, 500);
        }
    }
    
    // Start initialization after all functions are defined
    initializeQRCode();
</script>
@endpush

<!-- Barcode Print Modal -->
<div class="modal fade" id="barcodePrintModal" tabindex="-1" aria-labelledby="barcodePrintModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 450px;">
        <div class="modal-content" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header" style="background: linear-gradient(135deg, var(--pln-blue) 0%, var(--pln-blue-dark) 100%); border-bottom: none; padding: 1rem 1.25rem;">
                <h5 class="modal-title text-white" id="barcodePrintModalLabel" style="font-weight: 700; font-size: 1rem;">
                    <i class="fas fa-qrcode me-2"></i> Print Barcode Inventaris
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding: 1.5rem;">
                <div class="text-center" id="barcode-print-content">
                    <!-- Label Sticker Layout (Preview) -->
                    <div class="label-sticker-preview">
                        <div class="label-top-section-preview">
                            <div class="label-qr-section-preview">
                                <div id="print-barcode" class="label-qr-code-preview"></div>
                                <div class="label-code-preview">{{ $inventaris->kode_inventaris }}</div>
                            </div>
                            <div class="label-brand-section-preview">
                                <div class="label-brand-hash-preview"># PLN</div>
                                <div class="label-brand-name-preview">Icon Plus</div>
                            </div>
                        </div>
                        <div class="label-title-section-preview">
                            <strong>ASET PLN ICON PLUS</strong>
                        </div>
                        <div class="label-warning-section-preview">
                            <strong>DO NOT REMOVE THIS LABEL</strong>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="background: #f8f9fa; border-top: 1px solid #e9ecef; padding: 1rem 1.25rem;">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" style="min-width: 80px;">
                    <i class="fas fa-times me-1"></i>Tutup
                </button>
                <button type="button" class="btn btn-success btn-sm" onclick="window.printBarcode && window.printBarcode()" style="min-width: 100px; background: var(--pln-blue); border-color: var(--pln-blue);">
                    <i class="fas fa-print me-1"></i>Print
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    /* Barcode Print Modal Styling */
    #barcodePrintModal .modal-dialog {
        max-width: 450px;
    }
    
    #barcode-print-content {
        padding: 0.5rem;
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

    /* Label Sticker Preview Styles */
    .label-sticker-preview {
        width: 84mm;
        max-width: 100%;
        min-height: 44mm;
        background: white;
        border: 2px solid #000;
        border-radius: 8px;
        padding: 6mm;
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    @media (max-width: 500px) {
        .label-sticker-preview {
            width: 100%;
            max-width: 84mm;
            padding: 5mm;
        }
        
        .label-qr-code-preview {
            width: 25mm !important;
            height: 25mm !important;
            min-width: 25mm !important;
            min-height: 25mm !important;
        }
    }

    .label-top-section-preview {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 4px;
    }

    .label-qr-section-preview {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
    }

    .label-qr-code-preview {
        width: 28mm;
        height: 28mm;
        min-width: 28mm;
        min-height: 28mm;
        display: flex;
        align-items: center;
        justify-content: center;
        background: white;
        border: 1px solid #ddd;
        border-radius: 4px;
        margin-bottom: 4px;
    }

    .label-qr-code-preview canvas,
    .label-qr-code-preview img {
        width: 100% !important;
        height: 100% !important;
        object-fit: contain;
        display: block;
    }

    .label-code-preview {
        font-size: 8pt;
        font-weight: 600;
        color: #000;
        text-align: left;
        margin-top: 2px;
    }

    .label-brand-section-preview {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        text-align: right;
        flex: 1;
        margin-left: 8px;
    }

    .label-brand-hash-preview {
        font-size: 11pt;
        font-weight: 700;
        color: #000;
        line-height: 1.2;
        margin-bottom: 2px;
    }

    .label-brand-name-preview {
        font-size: 8pt;
        font-weight: 500;
        color: #000;
        line-height: 1.2;
    }

    .label-title-section-preview {
        text-align: center;
        padding: 3px 0;
        margin: 2px 0;
        border-top: 1px solid #ddd;
        border-bottom: 1px solid #ddd;
    }

    .label-title-section-preview strong {
        font-size: 7pt;
        font-weight: 700;
        color: #000;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .label-warning-section-preview {
        text-align: center;
        padding-top: 2px;
        margin-top: auto;
    }

    .label-warning-section-preview strong {
        font-size: 6pt;
        font-weight: 600;
        color: #000;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    /* Print Styles - Label Sticker di tengah A4 */
    @media print {
        @page {
            size: A4;
            margin: 0;
        }
        
        body * {
            visibility: hidden;
        }
        
        #barcodePrintModal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 9999;
            background: white !important;
            display: block !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        
        .modal-dialog {
            max-width: 100% !important;
            width: 100% !important;
            height: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }
        
        .modal-content {
            border: none !important;
            box-shadow: none !important;
            border-radius: 0 !important;
            margin: 0 !important;
            padding: 0 !important;
            width: 100mm !important;
            height: 60mm !important;
            background: white !important;
        }
        
        .modal-header, .modal-footer {
            display: none !important;
        }
        
        .modal-body {
            padding: 8mm !important;
            margin: 0 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            width: 100% !important;
            height: 100% !important;
        }
        
        #barcode-print-content, #barcode-print-content * {
            visibility: visible !important;
        }
        
        .label-sticker-preview {
            width: 84mm !important;
            height: 44mm !important;
            border: 2px solid #000 !important;
            background: white !important;
            padding: 6mm !important;
            margin: 0 !important;
            display: flex !important;
            flex-direction: column !important;
            page-break-inside: avoid;
            box-shadow: none !important;
        }
        
        .label-qr-code-preview {
            width: 28mm !important;
            height: 28mm !important;
            min-width: 28mm !important;
            min-height: 28mm !important;
        }
        
        .label-qr-code-preview canvas,
        .label-qr-code-preview img {
            width: 28mm !important;
            height: 28mm !important;
            max-width: 28mm !important;
            max-height: 28mm !important;
        }
        
        .label-code-preview {
            font-size: 8pt !important;
        }
        
        .label-brand-hash-preview {
            font-size: 11pt !important;
        }
        
        .label-brand-name-preview {
            font-size: 8pt !important;
        }
        
        .label-title-section-preview strong {
            font-size: 7pt !important;
        }
        
        .label-warning-section-preview strong {
            font-size: 6pt !important;
        }
        
        /* Hide everything else */
        .navbar, .sidebar, header, footer, .btn, button {
            display: none !important;
        }
    }
</style>

@if(session('show_barcode'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Wait for showBarcodeModal function to be available
        function tryShowModal() {
            if (typeof window.showBarcodeModal === 'function') {
                setTimeout(function() {
                    window.showBarcodeModal();
                }, 1000);
            } else {
                // Retry after 100ms
                setTimeout(tryShowModal, 100);
            }
        }
        tryShowModal();
    });
</script>
@endif

@endsection

