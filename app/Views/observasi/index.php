<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<!-- Hero Header Banner -->
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="hero-banner-card">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <h1 class="hero-title"><i class="bi bi-eye-fill me-2"></i>Observasi Kelas & Penugasan Observer</h1>
                    <p class="hero-subtitle mb-0">
                        Penilaian supervisi KBM langsung oleh Observer / Koordinator Bidang berdasarkan 20 Indikator Pilar 1 (Perencanaan s.d. Diskusi).
                    </p>
                </div>
                <div>
                    <?php if (in_array($role, ['admin', 'admin_tu', 'kepsek', 'waka'])): ?>
                        <button class="btn btn-hero-action" data-bs-toggle="modal" data-bs-target="#assignObserverModal">
                            <i class="bi bi-person-plus-fill me-1"></i> Penugasan Observer Baru
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Section for Observer Teacher: My Observer Tasks -->
<?php if (session()->get('guru_id')): ?>
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card-noor">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0 text-success"><i class="bi bi-clipboard2-check-fill me-2"></i>Tugas Observasi Kelas Saya</h5>
                    <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1 rounded-pill">
                        <?= count($myObserverTasks) ?> Penugasan Active
                    </span>
                </div>

                <div class="table-responsive">
                    <table class="table table-noor align-middle">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 50px;">No</th>
                                <th>Guru Target Observasi</th>
                                <th>Posisi / Bidang Studi</th>
                                <th class="text-center">Status Observasi</th>
                                <th class="text-center">Aksi Penilaian</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($myObserverTasks)): ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        <i class="bi bi-info-circle fs-4 d-block mb-1"></i>
                                        Belum ada tugas observasi kelas yang ditugaskan kepada Anda pada periode ini.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($myObserverTasks as $idx => $t): ?>
                                    <tr>
                                        <td class="text-center text-muted fw-semibold"><?= $idx + 1 ?></td>
                                        <td>
                                            <div class="fw-bold text-dark"><?= esc($t['target_nama']) ?></div>
                                            <small class="text-muted">NIP: <?= esc($t['target_nip'] ?: '-') ?></small>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border"><?= esc($t['target_posisi'] ?: 'Guru Bidang') ?></span>
                                            <?php if ($t['target_bidang']): ?>
                                                <small class="text-muted d-block"><?= esc($t['target_bidang']) ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($t['status'] === 'completed'): ?>
                                                <span class="badge bg-success"><i class="bi bi-check-all me-1"></i> Selesai Dinilai</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning text-dark"><i class="bi bi-clock me-1"></i> Belum Dinilai</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <a href="<?= base_url('/observasi/input/' . $t['id']) ?>" class="btn btn-sm btn-success rounded-pill px-3 fw-semibold">
                                                <i class="bi bi-pencil-square me-1"></i> <?= $t['status'] === 'completed' ? 'Edit Observasi' : 'Mulai Observasi (20 Indikator)' ?>
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

<!-- Section for Kepsek / Admin: All Observer Assignments -->
<?php if (in_array($role, ['admin', 'admin_tu', 'kepsek', 'waka'])): ?>
    <div class="row g-4">
        <div class="col-12">
            <div class="card-noor">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-card-checklist me-2"></i>Daftar Penugasan Observer (Kepsek / Manajemen)</h5>
                        <p class="text-muted mb-0" style="font-size: 0.82rem;">Monitoring guru yang ditugaskan sebagai observer supervisi KBM dan guru sasaran observasi.</p>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-noor align-middle">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 50px;">No</th>
                                <th>Guru Observer (Penilai)</th>
                                <th>Guru Target (Di-Observasi)</th>
                                <th class="text-center">Status</th>
                                <th>Catatan Kepsek</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($allAssignments)): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        Belum ada penugasan observer yang dibuat untuk periode ini. Klik tombol <strong>Penugasan Observer Baru</strong> di atas untuk membuat.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($allAssignments as $idx => $a): ?>
                                    <tr>
                                        <td class="text-center text-muted fw-semibold"><?= $idx + 1 ?></td>
                                        <td>
                                            <div class="fw-bold text-dark"><i class="bi bi-person-badge text-success me-1"></i><?= esc($a['observer_nama']) ?></div>
                                            <small class="text-muted"><?= esc($a['observer_posisi'] ?: 'Pendidik') ?></small>
                                        </td>
                                        <td>
                                            <div class="fw-bold text-dark"><i class="bi bi-person-check text-primary me-1"></i><?= esc($a['target_nama']) ?></div>
                                            <small class="text-muted"><?= esc($a['target_posisi'] ?: 'Pendidik') ?></small>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($a['status'] === 'completed'): ?>
                                                <span class="badge bg-success"><i class="bi bi-check-circle-fill me-1"></i> Completed</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split me-1"></i> Pending</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <small class="text-muted"><?= esc($a['catatan_kepsek'] ?: '-') ?></small>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm">
                                                <a href="<?= base_url('/observasi/input/' . $a['id']) ?>" class="btn btn-outline-success" title="Isi / Lihat Penilaian">
                                                    <i class="bi bi-clipboard-data"></i> Isi
                                                </a>
                                                <a href="<?= base_url('/observasi/delete/' . $a['id']) ?>" class="btn btn-outline-danger" onclick="return confirm('Hapus penugasan observer ini?')" title="Hapus Penugasan">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </div>
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

    <!-- Modal Modal Assign Observer -->
    <div class="modal fade" id="assignObserverModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content rounded-4 border-0">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title fw-bold"><i class="bi bi-person-plus-fill me-2"></i>Penugasan Observer Supervisi KBM</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?= base_url('/observasi/assign') ?>" method="POST">
                    <?= csrf_field() ?>
                    <input type="hidden" name="periode_id" value="<?= $activePeriode ? $activePeriode['id'] : 0 ?>">
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Pilih Guru Observer (Penilai Supervisi) *</label>
                            <select name="observer_guru_id" class="form-select" required>
                                <option value="">-- Pilih Guru Observer --</option>
                                <?php foreach ($gurus as $g): ?>
                                    <option value="<?= $g['id'] ?>">
                                        <?= esc($g['nama_guru']) ?> (<?= esc($g['posisi'] ?: 'Pendidik') ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <small class="text-muted">Guru yang ditugaskan langsung oleh Kepsek untuk melakukan supervisi KBM kelas.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Pilih Guru Target (Di-Observasi) *</label>
                            <select name="target_guru_id" class="form-select" required>
                                <option value="">-- Pilih Guru Target --</option>
                                <?php foreach ($gurus as $g): ?>
                                    <option value="<?= $g['id'] ?>">
                                        <?= esc($g['nama_guru']) ?> (<?= esc($g['posisi'] ?: 'Pendidik') ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Catatan / Instruksi Kepsek</label>
                            <textarea name="catatan_kepsek" class="form-control" rows="3" placeholder="Contoh: Fokus pada variasi metode KBM dan manajemen kelas..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 px-4 pb-4">
                        <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold">Simpan Penugasan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>
