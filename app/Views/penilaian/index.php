<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<!-- Hero Header Banner -->
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="hero-banner-card">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <h1 class="hero-title">Evaluasi KPI 360° & Penetapan Level Pendidik</h1>
                    <p class="hero-subtitle mb-0">
                        Matriks penilaian 5 pilar indikator kualitatif dan kuantitatif untuk penentuan predikat level karir pendidik MI Al-Husna.
                    </p>
                </div>
                <div>
                    <span class="badge bg-white text-dark px-3 py-2 rounded-pill fw-bold">
                        <i class="bi bi-people me-1 text-success"></i> <?= count($gurus) ?> Pendidik Terdaftar
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Teacher Evaluation List Card -->
<div class="card-noor">
    <div class="table-responsive">
        <table class="table table-noor align-middle">
            <thead>
                <tr>
                    <th class="text-center" style="width: 50px;">No</th>
                    <th>Nama Pendidik</th>
                    <th class="text-center">Posisi</th>
                    <th class="text-center">Pilar 1 (25%)</th>
                    <th class="text-center">Pilar 2 (25%)</th>
                    <th class="text-center">Pilar 3 (20%)</th>
                    <th class="text-center">Pilar 4 (15%)</th>
                    <th class="text-center">Pilar 5 (15%)</th>
                    <th class="text-center">Nilai Akhir</th>
                    <th class="text-center">Level Karir</th>
                    <th class="text-center">Status Approval</th>
                    <th class="text-center">Aksi Input / Validasi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($gurus as $idx => $g): ?>
                    <?php
                    $pRecord = null;
                    foreach ($penilaians as $p) {
                        if ($p['guru_id'] == $g['id']) {
                            $pRecord = $p;
                            break;
                        }
                    }
                    ?>
                    <tr>
                        <td class="text-center text-muted fw-semibold"><?= $idx + 1 ?></td>
                        <td>
                            <div class="fw-bold text-dark"><?= esc($g['nama_guru']) ?></div>
                            <small class="text-muted d-block"><?= esc($g['nip_nik'] ?: '-') ?></small>
                        </td>
                        <td class="text-center">
                            <?php if (!empty($g['posisi'])): ?>
                                <span class="badge bg-light text-dark border"><?= esc($g['posisi']) ?></span>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>

                        <?php if ($pRecord): ?>
                            <td class="text-center"><span class="fw-bold text-success"><?= number_format((float)$pRecord['skor_pilar_1'], 2) ?>%</span></td>
                            <td class="text-center"><span class="fw-bold text-success"><?= number_format((float)$pRecord['skor_pilar_2'], 2) ?>%</span></td>
                            <td class="text-center"><span class="fw-bold text-success"><?= number_format((float)$pRecord['skor_pilar_3'], 2) ?>%</span></td>
                            <td class="text-center"><span class="fw-bold text-success"><?= number_format((float)$pRecord['skor_pilar_4'], 2) ?>%</span></td>
                            <td class="text-center"><span class="fw-bold text-success"><?= number_format((float)$pRecord['skor_pilar_5'], 2) ?>%</span></td>
                            <td class="text-center">
                                <span class="fw-extrabold text-success fs-6"><?= number_format((float)$pRecord['nilai_akhir_total'], 2) ?></span>
                            </td>
                            <td class="text-center">
                                <?php if (in_array($g['role'] ?? '', ['admin', 'admin_tu']) || ($g['posisi'] ?? '') === 'Admin TU'): ?>
                                    <span class="badge bg-secondary-subtle text-secondary border">-</span>
                                <?php else: ?>
                                    <span class="badge badge-level badge-<?= strtolower($pRecord['predikat_level']) ?>">
                                        <?= esc($pRecord['predikat_level']) ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php if ($pRecord['status'] === 'approved'): ?>
                                    <span class="badge bg-success"><i class="bi bi-check-all me-1"></i> Disahkan</span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark"><i class="bi bi-clock me-1"></i> Submitted</span>
                                <?php endif; ?>
                            </td>
                        <?php else: ?>
                            <td colspan="6" class="text-muted text-center"><small><em>Belum dinilai</em></small></td>
                            <td class="text-center">
                                <?php if (in_array($g['role'] ?? '', ['admin', 'admin_tu']) || ($g['posisi'] ?? '') === 'Admin TU'): ?>
                                    <span class="badge bg-secondary-subtle text-secondary border">-</span>
                                <?php elseif (!empty($g['tingkatan_level'])): ?>
                                    <span class="badge badge-level badge-<?= strtolower($g['tingkatan_level']) ?>">
                                        <?= esc($g['tingkatan_level']) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-secondary-subtle text-secondary border">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center"><span class="badge bg-secondary">Pending</span></td>
                        <?php endif; ?>

                        <td class="text-center">
                            <div class="btn-group btn-group-sm" role="group">
                                <?php if ($g['user_id'] == session()->get('user_id')): ?>
                                    <button class="btn btn-secondary text-white" disabled title="Anda tidak dapat menilai diri sendiri" style="opacity: 0.7;">
                                        <i class="bi bi-person-x me-1"></i> Diri Sendiri
                                    </button>
                                <?php else: ?>
                                    <a href="<?= base_url('/penilaian/input/' . $g['id']) ?>" class="btn btn-success" title="Isi Evaluasi KPI">
                                        <i class="bi bi-pencil-square me-1"></i> Nilai
                                    </a>
                                <?php endif; ?>

                                <?php if ($pRecord && $pRecord['status'] !== 'approved' && in_array(session()->get('role'), ['admin', 'admin_tu', 'kepsek'])): ?>
                                    <a href="<?= base_url('/penilaian/approve/' . $pRecord['id']) ?>" class="btn btn-warning text-dark fw-bold" onclick="return confirm('Sahkan hasil penilaian KPI dan tingkat level untuk guru ini?')" title="Sah Pengesahan Kepsek">
                                        <i class="bi bi-check-circle me-1"></i> Sahkan
                                    </a>
                                <?php endif; ?>

                                <a href="<?= base_url('/laporan/rapor-guru/' . $g['id']) ?>" target="_blank" class="btn btn-outline-primary" title="Cetak Rapor KPI PDF">
                                    <i class="bi bi-file-earmark-pdf me-1"></i> Rapor
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
