<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card card-custom p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold text-success mb-0"><i class="bi bi-pencil-square me-2"></i>Edit Data Pendidik: <?= esc($guru['nama_guru']) ?></h4>
                <a href="<?= base_url('/guru') ?>" class="btn btn-outline-secondary btn-sm rounded-pill"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
            </div>

            <form action="<?= base_url('/guru/update/' . $guru['id']) ?>" method="POST">
                <?= csrf_field() ?>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Nama Lengkap Guru *</label>
                        <input type="text" name="nama_guru" class="form-control" required value="<?= esc($guru['nama_guru']) ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">NIP / NIK</label>
                        <input type="text" name="nip_nik" class="form-control" value="<?= esc($guru['nip_nik']) ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Username Login *</label>
                        <input type="text" name="username" class="form-control" value="<?= esc($guru['username']) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Email *</label>
                        <input type="email" name="email" class="form-control" value="<?= esc($guru['email']) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Reset Password (Kosongkan jika tidak diubah)</label>
                        <input type="password" name="password" class="form-control" placeholder="Password baru">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Role Hak Akses Sistem *</label>
                        <select name="role" class="form-select" required>
                            <option value="admin" <?= $guru['role'] === 'admin' ? 'selected' : '' ?>>Admin System (Full Access)</option>
                            <option value="admin_tu" <?= $guru['role'] === 'admin_tu' ? 'selected' : '' ?>>Admin TU (Tata Usaha)</option>
                            <option value="guru" <?= $guru['role'] === 'guru' ? 'selected' : '' ?>>Guru (Pendidik)</option>
                            <option value="koordinator" <?= $guru['role'] === 'koordinator' ? 'selected' : '' ?>>Koordinator Bidang</option>
                            <option value="waka" <?= $guru['role'] === 'waka' ? 'selected' : '' ?>>Wakil Kepala Sekolah</option>
                            <option value="kepsek" <?= $guru['role'] === 'kepsek' ? 'selected' : '' ?>>Kepala Sekolah</option>
                        </select>
                    </div>

                    <hr class="my-4">

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Posisi / Jabatan *</label>
                        <select name="posisi" class="form-select" required>
                            <?php foreach ($posisiOptions as $opt): ?>
                                <option value="<?= esc($opt) ?>" <?= $guru['posisi'] === $opt ? 'selected' : '' ?>><?= esc($opt) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Bidang Studi</label>
                        <input type="text" name="bidang_studi" class="form-control" value="<?= esc($guru['bidang_studi']) ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Tingkatan Level Karir</label>
                        <select name="tingkatan_level" class="form-select">
                            <option value="ECT" <?= $guru['tingkatan_level'] === 'ECT' ? 'selected' : '' ?>>Level 1: ECT (&lt; 70.0)</option>
                            <option value="DEV" <?= $guru['tingkatan_level'] === 'DEV' ? 'selected' : '' ?>>Level 2: Developing (70.0 - 79.9)</option>
                            <option value="PROF" <?= $guru['tingkatan_level'] === 'PROF' ? 'selected' : '' ?>>Level 3: Proficient (80.0 - 89.9)</option>
                            <option value="EXP" <?= $guru['tingkatan_level'] === 'EXP' ? 'selected' : '' ?>>Level 4: Expert (90.0 - 100.0)</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold">Target UKG (%)</label>
                        <input type="number" step="0.1" name="target_ukg_persen" class="form-control" value="<?= $guru['target_ukg_persen'] ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Target Jam Pelatihan (JP)</label>
                        <input type="number" name="target_jam_pelatihan" class="form-control" value="<?= $guru['target_jam_pelatihan'] ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Target B.Inggris (%)</label>
                        <input type="number" step="0.1" name="target_english_persen" class="form-control" value="<?= $guru['target_english_persen'] ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Target Digital (%)</label>
                        <input type="number" step="0.1" name="target_digital_persen" class="form-control" value="<?= $guru['target_digital_persen'] ?>">
                    </div>

                    <div class="col-12 mt-4 text-end">
                        <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold"><i class="bi bi-save me-2"></i>Perbarui Data Guru</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
