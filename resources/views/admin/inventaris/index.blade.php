@extends('layouts.app')

@section('title', 'Inventaris')
@section('page-title', 'Inventaris')
@section('page-subtitle', 'Kelola data inventaris PLN Icon Plus')

@section('page-actions')
    <a href="{{ route('inventaris.create') }}" class="btn btn-modern btn-primary">
        <i class="fas fa-plus"></i> Tambah inventaris 
    </a>
@endsection

@section('content')
<div class="container-fluid">


    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-building me-2"></i>Daftar Inventaris PLN Icon Plus
                        </h5>
                        
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif


                    <!-- Filter Data Inventaris -->
                    <div class="mb-4">
                        <h6 class="mb-3">
                            <i class="fas fa-filter me-2"></i>Filter Data Inventaris
                        </h6>
                        <form method="GET" action="{{ route('inventaris.index') }}">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="kategori_id" class="form-label">Kategori</label>
                                        <select class="form-select" id="kategori_id" name="kategori_id">
                                            <option value="">Semua Kategori</option>
                                            @foreach($kategori as $k)
                                                <option value="{{ $k->id }}" {{ request('kategori_id') == $k->id ? 'selected' : '' }}>
                                                    {{ $k->nama_kategori }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="kondisi" class="form-label">Kondisi</label>
                                        <select class="form-select" id="kondisi" name="kondisi">
                                            <option value="">Semua Kondisi</option>
                                            <option value="Baru" {{ request('kondisi') == 'Baru' ? 'selected' : '' }}>Baru</option>
                                            <option value="Baik" {{ request('kondisi') == 'Baik' ? 'selected' : '' }}>Baik</option>
                                            <option value="Rusak Ringan" {{ request('kondisi') == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                                            <option value="Rusak Berat" {{ request('kondisi') == 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                                        </select>
                                    </div>
                                </div>
                                @if(auth('admin')->user()->role === 'super_admin')
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="bidang_id" class="form-label">Bidang</label>
                                        <select class="form-select" id="bidang_id" name="bidang_id">
                                            <option value="">Semua Bidang</option>
                                            @foreach($bidang as $b)
                                                <option value="{{ $b->id }}" {{ request('bidang_id') == $b->id ? 'selected' : '' }}>
                                                    {{ $b->nama_bidang }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @endif
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label">&nbsp;</label>
                                        <div class="d-flex gap-2">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-search me-1"></i>Filter
                                            </button>
                                            <a href="{{ route('inventaris.index') }}" class="btn btn-outline-secondary">
                                                <i class="fas fa-times me-1"></i>Clear
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Bulk Operations Panel -->
                    <div class="row mb-4" id="bulk-actions-panel" style="display: none;">
                        <div class="col-12">
                            <div class="bulk-actions-card">
                                <div class="bulk-actions-content">
                                    <div class="bulk-info">
                                        <i class="fas fa-check-circle"></i>
                                        <span id="selected-count">0 item dipilih</span>
                                    </div>
                                    <div class="bulk-actions">
                                        <button type="button" class="btn btn-modern btn-danger" id="bulk-delete-btn">
                                            <i class="fas fa-trash"></i> Hapus Terpilih
                                        </button>
                                        <button type="button" class="btn btn-modern btn-success" id="bulk-export-csv-btn">
                                            <i class="fas fa-file-csv"></i> Export CSV
                                        </button>
                                        <button type="button" class="btn btn-modern btn-primary" id="bulk-export-excel-btn">
                                            <i class="fas fa-file-excel"></i> Export Excel
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive" style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
                        <table class="table modern-table" style="min-width: 2000px; white-space: nowrap; table-layout: fixed;">
                            <thead>
                                <tr>
                                    <th style="width: 50px; min-width: 50px;">
                                        <input type="checkbox" id="select-all" class="form-check-input modern-checkbox">
                                    </th>
                                    <th style="width: 60px; min-width: 60px;">No</th>
                                    <th style="width: 140px; min-width: 140px;">Kode</th>
                                    <th style="width: 250px; min-width: 250px;">Nama Barang</th>
                                    <th style="width: 120px; min-width: 120px;">Kategori</th>
                                    <th style="width: 90px; min-width: 90px;">Jumlah</th>
                                    <th style="width: 120px; min-width: 120px;">Kondisi</th>
                                    <th style="width: 120px; min-width: 120px;">Merk</th>
                                    <th style="width: 120px; min-width: 120px;">Harga</th>
                                    <th style="width: 80px; min-width: 80px;">Tahun</th>
                                    <th style="width: 120px; min-width: 120px;">Tanggal Pembelian</th>
                                    <th style="width: 300px; min-width: 300px;">Lokasi</th>
                                    <th style="width: 180px; min-width: 180px;">Bidang</th>
                                    <th style="width: 120px; min-width: 120px;">Tanggal Input</th>
                                    <th style="width: 180px; min-width: 180px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($inventaris as $item)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="form-check-input modern-checkbox item-checkbox" value="{{ $item->id }}">
                                    </td>
                                    <td>{{ $loop->iteration + ($inventaris->currentPage() - 1) * $inventaris->perPage() }}</td>
                                    <td>
                                        <span class="badge bg-primary">{{ $item->kode_inventaris }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($item->gambar)
                                                <img src="{{ asset($item->gambar) }}" alt="{{ $item->nama_barang }}" 
                                                     class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center" 
                                                     style="width: 40px; height: 40px;">
                                                    <i class="fas fa-box text-muted"></i>
                                                </div>
                                            @endif
                                            <div style="min-width: 0; flex: 1;">
                                                <strong>{{ $item->nama_barang }}</strong>
                                                @if($item->deskripsi)
                                                    <br><small class="text-muted">{{ Str::limit($item->deskripsi, 50) }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $item->kategori->nama_kategori }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $item->jumlah }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $kondisiColors = [
                                                'Baru' => 'success',
                                                'Baik' => 'primary',
                                                'Rusak Ringan' => 'warning',
                                                'Rusak Berat' => 'danger'
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $kondisiColors[$item->kondisi] }}">
                                            {{ $item->kondisi }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-muted">{{ $item->merk ?? '-' }}</span>
                                    </td>
                                    <td>
                                        @if($item->harga)
                                            <span class="text-success fw-bold">Rp {{ number_format($item->harga, 0, ',', '.') }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-muted">{{ $item->tahun ?? '-' }}</span>
                                    </td>
                                    <td>
                                        @if($item->tanggal_pembelian)
                                            <span class="text-info">{{ \Carbon\Carbon::parse($item->tanggal_pembelian)->format('d/m/Y') }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $item->ruang->nama_ruang }}</strong>
                                            <br><small class="text-muted">{{ $item->lantai->nama_lantai }} - {{ $item->gedung->nama_gedung }}</small>
                                            <br><small class="text-muted">{{ $item->kantor->nama_kantor }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $item->bidang->nama_bidang }}</strong>
                                            @if($item->subBidang)
                                                <br><small class="text-muted">{{ $item->subBidang->nama_sub_bidang }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <small>{{ $item->tanggal_input->format('d M Y') }}</small>
                                    </td>
                                    <td>
                                        @php($actor = Auth::guard('admin')->user())
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('inventaris.show', $item->id) }}" class="btn btn-sm btn-outline-primary" title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if(($actor && $actor->role === 'super_admin') || ($actor && in_array($actor->role, ['admin_regional','staf']) && (int)($item->kantor_id ?? 0) === (int)($actor->kantor_id ?? 0) && (int)($item->bidang_id ?? 0) === (int)($actor->bidang_id ?? 0)))
                                            <a href="{{ route('inventaris.edit', $item->id) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('inventaris.destroy', $item->id) }}" method="POST" 
                                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus inventaris ini?')" style="display: inline;">
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
                                    <td colspan="10" class="text-center py-4">
                                        <i class="fas fa-box text-muted" style="font-size: 3rem;"></i>
                                        <h5 class="text-muted mt-3">Belum ada inventaris</h5>
                                        <p class="text-muted">Klik tombol "Tambah Inventaris" untuk menambahkan data inventaris baru.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($inventaris->hasPages())
                        <div class="d-flex justify-content-center mt-3">
                            {{ $inventaris->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing bulk operations...');
    
    const selectAllCheckbox = document.getElementById('select-all');
    const itemCheckboxes = document.querySelectorAll('.item-checkbox');
    const bulkActionsPanel = document.getElementById('bulk-actions-panel');
    const selectedCountSpan = document.getElementById('selected-count');
    const bulkDeleteBtn = document.getElementById('bulk-delete-btn');
    const bulkExportCsvBtn = document.getElementById('bulk-export-csv-btn');
    const bulkExportExcelBtn = document.getElementById('bulk-export-excel-btn');
    
    console.log('Elements found:', {
        selectAllCheckbox: !!selectAllCheckbox,
        itemCheckboxes: itemCheckboxes.length,
        bulkActionsPanel: !!bulkActionsPanel,
        selectedCountSpan: !!selectedCountSpan,
        bulkDeleteBtn: !!bulkDeleteBtn,
        bulkExportCsvBtn: !!bulkExportCsvBtn,
        bulkExportExcelBtn: !!bulkExportExcelBtn
    });
    
    if (!selectAllCheckbox || !bulkActionsPanel || !selectedCountSpan) {
        console.error('Required elements not found!');
        return;
    }
    
    if (!bulkDeleteBtn) {
        console.error('Delete button not found!');
        return;
    }
    
    if (!bulkExportCsvBtn) {
        console.error('CSV button not found!');
        return;
    }
    
    if (!bulkExportExcelBtn) {
        console.error('Excel button not found!');
        return;
    }

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

        if (confirm(`Apakah Anda yakin ingin menghapus ${ids.length} inventaris yang dipilih?`)) {
            // Buat form untuk bulk delete
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("bulk.delete", "inventaris") }}';
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            ids.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = id;
                form.appendChild(input);
            });
            
        form.appendChild(csrfToken);
        document.body.appendChild(form);
        console.log('Form created, submitting...');
        form.submit();
        }
    });

    // Export Bulk CSV
    bulkExportCsvBtn.addEventListener('click', function() {
        console.log('CSV button clicked!');
        const checkedBoxes = document.querySelectorAll('.item-checkbox:checked');
        const ids = Array.from(checkedBoxes).map(cb => cb.value);
        
        console.log('Selected IDs:', ids);
        
        if (ids.length === 0) {
            alert('Pilih minimal 1 item untuk diexport');
            return;
        }

        // Buat form untuk bulk export CSV
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("bulk.export", "inventaris") }}';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        ids.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'ids[]';
            input.value = id;
            form.appendChild(input);
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
        form.action = '{{ route("bulk.export", "inventaris") }}';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        ids.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'ids[]';
            input.value = id;
            form.appendChild(input);
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

