<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Sistem KPI Guru MI Al-Husna') ?></title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons & FontAwesome -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Custom Noor Academy Theme CSS -->
    <link rel="stylesheet" href="<?= base_url('/css/noor-theme.css') ?>">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

    <!-- Sidebar -->
    <nav id="sidebar">
        <div class="brand">
            <div class="brand-icon">
                <i class="fa-solid fa-graduation-cap"></i>
            </div>
            <div>
                <div class="brand-text">MI Al-Husna</div>
                <div class="brand-sub">Sistem KPI & Level Karir</div>
            </div>
        </div>

        <div class="py-2 flex-grow-1">
            <div class="nav-section-title">Navigasi Utama</div>

            <a href="<?= base_url('/dashboard') ?>" class="nav-link <?= uri_string() === 'dashboard' ? 'active' : '' ?>">
                <i class="bi bi-grid-1x2-fill"></i> Dashboard
            </a>

            <?php if (in_array(session()->get('role'), ['admin', 'admin_tu', 'kepsek', 'waka', 'koordinator']) || session()->get('role') !== 'guru'): ?>
                <a href="<?= base_url('/guru') ?>" class="nav-link <?= strpos(uri_string(), 'guru') === 0 ? 'active' : '' ?>">
                    <i class="bi bi-people-fill"></i> Kelola Data Pendidik & User
                </a>
                <a href="<?= base_url('/periode') ?>" class="nav-link <?= strpos(uri_string(), 'periode') === 0 ? 'active' : '' ?>">
                    <i class="bi bi-calendar3"></i> Periode KPI
                </a>
                <a href="<?= base_url('/indikator') ?>" class="nav-link <?= strpos(uri_string(), 'indikator') === 0 ? 'active' : '' ?>">
                    <i class="bi bi-list-check"></i> 5 Pilar Indikator
                </a>
            <?php endif; ?>

            <div class="nav-section-title">Evaluasi & Presensi</div>

            <a href="<?= base_url('/observasi') ?>" class="nav-link <?= strpos(uri_string(), 'observasi') === 0 ? 'active' : '' ?>">
                <i class="bi bi-eye-fill"></i> Observasi Kelas KBM
            </a>

            <a href="<?= base_url('/penilaian') ?>" class="nav-link <?= strpos(uri_string(), 'penilaian') === 0 ? 'active' : '' ?>">
                <i class="bi bi-person-workspace"></i> Penilaian Mandiri / 360°
            </a>

            <?php if (session()->get('guru_id')): ?>
                <a href="<?= base_url('/evaluasi-metode') ?>" class="nav-link <?= strpos(uri_string(), 'evaluasi-metode') === 0 ? 'active' : '' ?>">
                    <i class="bi bi-journal-check"></i> Evaluasi Metode (Pilar 5)
                </a>
                <a href="<?= base_url('/presensi') ?>" class="nav-link <?= strpos(uri_string(), 'presensi') === 0 ? 'active' : '' ?>">
                    <i class="bi bi-calendar-check-fill"></i> Presensi & Log KBM
                </a>
            <?php endif; ?>

            <a href="<?= base_url('/portofolio') ?>" class="nav-link <?= strpos(uri_string(), 'portofolio') === 0 ? 'active' : '' ?>">
                <i class="bi bi-bar-chart-steps"></i> Program Pengembangan
            </a>

            <div class="nav-section-title">Laporan & Rapor</div>

            <a href="<?= base_url('/laporan/rekap-sekolah') ?>" class="nav-link <?= uri_string() === 'laporan/rekap-sekolah' ? 'active' : '' ?>">
                <i class="bi bi-file-earmark-spreadsheet-fill"></i> Hasil Evaluasi Sekolah
            </a>

            <?php if (session()->get('guru_id')): ?>
                <a href="<?= base_url('/laporan/rapor-guru/' . session()->get('guru_id')) ?>" class="nav-link <?= strpos(uri_string(), 'rapor-guru') !== false ? 'active' : '' ?>">
                    <i class="bi bi-file-earmark-pdf-fill"></i> Rapor KPI Saya
                </a>
            <?php endif; ?>
        </div>

        <!-- Sidebar Footer Callout Card (Image 1/2/3/4 Bottom Sidebar Style) -->
        <div class="sidebar-footer-card">
            <div class="icon-box">
                <i class="bi bi-shield-check"></i>
            </div>
            <div>
                <div class="fw-bold" style="font-size: 0.78rem; color: #1e293b;">AMANAH</div>
                <div class="text-muted" style="font-size: 0.7rem;">Excellence in Service</div>
            </div>
        </div>

        <div class="px-3 pb-3">
            <a href="<?= base_url('/logout') ?>" class="btn btn-outline-danger btn-sm w-100 rounded-pill fw-semibold">
                <i class="bi bi-box-arrow-right me-1"></i> Keluar
            </a>
        </div>
    </nav>

    <!-- Content Wrapper -->
    <div id="content-wrapper">
        <!-- Top Navbar -->
        <header class="top-navbar">
            <div class="d-flex align-items-center gap-3">
                <button class="navbar-toggle-btn d-lg-none" id="sidebarToggle">
                    <i class="bi bi-text-indent-left fs-5"></i>
                </button>
            </div>

            <div class="d-flex align-items-center gap-3">
                <a href="#" class="nav-icon-btn" title="Notifikasi">
                    <i class="bi bi-bell"></i>
                    <span class="notification-badge"></span>
                </a>

                <div class="user-profile-badge">
                    <div>
                        <div class="fw-bold lh-1 text-dark" style="font-size: 0.85rem; text-align: right;"><?= esc(session()->get('nama_lengkap')) ?></div>
                        <small class="text-muted text-capitalize d-block" style="font-size: 0.72rem; text-align: right;"><?= str_replace('_', ' ', session()->get('role')) ?></small>
                    </div>
                    <div class="user-avatar-circle">
                        <?= strtoupper(substr(session()->get('nama_lengkap') ?? 'U', 0, 1)) ?>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Body -->
        <main class="p-4">
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm mb-4 border-0" role="alert" style="background: #ecfdf5; color: #065f46;">
                    <i class="bi bi-check-circle-fill me-2"></i><?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm mb-4 border-0" role="alert" style="background: #fef2f2; color: #991b1b;">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i><?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?= $this->renderSection('content') ?>
        </main>

        <!-- Footer -->
        <footer>
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                <span>&copy; 2026 <strong>MI Al-Husna</strong> — Sistem Evaluasi KPI & Level Karir Guru. All Rights Reserved.</span>
                <span class="text-muted"><i class="bi bi-code-slash text-success me-1"></i>Noor Academy Design System</span>
            </div>
        </footer>
    </div>

    <!-- Sidebar Mobile Backdrop Overlay -->
    <div id="sidebarBackdrop" class="sidebar-backdrop"></div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarBackdrop = document.getElementById('sidebarBackdrop');

        function toggleMobileSidebar() {
            sidebar?.classList.toggle('show');
            sidebarBackdrop?.classList.toggle('show');
        }

        sidebarToggle?.addEventListener('click', toggleMobileSidebar);
        sidebarBackdrop?.addEventListener('click', toggleMobileSidebar);

        // Auto close sidebar when clicking a link on mobile
        document.querySelectorAll('#sidebar .nav-link').forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth < 992) {
                    sidebar?.classList.remove('show');
                    sidebarBackdrop?.classList.remove('show');
                }
            });
        });
    </script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>
