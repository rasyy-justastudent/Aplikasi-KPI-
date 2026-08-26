<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<?php if ($role === 'guru'): ?>

    <!-- ==================================================== -->
    <!-- DASHBOARD GURU / PENDIDIK (NOOR ACADEMY LAYOUT)      -->
    <!-- ==================================================== -->

    <!-- 1. Hero Header Banner (Image 1 Style) -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="hero-banner-card">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div>
                        <h1 class="hero-title">Hasil Evaluasi Kinerja</h1>
                        <p class="hero-subtitle mb-0">
                            Tinjauan komprehensif atas performa akademis dan pembinaan karakter, membandingkan pencapaian aktual dengan target standar mutu sekolah.
                        </p>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <?php if (!empty($myGuruData['id'])): ?>
                            <a href="<?= base_url('/laporan/rapor-guru/' . $myGuruData['id']) ?>" target="_blank" class="btn-hero-action">
                                <i class="bi bi-download"></i> Unduh Laporan PDF
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Top Metric Cards Row (Image 1 Top Cards) -->
    <div class="row g-4 mb-4">
        <!-- Card 1: Skor Keseluruhan -->
        <div class="col-md-4">
            <div class="metric-noor-card">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <span class="metric-label-title">Skor Keseluruhan</span>
                    <span class="badge-amber-pill">
                        <i class="bi bi-star-fill me-1"></i> <?= $myPenilaian ? esc($myPenilaian['predikat_level']) : esc(($myGuruData['tingkatan_level'] ?? null) ?: 'Sangat Baik') ?>
                    </span>
                </div>
                <div>
                    <div class="metric-big-number">
                        <?= $myPenilaian ? number_format($myPenilaian['nilai_akhir_total'], 1) : '92.4' ?>
                        <span class="metric-big-sub">/ 100</span>
                    </div>
                    <div class="mt-3">
                        <svg width="100%" height="24" viewBox="0 0 200 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M0 25 C30 20, 60 28, 90 15 C120 2, 150 18, 200 8" stroke="#10b981" stroke-width="3" fill="none" stroke-linecap="round"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2: Pertumbuhan Kinerja -->
        <div class="col-md-4">
            <div class="metric-noor-card">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <span class="metric-label-title">Pertumbuhan Kinerja</span>
                    <span class="badge-mint-pill">
                        <i class="bi bi-graph-up-arrow me-1"></i> +4.2%
                    </span>
                </div>
                <div class="d-flex flex-column gap-3 mt-1">
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-1" style="font-size: 0.82rem;">
                            <span class="text-muted">Semester Lalu</span>
                            <span class="fw-bold">88.2</span>
                        </div>
                        <div class="progress-noor-track">
                            <div class="progress-noor-bar bg-noor-emerald" style="width: 88.2%;"></div>
                        </div>
                    </div>
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-1" style="font-size: 0.82rem;">
                            <span class="text-muted">Saat Ini</span>
                            <span class="fw-bold"><?= $myPenilaian ? number_format($myPenilaian['nilai_akhir_total'], 1) : '92.4' ?></span>
                        </div>
                        <div class="progress-noor-track">
                            <div class="progress-noor-bar bg-noor-mint" style="width: <?= $myPenilaian ? $myPenilaian['nilai_akhir_total'] : 92.4 ?>%;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 3: Pencapaian Target -->
        <div class="col-md-4">
            <div class="metric-noor-card">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="metric-label-title">Pencapaian Target</span>
                </div>
                <div class="gauge-circle-wrapper my-auto">
                    <svg width="110" height="110" viewBox="0 0 100 100">
                        <circle cx="50" cy="50" r="40" stroke="#e5e7eb" stroke-width="10" fill="none" />
                        <circle cx="50" cy="50" r="40" stroke="#0d5c46" stroke-width="10" fill="none" 
                                stroke-dasharray="251.2" stroke-dashoffset="12.5" stroke-linecap="round" 
                                transform="rotate(-90 50 50)" />
                    </svg>
                    <div class="gauge-circle-text">
                        <div class="gauge-percentage"><?= $myPresensi ? $myPresensi['persentase'] : '95' ?>%</div>
                        <div class="gauge-sublabel">SELESAI</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. Analisis Kompetensi & Rincian Indikator Kinerja (Image 1 Middle Section) -->
    <div class="row g-4 mb-4">
        <!-- Analisis Kompetensi Radar Chart -->
        <div class="col-lg-5">
            <div class="card-noor h-100">
                <div class="mb-3">
                    <h5 class="fw-bold mb-1" style="color: var(--noor-text-main);">Analisis Kompetensi</h5>
                    <p class="text-muted" style="font-size: 0.82rem;">Sebaran nilai berdasarkan 5 pilar kompetensi guru.</p>
                </div>
                <div style="height: 310px;" class="position-relative">
                    <canvas id="myKpiRadarChart"></canvas>
                </div>
                <div class="d-flex justify-content-center gap-4 mt-3" style="font-size: 0.78rem;">
                    <span><i class="bi bi-circle-fill" style="color: #0d5c46;"></i> Skor Aktual</span>
                    <span><i class="bi bi-circle text-muted"></i> Target (Standar)</span>
                </div>
            </div>
        </div>

        <!-- Rincian Indikator Kinerja Breakdown Bars -->
        <div class="col-lg-7">
            <div class="card-noor h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="fw-bold mb-1" style="color: var(--noor-text-main);">Rincian Indikator Kinerja</h5>
                        <p class="text-muted mb-0" style="font-size: 0.82rem;">Perbandingan evaluasi Mandiri, Kepala Sekolah, dan Pengawas.</p>
                    </div>
                    <div class="d-flex gap-3" style="font-size: 0.75rem;">
                        <span class="d-flex align-items-center gap-1"><span style="width: 10px; height: 10px; background: #0d5c46; border-radius: 2px;"></span> Kepala Sekolah</span>
                        <span class="d-flex align-items-center gap-1"><span style="width: 10px; height: 10px; background: #a7f3d0; border-radius: 2px;"></span> Pengawas</span>
                    </div>
                </div>

                <div class="d-flex flex-column gap-3 pt-2">
                    <!-- Item 1: Pilar 1 Perencanaan Pembelajaran -->
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="fw-bold text-dark" style="font-size: 0.875rem;">Perencanaan Pembelajaran (Observasi Kelas)</span>
                            <span class="text-muted" style="font-size: 0.78rem;">Target: 90</span>
                        </div>
                        <div class="dual-progress-container">
                            <div class="dual-progress-part emerald" style="width: 55%;"><?= $myPenilaian ? $myPenilaian['skor_pilar_1'] : 95 ?></div>
                            <div class="dual-progress-part mint" style="width: 45%;"><?= max(0, ($myPenilaian ? $myPenilaian['skor_pilar_1'] : 92) - 3) ?></div>
                        </div>
                    </div>

                    <!-- Item 2: Pilar 2 Pelaksanaan KBM / Profesional -->
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="fw-bold text-dark" style="font-size: 0.875rem;">Pelaksanaan KBM & Profesional</span>
                            <span class="text-muted" style="font-size: 0.78rem;">Target: 85</span>
                        </div>
                        <div class="dual-progress-container">
                            <div class="dual-progress-part emerald" style="width: 50%;"><?= $myPenilaian ? $myPenilaian['skor_pilar_2'] : 88 ?></div>
                            <div class="dual-progress-part mint" style="width: 48%;"><?= max(0, ($myPenilaian ? $myPenilaian['skor_pilar_2'] : 86) - 2) ?></div>
                        </div>
                    </div>

                    <!-- Item 3: Pilar 3 Evaluasi & Kepribadian -->
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="fw-bold text-dark" style="font-size: 0.875rem;">Kepribadian & Refleksi Kinerja</span>
                            <span class="text-muted" style="font-size: 0.78rem;">Target: 88</span>
                        </div>
                        <div class="dual-progress-container">
                            <div class="dual-progress-part emerald" style="width: 52%;"><?= $myPenilaian ? $myPenilaian['skor_pilar_3'] : 90 ?></div>
                            <div class="dual-progress-part mint" style="width: 46%;"><?= max(0, ($myPenilaian ? $myPenilaian['skor_pilar_3'] : 89) - 1) ?></div>
                        </div>
                    </div>

                    <!-- Item 4: Pilar 4 & 5 Pengembangan Diri & Metode -->
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="fw-bold text-dark" style="font-size: 0.875rem;">Pengembangan Diri & Variasi Metode</span>
                            <span class="text-muted" style="font-size: 0.78rem;">Target: 80</span>
                        </div>
                        <div class="dual-progress-container">
                            <div class="dual-progress-part amber" style="width: 45%;"><?= $myPenilaian ? $myPenilaian['skor_pilar_5'] : 79 ?></div>
                            <div class="dual-progress-part light-amber" style="width: 43%;"><?= max(0, ($myPenilaian ? $myPenilaian['skor_pilar_5'] : 78) - 1) ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 4. Catatan & Feedback Row (Image 1 Bottom Section) -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="feedback-container-card">
                <div class="row g-4 align-items-center">
                    <div class="col-lg-4">
                        <h4 class="fw-bold mb-2" style="color: var(--noor-text-main);">Catatan &<br>Feedback</h4>
                        <p class="text-muted mb-0" style="font-size: 0.88rem;">
                            Ringkasan evaluasi kualitatif dari pihak manajemen sekolah untuk pengembangan karir selanjutnya.
                        </p>
                    </div>
                    <div class="col-lg-8">
                        <div class="d-flex flex-column gap-3">
                            <!-- Apresiasi Kinerja Quote -->
                            <div class="quote-box-card emerald-border">
                                <div class="quote-title text-success">
                                    <i class="bi bi-chat-left-quote-fill fs-5"></i> Apresiasi Kinerja
                                </div>
                                <div class="quote-text">
                                    "<?= esc(session()->get('nama_lengkap')) ?> telah menunjukkan dedikasi yang luar biasa dalam mengelola kelas. Pendekatan persuasif dalam menangani siswa yang kurang motivasi sangat patut diapresiasi. Penggunaan teknologi dalam evaluasi pembelajaran juga sudah menjadi role-model bagi guru-guru muda lainnya."
                                </div>
                            </div>

                            <!-- Area Pengembangan Quote -->
                            <div class="quote-box-card amber-border">
                                <div class="quote-title" style="color: var(--noor-amber-text);">
                                    <i class="bi bi-lightbulb-fill fs-5"></i> Area Pengembangan
                                </div>
                                <div class="quote-text">
                                    <?php if ($myLevelInfo && !empty($myLevelInfo['rekomendasi'])): ?>
                                        <?= esc($myLevelInfo['rekomendasi']) ?>
                                    <?php else: ?>
                                        <ul class="mb-0 ps-3">
                                            <li>Disarankan untuk lebih aktif dalam forum KKG (Kelompok Kerja Guru) tingkat kota untuk memperluas jejaring dan update kurikulum terbaru.</li>
                                            <li>Perlu meningkatkan publikasi karya ilmiah atau modul pembelajaran mandiri di semester depan.</li>
                                        </ul>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mt-4 pt-3 border-top">
                            <div class="d-flex align-items-center gap-3">
                                <div class="user-avatar-circle" style="width: 44px; height: 44px; font-size: 1.1rem; background: var(--noor-emerald);">
                                    H
                                </div>
                                <div>
                                    <div class="fw-bold text-dark mb-0" style="font-size: 0.92rem;">Ust. H. Rahman Hakim</div>
                                    <small class="text-muted">Kepala Sekolah</small>
                                </div>
                            </div>

                            <a href="<?= base_url('/portofolio') ?>" class="btn-noor-primary">
                                Rencanakan Program Pengembangan <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php else: ?>

    <!-- ==================================================== -->
    <!-- DASHBOARD ADMIN / KEPSEK / WAKA (NOOR ACADEMY LAYOUT)-->
    <!-- ==================================================== -->

    <!-- Header Banner -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="hero-banner-card">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div>
                        <h1 class="hero-title">Assalamu'alaikum, <?= esc(session()->get('nama_lengkap')) ?>! 👋</h1>
                        <p class="hero-subtitle mb-0">
                            Sistem Informasi Evaluasi Kinerja (KPI) & Klasifikasi 4 Level Pendidik MI Al-Husna.
                        </p>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="<?= base_url('/guru') ?>" class="btn-hero-action">
                            <i class="bi bi-people-fill"></i> Kelola Data Pendidik & User
                        </a>
                        <a href="<?= base_url('/guru/create') ?>" class="btn-hero-action" style="background: #ffffff; color: var(--noor-emerald-dark);">
                            <i class="bi bi-person-plus-fill"></i> Tambah Pendidik Baru
                        </a>
                        <a href="<?= base_url('/penilaian') ?>" class="btn-hero-action">
                            <i class="bi bi-clipboard-check"></i> Kelola Evaluasi KPI
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Managerial Metric Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <a href="<?= base_url('/guru') ?>" class="text-decoration-none">
                <div class="metric-noor-card">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="metric-label-title">Total Pendidik</span>
                        <div class="icon-box p-2 bg-light rounded-3 text-success"><i class="bi bi-people-fill fs-4"></i></div>
                    </div>
                    <div class="metric-big-number"><?= $totalGuru ?></div>
                    <div class="metric-big-sub mt-2 text-success fw-bold">Klik untuk Kelola CRUD Pendidik &rarr;</div>
                </div>
            </a>
        </div>

        <div class="col-md-3">
            <div class="metric-noor-card">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="metric-label-title">Level 4 (Expert)</span>
                    <span class="badge-amber-pill">Expert</span>
                </div>
                <div class="metric-big-number text-warning"><?= $levelCounts['EXP'] ?></div>
                <div class="metric-big-sub mt-2">Mentor / Pakar Kinerja</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="metric-noor-card">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="metric-label-title">Level 3 (Proficient)</span>
                    <span class="badge-mint-pill">Proficient</span>
                </div>
                <div class="metric-big-number text-success"><?= $levelCounts['PROF'] ?></div>
                <div class="metric-big-sub mt-2">Guru Mahir Mandiri</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="metric-noor-card">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="metric-label-title">ECT & Developing</span>
                    <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1 rounded-pill" style="font-size: 0.75rem;">Pembinaan</span>
                </div>
                <div class="metric-big-number text-info"><?= $levelCounts['ECT'] + $levelCounts['DEV'] ?></div>
                <div class="metric-big-sub mt-2">Memerlukan Pendampingan</div>
            </div>
        </div>
    </div>

    <!-- Quick CRUD Action Panel for Admin -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card-noor border-start border-4 border-success">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div>
                        <h5 class="fw-bold text-dark mb-1"><i class="bi bi-person-gear text-success me-2"></i>Manajemen Data Pendidik & User (CRUD)</h5>
                        <p class="text-muted mb-0" style="font-size: 0.85rem;">Fitur kelola roster 48 pendidik: Tambah akun baru, Edit profil/jabatan, Reset password, & Hapus data.</p>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="<?= base_url('/guru') ?>" class="btn btn-outline-success rounded-pill px-4 fw-semibold">
                            <i class="bi bi-card-list me-1"></i> Lihat & Edit Data Pendidik
                        </a>
                        <a href="<?= base_url('/guru/create') ?>" class="btn btn-success rounded-pill px-4 fw-bold">
                            <i class="bi bi-person-plus-fill me-1"></i> + Tambah Pendidik Baru
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Managerial Charts Row -->
    <div class="row g-4 mb-4">
        <div class="col-lg-7">
            <div class="card-noor h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0" style="color: var(--noor-text-main);"><i class="bi bi-radar text-success me-2"></i>Capaian 4 Pilar KPI Sekolah</h5>
                    <span class="badge-mint-pill">Skala 0-100%</span>
                </div>
                <div style="height: 310px;" class="position-relative">
                    <canvas id="kpiRadarChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card-noor h-100">
                <h5 class="fw-bold mb-3" style="color: var(--noor-text-main);"><i class="bi bi-pie-chart-fill text-success me-2"></i>Distribusi 4 Level Karir Guru</h5>
                <div style="height: 270px;" class="position-relative">
                    <canvas id="levelDoughnutChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Assessments Table -->
    <div class="row g-4">
        <div class="col-12">
            <div class="card-noor">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0" style="color: var(--noor-text-main);"><i class="bi bi-clock-history text-success me-2"></i>Evaluasi KPI Terbaru</h5>
                    <a href="<?= base_url('/penilaian') ?>" class="btn-noor-secondary">Lihat Semua</a>
                </div>

                <div class="table-responsive">
                    <table class="table table-noor align-middle">
                        <thead>
                            <tr>
                                <th>Nama Pendidik</th>
                                <th>Posisi</th>
                                <th>Nilai Total</th>
                                <th>Predikat Level</th>
                                <th>Status Approval</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($recentPenilaian)): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">Belum ada data evaluasi yang dimasukkan untuk periode ini.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($recentPenilaian as $rp): ?>
                                    <tr>
                                        <td class="fw-bold text-dark"><?= esc($rp['nama_guru']) ?></td>
                                        <td><span class="badge bg-light text-dark border"><?= esc($rp['posisi'] ?: 'Pendidik') ?></span></td>
                                        <td><span class="fw-extrabold text-success fs-6"><?= $rp['nilai_akhir_total'] ?></span> / 100</td>
                                        <td>
                                            <span class="badge badge-level badge-<?= strtolower($rp['predikat_level']) ?>">
                                                <?= $rp['predikat_level'] ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($rp['status'] === 'approved'): ?>
                                                <span class="badge bg-success"><i class="bi bi-check-all me-1"></i> Disahkan</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split me-1"></i> Menunggu Approval</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="<?= base_url('/laporan/rapor-guru/' . $rp['guru_id']) ?>" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill">
                                                <i class="bi bi-eye"></i> Rapor KPI
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

<?php endif; ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<?php if ($role === 'guru'): ?>
<script>
    // Personal Radar Chart Data for Teacher
    const myRadarCtx = document.getElementById('myKpiRadarChart').getContext('2d');
    new Chart(myRadarCtx, {
        type: 'radar',
        data: {
            labels: [
                'Pedagogik (Pilar 1)',
                'Profesional (Pilar 2)',
                'Kepribadian (Pilar 3)',
                'Sosial 360° (Pilar 4)'
            ],
            datasets: [{
                label: 'Skor Saya (%)',
                data: [
                    <?= $myPenilaian ? $myPenilaian['skor_pilar_1'] : 92 ?>,
                    <?= $myPenilaian ? $myPenilaian['skor_pilar_2'] : 88 ?>,
                    <?= $myPenilaian ? $myPenilaian['skor_pilar_3'] : 90 ?>,
                    <?= $myPenilaian ? $myPenilaian['skor_pilar_4'] : 85 ?>
                ],
                fill: true,
                backgroundColor: 'rgba(13, 92, 70, 0.2)',
                borderColor: '#0d5c46',
                pointBackgroundColor: '#0d5c46',
                pointBorderColor: '#ffffff',
                pointHoverBackgroundColor: '#ffffff',
                pointHoverBorderColor: '#0d5c46',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                r: {
                    angleLines: { color: 'rgba(0,0,0,0.08)' },
                    grid: { color: 'rgba(0,0,0,0.06)' },
                    suggestedMin: 50,
                    suggestedMax: 100,
                    ticks: { display: false }
                }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });
</script>
<?php else: ?>
<script>
    // School-wide Radar Chart Data for Admin/Leadership
    const radarCtx = document.getElementById('kpiRadarChart').getContext('2d');
    new Chart(radarCtx, {
        type: 'radar',
        data: {
            labels: [
                'Pedagogik (Pilar 1)',
                'Profesional (Pilar 2)',
                'Kepribadian (Pilar 3)',
                'Sosial 360° (Pilar 4)'
            ],
            datasets: [{
                label: 'Rata-Rata Sekolah (%)',
                data: [
                    <?= $avgScores['pilar_1'] ?: 88 ?>,
                    <?= $avgScores['pilar_2'] ?: 85 ?>,
                    <?= $avgScores['pilar_3'] ?: 87 ?>,
                    <?= $avgScores['pilar_4'] ?: 84 ?>
                ],
                fill: true,
                backgroundColor: 'rgba(13, 92, 70, 0.2)',
                borderColor: '#0d5c46',
                pointBackgroundColor: '#0d5c46',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                r: {
                    angleLines: { color: 'rgba(0,0,0,0.08)' },
                    suggestedMin: 50,
                    suggestedMax: 100
                }
            }
        }
    });

    // Doughnut Level Distribution
    const doughnutCtx = document.getElementById('levelDoughnutChart').getContext('2d');
    new Chart(doughnutCtx, {
        type: 'doughnut',
        data: {
            labels: ['Expert', 'Proficient', 'Developing', 'ECT'],
            datasets: [{
                data: [
                    <?= $levelCounts['EXP'] ?>,
                    <?= $levelCounts['PROF'] ?>,
                    <?= $levelCounts['DEV'] ?>,
                    <?= $levelCounts['ECT'] ?>
                ],
                backgroundColor: ['#854d0e', '#0d5c46', '#0284c7', '#6b7280'],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
</script>
<?php endif; ?>
<?= $this->endSection() ?>
