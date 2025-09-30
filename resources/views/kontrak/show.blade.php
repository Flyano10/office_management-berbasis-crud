@extends('layouts.app')

@section('title', 'Detail Kontrak - PLN Icon Plus Kantor Management')
@section('page-title', 'Detail Kontrak')

@section('page-actions')
    <a href="{{ route('kontrak.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
    <a href="{{ route('kontrak.edit', $kontrak->id) }}" class="btn btn-warning">
        <i class="fas fa-edit"></i> Edit
    </a>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-file-contract"></i>
                    Detail Kontrak
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nomor Kontrak</label>
                            <p class="form-control-plaintext">{{ $kontrak->nomor_kontrak }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Kontrak</label>
                            <p class="form-control-plaintext">{{ $kontrak->nama_kontrak }}</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Kantor</label>
                            <p class="form-control-plaintext">
                                {{ $kontrak->kantor->kode_kantor }} - {{ $kontrak->kantor->nama_kantor }}
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Status Perjanjian</label>
                            <p class="form-control-plaintext">
                                <span class="badge bg-{{ $kontrak->status_perjanjian == 'baru' ? 'primary' : ($kontrak->status_perjanjian == 'berjalan' ? 'success' : 'secondary') }}">
                                    {{ ucfirst($kontrak->status_perjanjian) }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tanggal Mulai</label>
                            <p class="form-control-plaintext">{{ \Carbon\Carbon::parse($kontrak->tanggal_mulai)->format('d/m/Y') }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tanggal Selesai</label>
                            <p class="form-control-plaintext">{{ \Carbon\Carbon::parse($kontrak->tanggal_selesai)->format('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nilai Kontrak</label>
                            <p class="form-control-plaintext">Rp {{ number_format($kontrak->nilai_kontrak, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">No Perjanjian Pihak 1</label>
                            <p class="form-control-plaintext">{{ $kontrak->no_perjanjian_pihak_1 ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">No Perjanjian Pihak 2</label>
                            <p class="form-control-plaintext">{{ $kontrak->no_perjanjian_pihak_2 ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Berita Acara</label>
                            <p class="form-control-plaintext">
                                @if($kontrak->berita_acara)
                                    <a href="{{ asset('uploads/berita_acara/' . $kontrak->berita_acara) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-download"></i> Download
                                    </a>
                                @else
                                    <span class="text-muted">Tidak ada file</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                @if($kontrak->keterangan)
                <div class="mb-3">
                    <label class="form-label fw-bold">Keterangan</label>
                    <p class="form-control-plaintext">{{ $kontrak->keterangan }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-info-circle"></i>
                    Informasi Tambahan
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Dibuat</label>
                    <p class="form-control-plaintext">{{ $kontrak->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Diperbarui</label>
                    <p class="form-control-plaintext">{{ $kontrak->updated_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
