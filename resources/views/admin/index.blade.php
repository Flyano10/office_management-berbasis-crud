@extends('layouts.app')

@section('title', 'Admin Management')
@section('page-title', 'Admin Management')
@section('page-subtitle', 'Kelola data administrator sistem')

@section('content')
    <div class="container-fluid">
        @php($actor = Auth::guard('admin')->user())
        <div class="d-flex justify-content-end align-items-center mb-3">
            @if($actor && in_array($actor->role, ['super_admin','admin','admin_regional','manager_bidang']))
                <a href="{{ route('admin.create') }}" class="btn btn-outline-primary">
                    <i class="fas fa-plus me-2"></i>Tambah Admin
                </a>
            @endif
        </div>

        <!-- Filters -->
        <div class="card mb-4">
            <div class="card-body">
                @php($actor = Auth::guard('admin')->user())
                <form method="GET" action="{{ route('admin.index') }}" class="row g-2 align-items-end">
                    <div class="col-md-5">
                        <label for="search" class="form-label">Cari Admin</label>
                        <input type="text" class="form-control" id="search" name="search"
                            value="{{ request('search') }}" placeholder="Nama, email, atau username...">
                    </div>
                    @if($actor && in_array($actor->role, ['super_admin','admin']))
                    <div class="col-md-2">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-select" id="role" name="role">
                            <option value="">Semua Role</option>
                            <option value="super_admin" {{ request('role') == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                            <option value="admin_regional" {{ request('role') == 'admin_regional' ? 'selected' : '' }}>Admin Regional</option>
                            <option value="manager_bidang" {{ request('role') == 'manager_bidang' ? 'selected' : '' }}>Manager Bidang</option>
                            <option value="staf" {{ request('role') == 'staf' ? 'selected' : '' }}>Staf</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="bidang_id" class="form-label">Bidang</label>
                        <select class="form-select" id="bidang_id" name="bidang_id">
                            <option value="">Semua Bidang</option>
                            @foreach ($bidangs as $bidang)
                                <option value="{{ $bidang->id }}" {{ request('bidang_id') == $bidang->id ? 'selected' : '' }}>{{ $bidang->nama_bidang }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="kantor_id" class="form-label">Kantor</label>
                        <select class="form-select" id="kantor_id" name="kantor_id">
                            <option value="">Semua Kantor</option>
                            @foreach ($kantors as $kantor)
                                <option value="{{ $kantor->id }}" {{ request('kantor_id') == $kantor->id ? 'selected' : '' }}>{{ $kantor->nama_kantor }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div class="col-md-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">Semua Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif
                            </option>
                        </select>
                    </div>
                    
                    <div class="col-12 d-flex justify-content-end gap-2 mt-2">
                        <a href="{{ route('admin.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-rotate-left me-1"></i>Reset
                        </a>
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="fas fa-filter me-1"></i>Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Admin Table -->
        <div class="card no-transform">
            <div class="card-body no-clip">
                @if ($admins->count() > 0)
                    <div class="table-responsive" style="overflow-x: auto; width: 100%;">
                        <table class="table table-hover table-borderless align-middle" style="min-width: 1300px; table-layout: fixed;">
                            <thead class="table-light">
                                @php($actor = Auth::guard('admin')->user())
                                <tr>
                                    <th class="col-name">Nama Admin</th>
                                    <th class="col-email">Email</th>
                                    <th class="col-username">Username</th>
                                    <th class="col-role">Role</th>
                                    <th class="col-bidang">Bidang</th>
                                    <th class="col-kantor">Kantor</th>
                                    <th class="col-status">Status</th>
                                    <th class="col-last">Login Terakhir</th>
                                    <th class="col-created">Dibuat</th>
                                    <th class="col-actions">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($admins as $admin)
                                    <tr>
                                        <td class="col-name">
                                            <div class="d-flex align-items-center">
                                                <div
                                                    class="avatar-sm bg-light text-secondary rounded-circle d-flex align-items-center justify-content-center me-2 border">
                                                    {{ strtoupper(substr($admin->nama_admin, 0, 2)) }}
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 cell-ellipsis" title="{{ $admin->nama_admin }}">{{ $admin->nama_admin }}</h6>
                                                    <small class="text-muted">ID: {{ $admin->id }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="col-email"><span class="cell-ellipsis" title="{{ $admin->email }}">{{ $admin->email }}</span></td>
                                        <td class="col-username">
                                            <span class="badge bg-light text-dark">{{ $admin->username }}</span>
                                        </td>
                                        <td class="col-role">
                                            <span class="badge bg-light text-dark text-uppercase">{{ str_replace('_',' ', $admin->role) }}</span>
                                        </td>
                                        <td class="col-bidang">
                                            @if ($admin->bidang)
                                                <small class="text-muted cell-ellipsis" title="{{ $admin->bidang->nama_bidang }}">{{ $admin->bidang->nama_bidang }}</small>
                                            @else
                                                <small class="text-muted">-</small>
                                            @endif
                                        </td>
                                        <td class="col-kantor">
                                            @if ($admin->kantor)
                                                <small class="text-muted cell-ellipsis" title="{{ $admin->kantor->nama_kantor }}">{{ $admin->kantor->nama_kantor }}</small>
                                            @else
                                                <small class="text-muted">-</small>
                                            @endif
                                        </td>
                                        <td class="col-status">
                                            <span class="badge {{ $admin->is_active ? 'bg-light text-dark' : 'bg-light text-muted' }}">{{ $admin->is_active ? 'Aktif' : 'Tidak Aktif' }}</span>
                                        </td>
                                        <td class="col-last">
                                            @if ($admin->last_login)
                                                <small class="text-muted">
                                                    {{ $admin->last_login->format('d/m/Y H:i') }}
                                                </small>
                                            @else
                                                <small class="text-muted">Belum pernah login</small>
                                            @endif
                                        </td>
                                        <td class="col-created">
                                            <small class="text-muted">
                                                {{ $admin->created_at->format('d/m/Y H:i') }}
                                            </small>
                                        </td>
                                        <td class="col-actions">
                                            <div class="d-flex flex-wrap gap-1 justify-content-start">
                                                <!-- View Button -->
                                                <a href="{{ route('admin.show', $admin->id) }}"
                                                    class="btn btn-sm btn-light btn-icon" title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>

                                                @php(
                                                    $inScopeRegional = (in_array($admin->role, ['manager_bidang','staf'])) && ($actor && (int)$admin->kantor_id === (int)($actor->kantor_id ?? 0)) && (!($actor->bidang_id ?? null) || (int)$admin->bidang_id === (int)$actor->bidang_id)
                                                )
                                                @php(
                                                    $inScopeMB = ($admin->role === 'staf') && ($actor && (int)$admin->kantor_id === (int)($actor->kantor_id ?? 0)) && ((int)$admin->bidang_id === (int)($actor->bidang_id ?? 0))
                                                )

                                                <!-- Edit Button: hanya super_admin/admin atau SELF -->
                                                @if(($actor && in_array($actor->role, ['super_admin','admin'])) || ($actor && (int)$actor->id === (int)$admin->id))
                                                <a href="{{ route('admin.edit', $admin->id) }}"
                                                    class="btn btn-sm btn-light btn-icon" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @endif

                                                <!-- Toggle/Delete Buttons: only if target not super_admin and actor allowed -->
                                                @if ($admin->role !== 'super_admin')
                                                    @if(($actor && $actor->role === 'super_admin') || ($actor && $actor->role === 'admin_regional' && $inScopeRegional) || ($actor && $actor->role === 'manager_bidang' && $inScopeMB))
                                                    <form action="{{ route('admin.toggle-status', $admin->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit"
                                                            class="btn btn-sm btn-light btn-icon"
                                                            title="{{ $admin->is_active ? 'Nonaktifkan' : 'Aktifkan' }}"
                                                            data-action="{{ $admin->is_active ? 'menonaktifkan' : 'mengaktifkan' }}"
                                                            onclick="return confirmToggle(this)">
                                                            <i class="fas {{ $admin->is_active ? 'fa-ban' : 'fa-check' }}"></i>
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('admin.destroy', $admin->id) }}"
                                                        method="POST" class="d-inline"
                                                        onsubmit="return confirm('Yakin ingin menghapus admin ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-light btn-icon"
                                                            title="Hapus">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                    @elseif($actor && $actor->role === 'super_admin')
                                                    @endif
                                                @else
                                                    <span class="btn btn-sm btn-light btn-icon disabled"
                                                        title="Super Admin tidak dapat dihapus">
                                                        <i class="fas fa-shield-alt"></i>
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="text-muted">
                            Menampilkan {{ $admins->firstItem() }} - {{ $admins->lastItem() }}
                            dari {{ $admins->total() }} admin
                        </div>
                        <div>
                            {{ $admins->links() }}
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Tidak ada admin ditemukan</h5>
                        <p class="text-muted">Coba ubah filter atau tambah admin baru</p>
                        <a href="{{ route('admin.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Tambah Admin Pertama
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        .avatar-sm {
            width: 32px;
            height: 32px;
            font-size: 12px;
            font-weight: 600;
        }

        /* Styling button untuk aksi admin */
        .d-flex.gap-1 .btn {
            font-size: 10px;
            padding: 2px 6px;
            min-width: 24px;
            height: 24px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        /* Pastikan tabel responsive */
        .table-responsive { position: relative; overflow-x: auto; }

        /* Prevent hover transform breaking sticky positioning */
        .card.no-transform { overflow: visible !important; }
        .card.no-transform:hover { transform: none; }
        .card.no-transform .card-body.no-clip { overflow: visible; }

        /* Stable table layout for sticky columns */
        .table { border-collapse: separate; border-spacing: 0; position: relative; }

        /* Sticky header for better scanning */
        .table thead th {
            position: sticky;
            top: 0;
            z-index: 6;
            white-space: nowrap;
        }

        /* Pin the very first header cell */
        .table thead th.col-name { left: 0; z-index: 8; background: var(--bg-accent); box-shadow: 2px 0 0 var(--border-light); background-clip: padding-box; }

        /* Truncate long text */
        .cell-ellipsis {
            display: inline-block;
            max-width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            vertical-align: middle;
        }

        /* Tighter vertical rhythm */
        .table tbody td,
        .table thead th {
            padding-top: 0.75rem;
            padding-bottom: 0.75rem;
        }

        /* Pastikan kolom aksi cukup lebar */
        /* Fixed widths to avoid wrapping */
        .col-name { min-width: 260px; width: 260px; left: 0; position: sticky; z-index: 7; background: #fff; box-shadow: 2px 0 0 var(--border-light); }
        .col-email { min-width: 260px; width: 260px; }
        .col-username { min-width: 140px; width: 140px; }
        .col-role { min-width: 160px; width: 160px; }
        .col-bidang { min-width: 160px; width: 160px; }
        .col-kantor { min-width: 220px; width: 220px; }
        .col-status { min-width: 110px; width: 110px; }
        .col-last { min-width: 140px; width: 140px; }
        .col-created { min-width: 140px; width: 140px; }
        .col-actions { min-width: 180px; width: 180px; }

        /* Sticky first column for body */
        td.col-name { position: sticky; left: 0; z-index: 7; background: #fff; box-shadow: 2px 0 0 var(--border-light); background-clip: padding-box; }
        .table tbody td { background-clip: padding-box; }
        .table-hover tbody tr:hover { background-color: transparent; }

        /* Subtle scrollbar for horizontal */
        .table-responsive::-webkit-scrollbar { height: 8px; }
        .table-responsive::-webkit-scrollbar-thumb { background: var(--border-color); border-radius: 8px; }
        .table-responsive::-webkit-scrollbar-thumb:hover { background: var(--text-light); }

        /* Styling button yang compact */
        .btn-sm {
            font-size: 10px !important;
            padding: 2px 6px !important;
            line-height: 1.2 !important;
        }

        /* Paksa kolom aksi jadi visible */
        td:last-child { white-space: nowrap !important; }

        /* Pastikan button muat di kolom aksi */
        td:last-child .d-flex {
            flex-wrap: wrap;
            gap: 2px;
        }

        /* Stabilize row backgrounds */
        .table tbody tr { background-color: #fff; }
    </style>

    <script>
        function confirmToggle(button) {
            const action = button.getAttribute('data-action');
            return confirm('Yakin ingin ' + action + ' admin ini?');
        }
    </script>
@endsection
