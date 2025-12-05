@extends('layouts.app')

@section('title', 'Scan QR Inventaris')
@section('page-title', 'Scan QR Inventaris')
@section('page-subtitle', 'Scan QR Code untuk melihat detail inventaris')

@section('page-actions')
    <a href="{{ route('inventaris.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Scanner Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-qrcode me-2"></i>Scanner QR Code
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <p class="text-muted">Arahkan kamera ke QR Code inventaris</p>
                    </div>
                    
                    <!-- Scanner Container -->
                    <div id="scanner-container" class="mb-3">
                        <div id="reader" style="width: 100%; max-width: 500px; margin: 0 auto;"></div>
                    </div>
                    
                    <!-- Manual Input -->
                    <div class="mt-4">
                        <h6 class="mb-3">Atau masukkan kode inventaris manual:</h6>
                        <div class="input-group">
                            <input type="text" class="form-control" id="manual-kode" placeholder="Masukkan kode inventaris...">
                            <button class="btn btn-primary" type="button" onclick="searchByKode()">
                                <i class="fas fa-search"></i> Cari
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Result Card (Hidden by default) -->
            <div class="card" id="result-card" style="display: none;">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-check-circle me-2"></i>Detail Inventaris
                    </h5>
                </div>
                <div class="card-body">
                    <div id="result-content">
                        <!-- Content will be loaded here -->
                    </div>
                </div>
            </div>

            <!-- Error Card (Hidden by default) -->
            <div class="card" id="error-card" style="display: none;">
                <div class="card-header bg-danger text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-exclamation-circle me-2"></i>Error
                    </h5>
                </div>
                <div class="card-body">
                    <p id="error-message" class="text-danger mb-0"></p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- html5-qrcode library -->
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<script>
let html5QrcodeScanner;

document.addEventListener('DOMContentLoaded', function() {
    // Initialize scanner
    html5QrcodeScanner = new Html5QrcodeScanner(
        "reader",
        { 
            fps: 10, 
            qrbox: { width: 250, height: 250 },
            aspectRatio: 1.0,
            showTorchButtonIfSupported: true,
            showZoomSliderIfSupported: true,
            defaultZoomValueIfSupported: 2
        },
        false
    );

    html5QrcodeScanner.render(onScanSuccess, onScanFailure);
});

function onScanSuccess(decodedText, decodedResult) {
    console.log(`Scan result: ${decodedText}`);
    
    // Stop scanning temporarily
    html5QrcodeScanner.pause();
    
    // Search inventaris by kode
    fetchInventaris(decodedText);
}

function onScanFailure(error) {
    // Handle scan failure silently
    // console.warn(`Scan error: ${error}`);
}

function searchByKode() {
    const kode = document.getElementById('manual-kode').value.trim();
    if (kode) {
        fetchInventaris(kode);
    }
}

function fetchInventaris(kode) {
    // Show loading
    document.getElementById('result-card').style.display = 'none';
    document.getElementById('error-card').style.display = 'none';
    
    fetch(`/api/inventaris/kode/${encodeURIComponent(kode)}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayResult(data.data);
            } else {
                displayError(data.message || 'Inventaris tidak ditemukan');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            displayError('Terjadi kesalahan saat mengambil data');
        })
        .finally(() => {
            // Resume scanning after 3 seconds
            setTimeout(() => {
                if (html5QrcodeScanner) {
                    html5QrcodeScanner.resume();
                }
            }, 3000);
        });
}

function displayResult(data) {
    const resultCard = document.getElementById('result-card');
    const resultContent = document.getElementById('result-content');
    
    let imageHtml = '';
    if (data.gambar) {
        imageHtml = `
            <div class="text-center mb-4">
                <img src="${data.gambar}" alt="${data.nama_barang}" class="img-fluid rounded" style="max-height: 200px;">
            </div>
        `;
    }
    
    const kondisiColors = {
        'Baru': 'success',
        'Baik': 'primary',
        'Rusak Ringan': 'warning',
        'Rusak Berat': 'danger'
    };
    
    resultContent.innerHTML = `
        ${imageHtml}
        <div class="row">
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <td class="fw-bold" style="width: 40%;">Kode</td>
                        <td><span class="badge bg-primary">${data.kode_inventaris}</span></td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Nama Barang</td>
                        <td>${data.nama_barang}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Kategori</td>
                        <td><span class="badge bg-info">${data.kategori}</span></td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Jumlah</td>
                        <td>${data.jumlah}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Kondisi</td>
                        <td><span class="badge bg-${kondisiColors[data.kondisi] || 'secondary'}">${data.kondisi}</span></td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Merk</td>
                        <td>${data.merk}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <td class="fw-bold" style="width: 40%;">Harga</td>
                        <td class="text-success fw-bold">${data.harga}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Tahun</td>
                        <td>${data.tahun}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Bidang</td>
                        <td>${data.bidang}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Sub Bidang</td>
                        <td>${data.sub_bidang}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Tanggal Input</td>
                        <td>${data.tanggal_input}</td>
                    </tr>
                </table>
            </div>
        </div>
        
        <div class="alert alert-light mt-3">
            <h6 class="fw-bold"><i class="fas fa-map-marker-alt me-2"></i>Lokasi:</h6>
            <p class="mb-0">
                <strong>${data.lokasi.ruang}</strong> - ${data.lokasi.lantai}<br>
                ${data.lokasi.gedung}, ${data.lokasi.kantor}
            </p>
        </div>
        
        ${data.deskripsi !== '-' ? `
        <div class="alert alert-info mt-3">
            <h6 class="fw-bold"><i class="fas fa-info-circle me-2"></i>Deskripsi:</h6>
            <p class="mb-0">${data.deskripsi}</p>
        </div>
        ` : ''}
        
        <div class="text-center mt-4">
            <a href="/admin/inventaris/${data.id}" class="btn btn-primary">
                <i class="fas fa-eye me-2"></i>Lihat Detail Lengkap
            </a>
            <button class="btn btn-outline-secondary ms-2" onclick="resetScanner()">
                <i class="fas fa-qrcode me-2"></i>Scan Lagi
            </button>
        </div>
    `;
    
    resultCard.style.display = 'block';
    document.getElementById('error-card').style.display = 'none';
    
    // Scroll to result
    resultCard.scrollIntoView({ behavior: 'smooth' });
}

function displayError(message) {
    const errorCard = document.getElementById('error-card');
    document.getElementById('error-message').textContent = message;
    errorCard.style.display = 'block';
    document.getElementById('result-card').style.display = 'none';
}

function resetScanner() {
    document.getElementById('result-card').style.display = 'none';
    document.getElementById('error-card').style.display = 'none';
    document.getElementById('manual-kode').value = '';
    
    // Resume scanning
    if (html5QrcodeScanner) {
        html5QrcodeScanner.resume();
    }
    
    // Scroll to scanner
    document.getElementById('scanner-container').scrollIntoView({ behavior: 'smooth' });
}

// Handle Enter key on manual input
document.getElementById('manual-kode').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        searchByKode();
    }
});
</script>

<style>
#reader {
    border: 3px solid var(--primary-color);
    border-radius: 1rem;
    overflow: hidden;
}

#reader video {
    border-radius: 0.75rem;
}

#reader__scan_region {
    background: #000 !important;
}

#reader__dashboard_section_csr button {
    background: var(--primary-color) !important;
    border: none !important;
    border-radius: 0.5rem !important;
    padding: 0.5rem 1rem !important;
}

#reader__dashboard_section_csr select {
    border-radius: 0.5rem !important;
    padding: 0.5rem !important;
}
</style>
@endsection