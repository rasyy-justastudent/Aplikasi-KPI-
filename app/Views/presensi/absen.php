

<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card card-custom p-4 mb-4">
            <h4 class="fw-bold mb-3"><i class="bi bi-calendar-check me-2"></i>Absen Harian Guru</h4>
            <form action="<?= base_url('/presensi/absen') ?>" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="guru_id" value="<?= esc($guruId) ?>">
                <div class="mb-3">
                    <label class="form-label fw-bold">Tanggal</label>
                    <input type="date" name="tanggal" class="form-control" value="<?= esc($today) ?>" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Status Kehadiran</label>
                    <select name="status_kehadiran" class="form-select" required>
                        <option value="" disabled <?= empty($existing) ? 'selected' : '' ?>>-- Pilih Status --</option>
                        <option value="hadir" <?= (isset($existing['status_kehadiran']) && $existing['status_kehadiran'] === 'hadir') ? 'selected' : '' ?>>Hadir</option>
                        <option value="izin" <?= (isset($existing['status_kehadiran']) && $existing['status_kehadiran'] === 'izin') ? 'selected' : '' ?>>Izin</option>
                        <option value="alpha" <?= (isset($existing['status_kehadiran']) && $existing['status_kehadiran'] === 'alpha') ? 'selected' : '' ?>>Alpha (Tidak Hadir)</option>
                    </select>
                </div>
                <div class="text-end">
                    <button type="submit" class="btn btn-success btn-lg rounded-pill"><i class="bi bi-save me-2"></i> Simpan Absen</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
