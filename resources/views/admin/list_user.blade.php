@extends('layouts.index')

@section('content')
<div class="akun-wrapper">
    <div class="akun-header">
        <div>
            <h1>Manajemen Akun</h1>
            <p>Kelola semua pengguna sistem</p>
        </div>
        <a href="{{ route('admin.create-user') }}" class="btn-tambah">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Tambah User
        </a>
    </div>

    @if(session('success'))
        <div class="alert-success-custom">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="card-table">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Pengguna</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $index => $user)
                <tr>
                    <td style="color:#9ca3af; width:40px;">{{ $index + 1 }}</td>
                    <td>
                        <div class="user-cell">
                            <div class="user-avatar">{{ strtoupper(substr($user->nama, 0, 1)) }}</div>
                            <div class="user-info">
                                <div class="user-name">{{ $user->nama }}</div>
                                <div class="user-email">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        @php
                            $roleName = $user->role->nama_role ?? '-';
                            $badgeClass = match(strtolower($roleName)) {
                                'admin'      => 'badge-admin',
                                'dosen'      => 'badge-dosen',
                                'mahasiswa'  => 'badge-mahasiswa',
                                default      => 'badge-default',
                            };
                        @endphp
                        <span class="badge-role {{ $badgeClass }}">{{ $roleName }}</span>
                    </td>
                    <td>
                        <div class="aksi-group">
                            <a href="{{ route('admin.edit-user', $user->id) }}" class="btn-edit">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 112.828 2.828L11.828 15.828a2 2 0 01-1.414.586H9v-1.414A2 2 0 019.586 13.5z"/></svg>
                                Edit
                            </a>
                            <form action="{{ route('admin.destroy-user', $user->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-hapus" onclick="return confirm('Yakin ingin menghapus user ini?')">
                                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4">
                        <div class="empty-state">
                            <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-5-3.87M9 20H4v-2a4 4 0 015-3.87M15 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            <p>Belum ada user terdaftar.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('ExtraCSS')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');

    .akun-wrapper {
        font-family: 'Plus Jakarta Sans', sans-serif;
        padding: 2rem;
        max-width: 1100px;
        margin: 0 auto;
    }

    .akun-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.75rem;
    }

    .akun-header h1 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #111827;
        margin: 0;
    }

    .akun-header p {
        font-size: 0.85rem;
        color: #6b7280;
        margin: 0.2rem 0 0;
    }

    .btn-tambah {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        background: #0D1F3C;
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 0.55rem 1.1rem;
        font-size: 0.875rem;
        font-weight: 600;
        text-decoration: none;
        transition: background 0.15s;
    }

    .btn-tambah:hover {
        background: #1d4ed8;
        color: #fff;
    }

    .alert-success-custom {
        background: #ecfdf5;
        border: 1px solid #6ee7b7;
        color: #065f46;
        border-radius: 8px;
        padding: 0.75rem 1rem;
        font-size: 0.875rem;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .card-table {
        background: #fff;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        overflow: hidden;
        box-shadow: 0 1px 4px rgba(0,0,0,0.06);
    }

    .card-table table {
        width: 100%;
        border-collapse: collapse;
        margin: 0;
    }

    .card-table thead th {
        background: #f9fafb;
        padding: 0.85rem 1.1rem;
        font-size: 0.78rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #6b7280;
        border-bottom: 1px solid #e5e7eb;
        text-align: left;
    }

    .card-table tbody tr {
        border-bottom: 1px solid #f3f4f6;
        transition: background 0.1s;
    }

    .card-table tbody tr:last-child {
        border-bottom: none;
    }

    .card-table tbody tr:hover {
        background: #f9fafb;
    }

    .card-table td {
        padding: 0.85rem 1.1rem;
        font-size: 0.875rem;
        color: #374151;
        vertical-align: middle;
    }

    .badge-role {
        display: inline-block;
        padding: 0.2rem 0.65rem;
        border-radius: 99px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .badge-admin    { background: #ede9fe; color: #5b21b6; }
    .badge-dosen    { background: #dbeafe; color: #1d4ed8; }
    .badge-mahasiswa{ background: #dcfce7; color: #15803d; }
    .badge-default  { background: #f3f4f6; color: #374151; }

    .user-avatar {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: #dbeafe;
        color: #1d4ed8;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.8rem;
        margin-right: 0.6rem;
        flex-shrink: 0;
    }

    .user-cell {
        display: flex;
        align-items: center;
    }

    .user-info .user-name { font-weight: 600; color: #111827; }
    .user-info .user-email { font-size: 0.78rem; color: #9ca3af; }

    .aksi-group {
        display: flex;
        gap: 0.4rem;
    }

    .btn-edit {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        background: #fff;
        border: 1px solid #d1d5db;
        color: #374151;
        border-radius: 6px;
        padding: 0.35rem 0.75rem;
        font-size: 0.8rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.15s;
    }

    .btn-edit:hover {
        background: #f3f4f6;
        color: #111827;
        border-color: #9ca3af;
    }

    .btn-hapus {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        background: #fff;
        border: 1px solid #fca5a5;
        color: #dc2626;
        border-radius: 6px;
        padding: 0.35rem 0.75rem;
        font-size: 0.8rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.15s;
    }

    .btn-hapus:hover {
        background: #fef2f2;
        border-color: #f87171;
    }

    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        color: #9ca3af;
    }

    .empty-state svg {
        margin-bottom: 0.75rem;
        opacity: 0.4;
    }
</style>
@endsection

@section('ExtraJS')
@endsection