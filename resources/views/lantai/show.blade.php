@extends('layouts.app')

@section('title', 'Detail Lantai - PLN Icon Plus Kantor Management')
@section('page-title', 'Detail Lantai')

@section('page-actions')
    <a href="{{ route('lantai.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
    <a href="{{ route('lantai.edit', $lantai->id) }}" class="btn btn-primary">
        <i class="fas fa-edit"></i> Edit
    </a>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-layer-group"></i>
                    Detail Lantai
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="30%">Nama Lantai:</th>
                                <td><strong>{{ $lantai->nama_lantai }}</strong></td>
                            </tr>
                            <tr>
                                <th>Nomor Lantai:</th>
                                <td><span class="badge bg-info">{{ $lantai->nomor_lantai }}</span></td>
                            </tr>
                            <tr>
                                <th>Gedung:</th>
                                <td>{{ $lantai->gedung->nama_gedung ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Kantor:</th>
                                <td>{{ $lantai->gedung->kantor->nama_kantor ?? 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection