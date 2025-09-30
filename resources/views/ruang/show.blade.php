@extends('layouts.app')

@section('title', 'Detail Ruang - PLN Icon Plus Kantor Management')
@section('page-title', 'Detail Ruang')

@section('page-actions')
    <a href="{{ route('ruang.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
    <a href="{{ route('ruang.edit', $ruang->id) }}" class="btn btn-primary">
        <i class="fas fa-edit"></i> Edit
    </a>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-door-open"></i>
                    Detail Ruang
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="30%">Nama Ruang:</th>
                                <td><strong>{{ $ruang->nama_ruang }}</strong></td>
                            </tr>
                            <tr>
                                <th>Kapasitas:</th>
                                <td><span class="badge bg-info">{{ $ruang->kapasitas }} orang</span></td>
                            </tr>
                            <tr>
                                <th>Status Ruang:</th>
                                <td>
                                    <span class="badge bg-{{ $ruang->status_ruang == 'tersedia' ? 'success' : ($ruang->status_ruang == 'terisi' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($ruang->status_ruang) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Lantai:</th>
                                <td>{{ $ruang->lantai->nama_lantai ?? 'N/A' }} (Lantai {{ $ruang->lantai->nomor_lantai ?? 'N/A' }})</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="30%">Gedung:</th>
                                <td>{{ $ruang->lantai->gedung->nama_gedung ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Kantor:</th>
                                <td>{{ $ruang->lantai->gedung->kantor->nama_kantor ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Bidang:</th>
                                <td><span class="badge bg-primary">{{ $ruang->bidang->nama_bidang ?? 'N/A' }}</span></td>
                            </tr>
                            <tr>
                                <th>Sub Bidang:</th>
                                <td><span class="badge bg-success">{{ $ruang->subBidang->nama_sub_bidang ?? 'N/A' }}</span></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

