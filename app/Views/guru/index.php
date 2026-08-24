<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h4 class="fw-bold text-success mb-1"><i class="bi bi-people-fill me-2"></i>Master Roster 48 Pendidik MI Al-Husna</h4>
        <p class="text-muted mb-0">Kelola data profil guru, akun sistem, dan target indikator standar kinerja.</p>
    </div>
    <div>
        <a href="<?= base_url('/guru/create') ?>" class="btn btn-success rounded-pill fw-bold px-4">
            <i class="bi bi-person-plus-fill me-2"></i> Tambah Pendidik Baru
        </a>
    </div>
</div>

<!-- Filters & Search Card -->
<div class="card card-custom p-3 mb-4">
    <form action="<?= base_url('/guru') ?>" method="GET" class="row g-3 align-items-center">
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" name="q" class="form-control bg-light border-start-0" placeholder="Cari nama guru, NIP, atau bidang studi..." value="<?= esc($keyword) ?>">
            </div>
        </div>
        <div class="col-md-4">
            <select name="level" class="form-select bg-light">
                <option value="">-- Semua Tingkatan Level --</option>
                <option value="ECT" <?= $levelFilter === 'ECT' ? 'selected' : '' ?>>Level 1: ECT (&lt; 70.0)</option>
                <option value="DEV" <?= $levelFilter === 'DEV' ? 'selected' : '' ?>>Level 2: Developing (70.0 - 79.9)</option>
                <option value="PROF" <?= $levelFilter === 'PROF' ? 'selected' : '' ?>>Level 3: Proficient (80.0 - 89.9)</option>
                <option value="EXP" <?= $levelFilter === 'EXP' ? 'selected' : '' ?>>Level 4: Expert (90.0 - 100.0)</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100 rounded-pill"><i class="bi bi-filter me-1"></i> Filter</button>
        </div>
    </form>
</div>

<!-- Roster Table Card -->
<div class="card card-custom p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Nama Pendidik & NIP</th>
                    <th>Posisi / Jabatan</th>
                    <th>Bidang Studi</th>
                    <th>Tingkatan Level</th>
                    <th>Target Standar</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($gurus)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">Data guru tidak ditemukan.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($gurus as $idx => $g): ?>
                        <tr>
                            <td><?= $idx + 1 ?></td>
                            <td>
                                <div class="fw-bold text-dark"><?= esc($g['nama_guru']) ?></div>
                                <small class="text-muted"><i class="bi bi-card-heading me-1"></i><?= esc($g['nip_nik'] ?: '-') ?> | User: @<?= esc($g['username'] ?: '-') ?></small>
                            </td>
                            <td><span class="badge bg-light text-dark border"><?= esc($g['posisi'] ?: 'Belum Diisi') ?></span></td>
                            <td><?= esc($g['bidang_studi'] ?: '-') ?></td>
                            <td>
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
                            <td>
                                <small class="d-block text-muted">UKG: <strong><?= $g['target_ukg_persen'] ?>%</strong> | Pelatihan: <strong><?= $g['target_jam_pelatihan'] ?> JP</strong></small>
                                <small class="d-block text-muted">English: <strong><?= $g['target_english_persen'] ?>%</strong> | Digital: <strong><?= $g['target_digital_persen'] ?>%</strong></small>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <a href="<?= base_url('/guru/edit/' . $g['id']) ?>" class="btn btn-outline-primary" title="Edit Data">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <a href="<?= base_url('/laporan/rapor-guru/' . $g['id']) ?>" class="btn btn-outline-success" title="Lihat Rapor KPI">
                                        <i class="bi bi-file-earmark-pdf"></i>
                                    </a>
                                    <a href="<?= base_url('/guru/delete/' . $g['id']) ?>" class="btn btn-outline-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data pendidik ini?')" title="Hapus">
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
<?= $this->endSection() ?>
