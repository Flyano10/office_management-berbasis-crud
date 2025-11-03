@extends('layouts.app')

@section('title', 'Kategori Inventaris')
@section('page-title', 'Kategori Inventaris')
@section('page-subtitle', 'Kelola data kategori inventaris PLN Icon Plus')

@section('page-actions')
<a href="{{ route('kategori-inventaris.create') }}" class="btn btn-modern btn-primary">
    <i class="fas fa-plus"></i> Tambah Kategori Inventaris
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
                            <i class="fas fa-tags me-2"></i>Kategori Inventaris
                        </h3>
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

                    <div class="table-responsive">
                        <table class="table modern-table">
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" id="select-all" class="form-check-input modern-checkbox">
                                    </th>
                                    <th>No</th>
                                    <th>Nama Kategori</th>
                                    <th>Deskripsi</th>
                                    <th>Jumlah Inventaris</th>
                                    <th>Dibuat</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($kategori as $item)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="form-check-input modern-checkbox item-checkbox" value="{{ $item->id }}">
                                    </td>
                                    <td>{{ $loop->iteration + ($kategori->currentPage() - 1) * $kategori->perPage() }}</td>
                                    <td>
                                        <strong>{{ $item->nama_kategori }}</strong>
                                    </td>
                                    <td>
                                        @if($item->deskripsi)
                                        {{ Str::limit($item->deskripsi, 50) }}
                                        @else
                                        <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $item->inventaris->count() }}</span>
                                    </td>
                                    <td>
                                        <small>{{ $item->created_at->format('d M Y') }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('kategori-inventaris.show', $item->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('kategori-inventaris.edit', $item->id) }}" class="btn btn-sm btn-outline-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('kategori-inventaris.destroy', $item->id) }}" method="POST"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <i class="fas fa-tags text-muted" style="font-size: 3rem;"></i>
                                        <h5 class="text-muted mt-3">Belum ada kategori</h5>
                                        <p class="text-muted">Klik tombol "Tambah Kategori" untuk menambahkan kategori baru.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($kategori->hasPages())
                    <div class="d-flex justify-content-center mt-3">
                        {{ $kategori->links() }}
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

            if (confirm(`Apakah Anda yakin ingin menghapus ${ids.length} kategori inventaris yang dipilih?`)) {
                // Buat form untuk bulk delete
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("bulk.delete", "kategori_inventaris") }}';

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
            form.action = '{{ route("bulk.export", "kategori_inventaris") }}';

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
            form.action = '{{ route("bulk.export", "kategori_inventaris") }}';

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