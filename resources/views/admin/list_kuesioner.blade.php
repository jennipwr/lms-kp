@extends('layouts.index')

@section('content')
<div class="page-wrapper">

    <div class="page-header">
        <h2 class="page-title">Daftar Kuesioner</h2>
        <a href="{{ route('admin.create-kuesioner') }}" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Tambah Kuesioner
        </a>
    </div>

    @if(session('success'))
    <div class="alert-success">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        {{ session('success') }}
    </div>
    @endif

    <div class="card">
        @if($list->count() == 0)
        <div class="empty-state">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
            <p>Belum ada kuesioner yang dibuat.</p>
        </div>
        @else
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Kuesioner</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($list as $i => $l)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $l->nama_kuesioner }}</td>
                        <td>
                            <form class="status-form" action="{{ route('admin.update-kuesioner', $l->id_list) }}" method="POST">
                                @csrf
                                <input type="hidden" name="nama_kuesioner" value="{{ $l->nama_kuesioner }}">
                                <select name="status"
                                    class="status-select s-{{ $l->status }}"
                                    onchange="this.className='status-select s-'+this.value; this.closest('form').submit()">
                                    <option value="draft"     @if($l->status=='draft')     selected @endif>Draft</option>
                                    <option value="published" @if($l->status=='published') selected @endif>Published</option>
                                    <option value="archived"  @if($l->status=='archived')  selected @endif>Archived</option>
                                </select>
                            </form>
                        </td>
                        <td>
                            <div class="actions-group">
                                <a href="{{ route('admin.edit-kuesioner', $l->id_list) }}" class="btn btn-warning">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                    Edit
                                </a>
                                <form action="{{ route('admin.delete-kuesioner', $l->id_list) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Hapus kuesioner ini?')">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

</div>
@endsection

@section('ExtraCSS')
<style>
    :root {
        --itb-navy:     #0D1F3C;
        --itb-navy-mid: #1A3560;
        --mcu-blue:     #1565C0;
        --mcu-gold:     #C9A84C;
        --mcu-green:    #2E7D32;
        --bg-light:     #F4F6FB;
        --text-main:    #1A2035;
        --text-muted:   #6B7A99;
        --border:       #D6DFF0;
        --white:        #FFFFFF;
        --danger:       #C62828;
    }

    .page-wrapper {
        /* padding: 2rem; */
        background: var(--bg-light);
        min-height: 100vh;
    }

    .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.75rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .page-title {
        font-size: 1.6rem;
        font-weight: 700;
        color: var(--itb-navy);
        letter-spacing: -0.3px;
        position: relative;
        padding-left: 1rem;
    }

    .page-title::before {
        content: '';
        position: absolute;
        left: 0;
        top: 10%;
        height: 80%;
        width: 4px;
        background: linear-gradient(to bottom, var(--mcu-gold), var(--mcu-blue));
        border-radius: 4px;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.5rem 1.2rem;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 600;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all 0.18s ease;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--mcu-blue), var(--itb-navy-mid));
        color: var(--white);
        box-shadow: 0 3px 10px rgba(21, 101, 192, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 5px 16px rgba(21, 101, 192, 0.4);
        color: var(--white);
        text-decoration: none;
    }

    .btn-warning {
        background: linear-gradient(135deg, var(--mcu-gold), #b8940e);
        color: var(--white);
        box-shadow: 0 2px 8px rgba(201, 168, 76, 0.35);
        padding: 0.35rem 0.9rem;
        font-size: 0.8rem;
    }

    .btn-warning:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(201, 168, 76, 0.45);
        color: var(--white);
        text-decoration: none;
    }

    .btn-danger {
        background: linear-gradient(135deg, var(--danger), #8e0000);
        color: var(--white);
        box-shadow: 0 2px 8px rgba(198, 40, 40, 0.3);
        padding: 0.35rem 0.9rem;
        font-size: 0.8rem;
    }

    .btn-danger:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(198, 40, 40, 0.4);
    }

    .alert-success {
        background: linear-gradient(135deg, #e8f5e9, #f1f8e9);
        border: 1px solid #a5d6a7;
        border-left: 4px solid var(--mcu-green);
        color: var(--mcu-green);
        padding: 0.8rem 1.2rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9rem;
    }

    .card {
        background: var(--white);
        border-radius: 12px;
        box-shadow: 0 2px 16px rgba(13, 31, 60, 0.08);
        overflow: hidden;
        border: 1px solid var(--border);
    }

    .table-wrapper {
        overflow-x: auto;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.9rem;
    }

    .data-table thead {
        background: linear-gradient(135deg, var(--itb-navy), var(--itb-navy-mid));
        color: var(--white);
    }

    .data-table thead th {
        padding: 0.9rem 1.2rem;
        text-align: left;
        font-weight: 600;
        font-size: 0.825rem;
        letter-spacing: 0.4px;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .data-table thead th:first-child {
        border-radius: 0;
        width: 60px;
        text-align: center;
    }

    .data-table thead th:last-child {
        text-align: center;
        width: 200px;
    }

    .data-table tbody tr {
        border-bottom: 1px solid var(--border);
        transition: background 0.12s ease;
    }

    .data-table tbody tr:last-child {
        border-bottom: none;
    }

    .data-table tbody tr:hover {
        background: #f0f4ff;
    }

    .data-table tbody td {
        padding: 0.9rem 1.2rem;
        color: var(--text-main);
        vertical-align: middle;
    }

    .data-table tbody td:first-child {
        text-align: center;
        color: var(--text-muted);
        font-weight: 600;
    }

    .data-table tbody td:last-child {
        text-align: center;
    }

    .badge {
        display: inline-block;
        padding: 0.28rem 0.75rem;
        border-radius: 20px;
        font-size: 0.775rem;
        font-weight: 600;
        letter-spacing: 0.3px;
        text-transform: capitalize;
    }

    .badge-published {
        background: #e8f5e9;
        color: var(--mcu-green);
        border: 1px solid #a5d6a7;
    }

    .badge-draft {
        background: #fff8e1;
        color: #f57f17;
        border: 1px solid #ffe082;
    }

    .badge-archived {
        background: #f3e5f5;
        color: #6a1b9a;
        border: 1px solid #ce93d8;
    }

    .actions-group {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    /* Status dropdown */
    .status-form {
        display: inline-flex;
        align-items: center;
        position: relative;
    }

    .status-select {
        appearance: none;
        padding: 0.32rem 1.8rem 0.32rem 0.7rem;
        border-radius: 20px;
        border: 1.5px solid transparent;
        font-size: 0.775rem;
        font-weight: 600;
        letter-spacing: 0.3px;
        cursor: pointer;
        outline: none;
        transition: all 0.15s ease;
        background-repeat: no-repeat;
        background-position: right 0.5rem center;
        background-size: 10px;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='10' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='3' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
    }

    .status-select.s-published {
        background-color: #e8f5e9;
        color: var(--mcu-green);
        border-color: #a5d6a7;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='10' viewBox='0 0 24 24' fill='none' stroke='%232E7D32' stroke-width='3' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
    }

    .status-select.s-draft {
        background-color: #fff8e1;
        color: #f57f17;
        border-color: #ffe082;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='10' viewBox='0 0 24 24' fill='none' stroke='%23f57f17' stroke-width='3' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
    }

    .status-select.s-archived {
        background-color: #f3e5f5;
        color: #6a1b9a;
        border-color: #ce93d8;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='10' viewBox='0 0 24 24' fill='none' stroke='%236a1b9a' stroke-width='3' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
    }

    .status-select:hover {
        filter: brightness(0.95);
    }

    .empty-state {
        text-align: center;
        padding: 3.5rem 2rem;
        color: var(--text-muted);
    }

    .empty-state svg {
        margin-bottom: 1rem;
        opacity: 0.4;
    }

    .empty-state p {
        font-size: 0.95rem;
        margin: 0;
    }
</style>
@endsection