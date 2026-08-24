<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h4 class="fw-bold text-success mb-1">
            <i class="bi bi-calendar-check-fill me-2"></i>Presensi Harian & Log Kegiatan KBM Guru
        </h4>
        <p class="text-muted mb-0">Fitur presensi real-time harian dan log kegiatan KBM untuk perhitungan Pilar 5 KPI.</p>
    </div>
    <?php if (in_array($role, ['admin_tu', 'waka', 'kepsek'])): ?>
        <div>
            <button class="btn btn-success rounded-pill fw-bold px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#addPresensiModal">
                <i class="bi bi-plus-circle me-2"></i> Catat Presensi Manual
            </button>
        </div>
    <?php endif; ?>
</div>

<!-- Real-Time Digital Attendance Card Widget for Teachers -->
<?php if ($guruId): ?>
    <div class="card card-custom border-0 shadow-sm mb-4 overflow-hidden" style="background: linear-gradient(135deg, #0f5132 0%, #198754 100%); color: white;">
        <div class="card-body p-4">
            <div class="row align-items-center g-4">
                <div class="col-lg-5 text-center text-lg-start border-end border-white border-opacity-25 pe-lg-4">
                    <span class="badge bg-warning text-dark fw-bold px-3 py-2 rounded-pill mb-2">
                        <i class="bi bi-clock-history me-1"></i> PRESENSI REAL-TIME
                    </span>
                    <div id="liveClockDisplay" class="display-5 fw-extrabold text-white my-1" style="letter-spacing: 2px; font-family: 'Courier New', monospace;">
                        00:00:00 WIB
                    </div>
                    <div id="liveDateDisplay" class="fs-6 text-white-50 fw-semibold">
                        <?= date('l, d F Y') ?>
                    </div>
                </div>

                <div class="col-lg-7">
                    <?php if ($todayAbsen): ?>
                        <div class="bg-white p-3 rounded-4 shadow-sm text-dark">
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <div class="rounded-circle bg-success text-white p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="bi bi-check-lg fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0 text-success">Anda Sudah Melakukan Presensi Hari Ini</h6>
                                    <small class="text-muted fw-bold">Dicatat pada tanggal: <?= date('d M Y', strtotime($todayAbsen['tanggal'])) ?></small>
                                </div>
                            </div>
                            <div class="row g-2 text-center mt-2">
                                <div class="col-6 col-md-3">
                                    <div class="bg-light rounded-3 p-2 border">
                                        <small class="d-block text-muted fw-bold" style="font-size: 0.72rem;">Status</small>
                                        <strong class="text-success"><?= strtoupper($todayAbsen['status_kehadiran']) ?></strong>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="bg-light rounded-3 p-2 border">
                                        <small class="d-block text-muted fw-bold" style="font-size: 0.72rem;">Jam Masuk</small>
                                        <strong class="text-dark"><?= $todayAbsen['jam_masuk'] ?: '-' ?></strong>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="bg-light rounded-3 p-2 border">
                                        <small class="d-block text-muted fw-bold" style="font-size: 0.72rem;">Jenis</small>
                                        <strong class="text-dark"><?= esc($todayAbsen['jenis_kegiatan']) ?></strong>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="bg-light rounded-3 p-2 border">
                                        <small class="d-block text-muted fw-bold" style="font-size: 0.72rem;">Agenda</small>
                                        <strong class="text-dark text-truncate d-block" title="<?= esc($todayAbsen['agenda_kegiatan'] ?: '-') ?>"><?= esc($todayAbsen['agenda_kegiatan'] ?: '-') ?></strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <form action="<?= base_url('/presensi/absen-harian') ?>" method="POST" class="bg-white p-3 rounded-4 shadow-sm text-dark">
                            <?= csrf_field() ?>
                            <input type="hidden" name="tanggal" value="<?= $today ?>">
                            <div class="row g-2 align-items-center">
                                <div class="col-md-5">
                                    <label class="form-label text-dark small mb-1 fw-bold">Status Kehadiran</label>
                                    <select name="status_kehadiran" class="form-select border border-secondary-subtle shadow-none bg-light text-dark fw-bold" required>
                                        <option value="Hadir">✅ Hadir Mengajar</option>
                                        <option value="Ijin">📩 Ijin Resmi</option>
                                        <option value="Sakit">🏥 Sakit</option>
                                        <option value="Pulang lebih awal">⏱️ Pulang Lebih Awal</option>
                                    </select>
                                </div>
                                <div class="col-md-7 mt-3 mt-md-0">
                                    <label class="form-label text-dark small mb-1 fw-bold">Agenda / Catatan KBM Today</label>
                                    <input type="text" name="agenda_kegiatan" class="form-control border border-secondary-subtle shadow-none bg-light text-dark" placeholder="Contoh: Mengajar KBM Matematika Kelas 5B">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-warning text-dark fw-bold w-100 mt-3 rounded-pill py-2 shadow-sm">
                                <i class="bi bi-fingerprint me-2 fs-5"></i> AMBIL PRESENSI HARIAN SEKARANG
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if ($rekap): ?>
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card card-custom p-3 bg-success-subtle border border-success-subtle">
                <small class="text-muted fw-bold">Total Hari Efektif KBM</small>
                <h3 class="fw-bold text-success mb-0"><?= $rekap['total_hari'] ?> Hari</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-custom p-3 bg-primary-subtle border border-primary-subtle">
                <small class="text-muted fw-bold">Jumlah Kehadiran (Hadir)</small>
                <h3 class="fw-bold text-primary mb-0"><?= $rekap['total_hadir'] ?> Hari</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-custom p-3 bg-warning-subtle border border-warning-subtle">
                <small class="text-muted fw-bold">Persentase Kehadiran Total</small>
                <h3 class="fw-bold text-dark mb-0"><?= $rekap['persentase'] ?>%</h3>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Presensi Table Card -->
<div class="card card-custom p-4 shadow-sm border-0">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-bold text-dark mb-0"><i class="bi bi-clock-history me-2 text-success"></i>Riwayat Log Presensi KBM & Rapat</h6>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Nama Pendidik</th>
                    <th>Posisi</th>
                    <th>Jenis Kegiatan</th>
                    <th>Status Kehadiran</th>
                    <th>Jam Masuk - Pulang</th>
                    <th>Agenda / Keterangan</th>
                    <?php if (in_array($role, ['admin_tu', 'waka'])): ?>
                        <th class="text-center">Aksi</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($presensis)): ?>
                    <tr>
                        <td colspan="9" class="text-center py-4 text-muted">Belum ada log presensi yang dicatat.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($presensis as $idx => $pr): ?>
                        <tr>
                            <td><?= $idx + 1 ?></td>
                            <td class="fw-bold"><i class="bi bi-calendar-event me-1 text-muted"></i><?= date('d M Y', strtotime($pr['tanggal'])) ?></td>
                            <td class="fw-bold text-dark"><?= esc($pr['nama_guru']) ?></td>
                            <td><span class="badge bg-light text-dark border"><?= esc($pr['posisi']) ?></span></td>
                            <td><span class="badge bg-info-subtle text-info-emphasis border border-info-subtle"><?= esc($pr['jenis_kegiatan']) ?></span></td>
                            <td>
                                <?php if ($pr['status_kehadiran'] === 'Hadir'): ?>
                                    <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Hadir</span>
                                <?php elseif (in_array($pr['status_kehadiran'], ['Ijin', 'Sakit'])): ?>
                                    <span class="badge bg-warning text-dark"><?= esc($pr['status_kehadiran']) ?></span>
                                <?php else: ?>
                                    <span class="badge bg-danger"><?= esc($pr['status_kehadiran']) ?></span>
                                <?php endif; ?>
                            </td>
                            <td><small><?= $pr['jam_masuk'] ? substr($pr['jam_masuk'], 0, 5) : '-' ?> s/d <?= $pr['jam_pulang'] ? substr($pr['jam_pulang'], 0, 5) : '-' ?></small></td>
                            <td><small class="text-muted"><?= esc($pr['agenda_kegiatan'] ?: $pr['keterangan'] ?: '-') ?></small></td>
                            <?php if (in_array($role, ['admin_tu', 'waka'])): ?>
                                <td class="text-center">
                                    <a href="<?= base_url('/presensi/delete/' . $pr['id']) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus log presensi ini?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Add Presensi -->
<div class="modal fade" id="addPresensiModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title fw-bold"><i class="bi bi-clock me-2"></i>Catat Presensi Guru Manual</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('/presensi/store') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Pilih Pendidik *</label>
                        <select name="guru_id" class="form-select" required>
                            <?php foreach ($gurus as $g): ?>
                                <option value="<?= $g['id'] ?>"><?= esc($g['nama_guru']) ?> (<?= esc($g['posisi']) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tanggal Presensi *</label>
                        <input type="date" name="tanggal" class="form-control" value="<?= date('Y-m-d') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Jenis Kegiatan *</label>
                        <select name="jenis_kegiatan" class="form-select" required>
                            <option value="Absen Harian">Absen Harian KBM</option>
                            <option value="kbm_harian">KBM Harian Kelas</option>
                            <option value="rapat_dinas">Rapat Dinas Guru</option>
                            <option value="upacara_resmi">Upacara / Kegiatan Resmi Sekolah</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Status Kehadiran *</label>
                        <select name="status_kehadiran" class="form-select" required>
                            <option value="Hadir">Hadir</option>
                            <option value="Ijin">Ijin</option>
                            <option value="Sakit">Sakit</option>
                            <option value="Tidak Hadir">Tidak Hadir (Alpa)</option>
                            <option value="Pulang lebih awal">Pulang Lebih Awal</option>
                        </select>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-bold">Jam Masuk</label>
                            <input type="time" name="jam_masuk" class="form-control" value="07:00">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold">Jam Pulang</label>
                            <input type="time" name="jam_pulang" class="form-control" value="14:00">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Agenda / Keterangan</label>
                        <input type="text" name="agenda_kegiatan" class="form-control" placeholder="Contoh: Mengajar KBM Kelas 4A / Rapat Dinas Evaluasi">
                    </div>
                </div>
                <div class="modal-footer border-top-0 px-4 pb-4">
                    <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold">Simpan Presensi</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Real-Time Clock Script
    function updateClock() {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        
        const clockElement = document.getElementById('liveClockDisplay');
        if (clockElement) {
            clockElement.textContent = `${hours}:${minutes}:${seconds} WIB`;
        }

        const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        
        const dayName = days[now.getDay()];
        const dayDate = now.getDate();
        const monthName = months[now.getMonth()];
        const year = now.getFullYear();

        const dateElement = document.getElementById('liveDateDisplay');
        if (dateElement) {
            dateElement.textContent = `${dayName}, ${dayDate} ${monthName} ${year}`;
        }
    }

    updateClock();
    setInterval(updateClock, 1000);
</script>
<?= $this->endSection() ?>
