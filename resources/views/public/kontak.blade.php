@extends('layouts.public')

@section('title', 'Kontak PLN Icon Plus')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center">
                    <h1 class="display-6 text-primary mb-3">
                        <i class="fas fa-phone me-2"></i>
                        Hubungi Kami
                    </h1>
                    <p class="lead text-muted">Informasi kontak PLN Icon Plus</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Information -->
    <div class="row g-4">
        <!-- Main Office -->
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title text-primary">
                        <i class="fas fa-building me-2"></i>Kantor Pusat
                    </h5>
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-map-marker-alt text-primary me-3 mt-1"></i>
                                <div>
                                    <h6 class="mb-1">Alamat</h6>
                                    <p class="text-muted mb-0">
                                        Jl. Gatot Subroto No. 1<br>
                                        Jakarta Selatan 12950<br>
                                        Indonesia
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-phone text-success me-3 mt-1"></i>
                                <div>
                                    <h6 class="mb-1">Telepon</h6>
                                    <p class="text-muted mb-0">+62 21 5300 1234</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-envelope text-info me-3 mt-1"></i>
                                <div>
                                    <h6 class="mb-1">Email</h6>
                                    <p class="text-muted mb-0">info@pln.co.id</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-clock text-warning me-3 mt-1"></i>
                                <div>
                                    <h6 class="mb-1">Jam Operasional</h6>
                                    <p class="text-muted mb-0">
                                        Senin - Jumat: 08:00 - 17:00<br>
                                        Sabtu: 08:00 - 12:00<br>
                                        Minggu: Tutup
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Form -->
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title text-primary">
                        <i class="fas fa-paper-plane me-2"></i>Kirim Pesan
                    </h5>
                    <form>
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="nama" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="telepon" class="form-label">Telepon</label>
                            <input type="tel" class="form-control" id="telepon">
                        </div>
                        <div class="mb-3">
                            <label for="subjek" class="form-label">Subjek</label>
                            <select class="form-select" id="subjek" required>
                                <option value="">Pilih subjek</option>
                                <option value="informasi">Informasi Umum</option>
                                <option value="teknical">Dukungan Teknis</option>
                                <option value="kerjasama">Kerjasama</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="pesan" class="form-label">Pesan</label>
                            <textarea class="form-control" id="pesan" rows="4" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-pln w-100">
                            <i class="fas fa-paper-plane me-2"></i>Kirim Pesan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Information -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-primary">
                        <i class="fas fa-info-circle me-2"></i>Informasi Tambahan
                    </h5>
                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="text-center">
                                <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                    <i class="fas fa-headset fa-lg"></i>
                                </div>
                                <h6>Customer Service</h6>
                                <p class="small text-muted mb-0">24/7 Support</p>
                                <p class="small text-muted">+62 21 5300 1234</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                    <i class="fas fa-tools fa-lg"></i>
                                </div>
                                <h6>Technical Support</h6>
                                <p class="small text-muted mb-0">Senin - Jumat</p>
                                <p class="small text-muted">08:00 - 17:00</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <div class="bg-info text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                    <i class="fas fa-envelope fa-lg"></i>
                                </div>
                                <h6>Email Support</h6>
                                <p class="small text-muted mb-0">Response dalam 24 jam</p>
                                <p class="small text-muted">support@pln.co.id</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center">
                    <h6 class="card-title text-primary mb-3">Akses Cepat</h6>
                    <div class="d-flex justify-content-center gap-3 flex-wrap">
                        <a href="{{ route('public.home') }}" class="btn btn-outline-primary">
                            <i class="fas fa-home me-1"></i>Home
                        </a>
                        <a href="{{ route('public.peta') }}" class="btn btn-outline-primary">
                            <i class="fas fa-map me-1"></i>Peta Lokasi
                        </a>
                        <a href="{{ route('public.directory') }}" class="btn btn-outline-primary">
                            <i class="fas fa-list me-1"></i>Directory Kantor
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
