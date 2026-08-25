<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card card-custom p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold text-success mb-0"><i class="bi bi-person-plus-fill me-2"></i>Tambah Data Pendidik Baru</h4>
                <a href="<?= base_url('/guru') ?>" class="btn btn-outline-secondary btn-sm rounded-pill"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
            </div>

            <form action="<?= base_url('/guru/store') ?>" method="POST">
                <?= csrf_field() ?>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Nama Lengkap Guru *</label>
                        <input type="text" name="nama_guru" class="form-control" required placeholder="Contoh: Ahmad Faridhi, S.Pd">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">NIP / NIK</label>
                        <input type="text" name="nip_nik" class="form-control" placeholder="Nomor Induk Pegawai">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Username Akun *</label>
                        <input type="text" name="username" class="form-control" required placeholder="Username login">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Email *</label>
                        <input type="email" name="email" class="form-control" required placeholder="email@mialhusna.sch.id">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Password Login *</label>
                        <input type="password" name="password" class="form-control" required placeholder="Password">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Role Hak Akses Sistem *</label>
                        <select name="role" class="form-select" required>
                            <option value="admin">Admin System (Full Access)</option>
                            <option value="admin_tu">Admin TU (Tata Usaha)</option>
                            <option value="guru" selected>Guru (Pendidik)</option>
                            <option value="koordinator">Koordinator Bidang</option>
                            <option value="waka">Wakil Kepala Sekolah</option>
                            <option value="kepsek">Kepala Sekolah</option>
                        </select>
                    </div>

                    <hr class="my-4">

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Posisi / Jabatan MI Al-Husna *</label>
                        <select name="posisi" class="form-select" required>
                            <?php foreach ($posisiOptions as $opt): ?>
                                <option value="<?= esc($opt) ?>"><?= esc($opt) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Bidang Studi yang Diampu</label>
                        <input type="text" name="bidang_studi" class="form-control" placeholder="Contoh: Bahasa Inggris / Tematik">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Tingkatan Level Saat Ini</label>
                        <select name="tingkatan_level" class="form-select">
                            <option value="ECT">Level 1: Guru Pemula (ECT)</option>
                            <option value="DEV">Level 2: Guru Berkembang (Developing)</option>
                            <option value="PROF">Level 3: Guru Mahir (Proficient)</option>
                            <option value="EXP">Level 4: Guru Ahli (Expert)</option>
                        </select>
                    </div>

                    <!-- Target Standards -->
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Target UKG (%)</label>
                        <input type="number" step="0.1" name="target_ukg_persen" class="form-control" value="85.0">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Target Jam Pelatihan (JP)</label>
                        <input type="number" name="target_jam_pelatihan" class="form-control" value="25">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Target B.Inggris (%)</label>
                        <input type="number" step="0.1" name="target_english_persen" class="form-control" value="40.0">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Target Digital (%)</label>
                        <input type="number" step="0.1" name="target_digital_persen" class="form-control" value="75.0">
                    </div>

                    <div class="col-12 mt-4 text-end">
                        <button type="submit" class="btn btn-success rounded-pill px-5 fw-bold"><i class="bi bi-save me-2"></i>Simpan Data Guru</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
