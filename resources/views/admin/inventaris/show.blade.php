@extends('layouts.app')

@section('title', 'Detail Inventaris')
@section('page-title', 'Detail Inventaris')
@section('page-subtitle', 'Detail data inventaris PLN Icon Plus')

@section('page-actions')
<a href="{{ route('inventaris.index') }}" class="btn btn-outline-secondary">
    <i class="fas fa-arrow-left"></i> Kembali
</a>

<a href="{{ route('inventaris.index') }}" class="btn btn-outline-secondary">
    <i class="fas fa-arrow-left"></i> Edit
</a>

@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title">
                            <i class="fas fa-box me-2"></i>Detail Inventaris
                        </h3>
                        <div>

                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">Kode Inventaris:</th>
                                    <td><span class="badge bg-primary">{{ $inventaris->kode_inventaris }}</span></td>
                                </tr>
                                <tr>
                                    <th>Nama Barang:</th>
                                    <td><strong>{{ $inventaris->nama_barang }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Kategori:</th>
                                    <td><span class="badge bg-info">{{ $inventaris->kategori->nama_kategori }}</span></td>
                                </tr>
                                <tr>
                                    <th>Jumlah:</th>
                                    <td><span class="badge bg-secondary">{{ $inventaris->jumlah }}</span></td>
                                </tr>
                                <tr>
                                    <th>Kondisi:</th>
                                    <td>
                                        @php
                                        $kondisiColors = [
                                        'Baru' => 'success',
                                        'Baik' => 'primary',
                                        'Rusak Ringan' => 'warning',
                                        'Rusak Berat' => 'danger'
                                        ];
                                        @endphp
                                        <span class="badge bg-{{ $kondisiColors[$inventaris->kondisi] }}">
                                            {{ $inventaris->kondisi }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Lokasi:</th>
                                    <td>
                                        <div>
                                            <strong>{{ $inventaris->ruang->nama_ruang }}</strong>
                                            <br><small class="text-muted">{{ $inventaris->lantai->nama_lantai }} - {{ $inventaris->gedung->nama_gedung }}</small>
                                            <br><small class="text-muted">{{ $inventaris->kantor->nama_kantor }}</small>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Bidang:</th>
                                    <td>
                                        <div>
                                            <strong>{{ $inventaris->bidang->nama_bidang }}</strong>
                                            @if($inventaris->subBidang)
                                            <br><small class="text-muted">{{ $inventaris->subBidang->nama_sub_bidang }}</small>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Tanggal Input:</th>
                                    <td>{{ $inventaris->tanggal_input->format('d M Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Deskripsi:</th>
                                    <td>
                                        @if($inventaris->deskripsi)
                                        {{ $inventaris->deskripsi }}
                                        @else
                                        <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Dibuat:</th>
                                    <td>{{ $inventaris->created_at->format('d M Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Diperbarui:</th>
                                    <td>{{ $inventaris->updated_at->format('d M Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-4">
                            @if($inventaris->gambar)
                            <div class="text-center">
                                <img src="{{ asset($inventaris->gambar) }}" alt="{{ $inventaris->nama_barang }}"
                                    class="img-fluid rounded shadow" style="max-width: 100%;">
                                <p class="text-muted small mt-2">Gambar Inventaris</p>
                            </div>
                            @else
                            <div class="text-center text-muted">
                                <i class="fas fa-image" style="font-size: 4rem;"></i>
                                <p class="mt-2">Tidak ada gambar</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection