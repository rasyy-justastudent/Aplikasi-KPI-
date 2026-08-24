<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<!-- Header Banner -->
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="hero-banner-card">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <h1 class="hero-title">Hasil Evaluasi & Rekapitulasi KPI Sekolah</h1>
                    <p class="hero-subtitle mb-0">
                        Rekapitulasi komprehensif 5 pilar indikator KPI dan klasifikasi 4 tingkat level karir pendidik MI Al-Husna.
                    </p>
                </div>
                <div>
                    <a href="<?= base_url('/laporan/export-csv') ?>" class="btn-hero-action">
                        <i class="bi bi-file-earmark-excel me-1"></i> Unduh Rekap Excel / CSV
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recap Matrix Card -->
<div class="card-noor">
    <div class="table-responsive">
        <table class="table table-noor align-middle">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Pendidik</th>
                    <th>Posisi</th>
                    <th>Pilar 1 (25%)</th>
                    <th>Pilar 2 (25%)</th>
                    <th>Pilar 3 (20%)</th>
                    <th>Pilar 4 (15%)</th>
                    <th>Pilar 5 (15%)</th>
                    <th>Total Skor</th>
                    <th>Tingkatan Level</th>
                    <th class="text-center">Cetak Rapor</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rekapData as $idx => $item): ?>
                    <?php
                    $g = $item['guru'];
                    $p = $item['penilaian'];
                    ?>
                    <tr>
                        <td><?= $idx + 1 ?></td>
                        <td>
                            <div class="fw-bold text-dark"><?= esc($g['nama_guru']) ?></div>
                            <small class="text-muted">NIP: <?= esc($g['nip_nik'] ?: '-') ?></small>
                        </td>
                        <td><span class="badge bg-light text-dark border"><?= esc($g['posisi']) ?></span></td>
                        <td><?= $p ? $p['skor_pilar_1'] : '0.00' ?>%</td>
                        <td><?= $p ? $p['skor_pilar_2'] : '0.00' ?>%</td>
                        <td><?= $p ? $p['skor_pilar_3'] : '0.00' ?>%</td>
                        <td><?= $p ? $p['skor_pilar_4'] : '0.00' ?>%</td>
                        <td><?= $p ? $p['skor_pilar_5'] : '0.00' ?>%</td>
                        <td><span class="fw-extrabold text-success fs-6"><?= $p ? $p['nilai_akhir_total'] : '0.00' ?></span></td>
                        <td>
                            <?php if (in_array($g['role'] ?? '', ['admin', 'admin_tu']) || ($g['posisi'] ?? '') === 'Admin TU'): ?>
                                <span class="badge bg-secondary-subtle text-secondary border">-</span>
                            <?php else: ?>
                                <span class="badge badge-level badge-<?= strtolower($p['predikat_level'] ?? $g['tingkatan_level']) ?>">
                                    <?= $p['predikat_level'] ?? $g['tingkatan_level'] ?>
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <a href="<?= base_url('/laporan/rapor-guru/' . $g['id']) ?>" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                <i class="bi bi-printer me-1"></i> Rapor
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
