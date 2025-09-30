@extends('layouts.app')

@section('title', 'Detail Sub Bidang - PLN Icon Plus Kantor Management')
@section('page-title', 'Detail Sub Bidang')

@section('page-actions')
    <a href="{{ route('sub-bidang.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
    <a href="{{ route('sub-bidang.edit', $subBidang->id) }}" class="btn btn-primary">
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
                    Detail Sub Bidang
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="30%">Nama Sub Bidang:</th>
                                <td><strong>{{ $subBidang->nama_sub_bidang }}</strong></td>
                            </tr>
                            <tr>
                                <th>Bidang:</th>
                                <td><span class="badge bg-primary">{{ $subBidang->bidang->nama_bidang ?? 'N/A' }}</span></td>
                            </tr>
                            <tr>
                                <th>Deskripsi:</th>
                                <td>{{ $subBidang->deskripsi ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
