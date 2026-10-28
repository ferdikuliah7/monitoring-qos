@extends('layouts.app')

@section('title', 'Edit Data Sekolah')

@section('styles')
<style>
    .school-edit-page {
        --se-blue: #1768e5;
        --se-ink: #102b59;
        --se-muted: #6f82a3;
        --se-line: #dce7f8;
        width: 100%;
        color: var(--se-ink);
    }

    .school-edit-page * {
        box-sizing: border-box;
    }

    .school-edit-hero {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 22px;
        padding: 25px 28px;
        margin-bottom: 22px;
        overflow: hidden;
        border: 1px solid rgba(190, 213, 249, .9);
        border-radius: 20px;
        background:
            radial-gradient(circle at 88% 12%, rgba(91, 157, 255, .20), transparent 29%),
            linear-gradient(120deg, rgba(255,255,255,.97), rgba(239,246,255,.94));
        box-shadow: 0 12px 30px rgba(34, 83, 153, .07);
    }

    .school-edit-hero::after {
        content: "";
        position: absolute;
        right: -34px;
        bottom: -75px;
        width: 190px;
        height: 190px;
        border: 1px solid rgba(23,104,229,.12);
        border-radius: 50%;
        box-shadow: 0 0 0 20px rgba(23,104,229,.035), 0 0 0 42px rgba(23,104,229,.025);
        pointer-events: none;
    }

    .school-edit-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 10px;
        color: #507bbd;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: .17em;
        text-transform: uppercase;
    }

    .school-edit-eyebrow span {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #2477f3;
        box-shadow: 0 0 0 4px rgba(36,119,243,.12);
    }

    .school-edit-hero h1 {
        margin: 0 0 8px;
        color: var(--se-ink);
        font-size: clamp(25px, 2.5vw, 32px);
        font-weight: 800;
        letter-spacing: -.035em;
        line-height: 1.15;
    }

    .school-edit-hero p {
        max-width: 650px;
        margin: 0;
        color: var(--se-muted);
        font-size: 13px;
        line-height: 1.7;
    }

    .school-edit-back {
        position: relative;
        z-index: 1;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 42px;
        padding: 0 16px;
        border: 1px solid #d4e2f8;
        border-radius: 11px;
        background: rgba(255,255,255,.88);
        color: #315783;
        font-size: 12px;
        font-weight: 750;
        text-decoration: none;
        white-space: nowrap;
        transition: transform .18s ease, background .18s ease, box-shadow .18s ease;
    }

    .school-edit-back:hover {
        transform: translateY(-2px);
        background: #fff;
        box-shadow: 0 7px 18px rgba(36, 83, 150, .10);
    }

    .school-edit-layout {
        display: grid;
        grid-template-columns: minmax(0, 1.7fr) minmax(245px, .85fr);
        align-items: start;
        gap: 20px;
    }

    .school-edit-card,
    .school-edit-aside {
        overflow: hidden;
        border: 1px solid var(--se-line);
        border-radius: 18px;
        background: rgba(255,255,255,.95);
        box-shadow: 0 12px 30px rgba(34, 83, 153, .065);
    }

    .school-edit-card-header {
        display: flex;
        align-items: flex-start;
        gap: 13px;
        padding: 22px 24px;
        border-bottom: 1px solid #e8effa;
        background: linear-gradient(120deg, #fff, #f7faff);
    }

    .school-edit-icon {
        display: inline-flex;
        flex: 0 0 44px;
        align-items: center;
        justify-content: center;
        width: 44px;
        height: 44px;
        border: 1px solid #d7e6ff;
        border-radius: 14px;
        background: #eaf2ff;
        color: var(--se-blue);
        font-size: 21px;
    }

    .school-edit-card-header h2 {
        margin: 1px 0 5px;
        color: var(--se-ink);
        font-size: 16px;
        font-weight: 800;
    }

    .school-edit-card-header p {
        margin: 0;
        color: var(--se-muted);
        font-size: 11px;
        line-height: 1.6;
    }

    .school-edit-body {
        padding: 24px;
    }

    .school-edit-group {
        margin-bottom: 21px;
    }

    .school-edit-group label {
        display: block;
        margin-bottom: 8px;
        color: #243e67;
        font-size: 12px;
        font-weight: 750;
    }

    .school-edit-required {
        color: #e24a5a;
    }

    .school-edit-control-wrap {
        position: relative;
    }

    .school-edit-control-icon {
        position: absolute;
        top: 50%;
        left: 14px;
        transform: translateY(-50%);
        color: #7d98c0;
        font-size: 15px;
        pointer-events: none;
    }

    .school-edit-group input,
    .school-edit-group select {
        display: block;
        width: 100%;
        min-height: 46px;
        padding: 11px 13px 11px 42px;
        border: 1px solid #d8e3f2;
        border-radius: 11px;
        outline: none;
        background: #fbfdff;
        color: #1d355b;
        font: inherit;
        font-size: 12px;
        transition: border-color .18s ease, box-shadow .18s ease, background .18s ease;
    }

    .school-edit-group input:hover,
    .school-edit-group select:hover {
        border-color: #b8cdeb;
    }

    .school-edit-group input:focus,
    .school-edit-group select:focus {
        border-color: #4389f4;
        background: #fff;
        box-shadow: 0 0 0 4px rgba(40, 119, 235, .11);
    }

    .school-edit-help {
        display: flex;
        align-items: flex-start;
        gap: 7px;
        margin-top: 8px;
        color: #7a8eaa;
        font-size: 10px;
        line-height: 1.65;
    }

    .school-edit-help span {
        color: #4280d8;
        font-weight: 800;
    }

    .school-edit-error {
        margin-top: 7px;
        color: #c53030;
        font-size: 11px;
    }

    .school-edit-control.is-invalid {
        border-color: #e76a76;
        background: #fffafa;
    }

    .school-edit-alert {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin-bottom: 18px;
        padding: 14px 16px;
        border: 1px solid #fecdd3;
        border-radius: 12px;
        background: #fff1f2;
        color: #9f2635;
        font-size: 12px;
        line-height: 1.6;
    }

    .school-edit-alert strong {
        display: block;
        margin-bottom: 3px;
    }

    .school-edit-alert ul {
        margin: 4px 0 0;
        padding-left: 18px;
    }

    .school-edit-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        flex-wrap: wrap;
        gap: 10px;
        padding-top: 20px;
        border-top: 1px solid #edf2fa;
    }

    .school-edit-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 43px;
        padding: 0 17px;
        border: 1px solid transparent;
        border-radius: 11px;
        cursor: pointer;
        font-size: 12px;
        font-weight: 800;
        text-decoration: none;
        transition: transform .18s ease, box-shadow .18s ease, background .18s ease;
    }

    .school-edit-btn:hover {
        transform: translateY(-1px);
    }

    .school-edit-btn-primary {
        border-color: #1768e5;
        background: linear-gradient(135deg, #3185ff, #125bd0);
        color: #fff;
        box-shadow: 0 8px 16px rgba(23,104,229,.2);
    }

    .school-edit-btn-primary:hover {
        box-shadow: 0 11px 22px rgba(23,104,229,.27);
    }

    .school-edit-btn-secondary {
        border-color: #d8e3f2;
        background: #fff;
        color: #496486;
    }

    .school-edit-btn-secondary:hover {
        background: #f4f8ff;
    }

    .school-edit-aside {
        padding: 22px;
        background:
            radial-gradient(circle at 100% 0, rgba(69,141,255,.14), transparent 35%),
            linear-gradient(155deg, #fff, #f3f8ff);
    }

    .school-edit-aside-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 48px;
        height: 48px;
        margin-bottom: 16px;
        border: 1px solid #d8e7ff;
        border-radius: 15px;
        background: #e8f1ff;
        color: #1768e5;
        font-size: 23px;
    }

    .school-edit-aside h3 {
        margin: 0 0 8px;
        color: var(--se-ink);
        font-size: 15px;
        font-weight: 800;
    }

    .school-edit-aside > p {
        margin: 0 0 18px;
        color: var(--se-muted);
        font-size: 11px;
        line-height: 1.7;
    }

    .school-edit-tip {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 12px 0;
        border-top: 1px solid #e3ecf9;
    }

    .school-edit-tip-icon {
        display: inline-flex;
        flex: 0 0 28px;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        border-radius: 9px;
        background: #e6f0ff;
        color: #286fce;
        font-size: 12px;
        font-weight: 800;
    }

    .school-edit-tip strong {
        display: block;
        margin-bottom: 3px;
        color: #294568;
        font-size: 11px;
    }

    .school-edit-tip small {
        display: block;
        color: #7a8eaa;
        font-size: 10px;
        line-height: 1.6;
    }

    @media (max-width: 980px) {
        .school-edit-layout {
            grid-template-columns: minmax(0, 1fr);
        }

        .school-edit-aside {
            padding: 18px;
        }
    }

    @media (max-width: 640px) {
        .school-edit-hero {
            align-items: flex-start;
            flex-direction: column;
            padding: 21px;
        }

        .school-edit-card-header,
        .school-edit-body {
            padding: 18px;
        }

        .school-edit-actions {
            flex-direction: column-reverse;
            align-items: stretch;
        }

        .school-edit-btn {
            width: 100%;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .school-edit-back,
        .school-edit-btn,
        .school-edit-group input,
        .school-edit-group select {
            transition: none;
        }
    }
</style>
@endsection

@section('content')
<div class="school-edit-page">
    <header class="school-edit-hero">
        <div>
            <div class="school-edit-eyebrow"><span></span> Manajemen Infrastruktur</div>
            <h1>Edit Data Sekolah</h1>
            <p>Perbarui konfigurasi sekolah dan target jaringan yang dipantau oleh NETMONITOR.</p>
        </div>
        <a href="{{ route('schools.index') }}" class="school-edit-back">
            <span aria-hidden="true">←</span> Kembali ke Data Sekolah
        </a>
    </header>

    @if ($errors->any())
        <div class="school-edit-alert" role="alert">
            <span aria-hidden="true">⚠</span>
            <div>
                <strong>Perubahan belum dapat disimpan.</strong>
                Periksa kembali informasi berikut:
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="school-edit-layout">
        <section class="school-edit-card">
            <div class="school-edit-card-header">
                <div class="school-edit-icon" aria-hidden="true">✎</div>
                <div>
                    <h2>Konfigurasi Sekolah</h2>
                    <p>Periksa data saat ini, lalu ubah bagian yang perlu diperbarui.</p>
                </div>
            </div>

            <div class="school-edit-body">
                <form action="{{ route('schools.update', $school) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="school-edit-group">
                        <label for="nama_sekolah">Nama Sekolah <span class="school-edit-required">*</span></label>
                        <div class="school-edit-control-wrap">
                            <span class="school-edit-control-icon" aria-hidden="true">🏫</span>
                            <input
                                class="school-edit-control @error('nama_sekolah') is-invalid @enderror"
                                type="text"
                                id="nama_sekolah"
                                name="nama_sekolah"
                                value="{{ old('nama_sekolah', $school->nama_sekolah) }}"
                                maxlength="255"
                                autocomplete="organization"
                                required
                                aria-describedby="edit-school-name-help"
                            >
                        </div>
                        <div class="school-edit-help" id="edit-school-name-help">
                            <span>i</span> Gunakan nama resmi sekolah agar mudah dikenali dalam laporan monitoring.
                        </div>
                        @error('nama_sekolah')
                            <div class="school-edit-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="school-edit-group">
                        <label for="ip_target">IP Target <span class="school-edit-required">*</span></label>
                        <div class="school-edit-control-wrap">
                            <span class="school-edit-control-icon" aria-hidden="true">⌁</span>
                            <input
                                class="school-edit-control @error('ip_target') is-invalid @enderror"
                                type="text"
                                id="ip_target"
                                name="ip_target"
                                value="{{ old('ip_target', $school->ip_target) }}"
                                placeholder="Contoh: 192.168.1.1"
                                maxlength="255"
                                spellcheck="false"
                                autocomplete="off"
                                required
                                aria-describedby="edit-ip-help"
                            >
                        </div>
                        <div class="school-edit-help" id="edit-ip-help">
                            <span>i</span> Pastikan alamat target sesuai perangkat jaringan yang dipantau.
                        </div>
                        @error('ip_target')
                            <div class="school-edit-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="school-edit-group">
                        <label for="bandwidth">Kapasitas Bandwidth (Mbps) <span class="school-edit-required">*</span></label>
                        <div class="school-edit-control-wrap">
                            <span class="school-edit-control-icon" aria-hidden="true">↕</span>
                            <input
                                class="school-edit-control @error('bandwidth') is-invalid @enderror"
                                type="number"
                                id="bandwidth"
                                name="bandwidth"
                                value="{{ old('bandwidth', $school->bandwidth) }}"
                                step="0.01"
                                min="1"
                                required
                                aria-describedby="edit-bandwidth-help"
                            >
                        </div>
                        <div class="school-edit-help" id="edit-bandwidth-help">
                            <span>i</span> Sesuaikan dengan kapasitas layanan internet yang digunakan sekolah.
                        </div>
                        @error('bandwidth')
                            <div class="school-edit-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="school-edit-group">
                        <label for="status">Status Monitoring <span class="school-edit-required">*</span></label>
                        <div class="school-edit-control-wrap">
                            <span class="school-edit-control-icon" aria-hidden="true">●</span>
                            <select
                                class="school-edit-control @error('status') is-invalid @enderror"
                                id="status"
                                name="status"
                                required
                                aria-describedby="edit-status-help"
                            >
                                <option value="1" @selected(old('status', $school->status) == 1)>Aktif</option>
                                <option value="0" @selected(old('status', $school->status) == 0)>Tidak Aktif</option>
                            </select>
                        </div>
                        <div class="school-edit-help" id="edit-status-help">
                            <span>i</span> Sekolah berstatus aktif dapat disertakan dalam proses monitoring sesuai konfigurasi sistem.
                        </div>
                        @error('status')
                            <div class="school-edit-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="school-edit-actions">
                        <a href="{{ route('schools.index') }}" class="school-edit-btn school-edit-btn-secondary">Batal</a>
                        <button type="submit" class="school-edit-btn school-edit-btn-primary">
                            <span aria-hidden="true">✓</span> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </section>

        <aside class="school-edit-aside">
            <div class="school-edit-aside-icon" aria-hidden="true">⌁</div>
            <h3>Informasi Perubahan</h3>
            <p>Pastikan pembaruan konfigurasi sesuai kondisi jaringan sekolah yang sebenarnya.</p>

            <div class="school-edit-tip">
                <div class="school-edit-tip-icon">01</div>
                <div>
                    <strong>Periksa IP target</strong>
                    <small>Perubahan alamat target dapat memengaruhi perangkat yang diuji oleh proses monitoring.</small>
                </div>
            </div>
            <div class="school-edit-tip">
                <div class="school-edit-tip-icon">02</div>
                <div>
                    <strong>Perbarui bandwidth dengan benar</strong>
                    <small>Gunakan kapasitas layanan yang sesuai dengan informasi langganan sekolah.</small>
                </div>
            </div>
            <div class="school-edit-tip">
                <div class="school-edit-tip-icon">03</div>
                <div>
                    <strong>Perhatikan status sekolah</strong>
                    <small>Pastikan status aktif atau tidak aktif sesuai kebutuhan pemantauan.</small>
                </div>
            </div>
        </aside>
    </div>
</div>
@endsection
