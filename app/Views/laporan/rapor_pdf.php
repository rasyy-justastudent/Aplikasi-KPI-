<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rapor Evaluasi Kinerja (KPI) Guru — <?= esc($guru['nama_guru']) ?></title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --noor-emerald-dark: #043e2e;
            --noor-emerald: #064e3b;
            --noor-emerald-light: #0d5c46;
            --noor-mint: #5eead4;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f4f7f6;
            color: #1e293b;
            padding: 2.5rem 1rem;
        }

        .rapor-card {
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.05);
            padding: 3rem;
            max-width: 900px;
            margin: 0 auto;
            border: 1px solid #e2e8f0;
        }

        /* Top Header Card */
        .header-box {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            padding: 1.5rem;
            text-align: center;
            border-top: 4px solid var(--noor-emerald-dark);
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
        }

        .header-logo-icon {
            width: 42px;
            height: 42px;
            background: #f1f5f9;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: var(--noor-emerald-dark);
        }

        .badge-tag-mint {
            background: #e6f4ef;
            color: #065f46;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 6px 14px;
            border-radius: 20px;
            letter-spacing: 0.02em;
        }

        .badge-tag-pink {
            background: #fce7f3;
            color: #9d174d;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.75rem;
            font-weight: 700;
            padding: 6px 14px;
            border-radius: 20px;
            letter-spacing: 0.04em;
        }

        /* Score Circular Gauge */
        .gauge-wrapper {
            position: relative;
            width: 130px;
            height: 130px;
            margin: 0 auto;
        }

        .gauge-center-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
        }

        /* Table Styling */
        .custom-report-table th {
            background-color: #f8fafc;
            color: #64748b;
            font-weight: 700;
            font-size: 0.72rem;
            letter-spacing: 0.06em;
            padding: 0.85rem 1rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .custom-report-table td {
            padding: 1rem;
            border-bottom: 1px solid #f1f5f9;
        }

        .pilar-num-badge {
            width: 24px;
            height: 24px;
            background: #e2e8f0;
            color: #475569;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.75rem;
        }        .mini-progress-bar {
            width: 100px;
            height: 8px;
            background: #e2e8f0;
            border-radius: 10px;
            overflow: hidden;
        }

        .mini-progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #10b981 0%, #0d5c46 100%);
            border-radius: 10px;
            transition: width 0.4s ease;
        }

        @media print {
            body {
                background-color: #ffffff !important;
                padding: 0 !important;
            }
            .rapor-card {
                box-shadow: none !important;
                border: none !important;
                padding: 0 !important;
                width: 100% !important;
                max-width: 100% !important;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    <!-- Action Buttons -->
    <div class="no-print text-center mb-4">
        <button onclick="window.print()" class="btn btn-success btn-lg rounded-pill px-5 fw-bold shadow">
            <i class="bi bi-printer me-2"></i> Cetak / Simpan PDF Rapor
        </button>
        <a href="<?= base_url('/laporan/rekap-sekolah') ?>" class="btn btn-outline-secondary btn-lg rounded-pill ms-2 px-4">
            Kembali
        </a>
    </div>

    <!-- Rapor Main Document -->
    <div class="rapor-card">

        <!-- 1. Header Card Banner -->
        <div class="header-box mb-4">
            <div class="d-flex align-items-center justify-content-center gap-2 mb-2">
                <div class="header-logo-icon"><i class="bi bi-bank"></i></div>
                <div class="text-start">
                    <div class="fw-bold text-uppercase" style="font-size: 0.88rem; letter-spacing: 0.05em; color: #1e293b;">MADRASAH IBTIDAIYAH AL-HUSNA</div>
                    <div class="text-muted" style="font-size: 0.78rem;">Rapor Evaluasi Kinerja (KPI) Guru</div>
                </div>
            </div>

            <div class="d-flex justify-content-center align-items-center flex-wrap gap-2 mt-3">
                <span class="badge-tag-mint">Tahun Ajaran <?= esc($activePeriode['tahun_pelajaran'] ?? '2026-2027') ?></span>
                <span class="badge-tag-mint">Semester <?= esc($activePeriode['semester'] ?? 'Ganjil') ?></span>
                <?php if (($penilaian['status'] ?? '') === 'approved'): ?>
                    <span class="badge-tag-mint" style="background: #dcfce7; color: #15803d;">
                        ✓ DISAHKAN KEPALA SEKOLAH
                    </span>
                <?php else: ?>
                    <span class="badge-tag-pink">
                        ≡ DRAFT EVALUASI
                    </span>
                <?php endif; ?>
            </div>
        </div>

        <!-- 2. Row: Identitas Pegawai & Skor KPI Akumulasi Card -->
        <div class="row g-4 mb-4 align-items-stretch">
            <!-- Left Column: Identitas Pegawai Card -->
            <div class="col-md-7">
                <div class="h-100 p-4 rounded-4 bg-white border" style="border-color: #e2e8f0 !important;">
                    <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
                        <i class="bi bi-person-vcard fs-5 text-dark"></i>
                        <span class="fw-bold text-dark" style="font-size: 0.9rem;">Identitas Pegawai</span>
                    </div>

                    <div class="row g-3" style="font-size: 0.85rem;">
                        <div class="col-6">
                            <div class="text-uppercase text-muted fw-semibold" style="font-size: 0.7rem; letter-spacing: 0.05em; font-family: 'JetBrains Mono', monospace;">NAMA LENGKAP</div>
                            <div class="fw-bold text-dark mt-1"><?= esc($guru['nama_guru']) ?></div>
                        </div>
                        <div class="col-6">
                            <div class="text-uppercase text-muted fw-semibold" style="font-size: 0.7rem; letter-spacing: 0.05em; font-family: 'JetBrains Mono', monospace;">NIP / NUPTK</div>
                            <div class="fw-bold text-dark mt-1"><?= esc($guru['nip_nik'] ?: '-') ?></div>
                        </div>
                        <div class="col-6">
                            <div class="text-uppercase text-muted fw-semibold" style="font-size: 0.7rem; letter-spacing: 0.05em; font-family: 'JetBrains Mono', monospace;">TUGAS UTAMA</div>
                            <div class="fw-semibold text-dark mt-1"><?= esc($guru['bidang_studi'] ?: 'Guru Kelas / Tematik') ?></div>
                        </div>
                        <div class="col-6">
                            <div class="text-uppercase text-muted fw-semibold" style="font-size: 0.7rem; letter-spacing: 0.05em; font-family: 'JetBrains Mono', monospace;">TUGAS TAMBAHAN</div>
                            <div class="fw-semibold text-dark mt-1"><?= esc($guru['posisi'] ?: 'Wali Kelas') ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Dark Emerald Skor KPI Akumulasi Card -->
            <div class="col-md-5">
                <div class="h-100 rounded-4 text-white text-center p-4 d-flex flex-column justify-content-between position-relative overflow-hidden" style="background: var(--noor-emerald-dark);">
                    <div class="text-uppercase tracking-widest fw-bold opacity-75" style="font-size: 0.72rem; font-family: 'JetBrains Mono', monospace;">
                        SKOR KPI AKUMULASI
                    </div>

                    <!-- Circular SVG Ring Gauge -->
                    <div class="gauge-wrapper my-3">
                        <svg width="130" height="130" viewBox="0 0 100 100">
                            <circle cx="50" cy="50" r="40" stroke="rgba(255,255,255,0.15)" stroke-width="8" fill="none" />
                            <?php 
                            $scoreVal = (float)($penilaian['nilai_akhir_total'] ?? 74.4);
                            $dashOffset = 251.2 * (1 - ($scoreVal / 100));
                            ?>
                            <circle cx="50" cy="50" r="40" stroke="#5eead4" stroke-width="8" fill="none"
                                     stroke-dasharray="251.2" stroke-dashoffset="<?= $dashOffset ?>"
                                     stroke-linecap="round" transform="rotate(-90 50 50)" />
                        </svg>
                        <div class="gauge-center-text">
                            <div class="display-6 fw-extrabold text-white lh-1"><?= number_format($scoreVal, 1) ?></div>
                            <small class="opacity-75" style="font-size: 0.7rem;">/ 100</small>
                        </div>
                    </div>

                    <div class="p-2.5 rounded-3 bg-black bg-opacity-20 border border-white border-opacity-10">
                        <div class="text-white-50" style="font-size: 0.72rem;">Hasil Akhir & Klasifikasi</div>
                        <div class="fw-bold text-white mt-0.5" style="font-size: 0.88rem;">
                            <?php if (in_array($guru['role'] ?? '', ['admin', 'admin_tu']) || ($guru['posisi'] ?? '') === 'Admin TU'): ?>
                                Admin TU (Staf Tenaga Kependidikan)
                            <?php else: ?>
                                <?php 
                                $lvlDisplay = esc($levelInfo['level_name'] ?? 'Guru Berkembang');
                                if (strpos($lvlDisplay, 'Tingkat') === false && !empty($levelInfo['level_num'])) {
                                    $lvlDisplay = 'Tingkat ' . $levelInfo['level_num'] . ': ' . $lvlDisplay;
                                }
                                ?>
                                <?= $lvlDisplay ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Rincian Nilai 4 Pilar Evaluasi Terbobot Table Card -->
        <div class="p-4 rounded-4 bg-white border mb-4" style="border-color: #e2e8f0 !important;">
            <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
                <i class="bi bi-table fs-5 text-dark"></i>
                <span class="fw-bold text-dark" style="font-size: 0.9rem;">Rincian Nilai 4 Pilar Evaluasi Terbobot (25% per Pilar)</span>
            </div>

            <table class="table table-borderless align-middle custom-report-table mb-0">
                <thead>
                    <tr class="text-uppercase text-muted border-bottom" style="font-size: 0.72rem; font-family: 'JetBrains Mono', monospace;">
                        <th style="width: 45%;">PILAR EVALUASI</th>
                        <th class="text-center" style="width: 15%;">BOBOT (%)</th>
                        <th class="text-center" style="width: 25%;">SKOR CAPAIAN</th>
                        <th class="text-end" style="width: 15%;">NILAI TERBOBOT</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $pilars = [
                        ['num' => '1', 'name' => 'Kompetensi Pedagogik (Observasi & Metode)', 'bobot' => 25, 'skor' => $penilaian['skor_pilar_1'] ?? 70.0],
                        ['num' => '2', 'name' => 'Kompetensi Profesional', 'bobot' => 25, 'skor' => $penilaian['skor_pilar_2'] ?? 76.0],
                        ['num' => '3', 'name' => 'Kepribadian & Kedisiplinan', 'bobot' => 25, 'skor' => $penilaian['skor_pilar_3'] ?? 80.0],
                        ['num' => '4', 'name' => 'Kompetensi Sosial (360°)', 'bobot' => 25, 'skor' => $penilaian['skor_pilar_4'] ?? 74.0],
                    ];
                    foreach ($pilars as $p):
                        $terbobot = number_format(($p['skor'] * ($p['bobot'] / 100)), 2);
                    ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2.5">
                                    <span class="pilar-num-badge"><?= $p['num'] ?></span>
                                    <span class="fw-semibold text-dark" style="font-size: 0.88rem;"><?= $p['name'] ?></span>
                                </div>
                            </td>
                            <td class="text-center fw-semibold text-muted" style="font-size: 0.85rem;"><?= $p['bobot'] ?>%</td>
                            <td>
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    <div class="mini-progress-bar flex-grow-1" style="max-width: 100px;">
                                        <div class="mini-progress-fill" style="width: <?= min(100, max(0, $p['skor'])) ?>%;"></div>
                                    </div>
                                    <span class="fw-bold text-success font-monospace" style="font-size: 0.85rem; min-width: 55px; text-align: right;"><?= number_format($p['skor'], 2) ?>%</span>
                                </div>
                            </td>
                            <td class="text-end fw-extrabold text-dark font-monospace" style="font-size: 0.92rem;"><?= $terbobot ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot class="border-top">
                    <tr>
                        <td colspan="3" class="text-end fw-bold text-dark py-3" style="font-size: 0.85rem;">Total Nilai Akhir</td>
                        <td class="text-end fw-extrabold text-dark py-3" style="font-size: 1.05rem;"><?= number_format($penilaian['nilai_akhir_total'], 2) ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- 4. Row: Rekomendasi Pembinaan & Catatan Evaluator Side-by-Side Cards -->
        <div class="row g-4 mb-5">
            <!-- Left Card: Rekomendasi Pembinaan -->
            <div class="col-md-6">
                <div class="p-4 rounded-4 h-100" style="background: #f0fdf4; border: 1px solid #dcfce7;">
                    <div class="d-flex align-items-center gap-2 mb-2 text-success">
                        <i class="bi bi-lightbulb fs-5"></i>
                        <span class="fw-bold text-dark" style="font-size: 0.88rem;">Rekomendasi Pembinaan</span>
                    </div>
                    <p class="text-muted mb-0" style="font-size: 0.83rem; line-height: 1.6;">
                        <?= esc($levelInfo['rekomendasi'] ?? 'Berdasarkan hasil evaluasi, disarankan untuk meningkatkan partisipasi dalam pelatihan metodologi pembelajaran aktif. Perlu pendampingan khusus dalam penyusunan RPP berdiferensiasi untuk semester mendatang.') ?>
                    </p>
                </div>
            </div>

            <!-- Right Card: Catatan Evaluator -->
            <div class="col-md-6">
                <div class="p-4 rounded-4 h-100 bg-white border" style="border-color: #e2e8f0 !important;">
                    <div class="d-flex align-items-center gap-2 mb-2 text-dark">
                        <i class="bi bi-journal-text fs-5"></i>
                        <span class="fw-bold text-dark" style="font-size: 0.88rem;">Catatan Evaluator</span>
                    </div>
                    <p class="text-muted fst-italic mb-0" style="font-size: 0.83rem; line-height: 1.6;">
                        "<?= esc($penilaian['observer_comments'] ?: 'Tingkat kedisiplinan dan kepribadian sangat baik, menjadi teladan bagi siswa. Namun perlu inovasi lebih lanjut dalam penyampaian materi di kelas agar siswa lebih antusias.') ?>"
                    </p>
                </div>
            </div>
        </div>

        <!-- 5. Signature Blocks -->
        <div class="row text-center pt-3" style="font-size: 0.85rem;">
            <div class="col-6">
                <p class="mb-5 text-muted">Guru yang Dievaluasi</p>
                <div class="fw-bold text-dark text-decoration-underline mt-4" style="font-size: 0.92rem;"><?= esc($guru['nama_guru']) ?></div>
                <small class="text-muted d-block mt-1" style="font-family: 'JetBrains Mono', monospace;">NIP. <?= esc($guru['nip_nik'] ?: '-') ?></small>
            </div>
            <div class="col-6">
                <p class="mb-5 text-muted">Kepala Madrasah</p>
                <div class="fw-bold text-dark text-decoration-underline mt-4" style="font-size: 0.92rem;">Dr. H. Ahmad Kepsek, M.Pd</div>
                <small class="text-muted d-block mt-1" style="font-family: 'JetBrains Mono', monospace;">NIP. 197508122000121001</small>
            </div>
        </div>

    </div>

</body>
</html>
