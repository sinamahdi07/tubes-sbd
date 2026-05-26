<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import Data Games — Excel</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <style>
        /* ── Reset & Base ── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background: #0f0f13;
            color: #e2e8f0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        /* ── Card ── */
        .card {
            background: #1a1a24;
            border: 1px solid #2d2d3d;
            border-radius: 20px;
            padding: 3rem;
            width: 100%;
            max-width: 600px;
            box-shadow: 0 25px 60px rgba(0,0,0,.5);
        }

        /* ── Header ── */
        .header { text-align: center; margin-bottom: 2.5rem; }
        .header-icon {
            width: 72px; height: 72px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border-radius: 20px;
            display: inline-flex; align-items: center; justify-content: center;
            font-size: 2rem;
            margin-bottom: 1.25rem;
            box-shadow: 0 8px 24px rgba(99,102,241,.35);
        }
        .header h1 { font-size: 1.75rem; font-weight: 800; color: #fff; margin-bottom: .5rem; }
        .header p  { font-size: .95rem; color: #94a3b8; line-height: 1.6; }

        /* ── Alert ── */
        .alert {
            border-radius: 12px;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
            font-size: .9rem;
            display: flex;
            align-items: flex-start;
            gap: .75rem;
        }
        .alert-success { background: rgba(16,185,129,.12); border: 1px solid rgba(16,185,129,.3); color: #34d399; }
        .alert-error   { background: rgba(239,68,68,.12);  border: 1px solid rgba(239,68,68,.3);  color: #f87171; }
        .alert-icon { font-size: 1.25rem; flex-shrink: 0; }
        .alert-content strong { display: block; margin-bottom: .25rem; }

        /* ── Failures list ── */
        .failures {
            margin-top: 1rem;
            background: rgba(239,68,68,.08);
            border: 1px solid rgba(239,68,68,.2);
            border-radius: 10px;
            padding: 1rem;
        }
        .failures h3 { font-size: .85rem; color: #f87171; margin-bottom: .75rem; font-weight: 600; }
        .failures ul { list-style: none; }
        .failures li {
            font-size: .82rem;
            color: #fca5a5;
            padding: .3rem 0;
            border-bottom: 1px solid rgba(239,68,68,.1);
        }
        .failures li:last-child { border-bottom: none; }

        /* ── Form ── */
        .form-group { margin-bottom: 1.5rem; }
        label {
            display: block;
            font-size: .875rem;
            font-weight: 600;
            color: #cbd5e1;
            margin-bottom: .6rem;
        }

        /* Drop zone */
        .dropzone {
            border: 2px dashed #3d3d52;
            border-radius: 14px;
            padding: 2.5rem;
            text-align: center;
            cursor: pointer;
            transition: all .25s;
            background: #13131e;
            position: relative;
        }
        .dropzone:hover,
        .dropzone.drag-over {
            border-color: #6366f1;
            background: rgba(99,102,241,.06);
        }
        .dropzone input[type="file"] {
            position: absolute; inset: 0;
            width: 100%; height: 100%;
            opacity: 0; cursor: pointer;
        }
        .dropzone-icon  { font-size: 2.5rem; margin-bottom: .75rem; }
        .dropzone-text  { font-size: .95rem; color: #94a3b8; }
        .dropzone-text strong { color: #a5b4fc; }
        .dropzone-hint  { font-size: .8rem; color: #64748b; margin-top: .4rem; }
        .file-selected  {
            margin-top: .75rem;
            font-size: .85rem;
            color: #818cf8;
            font-weight: 500;
            display: none;
        }

        /* ── Info box ── */
        .info-box {
            background: #13131e;
            border: 1px solid #2d2d3d;
            border-radius: 12px;
            padding: 1.25rem;
            margin-bottom: 1.75rem;
        }
        .info-box h3 { font-size: .875rem; font-weight: 700; color: #a5b4fc; margin-bottom: .75rem; }
        .col-list {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: .35rem .75rem;
        }
        .col-item {
            display: flex;
            align-items: center;
            gap: .5rem;
            font-size: .82rem;
            color: #94a3b8;
        }
        .col-badge {
            background: #1e1e2e;
            border: 1px solid #3d3d52;
            color: #818cf8;
            font-size: .75rem;
            font-weight: 600;
            padding: .15rem .5rem;
            border-radius: 6px;
            font-family: monospace;
        }

        /* ── Submit button ── */
        .btn-submit {
            width: 100%;
            padding: .875rem;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: #fff;
            font-size: 1rem;
            font-weight: 700;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            transition: all .25s;
            box-shadow: 0 4px 16px rgba(99,102,241,.3);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .5rem;
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(99,102,241,.45);
        }
        .btn-submit:active { transform: translateY(0); }
        .btn-submit:disabled { opacity: .6; cursor: not-allowed; transform: none; }

        /* ── Back link ── */
        .back-link {
            display: block;
            text-align: center;
            margin-top: 1.25rem;
            color: #64748b;
            font-size: .875rem;
            text-decoration: none;
            transition: color .2s;
        }
        .back-link:hover { color: #94a3b8; }
    </style>
</head>
<body>

<div class="card">

    <!-- Header -->
    <div class="header">
        <div class="header-icon">📊</div>
        <h1>Import Data Games</h1>
        <p>Upload file Excel (.xlsx / .xls / .csv) berisi data game.<br>
           Data akan otomatis dinormalisasi ke 3NF.</p>
    </div>

    <!-- Alert: Success -->
    @if(session('success'))
    <div class="alert alert-success">
        <span class="alert-icon">✅</span>
        <div class="alert-content">
            <strong>Import Berhasil!</strong>
            {{ session('success') }}
        </div>
    </div>
    @if(session('failures') && count(session('failures')) > 0)
    <div class="failures">
        <h3>⚠️ {{ count(session('failures')) }} baris gagal diimport:</h3>
        <ul>
            @foreach(session('failures') as $f)
            <li>Baris {{ $f['row'] }} [{{ $f['title'] }}] — {{ $f['error'] }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    @endif

    <!-- Alert: Error -->
    @if(session('error'))
    <div class="alert alert-error">
        <span class="alert-icon">❌</span>
        <div class="alert-content">
            <strong>Import Gagal!</strong>
            {{ session('error') }}
        </div>
    </div>
    @endif

    <!-- Validation errors -->
    @if($errors->any())
    <div class="alert alert-error">
        <span class="alert-icon">⚠️</span>
        <div class="alert-content">
            <strong>Validasi Gagal:</strong>
            @foreach($errors->all() as $err)
                {{ $err }}
            @endforeach
        </div>
    </div>
    @endif

    <!-- Info: kolom yang dibutuhkan -->
    <div class="info-box">
        <h3>📋 Kolom yang dibutuhkan di Excel (baris pertama = header):</h3>
        <div class="col-list">
            <div class="col-item"><span class="col-badge">title</span> Judul game</div>
            <div class="col-item"><span class="col-badge">developer</span> Nama developer</div>
            <div class="col-item"><span class="col-badge">publisher</span> Nama publisher</div>
            <div class="col-item"><span class="col-badge">genre</span> Genre (koma jika &gt;1)</div>
            <div class="col-item"><span class="col-badge">price</span> Harga (angka)</div>
            <div class="col-item"><span class="col-badge">release_date</span> Tanggal rilis</div>
            <div class="col-item"><span class="col-badge">thumbnail</span> URL thumbnail</div>
            <div class="col-item"><span class="col-badge">screenshot</span> URL screenshot(s)</div>
            <div class="col-item"><span class="col-badge">description</span> Deskripsi game</div>
        </div>
    </div>

    <!-- Form -->
    <form id="importForm" action="{{ route('import.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="file">File Excel</label>
            <div class="dropzone" id="dropzone">
                <input type="file" id="file" name="file" accept=".xlsx,.xls,.csv"
                       onchange="onFileSelected(this)">
                <div class="dropzone-icon">📂</div>
                <div class="dropzone-text">
                    <strong>Klik atau drag & drop</strong> file Excel di sini
                </div>
                <div class="dropzone-hint">Format: .xlsx, .xls, .csv — Maks 10MB</div>
                <div class="file-selected" id="fileSelected"></div>
            </div>
        </div>

        <button type="submit" class="btn-submit" id="submitBtn">
            <span>⬆️</span> Import Sekarang
        </button>
    </form>

    <a href="/" class="back-link">← Kembali ke halaman utama</a>
</div>

<script>
    // Tampilkan nama file yang dipilih
    function onFileSelected(input) {
        const el = document.getElementById('fileSelected');
        if (input.files && input.files[0]) {
            el.textContent = '📄 ' + input.files[0].name;
            el.style.display = 'block';
        }
    }

    // Drag & drop visual feedback
    const dz = document.getElementById('dropzone');
    dz.addEventListener('dragover', e => { e.preventDefault(); dz.classList.add('drag-over'); });
    dz.addEventListener('dragleave', () => dz.classList.remove('drag-over'));
    dz.addEventListener('drop', e => {
        e.preventDefault();
        dz.classList.remove('drag-over');
        const file = document.getElementById('file');
        file.files = e.dataTransfer.files;
        onFileSelected(file);
    });

    // Disable tombol saat submit
    document.getElementById('importForm').addEventListener('submit', function() {
        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.innerHTML = '<span>⏳</span> Sedang mengimport...';
    });
</script>

</body>
</html>
