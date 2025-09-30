@extends('layouts.app')

@section('title', 'Detail Bidang - PLN Icon Plus Kantor Management')
@section('page-title', 'Detail Bidang')

@section('page-actions')
    <a href="{{ route('bidang.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
    <a href="{{ route('bidang.edit', $bidang->id) }}" class="btn btn-primary">
        <i class="fas fa-edit"></i> Edit
    </a>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-sitemap"></i>
                    Detail Bidang
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="30%">Nama Bidang:</th>
                                <td><strong>{{ $bidang->nama_bidang }}</strong></td>
                            </tr>
                            <tr>
                                <th>Deskripsi:</th>
                                <td>{{ $bidang->deskripsi ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6>Sub Bidang ({{ $bidang->subBidang->count() }})</h6>
                        @if($bidang->subBidang->count() > 0)
                            <div class="list-group">
                                @foreach($bidang->subBidang as $sub)
                                    <div class="list-group-item">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">{{ $sub->nama_sub_bidang }}</h6>
                                            <small class="text-muted">{{ $sub->kode_sub_bidang }}</small>
                                        </div>
                                        <p class="mb-1">{{ $sub->deskripsi ?? '-' }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted">Belum ada sub bidang</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
