<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="mb-4">
    <h4 class="fw-bold text-success mb-1"><i class="bi bi-list-check me-2"></i>Matriks 4 Pilar & Indikator Instrument KPI</h4>
    <p class="text-muted mb-0">Total bobot akumulasi 100% (4 Pilar x 25.00%) dengan rincian butir indikator penilaian 360°.</p>
</div>

<!-- Pilar Tabs -->
<div class="row g-3 mb-4">
    <?php foreach ($kategoris as $kat): ?>
        <div class="col-md-3 col-sm-6">
            <a href="<?= base_url('/indikator?kategori_id=' . $kat['id']) ?>" class="text-decoration-none">
                <div class="card card-custom p-3 text-center <?= ($selectedKategori && $selectedKategori['id'] == $kat['id']) ? 'border border-2 border-success bg-success-subtle' : '' ?>">
                    <span class="badge bg-success text-white fw-bold mx-auto mb-2 px-3 py-1"><?= number_format((float)$kat['bobot_persen'], 2) ?>%</span>
                    <h6 class="fw-bold text-dark mb-1" style="font-size: 0.85rem;"><?= esc($kat['kode_kategori']) ?></h6>
                    <small class="text-muted d-block text-truncate" style="font-size: 0.75rem;"><?= esc($kat['nama_kategori']) ?></small>
                </div>
            </a>
        </div>
    <?php endforeach; ?>
</div>

<?php if ($selectedKategori): ?>
    <div class="card card-custom p-4">
        <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
            <div>
                <span class="badge bg-success mb-1">Bobot: <?= $selectedKategori['bobot_persen'] ?>%</span>
                <h4 class="fw-bold text-dark mb-1"><?= esc($selectedKategori['nama_kategori']) ?></h4>
                <p class="text-muted mb-0" style="font-size: 0.9rem;"><?= esc($selectedKategori['deskripsi']) ?></p>
            </div>
            <div>
                <span class="badge bg-light text-dark border fs-6 px-3 py-2">Total: <?= count($indikators) ?> Indikator</span>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width: 80px;">Kode</th>
                        <th>Sub-Aspek Evaluasi</th>
                        <th>Pertanyaan / Indikator Penilaian</th>
                        <th>Tipe Jawaban</th>
                        <th>Target Standar MI Al-Husna</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($indikators as $ind): ?>
                        <tr>
                            <td><span class="badge bg-secondary fw-bold"><?= esc($ind['kode_indikator']) ?></span></td>
                            <td class="fw-bold text-success"><?= esc($ind['sub_aspek']) ?></td>
                            <td><?= esc($ind['pertanyaan_indikator']) ?></td>
                            <td><span class="badge bg-light text-dark border"><?= esc($ind['tipe_jawaban']) ?></span></td>
                            <td><span class="badge bg-info-subtle text-info-emphasis border border-info-subtle"><?= esc($ind['target_standar'] ?: '-') ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>
<?= $this->endSection() ?>
