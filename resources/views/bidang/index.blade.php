@extends('layouts.app')

@section('title', 'Data Bidang - PLN Icon Plus Kantor Management')
@section('page-title', 'Data Bidang')

@section('page-actions')
    @php($actor = Auth::guard('admin')->user())
    <div class="btn-group" role="group">
        @if(($actor && $actor->role !== 'staf'))
        <a href="{{ route('bidang.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Bidang
        </a>
        <button type="button" class="btn btn-outline-danger" onclick="bulkDelete()" id="bulkDeleteBtn" disabled>
            <i class="fas fa-trash"></i> Bulk Delete
        </button>
        @endif
        <button type="button" class="btn btn-outline-success" onclick="bulkExport('csv')" id="bulkExportCsvBtn" disabled>
            <i class="fas fa-file-csv"></i> Export CSV
        </button>
        <button type="button" class="btn btn-outline-info" onclick="bulkExport('excel')" id="bulkExportExcelBtn" disabled>
            <i class="fas fa-file-excel"></i> Export Excel
        </button>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-sitemap"></i>
                    Daftar Bidang PLN Icon Plus
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
                                    Filter Data Bidang
                                </h6>
                            </div>
                            <div class="card-body">
                                <form id="filter-form" method="GET">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="form-label">Nama Bidang</label>
                                            <input type="text" class="form-control" name="nama_bidang" value="{{ request('nama_bidang') }}" placeholder="Cari nama bidang...">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Aksi</label>
                                            <div class="d-grid gap-2">
                                                <button type="submit" class="btn btn-primary btn-sm">
                                                    <i class="fas fa-search"></i> Filter
                                                </button>
                                                <a href="{{ route('bidang.index') }}" class="btn btn-outline-secondary btn-sm">
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

                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>
                                    <input type="checkbox" id="select-all" class="form-check-input">
                                </th>
                                <th>#</th>
                                <th>Nama Bidang</th>
                                <th>Deskripsi</th>
                                <th>Jumlah Sub Bidang</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bidang as $index => $b)
                            <tr>
                                <td>
                                    <input type="checkbox" class="form-check-input item-checkbox" value="{{ $b->id }}">
                                </td>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <strong>{{ $b->nama_bidang }}</strong>
                                </td>
                                <td>
                                    <small>{{ $b->deskripsi ?? '-' }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $b->subBidang->count() }} Sub Bidang</span>
                                </td>
                                <td>
                                    @php($actor = Auth::guard('admin')->user())
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('bidang.show', $b->id) }}" class="btn btn-sm btn-outline-info" title="Lihat">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if(($actor && $actor->role === 'super_admin') || ($actor && $actor->role === 'staf' && (int)$actor->bidang_id === (int)$b->id))
                                        <a href="{{ route('bidang.edit', $b->id) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @endif
                                        @if($actor && $actor->role === 'super_admin')
                                        <form action="{{ route('bidang.destroy', $b->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus bidang ini?')">
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
                                <td colspan="6" class="text-center">
                                    <div class="py-4">
                                        <i class="fas fa-sitemap fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">Belum ada data bidang</p>
                                        @if(($actor && $actor->role !== 'staf'))
                                        <a href="{{ route('bidang.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus"></i> Tambah Bidang Pertama
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

        if (confirm(`Apakah Anda yakin ingin menghapus ${ids.length} bidang yang dipilih?`)) {
            // Buat form untuk bulk delete
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("bulk.delete", "bidang") }}';
            
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
        form.action = '{{ route("bulk.export", "bidang") }}';
        
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
        form.action = '{{ route("bulk.export", "bidang") }}';
        
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
