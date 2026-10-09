<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PT Bukit Limau Teknologi Indonesia — Sistem Manajemen Aset</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Archivo:wght@600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --ink: #17191d;
            --paper: #faf9f7;
            --paper-alt: #f2f0ec;
            --orange: #e2611c;
            --orange-dark: #b84c14;
            --steel: #3d5872;
            --line: #e0ddd6;
            --muted: #6f7278;
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            background: var(--paper);
            color: var(--ink);
            font-family: 'Inter', sans-serif;
            font-size: 16px;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }

        h1,
        h2,
        h3 {
            font-family: 'Archivo', sans-serif;
            color: var(--ink);
            margin: 0;
            letter-spacing: -0.01em;
        }

        a {
            color: inherit;
        }

        img {
            max-width: 100%;
            display: block;
        }

        .wrap {
            max-width: 1120px;
            margin: 0 auto;
            padding: 0 32px;
        }

        /* ---------- Header ---------- */

        header {
            border-bottom: 1px solid var(--line);
            background: var(--paper);
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .header-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 18px 0;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .brand-mark {
            width: 34px;
            height: 34px;
            border: 2.5px solid var(--orange);
            border-radius: 7px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Archivo', sans-serif;
            font-weight: 800;
            font-size: 13px;
            color: var(--orange);
            flex-shrink: 0;
        }

        .brand-name {
            font-family: 'Archivo', sans-serif;
            font-weight: 700;
            font-size: 15px;
            line-height: 1.25;
        }

        .brand-name span {
            display: block;
            font-family: 'Inter', sans-serif;
            font-weight: 400;
            font-size: 11.5px;
            color: var(--muted);
        }

        nav {
            display: flex;
            align-items: center;
            gap: 28px;
        }

        nav a.nav-link {
            font-size: 14.5px;
            text-decoration: none;
            color: var(--ink);
            border-bottom: 1px solid transparent;
            padding-bottom: 2px;
            transition: border-color .15s ease;
        }

        nav a.nav-link:hover {
            border-color: var(--orange);
        }

        .btn {
            display: inline-block;
            font-family: 'Inter', sans-serif;
            font-weight: 600;
            font-size: 14.5px;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            border: 1px solid transparent;
        }

        .btn-primary {
            background: var(--orange);
            color: #fff;
            transition: background .15s ease;
        }

        .btn-primary:hover {
            background: var(--orange-dark);
        }

        @media (max-width: 720px) {
            nav .nav-link {
                display: none;
            }
        }

        /* ---------- Hero ---------- */

        .hero {
            padding: 72px 0 64px;
        }

        .hero-grid {
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            gap: 56px;
            align-items: start;
        }

        .kicker {
            font-size: 14px;
            color: var(--steel);
            font-weight: 600;
            margin: 0 0 14px;
            padding-bottom: 12px;
            border-bottom: 2px solid var(--orange);
            display: inline-block;
        }

        .hero h1 {
            font-size: 44px;
            font-weight: 800;
            line-height: 1.08;
            margin-bottom: 20px;
        }

        .hero p.lead {
            font-size: 17px;
            color: var(--muted);
            max-width: 46ch;
            margin-bottom: 28px;
        }

        .hero-actions {
            display: flex;
            gap: 14px;
            align-items: center;
        }

        .hero-actions .btn-secondary {
            border-color: var(--line);
            font-weight: 500;
            color: var(--ink);
        }

        /* Profile card */

        .profile-card {
            border: 1px solid var(--line);
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
        }

        .profile-card-head {
            padding: 18px 22px;
            border-bottom: 1px solid var(--line);
            background: var(--paper-alt);
        }

        .profile-card-head h2 {
            font-size: 15px;
            font-weight: 700;
        }

        .profile-row {
            display: grid;
            grid-template-columns: 100px 1fr;
            gap: 16px;
            padding: 14px 22px;
            border-bottom: 1px solid var(--line);
        }

        .profile-row:last-child {
            border-bottom: none;
        }

        .profile-row dt {
            font-size: 13px;
            color: var(--muted);
            font-weight: 500;
            margin: 0;
        }

        .profile-row dd {
            font-size: 14.5px;
            margin: 0;
            font-weight: 500;
        }

        @media (max-width: 860px) {
            .hero-grid {
                grid-template-columns: 1fr;
            }

            .hero h1 {
                font-size: 34px;
            }
        }

        /* ---------- Section shell ---------- */

        section.block {
            padding: 56px 0;
            border-top: 1px solid var(--line);
        }

        .section-head {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 24px;
            margin-bottom: 28px;
            flex-wrap: wrap;
        }

        .section-head h2 {
            font-size: 26px;
            font-weight: 700;
        }

        .section-head p {
            color: var(--muted);
            font-size: 14.5px;
            margin: 6px 0 0;
        }

        /* ---------- Struktur organisasi ---------- */

        .chart-frame {
            border: 1px solid var(--line);
            border-radius: 8px;
            background: #fff;
            padding: 20px;
            overflow-x: auto;
        }

        .chart-frame img {
            min-width: 900px;
            width: 100%;
        }

        .chart-hint {
            display: none;
            font-size: 13px;
            color: var(--muted);
            margin-top: 10px;
        }

        @media (max-width: 720px) {
            .chart-hint {
                display: block;
            }
        }

        /* ---------- Modul sistem ---------- */

        .modules {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            border: 1px solid var(--line);
            border-radius: 8px;
            overflow: hidden;
            background: #fff;
        }

        .module {
            padding: 26px 22px;
            border-right: 1px solid var(--line);
        }

        .module:last-child {
            border-right: none;
        }

        .module svg {
            width: 22px;
            height: 22px;
            color: var(--orange);
            margin-bottom: 14px;
        }

        .module h3 {
            font-size: 15.5px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .module p {
            font-size: 13.5px;
            color: var(--muted);
            margin: 0;
        }

        .module .tag {
            display: inline-block;
            margin-top: 12px;
            font-size: 11.5px;
            color: var(--steel);
            font-weight: 600;
            background: #eef2f5;
            padding: 3px 8px;
            border-radius: 4px;
        }

        @media (max-width: 860px) {
            .modules {
                grid-template-columns: 1fr 1fr;
            }

            .module {
                border-bottom: 1px solid var(--line);
            }

            .module:nth-child(2) {
                border-right: none;
            }

            .module:nth-child(4) {
                border-right: none;
            }
        }

        @media (max-width: 520px) {
            .modules {
                grid-template-columns: 1fr;
            }

            .module {
                border-right: none;
            }
        }

        /* ---------- Footer ---------- */

        footer {
            border-top: 1px solid var(--line);
            padding: 36px 0 40px;
            background: var(--paper-alt);
        }

        .footer-grid {
            display: flex;
            justify-content: space-between;
            gap: 24px;
            flex-wrap: wrap;
        }

        .footer-col p {
            margin: 0 0 4px;
            font-size: 13.5px;
            color: var(--muted);
        }

        .footer-col h4 {
            font-family: 'Inter', sans-serif;
            font-size: 13px;
            font-weight: 600;
            color: var(--ink);
            margin: 0 0 8px;
        }

        .colophon {
            margin-top: 28px;
            padding-top: 20px;
            border-top: 1px solid var(--line);
            font-size: 12.5px;
            color: var(--muted);
        }

        @media (prefers-reduced-motion: reduce) {
            html {
                scroll-behavior: auto;
            }

            * {
                transition: none !important;
            }
        }
    </style>
</head>

<body>

    <header>
        <div class="wrap header-inner">
            <a href="/" class="brand">
                <span class="brand-mark">B</span>
                <span class="brand-name">
                    PT Bukit Limau Teknologi Indonesia
                    <span>Sistem Manajemen Aset</span>
                </span>
            </a>
            <nav>
                <a href="#profil" class="nav-link">Profil Perusahaan</a>
                <a href="#struktur" class="nav-link">Struktur Organisasi</a>
                <a href="#modul" class="nav-link">Modul Sistem</a>
                <a href="/aset" class="btn btn-primary">Masuk ke Sistem</a>
            </nav>
        </div>
    </header>

    <main>
        <section class="hero" id="profil">
            <div class="wrap hero-grid">
                <div>
                    <span class="kicker">Sistem Informasi Manajemen Aset</span>
                    <h1>PT Bukit Limau<br>Teknologi Indonesia</h1>
                    <p class="lead">
                        Platform internal untuk mencatat, melacak, dan melaporkan aset
                        perusahaan — mulai dari barang masuk, barang keluar, hingga nomor
                        seri tiap unit.
                    </p>
                    <div class="hero-actions">
                        <a href="/aset" class="btn btn-primary">Masuk ke Sistem</a>
                        <a href="#struktur" class="btn btn-secondary">Lihat Struktur Organisasi</a>
                    </div>
                </div>

                <div class="profile-card">
                    <div class="profile-card-head">
                        <h2>Profil Perusahaan</h2>
                    </div>
                    <dl style="margin:0;">
                        <div class="profile-row">
                            <dt>Nama</dt>
                            <dd>PT Bukit Limau Teknologi Indonesia</dd>
                        </div>
                        <div class="profile-row">
                            <dt>Alamat</dt>
                            <dd>Jl. Bukit Wato-Wato VII Permata Blok B2A/14, RT.03/RW.08, Puri, Bringin, Kec. Ngaliyan, Kota Semarang, Jawa Tengah 50189</dd>
                        </div>
                        <div class="profile-row">
                            <dt>Pimpinan</dt>
                            <dd>Shidqi Muchtar, S.Kom</dd>
                        </div>
                        <div class="profile-row">
                            <dt>Karyawan</dt>
                            <dd>36 orang</dd>
                        </div>
                        <div class="profile-row">
                            <dt>Didirikan</dt>
                            <dd>2025</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </section>

        <section class="block" id="struktur">
            <div class="wrap">
                <div class="section-head">
                    <div>
                        <h2>Struktur Organisasi</h2>
                        <p>Susunan jabatan dari Direktur hingga staf di setiap divisi.</p>
                    </div>
                </div>
                <div class="chart-frame">
                    <img src="{{ asset('images/blti-org-structure.jpg') }}" alt="Struktur organisasi PT Bukit Limau Teknologi Indonesia">
                </div>
                <p class="chart-hint">Geser ke samping untuk melihat struktur secara lengkap.</p>
            </div>
        </section>

        <section class="block" id="modul">
            <div class="wrap">
                <div class="section-head">
                    <div>
                        <h2>Modul Sistem</h2>
                        <p>Empat menu utama yang tersedia di sistem ini.</p>
                    </div>
                </div>

                <div class="modules">
                    <div class="module">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                            <path d="M3 7l9-4 9 4-9 4-9-4z" />
                            <path d="M3 7v10l9 4 9-4V7" />
                            <path d="M12 11v10" />
                        </svg>
                        <h3>Daftar Aset</h3>
                        <p>Melihat seluruh aset perusahaan beserta jumlah stok yang tersedia saat ini.</p>
                    </div>
                    <div class="module">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                            <path d="M12 4v12m0 0l-4-4m4 4l4-4" />
                            <path d="M4 18v2h16v-2" />
                        </svg>
                        <h3>Aset Masuk</h3>
                        <p>Mencatat aset yang baru diterima, lengkap dengan nomor seri untuk barang yang memerlukannya.</p>
                    </div>
                    <div class="module">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                            <path d="M12 20V8m0 0l-4 4m4-4l4 4" />
                            <path d="M4 4v2h16V4" />
                        </svg>
                        <h3>Aset Keluar</h3>
                        <p>Mencatat aset yang dikeluarkan atau didistribusikan ke unit kerja terkait.</p>
                    </div>
                    <div class="module">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                            <circle cx="9" cy="8" r="3.2" />
                            <path d="M2.5 19c.7-3.4 3.3-5.5 6.5-5.5s5.8 2.1 6.5 5.5" />
                            <circle cx="17.5" cy="9" r="2.4" />
                            <path d="M15.8 13.7c1.9.2 3.5 1.7 4 4" />
                        </svg>
                        <h3>Kelola User</h3>
                        <p>Mengatur akun pengguna sistem — khusus untuk Pimpinan.</p>
                        <span class="tag">Akses Pimpinan</span>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <div class="wrap">
            <div class="footer-grid">
                <div class="footer-col">
                    <h4>PT Bukit Limau Teknologi Indonesia</h4>
                    <p>Jl. Bukit Wato-Wato VII Permata Blok B2A/14, RT.03/RW.08</p>
                    <p>Puri, Bringin, Kec. Ngaliyan, Kota Semarang, Jawa Tengah 50189</p>
                </div>
                <div class="footer-col">
                    <h4>Sistem</h4>
                    <p><a href="/aset" style="text-decoration:none; color:inherit;">Masuk ke panel admin</a></p>
                    <p>Didirikan 2025 · 36 karyawan</p>
                </div>
            </div>
            <div class="colophon">
                Disusun dalam rangka Kerja Praktik — Azriel Akbar Ferdiansyah, NIM G.231.19.0230.
                Pembimbing: Fahrorozi, S.Kom (Product Manager, PT Bukit Limau Teknologi Indonesia).
            </div>
        </div>
    </footer>

</body>

</html>