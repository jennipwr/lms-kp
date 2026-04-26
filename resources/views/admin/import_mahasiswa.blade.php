@extends('layouts.index')

@section('content')
<div class="import-wrapper">
    <div class="import-header">
        <h1>Import Mahasiswa</h1>
        <p>Upload file Excel untuk membuat akun mahasiswa secara massal</p>
    </div>

    @if(session('success'))
        <div class="alert-success">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert-danger">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('error') }}
        </div>
    @endif

    <div class="form-card">
        <form action="{{ route('admin.import-mahasiswa') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="upload-zone" id="uploadZone">
                <input type="file" name="file" id="fileInput" accept=".xlsx,.xls" required>
                <div class="upload-icon">
                    <svg width="36" height="36" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                </div>
                <p>Klik atau seret file Excel ke sini</p>
                <p class="hint-text">Format: .xlsx atau .xls</p>
                <p class="file-name" id="fileName" style="display:none;"></p>
            </div>

            <button type="submit" class="btn-import">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M12 3v13.5m0 0l-4.5-4.5m4.5 4.5l4.5-4.5"/></svg>
                Upload & Generate Akun
            </button>
        </form>
    </div>

    @if(session('accounts'))
        <div class="result-card">
            <div class="result-card-header">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <h3>Akun Berhasil Dibuat</h3>
                <span class="badge-count">{{ count(session('accounts')) }} akun</span>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>NRP</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Password</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(session('accounts') as $acc)
                    <tr>
                        <td>{{ $acc['nrp'] }}</td>
                        <td>{{ $acc['nama'] }}</td>
                        <td>{{ $acc['email'] }}</td>
                        <td><span class="password-chip">{{ $acc['password'] }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection

@section('ExtraCSS')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');

    .import-wrapper {
        font-family: 'Plus Jakarta Sans', sans-serif;
        padding: 2rem;
        max-width: 760px;
        margin: 0 auto;
    }

    .import-header {
        margin-bottom: 1.75rem;
    }

    .import-header h1 {
        font-size: 1.4rem;
        font-weight: 700;
        color: #111827;
        margin: 0 0 0.2rem;
    }

    .import-header p {
        font-size: 0.85rem;
        color: #6b7280;
        margin: 0;
    }

    .alert-success {
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

    .alert-danger {
        background: #fef2f2;
        border: 1px solid #fca5a5;
        color: #991b1b;
        border-radius: 8px;
        padding: 0.75rem 1rem;
        font-size: 0.875rem;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 1.75rem;
        box-shadow: 0 1px 4px rgba(0,0,0,0.06);
        margin-bottom: 1.5rem;
    }

    .upload-zone {
        border: 2px dashed #d1d5db;
        border-radius: 10px;
        padding: 2rem 1.5rem;
        text-align: center;
        background: #f9fafb;
        cursor: pointer;
        transition: border-color 0.15s, background 0.15s;
        position: relative;
        margin-bottom: 1.25rem;
    }

    .upload-zone:hover, .upload-zone.drag-over {
        border-color: #2563eb;
        background: #eff6ff;
    }

    .upload-zone input[type="file"] {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
    }

    .upload-icon {
        color: #9ca3af;
        margin-bottom: 0.75rem;
    }

    .upload-zone p {
        margin: 0;
        font-size: 0.875rem;
        color: #6b7280;
    }

    .upload-zone .file-name {
        font-size: 0.82rem;
        font-weight: 600;
        color: #2563eb;
        margin-top: 0.5rem;
    }

    .hint-text {
        font-size: 0.78rem;
        color: #9ca3af;
        margin-top: 0.4rem;
    }

    .btn-import {
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
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        transition: background 0.15s;
    }

    .btn-import:hover { background: #1A3560; }

    .result-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 1px 4px rgba(0,0,0,0.06);
    }

    .result-card-header {
        background: #f9fafb;
        border-bottom: 1px solid #e5e7eb;
        padding: 1rem 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .result-card-header h3 {
        font-size: 0.9rem;
        font-weight: 700;
        color: #111827;
        margin: 0;
    }

    .result-card-header .badge-count {
        background: #dbeafe;
        color: #1d4ed8;
        font-size: 0.75rem;
        font-weight: 700;
        border-radius: 99px;
        padding: 0.1rem 0.55rem;
    }

    .result-card table {
        width: 100%;
        border-collapse: collapse;
    }

    .result-card thead th {
        background: #f9fafb;
        padding: 0.75rem 1.1rem;
        font-size: 0.78rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #6b7280;
        border-bottom: 1px solid #e5e7eb;
        text-align: left;
    }

    .result-card tbody tr {
        border-bottom: 1px solid #f3f4f6;
    }

    .result-card tbody tr:last-child { border-bottom: none; }

    .result-card tbody tr:hover { background: #f9fafb; }

    .result-card td {
        padding: 0.75rem 1.1rem;
        font-size: 0.875rem;
        color: #374151;
    }

    .password-chip {
        font-family: monospace;
        background: #f3f4f6;
        border-radius: 5px;
        padding: 0.15rem 0.5rem;
        font-size: 0.82rem;
        color: #374151;
    }
</style>
@endsection

@section('ExtraJS')
<script>
    const fileInput = document.getElementById('fileInput');
    const fileName = document.getElementById('fileName');
    const uploadZone = document.getElementById('uploadZone');

    fileInput.addEventListener('change', function () {
        if (this.files[0]) {
            fileName.textContent = '✓ ' + this.files[0].name;
            fileName.style.display = 'block';
        }
    });

    uploadZone.addEventListener('dragover', e => { e.preventDefault(); uploadZone.classList.add('drag-over'); });
    uploadZone.addEventListener('dragleave', () => uploadZone.classList.remove('drag-over'));
    uploadZone.addEventListener('drop', () => uploadZone.classList.remove('drag-over'));
</script>
@endsection