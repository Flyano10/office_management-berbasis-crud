@extends('layouts.app')

@section('title', 'Data Kantor - PLN Icon Plus Kantor Management')
@section('page-title', 'Data Kantor')
@section('page-subtitle', 'Kelola data kantor PLN Icon Plus')

@section('page-actions')
    @if((Auth::guard('admin')->user()->role ?? '') === 'super_admin')
    <a href="{{ route('kantor.create') }}" class="btn btn-modern btn-primary">
        <i class="fas fa-plus"></i> Tambah Kantor
    </a>
    @endif
@endsection

@section('content')
<div class="container-fluid">
    <!-- Main Content Card -->
    <div class="row">
        <div class="col-12">
            <div class="kantor-card">
                <div class="kantor-header">
                    <h5 class="kantor-title">
                        <i class="fas fa-building"></i>
                        Daftar Kantor PLN Icon Plus
                    </h5>
                </div>
                <div class="kantor-body">
                    <!-- Filter Panel -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="filter-card">
                                <div class="filter-header">
                                    <h6 class="filter-title">
                                        <i class="fas fa-filter"></i>
                                        Filter Data Kantor
                                    </h6>
                                </div>
                                <div class="filter-body">
                                    <form id="filter-form" method="GET">
                                        <div class="row g-3">
                                            <div class="col-md-3">
                                                <label class="form-label">Status Kantor</label>
                                                <select class="form-select modern-select" name="status_kantor" id="status-filter">
                                                    <option value="">Semua Status</option>
                                                    <option value="Aktif" {{ request('status_kantor') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                                    <option value="Tidak Aktif" {{ request('status_kantor') == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Jenis Kantor</label>
                                                <select class="form-select modern-select" name="jenis_kantor" id="jenis-filter">
                                                    <option value="">Semua Jenis</option>
                                                    @foreach($jenisKantor ?? [] as $jenis)
                                                        <option value="{{ $jenis->id }}" {{ request('jenis_kantor') == $jenis->id ? 'selected' : '' }}>
                                                            {{ $jenis->nama_jenis }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Kota</label>
                                                <select class="form-select modern-select" name="kota" id="kota-filter">
                                                    <option value="">Semua Kota</option>
                                                    @foreach($kota ?? [] as $k)
                                                        <option value="{{ $k->id }}" {{ request('kota') == $k->id ? 'selected' : '' }}>
                                                            {{ $k->nama_kota }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Aksi</label>
                                                <div class="d-grid gap-2">
                                                    <button type="submit" class="btn btn-modern btn-filter">
                                                        <i class="fas fa-search"></i> Filter
                                                    </button>
                                                    <a href="{{ route('kantor.index') }}" class="btn btn-modern btn-clear">
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

                    <!-- Bulk Operations Panel (Super Admin Only) -->
                    @if((Auth::guard('admin')->user()->role ?? '') === 'super_admin')
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
                    @endif

                    <div class="table-wrapper">
                        <div class="table-responsive" id="tableContainer">
                            <table class="table modern-table">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">
                                        <input type="checkbox" id="select-all" class="form-check-input modern-checkbox">
                                    </th>
                                    <th style="width: 60px;">#</th>
                                    <th style="min-width: 120px;">Kode Kantor</th>
                                    <th style="min-width: 200px;">Nama Kantor</th>
                                    <th style="min-width: 120px;">Jenis</th>
                                    <th style="min-width: 250px;">Alamat</th>
                                    <th style="min-width: 180px;">Kota</th>
                                    <th style="width: 100px;">Status</th>
                                    <th style="width: 140px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($kantor as $k)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="form-check-input modern-checkbox item-checkbox" value="{{ $k->id }}">
                                    </td>
                                    <td>
                                        <span style="color: #64748b; font-weight: 600;">{{ $loop->iteration + ($kantor->currentPage() - 1) * $kantor->perPage() }}</span>
                                    </td>
                                    <td>
                                        <span class="badge modern-badge badge-kantor">{{ $k->kode_kantor }}</span>
                                    </td>
                                    <td>
                                        <div class="kantor-info">
                                            <strong>{{ $k->nama_kantor }}</strong>
                                            @if($k->parentKantor)
                                                <small>Parent: {{ $k->parentKantor->nama_kantor }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge modern-badge badge-jenis">{{ $k->jenisKantor->nama_jenis }}</span>
                                    </td>
                                    <td class="alamat-cell">{{ Str::limit($k->alamat, 50) }}</td>
                                    <td>
                                        <div class="location-info">
                                            <strong>{{ $k->kota->nama_kota }}</strong>
                                            <small>{{ $k->kota->provinsi->nama_provinsi }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge modern-badge {{ $k->status_kantor == 'aktif' ? 'badge-success' : 'badge-danger' }}">
                                            {{ ucfirst($k->status_kantor) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('kantor.show', $k->id) }}" class="btn btn-modern btn-info btn-sm" title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @php($actor = Auth::guard('admin')->user())
                                            @if(($actor && $actor->role === 'super_admin') || ($actor && in_array($actor->role, ['admin_regional','staf']) && (int)$actor->kantor_id === (int)$k->id))
                                            <a href="{{ route('kantor.edit', $k->id) }}" class="btn btn-modern btn-warning btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @endif
                                            @if($actor && $actor->role === 'super_admin')
                                            <form action="{{ route('kantor.destroy', $k->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-modern btn-danger btn-sm" 
                                                        onclick="return confirm('Apakah Anda yakin ingin menghapus kantor ini?')" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted empty-state">
                                        <div class="empty-content">
                                            <i class="fas fa-building"></i>
                                            <p>Belum ada data kantor. Silakan tambah data kantor baru.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        </div>
                        <!-- Scroll Controls Below Table -->
                        <div class="table-scroll-controls">
                            <button class="scroll-btn scroll-left" id="scrollLeftBtn" aria-label="Scroll left">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <div class="scroll-indicator">
                                <span id="scrollPosition">0</span> / <span id="scrollMax">0</span>
                            </div>
                            <button class="scroll-btn scroll-right" id="scrollRightBtn" aria-label="Scroll right">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Pagination -->
                    @if(method_exists($kantor, 'links'))
                    <div class="pagination-wrapper mt-4">
                        {{ $kantor->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Table Horizontal Scroll with Arrow Buttons
    const tableContainer = document.getElementById('tableContainer');
    const scrollLeftBtn = document.getElementById('scrollLeftBtn');
    const scrollRightBtn = document.getElementById('scrollRightBtn');

    // Initialize auto-scroll functionality (no click-hold needed)
    if (tableContainer) {
        let autoScrollInterval = null;
        let scrollSpeed = 0;

        // Auto-scroll on mouse move near edges
        tableContainer.addEventListener('mousemove', function(e) {
            if (tableContainer.scrollWidth <= tableContainer.clientWidth) {
                return; // No scroll needed
            }

            const rect = tableContainer.getBoundingClientRect();
            const mouseX = e.clientX - rect.left;
            const containerWidth = rect.width;
            const edgeThreshold = 50; // Distance from edge to trigger scroll
            const maxSpeed = 15; // Maximum scroll speed

            // Clear existing interval
            if (autoScrollInterval) {
                clearInterval(autoScrollInterval);
                autoScrollInterval = null;
            }

            // Check if mouse is near left edge
            if (mouseX < edgeThreshold && tableContainer.scrollLeft > 0) {
                scrollSpeed = -maxSpeed * (1 - mouseX / edgeThreshold);
                autoScrollInterval = setInterval(function() {
                    if (tableContainer.scrollLeft > 0) {
                        tableContainer.scrollLeft += scrollSpeed;
                    } else {
                        clearInterval(autoScrollInterval);
                        autoScrollInterval = null;
                    }
                }, 16); // ~60fps
            }
            // Check if mouse is near right edge
            else if (mouseX > containerWidth - edgeThreshold) {
                const maxScroll = tableContainer.scrollWidth - tableContainer.clientWidth;
                if (tableContainer.scrollLeft < maxScroll) {
                    const distanceFromRight = containerWidth - mouseX;
                    scrollSpeed = maxSpeed * (1 - distanceFromRight / edgeThreshold);
                    autoScrollInterval = setInterval(function() {
                        const maxScroll = tableContainer.scrollWidth - tableContainer.clientWidth;
                        if (tableContainer.scrollLeft < maxScroll) {
                            tableContainer.scrollLeft += scrollSpeed;
                        } else {
                            clearInterval(autoScrollInterval);
                            autoScrollInterval = null;
                        }
                    }, 16); // ~60fps
                }
            }
        });

        // Stop auto-scroll when mouse leaves
        tableContainer.addEventListener('mouseleave', function() {
            if (autoScrollInterval) {
                clearInterval(autoScrollInterval);
                autoScrollInterval = null;
            }
        });

        // Horizontal wheel scroll - natural scrolling (primary method)
        // This works with mouse wheel, trackpad, and touch gestures
        tableContainer.addEventListener('wheel', function(e) {
            // Only handle if there's horizontal scroll available
            if (tableContainer.scrollWidth <= tableContainer.clientWidth) {
                return;
            }

            // Check if this is a horizontal scroll gesture
            const deltaX = e.deltaX;
            const deltaY = e.deltaY;
            const hasHorizontalScroll = Math.abs(deltaX) > 0;
            const hasVerticalScroll = Math.abs(deltaY) > 0;
            
            // Priority 1: If horizontal scroll detected (trackpad swipe or mouse wheel tilt), use it directly
            if (hasHorizontalScroll && Math.abs(deltaX) >= Math.abs(deltaY)) {
                e.preventDefault();
                tableContainer.scrollLeft += deltaX;
                return;
            }
            
            // Priority 2: If shift + vertical scroll, convert to horizontal
            if (e.shiftKey && hasVerticalScroll) {
                e.preventDefault();
                tableContainer.scrollLeft += deltaY;
                return;
            }
            
            // Priority 3: For trackpad, if there's any horizontal component, use it
            // This handles diagonal swipes on trackpad
            if (hasHorizontalScroll && Math.abs(deltaX) > 2) {
                e.preventDefault();
                tableContainer.scrollLeft += deltaX;
                return;
            }
        }, { passive: false });

        // Also enable horizontal scroll with trackpad gestures
        // This handles two-finger horizontal swipe on trackpad
        let lastTouchX = null;
        tableContainer.addEventListener('touchstart', function(e) {
            if (e.touches.length === 1) {
                lastTouchX = e.touches[0].clientX;
            }
        }, { passive: true });

        tableContainer.addEventListener('touchmove', function(e) {
            if (e.touches.length === 1 && lastTouchX !== null) {
                const currentX = e.touches[0].clientX;
                const diffX = lastTouchX - currentX;
                
                if (Math.abs(diffX) > 5) { // Minimum swipe distance
                    e.preventDefault();
                    tableContainer.scrollLeft += diffX;
                    lastTouchX = currentX;
                }
            }
        }, { passive: false });
    }

    // Initialize scroll buttons (if they exist)
    if (tableContainer && scrollLeftBtn && scrollRightBtn) {
        const scrollPosition = document.getElementById('scrollPosition');
        const scrollMax = document.getElementById('scrollMax');

        function updateScrollButtons() {
            const { scrollLeft, scrollWidth, clientWidth } = tableContainer;
            const maxScroll = scrollWidth - clientWidth;
            
            // Update scroll indicator
            if (scrollPosition && scrollMax) {
                scrollPosition.textContent = Math.round(scrollLeft);
                scrollMax.textContent = Math.round(maxScroll);
            }
            
            // Enable/disable left button
            if (scrollLeft > 5) {
                scrollLeftBtn.disabled = false;
            } else {
                scrollLeftBtn.disabled = true;
            }
            
            // Enable/disable right button
            if (scrollLeft < maxScroll - 5) {
                scrollRightBtn.disabled = false;
            } else {
                scrollRightBtn.disabled = true;
            }
        }

        // Initial check
        setTimeout(updateScrollButtons, 100);

        // Scroll event listener
        tableContainer.addEventListener('scroll', updateScrollButtons);

        // Window resize listener
        window.addEventListener('resize', updateScrollButtons);

        // Left scroll button
        scrollLeftBtn.addEventListener('click', function() {
            if (!this.disabled) {
                tableContainer.scrollBy({
                    left: -300,
                    behavior: 'smooth'
                });
            }
        });

        // Right scroll button
        scrollRightBtn.addEventListener('click', function() {
            if (!this.disabled) {
                tableContainer.scrollBy({
                    left: 300,
                    behavior: 'smooth'
                });
            }
        });
    }

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
            window.Toast.warning('Pilih minimal 1 item untuk dihapus');
            return;
        }

        if (confirm(`Apakah Anda yakin ingin menghapus ${ids.length} kantor yang dipilih?`)) {
            // Buat form untuk bulk delete
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("bulk.delete", "kantor") }}';
            
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
        form.action = '{{ route("bulk.export", "kantor") }}';
        
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
        form.action = '{{ route("bulk.export", "kantor") }}';
        
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

// Fungsi pencarian
function searchKantor() {
    const searchInput = document.querySelector('.main-content .search-input');
    if (!searchInput) return;
    
    const searchTerm = searchInput.value.toLowerCase().trim();
    const tableRows = document.querySelectorAll('.modern-table tbody tr');
    
    tableRows.forEach(row => {
        const text = row.textContent.toLowerCase();
        if (text.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Tambah event listener untuk input pencarian
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('.main-content .search-input');
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            searchKantor();
        });
    }
});
</script>
@endsection

@push('styles')
<style>
    :root {
        --pln-blue: #21618C;
        --pln-blue-dark: #1A4D73;
        --pln-blue-light: #2E86AB;
        --pln-blue-lighter: #E8F4F8;
        --pln-blue-bg: #F5FAFC;
    }
    /* Aksi Header */
    .header-actions {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .search-container {
        position: relative;
        display: flex;
        align-items: center;
    }

    .search-input {
        padding: 0.75rem 1rem;
        border: 2px solid #e2e8f0;
        border-radius: 0.75rem;
        background: white;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        width: 300px;
    }

    .search-input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .search-btn {
        position: absolute;
        right: 0.5rem;
        background: #3b82f6;
        color: white;
        border: none;
        border-radius: 0.5rem;
        padding: 0.5rem;
        transition: all 0.3s ease;
    }

    .search-btn:hover {
        background: #2563eb;
        transform: scale(1.05);
    }

    /* Card Kantor - Modern Design */
    .kantor-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 1px 3px rgba(33, 97, 140, 0.08), 0 4px 12px rgba(33, 97, 140, 0.05);
        border: 1px solid rgba(33, 97, 140, 0.1);
        overflow: hidden;
    }

    .kantor-header {
        background: white;
        padding: 1.5rem 1.75rem;
        border-bottom: 1px solid rgba(33, 97, 140, 0.1);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .kantor-title {
        font-size: 1.35rem;
        font-weight: 700;
        color: var(--pln-blue);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        letter-spacing: -0.02em;
    }

    .kantor-title i {
        color: var(--pln-blue);
        font-size: 1.5rem;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--pln-blue-lighter);
        border-radius: 10px;
    }

    .kantor-body {
        padding: 1.5rem 1.75rem;
    }

    /* Table Wrapper with Scroll Controls */
    .table-wrapper {
        position: relative;
        margin: 1.5rem 0;
    }

    .table-responsive {
        border-radius: 0;
        overflow-x: auto;
        overflow-y: visible;
        scrollbar-width: none; /* Firefox */
        -ms-overflow-style: none; /* IE and Edge */
        scroll-behavior: smooth;
        touch-action: pan-x pinch-zoom; /* Enable horizontal panning on touch devices */
    }

    /* Default cursor on table area */
    .table-responsive tbody,
    .table-responsive tbody tr,
    .table-responsive tbody td {
        cursor: default;
    }

    /* Interactive elements keep pointer cursor and allow selection */
    .table-responsive input,
    .table-responsive button,
    .table-responsive a,
    .table-responsive label,
    .table-responsive .badge {
        cursor: pointer !important;
        user-select: auto !important;
        -webkit-user-select: auto !important;
        -moz-user-select: auto !important;
        -ms-user-select: auto !important;
    }

    .table-responsive::-webkit-scrollbar {
        display: none; /* Chrome, Safari, Opera */
    }

    /* Scroll Controls Below Table */
    .table-scroll-controls {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 1rem;
        padding: 0.75rem 1rem;
        background: #fafbfc;
        border-top: 1px solid rgba(33, 97, 140, 0.1);
        margin-top: 0;
    }

    .scroll-btn {
        background: white;
        color: var(--pln-blue);
        border: 2px solid var(--pln-blue);
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 2px 6px rgba(33, 97, 140, 0.15);
        transition: all 0.2s ease;
    }

    .scroll-btn:hover:not(:disabled) {
        background: var(--pln-blue-lighter);
        border-color: var(--pln-blue-dark);
        box-shadow: 0 4px 10px rgba(33, 97, 140, 0.25);
        transform: scale(1.1);
    }

    .scroll-btn:active:not(:disabled) {
        transform: scale(0.95);
    }

    .scroll-btn:disabled {
        opacity: 0.4;
        cursor: not-allowed;
    }

    .scroll-btn i {
        font-size: 0.85rem;
        color: var(--pln-blue);
    }

    .scroll-indicator {
        font-size: 0.8rem;
        color: #64748b;
        font-weight: 600;
        min-width: 60px;
        text-align: center;
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .table-scroll-controls {
            padding: 0.5rem;
            gap: 0.75rem;
        }
        
        .scroll-btn {
            width: 32px;
            height: 32px;
        }
        
        .scroll-indicator {
            font-size: 0.75rem;
            min-width: 50px;
        }
        
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        .table-responsive::-webkit-scrollbar {
            display: block;
            height: 4px;
        }
        
        .table-responsive::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        
        .table-responsive::-webkit-scrollbar-thumb {
            background: var(--pln-blue);
            border-radius: 2px;
        }
    }

    /* Card Filter - Modern Collapsible Design */
    .filter-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(33, 97, 140, 0.08), 0 2px 8px rgba(33, 97, 140, 0.05);
        border: 1px solid rgba(33, 97, 140, 0.1);
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .filter-header {
        background: white;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid rgba(33, 97, 140, 0.1);
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .filter-header:hover {
        background: var(--pln-blue-lighter);
    }

    .filter-title {
        font-size: 1rem;
        font-weight: 600;
        color: var(--pln-blue);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .filter-title i {
        color: var(--pln-blue);
        font-size: 1.1rem;
    }

    .filter-body {
        padding: 1.5rem;
        background: #fafbfc;
    }

    .form-label {
        font-weight: 600;
        color: #1e293b;
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
        display: block;
    }

    .modern-select {
        border: 1.5px solid rgba(33, 97, 140, 0.2);
        border-radius: 10px;
        padding: 0.625rem 1rem;
        background: white;
        transition: all 0.2s ease;
        font-size: 0.9rem;
        color: #1e293b;
        font-weight: 500;
    }

    .modern-select:hover {
        border-color: rgba(33, 97, 140, 0.4);
    }

    .modern-select:focus {
        border-color: var(--pln-blue);
        box-shadow: 0 0 0 4px rgba(33, 97, 140, 0.1);
        outline: none;
        background: white;
    }

    /* Button Modern - Clean Design */
    .btn-modern {
        border-radius: 10px;
        padding: 0.625rem 1.25rem;
        font-weight: 600;
        transition: all 0.2s ease;
        border: 1px solid transparent;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        cursor: pointer;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }

    .btn-modern:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(33, 97, 140, 0.2);
    }

    .btn-modern.btn-primary {
        background: var(--pln-blue);
        color: white;
        border: 1px solid var(--pln-blue);
        box-shadow: 0 2px 6px rgba(33, 97, 140, 0.15);
    }

    .btn-modern.btn-primary:hover {
        background: var(--pln-blue-dark);
        border-color: var(--pln-blue-dark);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(33, 97, 140, 0.25);
    }

    .btn-modern.btn-filter {
        background: var(--pln-blue);
        color: white;
        border: 1px solid var(--pln-blue);
        box-shadow: 0 2px 6px rgba(33, 97, 140, 0.15);
    }

    .btn-modern.btn-filter:hover {
        background: var(--pln-blue-dark);
        border-color: var(--pln-blue-dark);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(33, 97, 140, 0.25);
    }

    .btn-modern.btn-clear {
        background: white;
        color: #64748b;
        border: 1px solid #e2e8f0;
    }

    .btn-modern.btn-clear:hover {
        background: #f8f9fa;
        color: #475569;
        border-color: #cbd5e0;
        transform: translateY(-1px);
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
    }

    /* Action Buttons - PLN Branding */
    .btn-modern.btn-info {
        background: var(--pln-blue);
        color: white;
        border: 1px solid var(--pln-blue);
    }

    .btn-modern.btn-info:hover {
        background: var(--pln-blue-dark);
        border-color: var(--pln-blue-dark);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(33, 97, 140, 0.25);
    }

    .btn-modern.btn-warning {
        background: var(--pln-blue);
        color: white;
        border: 1px solid var(--pln-blue);
    }

    .btn-modern.btn-warning:hover {
        background: var(--pln-blue-dark);
        border-color: var(--pln-blue-dark);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(33, 97, 140, 0.25);
    }

    .btn-modern.btn-danger {
        background: #dc3545;
        color: white;
        border: 1px solid #dc3545;
    }

    .btn-modern.btn-danger:hover {
        background: #c82333;
        border-color: #c82333;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(220, 53, 69, 0.25);
    }

    .btn-modern.btn-success {
        background: #28a745;
        color: white;
        border: 1px solid #28a745;
    }

    .btn-modern.btn-success:hover {
        background: #218838;
        border-color: #218838;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(40, 167, 69, 0.25);
    }

    /* Aksi Bulk */
    .bulk-actions-card {
        background: var(--pln-blue-lighter);
        border: 2px solid var(--pln-blue);
        border-radius: 10px;
        padding: 1rem 1.25rem;
        box-shadow: 0 2px 8px rgba(33, 97, 140, 0.15);
    }

    .bulk-actions-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .bulk-info {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--pln-blue-dark);
        font-weight: 600;
    }

    .bulk-info i {
        color: var(--pln-blue);
    }

    .bulk-actions {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
        align-items: center;
    }

    .bulk-actions .btn-modern {
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        border: 1px solid transparent;
    }

    .bulk-actions .btn-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    /* Tabel Modern - Clean & Professional */
    .modern-table {
        margin: 0;
        border-radius: 0;
        overflow: hidden;
        border: 1px solid rgba(33, 97, 140, 0.1);
        background: white;
    }

    .modern-table thead th {
        background: white;
        border: none;
        padding: 1rem 1.25rem;
        font-weight: 700;
        color: var(--pln-blue);
        border-bottom: 2px solid var(--pln-blue);
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        position: sticky;
        top: 0;
        z-index: 10;
        white-space: nowrap;
    }

    .modern-table tbody td {
        padding: 1rem 1.25rem;
        border: none;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
        font-size: 0.9rem;
        color: #1e293b;
    }

    .modern-table tbody tr {
        transition: all 0.15s ease;
        background: white;
    }

    .modern-table tbody tr:hover {
        background: var(--pln-blue-lighter);
    }

    .modern-table tbody tr:last-child td {
        border-bottom: none;
    }

    .modern-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Modern Checkbox */
    .modern-checkbox {
        width: 1.2rem;
        height: 1.2rem;
        border-radius: 4px;
        border: 2px solid rgba(33, 97, 140, 0.3);
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .modern-checkbox:checked {
        background: var(--pln-blue);
        border-color: var(--pln-blue);
    }

    .modern-checkbox:focus {
        box-shadow: 0 0 0 3px rgba(33, 97, 140, 0.15);
        outline: none;
    }

    /* Modern Badges - Clean Design */
    .modern-badge {
        padding: 0.375rem 0.875rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.75rem;
        display: inline-block;
        letter-spacing: 0.3px;
    }

    .badge-kantor {
        background: var(--pln-blue);
        color: white;
    }

    .badge-jenis {
        background: var(--pln-blue-light);
        color: white;
    }

    .badge-success {
        background: #10b981;
        color: white;
    }

    .badge-danger {
        background: #ef4444;
        color: white;
    }

    /* Table Content - Modern Typography */
    .kantor-info strong {
        color: #1e293b;
        font-weight: 700;
        font-size: 0.95rem;
        display: block;
        margin-bottom: 0.25rem;
    }

    .kantor-info small {
        color: #64748b;
        font-size: 0.8rem;
    }

    .location-info strong {
        color: #1e293b;
        font-weight: 600;
        font-size: 0.9rem;
        display: block;
        margin-bottom: 0.25rem;
    }

    .location-info small {
        color: #64748b;
        font-size: 0.8rem;
    }

    .alamat-cell {
        max-width: 250px;
        word-wrap: break-word;
        color: #475569;
        font-size: 0.875rem;
        line-height: 1.5;
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
        align-items: center;
    }

    .action-buttons .btn-modern {
        padding: 0.5rem 0.75rem;
        min-width: 2.5rem;
        height: 2.5rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        font-size: 0.8rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        transition: all 0.2s ease;
    }

    .action-buttons .btn-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    .action-buttons .btn-modern.btn-sm {
        padding: 0.4rem 0.65rem;
        min-width: 2.25rem;
        height: 2.25rem;
        font-size: 0.75rem;
    }

    /* Empty State - Modern Design */
    .empty-state {
        padding: 4rem 2rem;
    }

    .empty-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1rem;
    }

    .empty-content i {
        font-size: 3.5rem;
        color: var(--pln-blue-lighter);
    }

    .empty-content p {
        margin: 0;
        color: #64748b;
        font-size: 1rem;
        font-weight: 500;
    }

    /* Pagination - Modern Design */
    .pagination-wrapper {
        display: flex;
        justify-content: center;
        padding: 1.5rem 0;
    }

    .pagination-wrapper .pagination {
        margin: 0;
        gap: 0.5rem;
    }

    .pagination-wrapper .page-link {
        border: 1px solid rgba(33, 97, 140, 0.2);
        color: var(--pln-blue);
        padding: 0.5rem 0.875rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .pagination-wrapper .page-link:hover {
        background: var(--pln-blue-lighter);
        border-color: var(--pln-blue);
        color: var(--pln-blue-dark);
    }

    .pagination-wrapper .page-item.active .page-link {
        background: var(--pln-blue);
        border-color: var(--pln-blue);
        color: white;
    }

    .pagination-wrapper .page-item.disabled .page-link {
        color: #94a3b8;
        border-color: #e2e8f0;
        background: #f8f9fa;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .header-actions {
            flex-direction: column;
            align-items: stretch;
        }

        .search-input {
            width: 100%;
        }

        .bulk-actions-content {
            flex-direction: column;
            align-items: stretch;
        }

        .bulk-actions {
            justify-content: center;
        }

        .action-buttons {
            justify-content: center;
        }

        .modern-table {
            font-size: 0.9rem;
        }

        .modern-table th,
        .modern-table td {
            padding: 0.75rem 0.5rem;
        }
    }
</style>
@endpush
