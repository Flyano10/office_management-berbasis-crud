@extends('layouts.app')

@section('title', 'Detail Realisasi - PLN Icon Plus Kantor Management')
@section('page-title', 'Detail Realisasi')

@section('page-actions')
    <div class="btn-group">
        <a href="{{ route('realisasi.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <a href="{{ route('realisasi.edit', $realisasi->id) }}" class="btn btn-warning">
            <i class="fas fa-edit"></i> Edit
        </a>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-line"></i>
                    Detail Realisasi
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">No Pihak - 1:</th>
                                <td>{{ $realisasi->kontrak->no_perjanjian_pihak_1 ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>No Pihak - 2:</th>
                                <td>{{ $realisasi->kontrak->no_perjanjian_pihak_2 ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Realisasi:</th>
                                <td>{{ \Carbon\Carbon::parse($realisasi->tanggal_realisasi)->format('d F Y') }}</td>
                            </tr>
                            <tr>
                                <th>Kompensasi:</th>
                                <td>
                                    <span class="badge {{ $realisasi->kompensasi == 'Pemeliharaan' ? 'bg-warning' : 'bg-info' }}">
                                        {{ $realisasi->kompensasi }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Rp. Kompensasi:</th>
                                <td><strong>Rp {{ number_format($realisasi->rp_kompensasi, 0, ',', '.') }}</strong></td>
                            </tr>
                            <tr>
                                <th>Lokasi Kantor:</th>
                                <td>{{ $realisasi->lokasi_kantor ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Upload Berita Acara:</th>
                                <td>
                                    @if($realisasi->upload_berita_acara)
                                        <a href="{{ asset('uploads/berita_acara/' . $realisasi->upload_berita_acara) }}" 
                                           target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                    @else
                                        <span class="text-muted">Tidak ada file</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Dibuat:</th>
                                <td>{{ $realisasi->created_at->format('d F Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-12">
                        <h6>Deskripsi:</h6>
                        <div class="border rounded p-3 bg-light">
                            {{ $realisasi->deskripsi }}
                        </div>
                    </div>
                </div>

                @if($realisasi->alamat)
                <div class="row mt-3">
                    <div class="col-md-12">
                        <h6>Alamat:</h6>
                        <div class="border rounded p-3 bg-light">
                            {{ $realisasi->alamat }}
                        </div>
                    </div>
                </div>
                @endif

                <div class="row mt-4">
                    <div class="col-md-12">
                        <h6>Informasi Kontrak:</h6>
                        <div class="border rounded p-3 bg-light">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Nama Perjanjian:</strong><br>
                                    {{ $realisasi->kontrak->nama_perjanjian ?? 'N/A' }}
                                </div>
                                <div class="col-md-6">
                                    <strong>Asset Owner:</strong><br>
                                    {{ $realisasi->kontrak->asset_owner ?? 'N/A' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
