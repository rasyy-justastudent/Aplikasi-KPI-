<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h4 class="fw-bold text-success mb-1"><i class="bi bi-calendar3 me-2"></i>Kelola Periode / Tahun Pelajaran KPI</h4>
        <p class="text-muted mb-0">Pengaturan jadwal periode penilaian (Draft, Open, Review, Closed).</p>
    </div>
    <div>
        <button class="btn btn-success rounded-pill fw-bold px-4" data-bs-toggle="modal" data-bs-target="#addPeriodeModal">
            <i class="bi bi-plus-circle me-2"></i> Tambah Periode Baru
        </button>
    </div>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i><?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card card-custom p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th class="text-center" style="width: 50px;">No</th>
                    <th>Tahun Pelajaran</th>
                    <th class="text-center">Semester</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Selesai</th>
                    <th class="text-center">Status Periode</th>
                    <th class="text-center">Aksi Toggle Status & Kelola</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($periodes as $idx => $p): ?>
                    <tr>
                        <td class="text-center text-muted fw-semibold"><?= $idx + 1 ?></td>
                        <td class="fw-bold text-dark"><?= esc($p['tahun_pelajaran']) ?></td>
                        <td class="text-center"><span class="badge bg-light text-dark border"><?= esc($p['semester']) ?></span></td>
                        <td><i class="bi bi-calendar-event text-muted me-1"></i><?= date('d M Y', strtotime($p['tgl_mulai'])) ?></td>
                        <td><i class="bi bi-calendar-event text-muted me-1"></i><?= date('d M Y', strtotime($p['tgl_selesai'])) ?></td>
                        <td class="text-center">
                            <?php if ($p['status'] === 'open'): ?>
                                <span class="badge bg-success px-3 py-2"><i class="bi bi-play-circle-fill me-1"></i> Active / Open</span>
                            <?php elseif ($p['status'] === 'review'): ?>
                                <span class="badge bg-warning text-dark px-3 py-2"><i class="bi bi-eye-fill me-1"></i> Review Mode</span>
                            <?php elseif ($p['status'] === 'closed'): ?>
                                <span class="badge bg-secondary px-3 py-2"><i class="bi bi-lock-fill me-1"></i> Closed</span>
                            <?php else: ?>
                                <span class="badge bg-light text-dark border px-3 py-2"><i class="bi bi-pencil-fill me-1"></i> Draft</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <div class="d-inline-flex gap-1">
                                <div class="btn-group btn-group-sm">
                                    <a href="<?= base_url('/periode/status/' . $p['id'] . '/open') ?>" class="btn btn-outline-success <?= $p['status'] === 'open' ? 'active' : '' ?>">Open</a>
                                    <a href="<?= base_url('/periode/status/' . $p['id'] . '/review') ?>" class="btn btn-outline-warning <?= $p['status'] === 'review' ? 'active' : '' ?>">Review</a>
                                    <a href="<?= base_url('/periode/status/' . $p['id'] . '/closed') ?>" class="btn btn-outline-secondary <?= $p['status'] === 'closed' ? 'active' : '' ?>">Close</a>
                                </div>
                                <a href="<?= base_url('/periode/delete/' . $p['id']) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus periode ini?')" title="Hapus Periode">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Add Periode -->
<div class="modal fade" id="addPeriodeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title fw-bold"><i class="bi bi-calendar-plus me-2"></i>Tambah Periode KPI Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('/periode/store') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tahun Pelajaran *</label>
                        <input type="text" name="tahun_pelajaran" class="form-control" required placeholder="Contoh: 2026-2027">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Semester *</label>
                        <select name="semester" class="form-select" required>
                            <option value="Ganjil">Ganjil</option>
                            <option value="Genap">Genap</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tanggal Mulai *</label>
                        <input type="date" name="tgl_mulai" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tanggal Selesai *</label>
                        <input type="date" name="tgl_selesai" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Status Awal</label>
                        <select name="status" class="form-select">
                            <option value="draft">Draft</option>
                            <option value="open">Open (Aktifkan langsung)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-top-0 px-4 pb-4">
                    <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold">Simpan Periode</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
