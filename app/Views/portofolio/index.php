<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<!-- 1. Top Header Section (Image 2 Style) -->
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="hero-banner-card" style="background: #ffffff; border: 1px solid var(--noor-border); box-shadow: var(--noor-shadow-sm); color: var(--noor-text-main);">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <h1 class="hero-title" style="color: var(--noor-text-main);">Program Pengembangan & Portofolio</h1>
                    <p class="hero-subtitle mb-0 text-muted">
                        Rekomendasi pelatihan yang disesuaikan dengan hasil evaluasi kinerja Anda. Tingkatkan kapasitas dan raih predikat Ihsan dalam mendidik.
                    </p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn-noor-secondary">
                        <i class="bi bi-funnel"></i> Filter
                    </button>
                    <?php if ($role === 'guru'): ?>
                        <button class="btn-noor-primary" data-bs-toggle="modal" data-bs-target="#uploadPortofolioModal">
                            <i class="bi bi-upload"></i> Unggah Berkas
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- 3. Bottom Banner Callout (Image 2 Bottom CTA) -->
<div class="row mb-5">
    <div class="col-12">
        <div class="hero-banner-card" style="background: var(--noor-emerald-dark); border-radius: var(--noor-radius-lg);">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="icon-box p-3 rounded-4 bg-white bg-opacity-10 text-white fs-2">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-1 text-white">Punya Usulan Program?</h4>
                        <p class="mb-0 text-white-50" style="font-size: 0.9rem;">
                            Tidak menemukan pelatihan yang sesuai dengan kebutuhan pengembangan Anda? Ajukan proposal pelatihan internal.
                        </p>
                    </div>
                </div>
                <button class="btn btn-light rounded-pill px-4 py-2.5 fw-bold text-dark shadow-sm">
                    Ajukan Program
                </button>
            </div>
        </div>
    </div>
</div>

<!-- 4. File Manager Section (Folders & Upload Monitoring) -->
<?php if ($role !== 'guru'): ?>
    <div class="card-noor mb-4">
        <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
            <div>
                <h5 class="fw-bold text-dark mb-0">
                    <i class="bi bi-people-fill text-success me-2"></i>Status Pengunggahan Portofolio & Sertifikat Guru
                </h5>
                <small class="text-muted">Pantau kelengkapan pengunggahan sertifikat dari seluruh pendidik.</small>
            </div>
            <?php if (!empty($selectedGuruId)): ?>
                <a href="<?= base_url('/portofolio') ?>" class="btn btn-sm btn-outline-secondary rounded-pill">
                    <i class="bi bi-x-circle me-1"></i> Tampilkan Semua Guru
                </a>
            <?php endif; ?>
        </div>

        <div class="table-responsive">
            <table class="table table-noor align-middle">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Pendidik</th>
                        <th>Posisi / Jabatan</th>
                        <th class="text-center">Sertifikat B. Inggris</th>
                        <th class="text-center">Sertifikat Pelatihan</th>
                        <th class="text-center">Total Berkas</th>
                        <th class="text-center">Status Pengunggahan</th>
                        <th class="text-center">Aksi / Filter</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($teacherStatusList)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">Belum ada data guru.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($teacherStatusList as $idx => $ts): ?>
                            <tr class="<?= ((string)$selectedGuruId === (string)$ts['guru']['id']) ? 'table-warning fw-bold' : '' ?>">
                                <td><?= $idx + 1 ?></td>
                                <td class="fw-bold text-dark"><?= esc($ts['guru']['nama_guru']) ?></td>
                                <td><span class="badge bg-light text-dark border"><?= esc($ts['guru']['posisi'] ?: 'Guru Pengajar') ?></span></td>
                                <td class="text-center">
                                    <?php if ($ts['count_inggris'] > 0): ?>
                                        <span class="badge bg-warning text-dark"><i class="bi bi-check-circle me-1"></i><?= $ts['count_inggris'] ?> Berkas</span>
                                    <?php else: ?>
                                        <span class="badge bg-light text-muted border">Belum Ada</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($ts['count_pelatihan'] > 0): ?>
                                        <span class="badge bg-info text-white"><i class="bi bi-check-circle me-1"></i><?= $ts['count_pelatihan'] ?> Berkas (<?= $ts['total_jp'] ?> JP)</span>
                                    <?php else: ?>
                                        <span class="badge bg-light text-muted border">Belum Ada</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center fw-bold"><?= $ts['count_total'] ?> Berkas</td>
                                <td class="text-center">
                                    <?php if ($ts['has_uploaded']): ?>
                                        <span class="badge bg-success py-2 px-3 rounded-pill"><i class="bi bi-check-all me-1 fs-6"></i> Sudah Mengunggah (<?= $ts['count_total'] ?> Berkas)</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger py-2 px-3 rounded-pill"><i class="bi bi-exclamation-circle me-1"></i> Belum Mengunggah Berkas</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <a href="<?= base_url('/portofolio?guru_id=' . $ts['guru']['id']) ?>" class="btn btn-sm <?= ((string)$selectedGuruId === (string)$ts['guru']['id']) ? 'btn-warning fw-bold' : 'btn-outline-success' ?> rounded-pill">
                                        <i class="bi bi-folder2-open me-1"></i> Lihat Berkas
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

<!-- Grid Folders (File Manager View) -->
<div class="card-noor mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold text-dark mb-0">
            <i class="bi bi-folder2 me-2 text-warning"></i>Folder File Sertifikat Guru (<?= $countTotal ?? 0 ?> Total Berkas)
        </h5>
        <?php if (!empty($currentType)): ?>
            <a href="<?= base_url('/portofolio') ?>" class="btn btn-sm btn-outline-secondary rounded-pill">Tampilkan Semua Folder</a>
        <?php endif; ?>
    </div>

    <div class="row g-3 mb-4">
        <!-- Folder 1: Sertifikat Bahasa Inggris -->
        <div class="col-md-6">
            <a href="<?= base_url('/portofolio?type=sertifikat_bahasa_inggris') ?>" class="text-decoration-none">
                <div class="p-3 rounded-4 border <?= ($currentType ?? '') === 'sertifikat_bahasa_inggris' ? 'bg-warning bg-opacity-10 border-warning' : 'bg-light' ?>" style="transition: all 0.2s ease;">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <i class="bi bi-folder-fill text-warning fs-1"></i>
                        <span class="badge bg-warning text-dark rounded-pill"><?= $countInggris ?? 0 ?> Berkas</span>
                    </div>
                    <h6 class="fw-bold text-dark mb-1">Sertifikat Bahasa Inggris</h6>
                    <small class="text-muted d-block">Folder Lampiran Bahasa Inggris</small>
                </div>
            </a>
        </div>

        <!-- Folder 2: Sertifikat Pelatihan & Workshop -->
        <div class="col-md-6">
            <a href="<?= base_url('/portofolio?type=sertifikat_pelatihan') ?>" class="text-decoration-none">
                <div class="p-3 rounded-4 border <?= ($currentType ?? '') === 'sertifikat_pelatihan' ? 'bg-info bg-opacity-10 border-info' : 'bg-light' ?>" style="transition: all 0.2s ease;">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <i class="bi bi-folder-fill text-info fs-1"></i>
                        <span class="badge bg-info text-white rounded-pill"><?= $countPelatihan ?? 0 ?> Berkas</span>
                    </div>
                    <h6 class="fw-bold text-dark mb-1">Sertifikat Pelatihan</h6>
                    <small class="text-muted d-block">Pelatihan & Workshop (Target 25 JP)</small>
                </div>
            </a>
        </div>
    </div>

    <!-- Table of Files -->
    <div class="table-responsive">
        <table class="table table-noor align-middle">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Pendidik</th>
                    <th>Folder Dokumen</th>
                    <th>Judul Dokumen</th>
                    <th>JP</th>
                    <th>Status Validasi</th>
                    <th>Catatan Validator</th>
                    <th class="text-center">Aksi / Validasi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($portofolios)): ?>
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">Belum ada berkas portofolio terunggah dalam folder ini.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($portofolios as $idx => $po): ?>
                        <tr>
                            <td><?= $idx + 1 ?></td>
                            <td class="fw-bold text-dark"><?= esc($po['nama_guru']) ?></td>
                            <td>
                                <span class="badge bg-light text-dark border">
                                    <?= str_replace('_', ' ', strtoupper($po['jenis_dokumen'])) ?>
                                </span>
                            </td>
                            <td class="fw-bold text-success"><?= esc($po['judul_dokumen']) ?></td>
                            <td><span class="badge bg-secondary"><?= $po['jumlah_jam_jp'] ?> JP</span></td>
                            <td>
                                <?php if ($po['status_validasi'] === 'valid'): ?>
                                    <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Valid</span>
                                <?php elseif ($po['status_validasi'] === 'invalid'): ?>
                                    <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i> Invalid</span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split me-1"></i> Pending</span>
                                <?php endif; ?>
                            </td>
                            <td><small class="text-muted"><?= esc($po['catatan_validator'] ?: '-') ?></small></td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <a href="<?= base_url($po['file_url']) ?>" target="_blank" class="btn btn-outline-primary rounded-pill px-3">
                                        <i class="bi bi-eye me-1"></i> Lihat PDF
                                    </a>
                                    <?php if (in_array($role, ['admin', 'admin_tu', 'kepsek'])): ?>
                                        <button class="btn btn-outline-success rounded-pill px-3 ms-1" data-bs-toggle="modal" data-bs-target="#valModal_<?= $po['id'] ?>">
                                            Validasi
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>

                        <!-- Modal Validate per Document -->
                        <?php if (in_array($role, ['admin', 'admin_tu', 'kepsek'])): ?>
                            <div class="modal fade" id="valModal_<?= $po['id'] ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content rounded-4 border-0">
                                        <div class="modal-header bg-success text-white">
                                            <h5 class="modal-title fw-bold">Validasi Portofolio: <?= esc($po['judul_dokumen']) ?></h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="<?= base_url('/portofolio/validate/' . $po['id']) ?>" method="POST">
                                            <?= csrf_field() ?>
                                            <div class="modal-body p-4">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Status Validasi</label>
                                                    <select name="status_validasi" class="form-select" required>
                                                        <option value="valid" <?= $po['status_validasi'] === 'valid' ? 'selected' : '' ?>>Valid (Disetujui)</option>
                                                        <option value="invalid" <?= $po['status_validasi'] === 'invalid' ? 'selected' : '' ?>>Invalid (Ditolak / Belum Memenuhi)</option>
                                                        <option value="pending" <?= $po['status_validasi'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Catatan Validator</label>
                                                    <textarea name="catatan_validator" class="form-control" rows="3" placeholder="Catatan masukan untuk guru..."><?= esc($po['catatan_validator'] ?? '') ?></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-top-0 px-4 pb-4">
                                                <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold">Simpan Validasi</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php if ($role === 'guru'): ?>
    <!-- Modal Upload Portofolio -->
    <div class="modal fade" id="uploadPortofolioModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content rounded-4 border-0">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title fw-bold"><i class="bi bi-upload me-2"></i>Unggah Berkas Portofolio Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?= base_url('/portofolio/store') ?>" method="POST" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Pilih Folder Target Dokumen *</label>
                            <select name="jenis_dokumen" class="form-select" required>
                                <option value="sertifikat_pelatihan" <?= ($currentType ?? '') === 'sertifikat_pelatihan' ? 'selected' : '' ?>>📁 Folder Sertifikat Pelatihan / Workshop (Target 25 JP)</option>
                                <option value="sertifikat_bahasa_inggris" <?= ($currentType ?? '') === 'sertifikat_bahasa_inggris' ? 'selected' : '' ?>>📁 Folder Sertifikat Kompetensi Bahasa Inggris</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Judul / Nama Dokumen *</label>
                            <input type="text" name="judul_dokumen" class="form-control" placeholder="Contoh: Sertifikat Pelatihan Kurikulum Merdeka & AI" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Jumlah Jam Pelatihan (JP)</label>
                            <input type="number" name="jumlah_jam_jp" class="form-control" value="8" placeholder="Contoh: 32">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">File Dokumen PDF / Gambar *</label>
                            <input type="file" name="file_dokumen" class="form-control" accept=".pdf,.png,.jpg,.jpeg">
                            <small class="text-muted">Format yang didukung: PDF, PNG, JPG (Maks. 10MB)</small>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 px-4 pb-4">
                        <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold">Unggah Berkas</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>
