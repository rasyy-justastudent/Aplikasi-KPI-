<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<!-- Header Banner -->
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="hero-banner-card">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <span class="badge-hero-pill mb-2"><i class="bi bi-eye-fill me-1"></i> Supervisi KBM Pilar 1</span>
                    <h1 class="hero-title">Instrumen Observasi Kelas & Supervisi Pembelajaran</h1>
                    <p class="hero-subtitle mb-0">
                        Penilaian langsung oleh Observer/Koordinator Bidang (20 Indikator A-G) untuk pendidik MI Al-Husna.
                    </p>
                </div>
                <div>
                    <a href="<?= base_url('/observasi') ?>" class="btn-hero-action">
                        <i class="bi bi-arrow-left"></i> Kembali ke Daftar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Target Teacher & Observer Card -->
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="card-noor">
            <div class="row g-3 align-items-center">
                <div class="col-md-6">
                    <div class="d-flex align-items-center gap-3">
                        <div class="user-avatar-circle" style="width: 52px; height: 52px; font-size: 1.3rem; background: var(--noor-emerald);">
                            <?= strtoupper(substr($assignment['target_nama'], 0, 1)) ?>
                        </div>
                        <div>
                            <span class="text-muted" style="font-size: 0.78rem;">GURU TARGET OBSERVASI</span>
                            <h5 class="fw-bold text-dark mb-0"><?= esc($assignment['target_nama']) ?></h5>
                            <small class="text-muted">NIP: <?= esc($assignment['target_nip'] ?: '-') ?> | Posisi: <?= esc($assignment['target_posisi'] ?: 'Pendidik') ?></small>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 border-start-md">
                    <div class="d-flex justify-content-md-end align-items-center gap-4">
                        <div>
                            <span class="text-muted" style="font-size: 0.78rem;">OBSERVER / PENILAI</span>
                            <div class="fw-bold text-success"><i class="bi bi-person-badge me-1"></i><?= esc($assignment['observer_nama']) ?></div>
                            <small class="text-muted">Periode: <?= esc($activePeriode['tahun_pelajaran']) ?> (<?= esc($activePeriode['semester']) ?>)</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Form 20 Indicators Form -->
<form action="<?= base_url('/observasi/save') ?>" method="POST">
    <?= csrf_field() ?>
    <input type="hidden" name="assignment_id" value="<?= $assignment['id'] ?>">
    <input type="hidden" name="target_guru_id" value="<?= $assignment['target_guru_id'] ?>">
    <input type="hidden" name="periode_id" value="<?= $assignment['periode_id'] ?>">

    <?php $indNum = 1; ?>
    <?php foreach ($groupedIndikators as $subAspek => $indikators): ?>
        <div class="card-noor mb-4">
            <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
                <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1 rounded-pill fw-bold" style="font-size: 0.85rem;">
                    <?= esc($subAspek) ?>
                </span>
            </div>

            <div class="d-flex flex-column gap-4">
                <?php foreach ($indikators as $ind): ?>
                    <?php 
                    $indId = $ind['id'];
                    $currentVal = $existingScores[$indId] ?? 4; // default 4 if unassessed
                    ?>
                    <div class="p-3 rounded-4 bg-light-subtle border">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-start gap-2 mb-3">
                            <div>
                                <span class="badge bg-secondary-subtle text-dark border me-2 fw-bold"><?= esc($ind['kode_indikator']) ?></span>
                                <span class="fw-bold text-dark fs-6"><?= esc($ind['pertanyaan_indikator']) ?></span>
                            </div>
                            <?php if ($ind['target_standar']): ?>
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill" style="font-size: 0.75rem;">
                                    Target: <?= esc($ind['target_standar']) ?>
                                </span>
                            <?php endif; ?>
                        </div>

                        <!-- 5 Scale Radio Rating Options -->
                        <div class="row g-2">
                            <?php 
                            $scales = [
                                1 => ['label' => '1 - Kurang', 'sub' => 'Tidak Sesuai'],
                                2 => ['label' => '2 - Cukup', 'sub' => 'Perlu Bimbingan'],
                                3 => ['label' => '3 - Baik', 'sub' => 'Memenuhi Standar'],
                                4 => ['label' => '4 - Sangat Baik', 'sub' => 'Melebihi Standar'],
                                5 => ['label' => '5 - Istimewa', 'sub' => 'Role Model / Pakar']
                            ];
                            ?>
                            <?php foreach ($scales as $val => $s): ?>
                                <div class="col-6 col-md">
                                    <label class="form-option-card <?= $currentVal == $val ? 'selected' : '' ?> text-center py-2 px-1 h-100 m-0">
                                        <input type="radio" name="scores[<?= $indId ?>]" value="<?= $val ?>" <?= $currentVal == $val ? 'checked' : '' ?> class="d-none" required>
                                        <div class="fw-bold text-dark" style="font-size: 0.82rem;"><?= $s['label'] ?></div>
                                        <small class="text-muted d-block" style="font-size: 0.7rem;"><?= $s['sub'] ?></small>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php $indNum++; ?>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>

    <!-- Observer Feedback Comments -->
    <div class="card-noor mb-4">
        <h5 class="fw-bold text-dark mb-2"><i class="bi bi-chat-left-text-fill me-2 text-success"></i>Catatan & Feedback Observer Supervisi KBM</h5>
        <p class="text-muted mb-3" style="font-size: 0.85rem;">Berikan apresiasi kinerja, evaluasi kualitatif, serta saran pengembangan untuk pembelajaran di kelas guru target.</p>
        <textarea name="observer_comments" class="form-control rounded-3" rows="4" placeholder="Tuliskan catatan hasil supervisi kelas, kelebihan KBM, serta area pengembangan..."><?= esc($existingHeader['observer_comments'] ?? '') ?></textarea>
    </div>

    <!-- Submit Button Card -->
    <div class="card-noor mb-5">
        <div class="d-flex justify-content-between align-items-center">
            <a href="<?= base_url('/observasi') ?>" class="btn btn-light rounded-pill px-4">Batal</a>
            <button type="submit" class="btn btn-success rounded-pill px-5 py-2 fw-bold fs-6">
                <i class="bi bi-check-circle-fill me-2"></i> Simpan Penilaian Observasi KBM
            </button>
        </div>
    </div>
</form>

<script>
    document.querySelectorAll('.form-option-card input[type="radio"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const name = this.name;
            document.querySelectorAll(`input[name="${name}"]`).forEach(r => {
                r.closest('.form-option-card').classList.remove('selected');
            });
            if (this.checked) {
                this.closest('.form-option-card').classList.add('selected');
            }
        });
    });
</script>

<?= $this->endSection() ?>
