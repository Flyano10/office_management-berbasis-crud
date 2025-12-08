@extends('layouts.app')

@section('title', 'Admin Management - PLN Icon Plus Kantor Management')
@section('page-title', 'Admin Management')
@section('page-subtitle', 'Kelola data administrator sistem')

@section('page-actions')
    @php($actor = Auth::guard('admin')->user())
    @if($actor && in_array($actor->role, ['super_admin','admin','admin_regional','manager_bidang']))
        <a href="{{ route('admin.create') }}" class="btn btn-modern btn-primary">
            <i class="fas fa-plus"></i> Tambah Admin
        </a>
    @endif
@endsection

@section('content')
<div class="container-fluid">
    <!-- Main Content Card -->
    <div class="row">
        <div class="col-12">
            <div class="admin-card">
                <div class="admin-header">
                    <h5 class="admin-title">
                        <i class="fas fa-users-cog"></i>
                        Daftar Administrator PLN Icon Plus
                    </h5>
                </div>
                <div class="admin-body">
                    <!-- Filter Panel -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="filter-card">
                                <div class="filter-header">
                                    <h6 class="filter-title">
                                        <i class="fas fa-filter"></i>
                                        Filter Data Admin
                                    </h6>
                                </div>
                                <div class="filter-body">
                                    @php($actor = Auth::guard('admin')->user())
                                    <form id="filter-form" method="GET" action="{{ route('admin.index') }}">
                                        <div class="row g-3">
                                            <div class="col-md-3">
                                                <label class="form-label">Cari Admin</label>
                                                <input type="text" class="form-control modern-input" id="search" name="search"
                                                    value="{{ request('search') }}" placeholder="Nama, email, atau username...">
                                            </div>
                                            @if($actor && in_array($actor->role, ['super_admin','admin']))
                                            <div class="col-md-2">
                                                <label class="form-label">Role</label>
                                                <select class="form-select modern-select" id="role" name="role">
                                                    <option value="">Semua Role</option>
                                                    <option value="super_admin" {{ request('role') == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                                                    <option value="admin_regional" {{ request('role') == 'admin_regional' ? 'selected' : '' }}>Admin Regional</option>
                                                    <option value="manager_bidang" {{ request('role') == 'manager_bidang' ? 'selected' : '' }}>Manager Bidang</option>
                                                    <option value="staf" {{ request('role') == 'staf' ? 'selected' : '' }}>Staf</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Bidang</label>
                                                <select class="form-select modern-select" id="bidang_id" name="bidang_id">
                                                    <option value="">Semua Bidang</option>
                                                    @foreach ($bidangs as $bidang)
                                                        <option value="{{ $bidang->id }}" {{ request('bidang_id') == $bidang->id ? 'selected' : '' }}>{{ $bidang->nama_bidang }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Kantor</label>
                                                <select class="form-select modern-select" id="kantor_id" name="kantor_id">
                                                    <option value="">Semua Kantor</option>
                                                    @foreach ($kantors as $kantor)
                                                        <option value="{{ $kantor->id }}" {{ request('kantor_id') == $kantor->id ? 'selected' : '' }}>{{ $kantor->nama_kantor }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @endif
                                            <div class="col-md-3">
                                                <label class="form-label">Status</label>
                                                <select class="form-select modern-select" id="status" name="status">
                                                    <option value="">Semua Status</option>
                                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                                                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row g-3 mt-2">
                                            <div class="col-md-3">
                                                <label class="form-label">Aksi</label>
                                                <div class="d-grid gap-2">
                                                    <button type="submit" class="btn btn-modern btn-filter">
                                                        <i class="fas fa-search"></i> Filter
                                                    </button>
                                                    <a href="{{ route('admin.index') }}" class="btn btn-modern btn-clear">
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

                    <div class="table-wrapper">
                        <div class="table-responsive" id="tableContainer">
                            <table class="table modern-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Admin</th>
                                    <th>Email</th>
                                    <th>Username</th>
                                    <th>Role</th>
                                    <th>Bidang</th>
                                    <th>Kantor</th>
                                    <th>Status</th>
                                    <th>Login Terakhir</th>
                                    <th>Dibuat</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php($actor = Auth::guard('admin')->user())
                                @forelse($admins as $admin)
                                <tr>
                                    <td>{{ $loop->iteration + ($admins->currentPage() - 1) * $admins->perPage() }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-light text-secondary rounded-circle d-flex align-items-center justify-content-center me-2 border" style="width: 40px; height: 40px; font-size: 0.875rem; font-weight: 600;">
                                                {{ strtoupper(substr($admin->nama_admin, 0, 2)) }}
                                            </div>
                                            <div>
                                                <strong>{{ $admin->nama_admin }}</strong>
                                                <br><small style="color: #64748b;">ID: {{ $admin->id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <small style="color: #64748b;">{{ $admin->email }}</small>
                                    </td>
                                    <td>
                                        <span class="badge modern-badge badge-primary">{{ $admin->username }}</span>
                                    </td>
                                    <td>
                                        <span class="badge modern-badge badge-info">{{ str_replace('_',' ', $admin->role) }}</span>
                                    </td>
                                    <td>
                                        @if ($admin->bidang)
                                            <small style="color: #64748b;">{{ $admin->bidang->nama_bidang }}</small>
                                        @else
                                            <small style="color: #64748b;">-</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($admin->kantor)
                                            <small style="color: #64748b;">{{ $admin->kantor->nama_kantor }}</small>
                                        @else
                                            <small style="color: #64748b;">-</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge modern-badge badge-{{ $admin->is_active ? 'success' : 'secondary' }}">
                                            {{ $admin->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($admin->last_login)
                                            <small style="color: #64748b;">{{ $admin->last_login->format('d/m/Y H:i') }}</small>
                                        @else
                                            <small style="color: #64748b;">Belum pernah login</small>
                                        @endif
                                    </td>
                                    <td>
                                        <small style="color: #64748b;">{{ $admin->created_at->format('d/m/Y H:i') }}</small>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('admin.show', $admin->id) }}" class="btn btn-modern btn-info btn-sm" title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            @php($inScopeRegional = (in_array($admin->role, ['manager_bidang','staf'])) && ($actor && (int)$admin->kantor_id === (int)($actor->kantor_id ?? 0)) && (!($actor->bidang_id ?? null) || (int)$admin->bidang_id === (int)$actor->bidang_id))
                                            @php($inScopeMB = ($admin->role === 'staf') && ($actor && (int)$admin->kantor_id === (int)($actor->kantor_id ?? 0)) && ((int)$admin->bidang_id === (int)($actor->bidang_id ?? 0)))

                                            @if(($actor && in_array($actor->role, ['super_admin','admin'])) || ($actor && (int)$actor->id === (int)$admin->id))
                                            <a href="{{ route('admin.edit', $admin->id) }}" class="btn btn-modern btn-warning btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @endif

                                            @if ($admin->role !== 'super_admin')
                                                @if(($actor && $actor->role === 'super_admin') || ($actor && $actor->role === 'admin_regional' && $inScopeRegional) || ($actor && $actor->role === 'manager_bidang' && $inScopeMB))
                                                <form action="{{ route('admin.toggle-status', $admin->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-modern btn-{{ $admin->is_active ? 'warning' : 'success' }} btn-sm" 
                                                            title="{{ $admin->is_active ? 'Nonaktifkan' : 'Aktifkan' }}"
                                                            data-action="{{ $admin->is_active ? 'menonaktifkan' : 'mengaktifkan' }}"
                                                            onclick="return confirmToggle(this)">
                                                        <i class="fas {{ $admin->is_active ? 'fa-ban' : 'fa-check' }}"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.destroy', $admin->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-modern btn-danger btn-sm" 
                                                            onclick="return confirm('Yakin ingin menghapus admin ini?')" title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                                @endif
                                            @else
                                                <span class="btn btn-modern btn-info btn-sm disabled" title="Super Admin tidak dapat dihapus">
                                                    <i class="fas fa-shield-alt"></i>
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="11" class="text-center text-muted empty-state">
                                        <div class="empty-content">
                                            <i class="fas fa-users-cog"></i>
                                            <p>Belum ada data admin. Silakan tambah data admin baru.</p>
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

                    @if($admins->hasPages())
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <div class="text-muted">
                                Menampilkan {{ $admins->firstItem() }} - {{ $admins->lastItem() }}
                                dari {{ $admins->total() }} admin
                            </div>
                            <div>
                                {{ $admins->links() }}
                            </div>
                        </div>
                    @endif
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

    // Initialize auto-scroll functionality
    if (tableContainer) {
        let autoScrollInterval = null;
        let scrollSpeed = 0;

        // Auto-scroll on mouse move near edges
        tableContainer.addEventListener('mousemove', function(e) {
            if (tableContainer.scrollWidth <= tableContainer.clientWidth) {
                return;
            }

            const rect = tableContainer.getBoundingClientRect();
            const mouseX = e.clientX - rect.left;
            const containerWidth = rect.width;
            const edgeThreshold = 50;
            const maxSpeed = 15;

            if (autoScrollInterval) {
                clearInterval(autoScrollInterval);
                autoScrollInterval = null;
            }

            if (mouseX < edgeThreshold && tableContainer.scrollLeft > 0) {
                scrollSpeed = -maxSpeed * (1 - mouseX / edgeThreshold);
                autoScrollInterval = setInterval(function() {
                    if (tableContainer.scrollLeft > 0) {
                        tableContainer.scrollLeft += scrollSpeed;
                    } else {
                        clearInterval(autoScrollInterval);
                        autoScrollInterval = null;
                    }
                }, 16);
            }
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
                    }, 16);
                }
            }
        });

        tableContainer.addEventListener('mouseleave', function() {
            if (autoScrollInterval) {
                clearInterval(autoScrollInterval);
                autoScrollInterval = null;
            }
        });

        // Horizontal wheel scroll
        tableContainer.addEventListener('wheel', function(e) {
            if (tableContainer.scrollWidth <= tableContainer.clientWidth) {
                return;
            }

            const deltaX = e.deltaX;
            const deltaY = e.deltaY;
            const hasHorizontalScroll = Math.abs(deltaX) > 0;
            const hasVerticalScroll = Math.abs(deltaY) > 0;
            
            if (hasHorizontalScroll && Math.abs(deltaX) >= Math.abs(deltaY)) {
                e.preventDefault();
                tableContainer.scrollLeft += deltaX;
                return;
            }
            
            if (e.shiftKey && hasVerticalScroll) {
                e.preventDefault();
                tableContainer.scrollLeft += deltaY;
                return;
            }
            
            if (hasHorizontalScroll && Math.abs(deltaX) > 2) {
                e.preventDefault();
                tableContainer.scrollLeft += deltaX;
                return;
            }
        }, { passive: false });

        // Touch scroll
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
                
                if (Math.abs(diffX) > 5) {
                    e.preventDefault();
                    tableContainer.scrollLeft += diffX;
                    lastTouchX = currentX;
                }
            }
        }, { passive: false });
    }

    // Initialize scroll buttons
    if (tableContainer && scrollLeftBtn && scrollRightBtn) {
        const scrollPosition = document.getElementById('scrollPosition');
        const scrollMax = document.getElementById('scrollMax');

        function updateScrollButtons() {
            const { scrollLeft, scrollWidth, clientWidth } = tableContainer;
            const maxScroll = scrollWidth - clientWidth;
            
            if (scrollPosition && scrollMax) {
                scrollPosition.textContent = Math.round(scrollLeft);
                scrollMax.textContent = Math.round(maxScroll);
            }
            
            if (scrollLeft > 5) {
                scrollLeftBtn.disabled = false;
            } else {
                scrollLeftBtn.disabled = true;
            }
            
            if (scrollLeft < maxScroll - 5) {
                scrollRightBtn.disabled = false;
            } else {
                scrollRightBtn.disabled = true;
            }
        }

        setTimeout(updateScrollButtons, 100);
        tableContainer.addEventListener('scroll', updateScrollButtons);
        window.addEventListener('resize', updateScrollButtons);

        scrollLeftBtn.addEventListener('click', function() {
            if (!this.disabled) {
                tableContainer.scrollBy({
                    left: -300,
                    behavior: 'smooth'
                });
            }
        });

        scrollRightBtn.addEventListener('click', function() {
            if (!this.disabled) {
                tableContainer.scrollBy({
                    left: 300,
                    behavior: 'smooth'
                });
            }
        });
    }
});

function confirmToggle(button) {
    const action = button.getAttribute('data-action');
    return confirm('Yakin ingin ' + action + ' admin ini?');
}
</script>

@push('styles')
<style>
    :root {
        --pln-blue: #21618C;
        --pln-blue-dark: #1A4D73;
        --pln-blue-light: #2E86AB;
        --pln-blue-lighter: #E8F4F8;
        --pln-blue-bg: #F5FAFC;
        --white: #FFFFFF;
        --gray-light: #F8F9FA;
        --gray-border: #E0E0E0;
        --text-dark: #1A1A1A;
        --text-gray: #6C757D;
        --success-color: #28A745;
        --danger-color: #DC3545;
    }

    /* Card Admin */
    .admin-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(33, 97, 140, 0.1);
        border: 1px solid rgba(33, 97, 140, 0.15);
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .admin-header {
        background: white;
        padding: 1.25rem 1.75rem;
        border-bottom: 2px solid var(--pln-blue);
    }

    .admin-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--pln-blue);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .admin-title i {
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

    .admin-body {
        padding: 1.5rem 1.75rem;
    }

    /* Card Filter */
    .filter-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(33, 97, 140, 0.1);
        border: 1px solid rgba(33, 97, 140, 0.15);
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .filter-header {
        background: white;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid var(--gray-border);
        transition: background 0.2s ease;
    }

    .filter-header:hover {
        background: var(--pln-blue-bg);
    }

    .filter-title {
        font-size: 0.9375rem;
        font-weight: 700;
        color: var(--pln-blue);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .filter-title i {
        color: var(--pln-blue);
        font-size: 1rem;
    }

    .filter-body {
        padding: 1.25rem 1.5rem;
        background: #fafbfc;
    }

    .form-label {
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
    }

    .modern-input,
    .modern-select {
        border: 1px solid #d1d5db;
        border-radius: 8px;
        padding: 0.625rem 0.875rem;
        background: white;
        transition: all 0.2s ease;
        font-size: 0.875rem;
    }

    .modern-input:hover,
    .modern-select:hover {
        border-color: var(--pln-blue);
    }

    .modern-input:focus,
    .modern-select:focus {
        border-color: var(--pln-blue);
        box-shadow: 0 0 0 3px rgba(33, 97, 140, 0.1);
        outline: none;
    }

    /* Button Modern */
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

    /* Table Wrapper with Scroll Controls */
    .table-wrapper {
        position: relative;
        margin: 1.5rem 0;
    }

    .table-responsive {
        border-radius: 0;
        overflow-x: auto;
        overflow-y: visible;
        scrollbar-width: none;
        -ms-overflow-style: none;
        scroll-behavior: smooth;
        touch-action: pan-x pinch-zoom;
    }

    .table-responsive::-webkit-scrollbar {
        display: none;
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

    /* Tabel Modern */
    .modern-table {
        margin: 0;
        border-radius: 0;
        overflow: hidden;
        box-shadow: none;
        width: 100%;
        min-width: 1400px;
    }

    .modern-table thead th {
        background: white;
        border: none;
        padding: 1rem;
        font-weight: 700;
        color: var(--pln-blue);
        border-bottom: 2px solid var(--pln-blue);
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        white-space: nowrap;
    }

    .modern-table tbody td {
        padding: 1rem;
        border: none;
        border-bottom: 1px solid var(--gray-border);
        vertical-align: middle;
        font-size: 0.875rem;
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

    /* Modern Badges */
    .modern-badge {
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.8125rem;
        letter-spacing: 0.3px;
    }

    .badge-primary {
        background: var(--pln-blue);
        color: white;
    }

    .badge-info {
        background: var(--pln-blue);
        color: white;
    }

    .badge-secondary {
        background: #6c757d;
        color: white;
    }

    .badge-success {
        background: #28a745;
        color: white;
    }

    .badge-warning {
        background: #ffc107;
        color: #1e293b;
    }

    .badge-danger {
        background: #dc3545;
        color: white;
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

    /* Empty State */
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
        color: var(--text-gray);
        font-size: 1rem;
        font-weight: 500;
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .modern-table {
            font-size: 0.9rem;
        }

        .modern-table th,
        .modern-table td {
            padding: 0.75rem 0.5rem;
        }

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
</style>
@endpush

@endsection
