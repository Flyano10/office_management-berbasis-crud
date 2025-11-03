@extends('layouts.app')

@section('title', 'Detail Kategori Inventaris')
@section('page-title', 'Detail Kategori Inventaris')
@section('page-subtitle', 'Detail data kategori inventaris PLN Icon Plus')

@section('page-actions')
<a href="{{ route('kategori-inventaris.index') }}" class="btn btn-outline-secondary">
    <i class="fas fa-arrow-left"></i> Kembali
</a>

<a href="{{ route('kategori-inventaris.edit', $kategori->id) }}" class="btn btn-outline-warning">
    <i class="fas fa-edit"></i> Edit
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
                            <i class="fas fa-tag me-2"></i>Detail Kategori Inventaris
                        </h3>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">Nama Kategori:</th>
                                    <td><strong>{{ $kategori->nama_kategori }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Deskripsi:</th>
                                    <td>
                                        @if($kategori->deskripsi)
                                            {{ $kategori->deskripsi }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Jumlah Inventaris:</th>
                                    <td>
                                        <span class="badge bg-info">{{ $kategori->inventaris->count() }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Dibuat:</th>
                                    <td>{{ $kategori->created_at->format('d M Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Diperbarui:</th>
                                    <td>{{ $kategori->updated_at->format('d M Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
