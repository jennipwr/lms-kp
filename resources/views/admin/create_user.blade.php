@extends('layouts.index')

@section('content')
<div class="form-wrapper">
    <a href="{{ route('admin.list-user') }}" class="btn-back">
        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        Kembali
    </a>

    <div class="form-header">
        <h1>Tambah User Baru</h1>
        <p>Isi data pengguna yang akan ditambahkan ke sistem</p>
    </div>

    @if ($errors->any())
        <div class="alert-errors">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-card">
        <form action="{{ route('admin.store-user') }}" method="POST" novalidate>
            @csrf

            <p class="section-label">Informasi Akun</p>

            <div class="form-group">
                <label>Role</label>
                <select name="role_id" id="role_id" class="form-control" required>
                    <option value="">-- Pilih Role --</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id_role }}">{{ $role->nama_role }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group field-extra" id="field-nrp">
                <label>NRP Mahasiswa</label>
                <input type="text" name="nrp" class="form-control" placeholder="Contoh: 1234567890">
            </div>

            <div class="form-group field-extra" id="field-nik">
                <label>NIK Dosen</label>
                <input type="text" name="nik" class="form-control" placeholder="Contoh: 1234567890">
            </div>

            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" class="form-control" placeholder="Masukkan nama lengkap" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" placeholder="contoh@email.com" required>
            </div>

            <hr class="divider">
            <p class="section-label">Keamanan</p>

            <div class="row-2">
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Min. 6 karakter" required>
                </div>
                <div class="form-group">
                    <label>Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password" required>
                </div>
            </div>

            <button type="submit" class="btn-submit">Simpan User</button>
        </form>
    </div>
</div>
@endsection

@section('ExtraCSS')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');

    .form-wrapper {
        font-family: 'Plus Jakarta Sans', sans-serif;
        padding: 2rem;
        max-width: 620px;
        margin: 0 auto;
    }

    .form-header {
        margin-bottom: 1.75rem;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        color: #6b7280;
        font-size: 0.85rem;
        font-weight: 500;
        text-decoration: none;
        margin-bottom: 1rem;
        transition: color 0.15s;
    }

    .btn-back:hover { color: #111827; }

    .form-header h1 {
        font-size: 1.4rem;
        font-weight: 700;
        color: #111827;
        margin: 0 0 0.2rem;
    }

    .form-header p {
        font-size: 0.85rem;
        color: #6b7280;
        margin: 0;
    }

    .alert-errors {
        background: #fef2f2;
        border: 1px solid #fca5a5;
        color: #991b1b;
        border-radius: 8px;
        padding: 0.75rem 1rem;
        font-size: 0.875rem;
        margin-bottom: 1.25rem;
    }

    .alert-errors ul { margin: 0; padding-left: 1.2rem; }

    .form-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 1.75rem;
        box-shadow: 0 1px 4px rgba(0,0,0,0.06);
    }

    .section-label {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.07em;
        color: #9ca3af;
        margin-bottom: 1rem;
        margin-top: 0;
    }

    .divider {
        border: none;
        border-top: 1px solid #f3f4f6;
        margin: 1.5rem 0;
    }

    .form-group {
        margin-bottom: 1.1rem;
    }

    .form-group label {
        display: block;
        font-size: 0.82rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.4rem;
    }

    .form-group label span.opt {
        font-weight: 400;
        color: #9ca3af;
        font-size: 0.78rem;
    }

    .form-group .form-control {
        width: 100%;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        padding: 0.55rem 0.85rem;
        font-size: 0.875rem;
        color: #111827;
        font-family: 'Plus Jakarta Sans', sans-serif;
        transition: border 0.15s, box-shadow 0.15s;
        background: #fff;
        box-sizing: border-box;
    }

    .form-group .form-control:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
    }

    .row-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .field-extra {
        display: none;
        animation: fadeSlide 0.2s ease;
    }

    @keyframes fadeSlide {
        from { opacity: 0; transform: translateY(-6px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .btn-submit {
        width: 100%;
        background: #0D1F3C;
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 0.65rem 1rem;
        font-size: 0.9rem;
        font-weight: 600;
        font-family: 'Plus Jakarta Sans', sans-serif;
        cursor: pointer;
        margin-top: 0.5rem;
        transition: background 0.15s;
    }

    .btn-submit:hover { background: #1A3560; }
</style>
@endsection

@section('ExtraJS')
<script>
    const roleSelect = document.getElementById('role_id');
    const fieldNRP = document.getElementById('field-nrp');
    const fieldNIK = document.getElementById('field-nik');

    roleSelect.addEventListener('change', function () {
        const roleId = parseInt(this.value);

        fieldNRP.style.display = 'none';
        fieldNRP.querySelector('input').required = false;

        fieldNIK.style.display = 'none';
        fieldNIK.querySelector('input').required = false;

        if (roleId === 3) {
            fieldNRP.style.display = 'block';
            fieldNRP.querySelector('input').required = true;
        } else if (roleId === 2) {
            fieldNIK.style.display = 'block';
            fieldNIK.querySelector('input').required = true;
        }
    });
</script>
@endsection