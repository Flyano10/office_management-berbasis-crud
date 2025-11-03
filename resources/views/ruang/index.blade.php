@extends('layouts.app')

@section('title', 'Data Ruang - PLN Icon Plus Kantor Management')
@section('page-title', 'Data Ruang')

@section('page-actions')
    <a href="{{ route('ruang.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Ruang
    </a>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-door-open"></i>
                    Daftar Ruang PLN Icon Plus
                </h5>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Advanced Filtering Panel -->
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">
                                    <i class="fas fa-filter"></i>
                                    Filter Data Ruang
                                </h6>
                            </div>
                            <div class="card-body">
                                <form id="filter-form" method="GET">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="form-label">Lantai</label>
                                            <select class="form-select" name="lantai" id="lantai-filter">
                                                <option value="">Semua Lantai</option>
                                                @foreach($lantai ?? [] as $l)
                                                    <option value="{{ $l->id }}" {{ request('lantai') == $l->id ? 'selected' : '' }}>
                                                        Lantai {{ $l->nomor_lantai }} - {{ $l->gedung->nama_gedung }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Status Ruang</label>
                                            <select class="form-select" name="status_ruang" id="status-filter">
                                                <option value="">Semua Status</option>
                                                <option value="Tersedia" {{ request('status_ruang') == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                                                <option value="Terisi" {{ request('status_ruang') == 'Terisi' ? 'selected' : '' }}>Terisi</option>
                                                <option value="Perbaikan" {{ request('status_ruang') == 'Perbaikan' ? 'selected' : '' }}>Perbaikan</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Bidang</label>
                                            <select class="form-select" name="bidang" id="bidang-filter">
                                                <option value="">Semua Bidang</option>
                                                @foreach($bidang ?? [] as $b)
                                                    <option value="{{ $b->id }}" {{ request('bidang') == $b->id ? 'selected' : '' }}>
                                                        {{ $b->nama_bidang }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Aksi</label>
                                            <div class="d-grid gap-2">
                                                <button type="submit" class="btn btn-primary btn-sm">
                                                    <i class="fas fa-search"></i> Filter
                                                </button>
                                                <a href="{{ route('ruang.index') }}" class="btn btn-outline-secondary btn-sm">
                                                    <i class="fas fa-times"></i> Clear
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bulk Operations Panel -->
                <div class="row mb-3" id="bulk-actions-panel" style="display: none;">
                    <div class="col-12">
                        <div class="alert alert-info bulk-actions-panel" data-no-toast="true">
                            <div class="d-flex justify-content-between align-items-center">
                                <span id="selected-count">0 item dipilih</span>
                                <div>
                                    <button type="button" class="btn btn-danger btn-sm me-2" id="bulk-delete-btn">
                                        <i class="fas fa-trash"></i> Hapus Terpilih
                                    </button>
                                    <button type="button" class="btn btn-success btn-sm me-2" id="bulk-export-csv-btn">
                                        <i class="fas fa-file-csv"></i> Export CSV
                                    </button>
                                    <button type="button" class="btn btn-primary btn-sm" id="bulk-export-excel-btn">
                                        <i class="fas fa-file-excel"></i> Export Excel
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <style>
                    .table-responsive::-webkit-scrollbar {
                        height: 8px;
                    }
                    .table-responsive::-webkit-scrollbar-track {
                        background: #f8f9fa;
                        border-radius: 4px;
                    }
                    .table-responsive::-webkit-scrollbar-thumb {
                        background: #6c757d;
                        border-radius: 4px;
                    }
                    .table-responsive::-webkit-scrollbar-thumb:hover {
                        background: #495057;
                    }
                    
                    /* Support dark mode untuk sticky columns */
                    @media (prefers-color-scheme: dark) {
                        .table-responsive tbody tr td[style*="position: sticky"] {
                            background-color: #212529 !important;
                            color: white;
                        }
                    }
                    
                    /* Pastikan sticky columns bekerja dengan baik */
                    .table-responsive tbody tr:hover td[style*="position: sticky"] {
                        background-color: #f8f9fa !important;
                    }
                </style>
                <div class="table-responsive" style="overflow-x: auto; scrollbar-width: thin; scrollbar-color: #6c757d #f8f9fa;">
                    <table class="table table-striped table-hover" style="min-width: 1200px;">
                        <thead class="table-dark">
                            <tr>
                                <th style="position: sticky; left: 0; background-color: #212529; z-index: 10; min-width: 50px;">
                                    <input type="checkbox" id="select-all" class="form-check-input">
                                </th>
                                <th style="position: sticky; left: 50px; background-color: #212529; z-index: 10; min-width: 50px;">#</th>
                                <th style="min-width: 200px;">Nama Ruang</th>
                                <th style="min-width: 120px;">Lantai</th>
                                <th style="min-width: 150px;">Gedung</th>
                                <th style="min-width: 200px;">Kantor</th>
                                <th style="min-width: 120px;">Bidang</th>
                                <th style="min-width: 120px;">Kapasitas</th>
                                <th style="min-width: 100px;">Status</th>
                                <th style="min-width: 150px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php($actor = Auth::guard('admin')->user())
                            @forelse($ruang as $index => $r)
                            <tr>
                                <td style="position: sticky; left: 0; background-color: white; z-index: 5; border-right: 1px solid #dee2e6; white-space: nowrap;">
                                    <input type="checkbox" class="form-check-input item-checkbox" value="{{ $r->id }}">
                                </td>
                                <td style="position: sticky; left: 50px; background-color: white; z-index: 5; border-right: 1px solid #dee2e6; white-space: nowrap;">{{ $index + 1 }}</td>
                                <td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 200px;">
                                    <strong>{{ $r->nama_ruang }}</strong>
                                </td>
                                <td>
                                    <span class="badge bg-info">Lantai {{ $r->lantai->nomor_lantai ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ $r->lantai->gedung->nama_gedung ?? 'N/A' }}</span>
                                </td>
                                <td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 200px;">
                                    <small>{{ $r->lantai->gedung->kantor->nama_kantor ?? 'N/A' }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-success">{{ $r->bidang->nama_bidang ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-warning">{{ $r->kapasitas }} orang</span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $r->status_ruang == 'tersedia' ? 'success' : ($r->status_ruang == 'terisi' ? 'danger' : 'warning') }}">
                                        {{ ucfirst($r->status_ruang) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('ruang.show', $r->id) }}" class="btn btn-sm btn-outline-info" title="Lihat">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @php($rowKantorId = $r->lantai->gedung->kantor->id ?? $r->lantai->gedung->kantor_id ?? null)
                                        @php($rowBidangId = $r->bidang_id ?? null)
                                        @if(($actor && $actor->role === 'super_admin') || ($actor && in_array($actor->role, ['admin_regional','staf']) && (int)$actor->kantor_id === (int)$rowKantorId && (int)$actor->bidang_id === (int)$rowBidangId))
                                        <a href="{{ route('ruang.edit', $r->id) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('ruang.destroy', $r->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus ruang ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center">
                                    <div class="py-4">
                                        <i class="fas fa-door-open fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">Belum ada data ruang</p>
                                        @php($canCreate = ($actor && $actor->role === 'super_admin') || ($actor && in_array($actor->role, ['admin_regional','staf'])))
                                        @if($canCreate)
                                        <a href="{{ route('ruang.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus"></i> Tambah Ruang Pertama
                                        </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('select-all');
    const itemCheckboxes = document.querySelectorAll('.item-checkbox');
    const bulkActionsPanel = document.getElementById('bulk-actions-panel');
    const selectedCountSpan = document.getElementById('selected-count');
    const bulkDeleteBtn = document.getElementById('bulk-delete-btn');
    const bulkExportCsvBtn = document.getElementById('bulk-export-csv-btn');
    const bulkExportExcelBtn = document.getElementById('bulk-export-excel-btn');

    // Fungsi Select All
    selectAllCheckbox.addEventListener('change', function() {
        itemCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateBulkActionsPanel();
    });

    // Perubahan checkbox individual
    itemCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateBulkActionsPanel();
            updateSelectAllCheckbox();
        });
    });

    function updateBulkActionsPanel() {
        const checkedBoxes = document.querySelectorAll('.item-checkbox:checked');
        const count = checkedBoxes.length;
        
        if (count > 0) {
            bulkActionsPanel.style.display = 'block';
            selectedCountSpan.textContent = `${count} item dipilih`;
        } else {
            bulkActionsPanel.style.display = 'none';
        }
    }

    function updateSelectAllCheckbox() {
        const checkedBoxes = document.querySelectorAll('.item-checkbox:checked');
        const totalBoxes = itemCheckboxes.length;
        
        if (checkedBoxes.length === 0) {
            selectAllCheckbox.indeterminate = false;
            selectAllCheckbox.checked = false;
        } else if (checkedBoxes.length === totalBoxes) {
            selectAllCheckbox.indeterminate = false;
            selectAllCheckbox.checked = true;
        } else {
            selectAllCheckbox.indeterminate = true;
        }
    }

    // Hapus Bulk
    bulkDeleteBtn.addEventListener('click', function() {
        const checkedBoxes = document.querySelectorAll('.item-checkbox:checked');
        const ids = Array.from(checkedBoxes).map(cb => cb.value);
        
        if (ids.length === 0) {
            alert('Pilih minimal 1 item untuk dihapus');
            return;
        }

        if (confirm(`Apakah Anda yakin ingin menghapus ${ids.length} ruang yang dipilih?`)) {
            // Buat form untuk bulk delete
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("bulk.delete", "ruang") }}';
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            
        // Buat multiple hidden input untuk setiap ID
        ids.forEach(id => {
            const idsInput = document.createElement('input');
            idsInput.type = 'hidden';
            idsInput.name = 'ids[]';
            idsInput.value = id;
            form.appendChild(idsInput);
        });
        
        form.appendChild(csrfToken);
        document.body.appendChild(form);
        form.submit();
        }
    });

    // Export Bulk CSV
    bulkExportCsvBtn.addEventListener('click', function() {
        const checkedBoxes = document.querySelectorAll('.item-checkbox:checked');
        const ids = Array.from(checkedBoxes).map(cb => cb.value);
        
        if (ids.length === 0) {
            alert('Pilih minimal 1 item untuk diexport');
            return;
        }

        // Buat form untuk bulk export CSV
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("bulk.export", "ruang") }}';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        // Buat multiple hidden input untuk setiap ID
        ids.forEach(id => {
            const idsInput = document.createElement('input');
            idsInput.type = 'hidden';
            idsInput.name = 'ids[]';
            idsInput.value = id;
            form.appendChild(idsInput);
        });
        
        const formatInput = document.createElement('input');
        formatInput.type = 'hidden';
        formatInput.name = 'format';
        formatInput.value = 'csv';
        
        form.appendChild(csrfToken);
        form.appendChild(formatInput);
        document.body.appendChild(form);
        form.submit();
    });

    // Export Bulk Excel
    bulkExportExcelBtn.addEventListener('click', function() {
        const checkedBoxes = document.querySelectorAll('.item-checkbox:checked');
        const ids = Array.from(checkedBoxes).map(cb => cb.value);
        
        if (ids.length === 0) {
            alert('Pilih minimal 1 item untuk diexport');
            return;
        }

        // Buat form untuk bulk export Excel
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("bulk.export", "ruang") }}';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        // Buat multiple hidden input untuk setiap ID
        ids.forEach(id => {
            const idsInput = document.createElement('input');
            idsInput.type = 'hidden';
            idsInput.name = 'ids[]';
            idsInput.value = id;
            form.appendChild(idsInput);
        });
        
        const formatInput = document.createElement('input');
        formatInput.type = 'hidden';
        formatInput.name = 'format';
        formatInput.value = 'excel';
        
        form.appendChild(csrfToken);
        form.appendChild(formatInput);
        document.body.appendChild(form);
        form.submit();
    });
});
</script>
@endsection
