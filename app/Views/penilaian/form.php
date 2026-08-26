<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<!-- 1. Hero Header Banner (Image 3 Style) -->
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="hero-banner-card" style="background: #eef7f3; color: var(--noor-text-main); border: 1px solid var(--noor-mint-border); box-shadow: none;">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <?php if (in_array(session()->get('role'), ['guru', 'admin', 'admin_tu'])): ?>
                        <h1 class="hero-title" style="color: var(--noor-emerald-dark);">Penilaian Rekan Sejawat 360°</h1>
                        <p class="hero-subtitle mb-0 text-muted">
                            Berikan penilaian objektif bagi pendidik: <strong><?= esc($guruTarget['nama_guru']) ?></strong> pada 15 butir indikator KPI Kompetensi Sosial & Penilaian Rekan Sejawat 360° di bawah ini.
                        </p>
                    <?php else: ?>
                        <h1 class="hero-title" style="color: var(--noor-emerald-dark);">Penilaian KPI Pendidik 360° (Kepala Sekolah / Management)</h1>
                        <p class="hero-subtitle mb-0 text-muted">
                            Berikan penilaian objektif bagi pendidik: <strong><?= esc($guruTarget['nama_guru']) ?></strong> pada instrumen Kompetensi Profesional, Kepribadian, dan Sosial 360° di bawah ini.
                        </p>
                    <?php endif; ?>
                </div>
                <div>
                    <a href="<?= base_url('/penilaian') ?>" class="btn-noor-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali ke Daftar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<form action="<?= base_url('/penilaian/save') ?>" method="POST" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <input type="hidden" name="guru_id" value="<?= $guruTarget['id'] ?>">
    <input type="hidden" name="periode_id" value="<?= $activePeriode['id'] ?>">
    <input type="hidden" name="jenis_penilaian" value="<?= $jenisPenilaian ?>">

    <div class="row g-4">
        <!-- Left Sidebar: Progres Pengisian (Image 3 Left Column) -->
        <div class="col-lg-3">
            <div class="step-wizard-card sticky-top" style="top: 100px;">
                <h6 class="fw-bold mb-3" style="color: var(--noor-text-main);">Progres Pengisian</h6>
                <?php foreach ($kategoris as $kStepIdx => $kStep): ?>
                    <div class="step-wizard-item active mb-2">
                        <div class="step-badge-num"><?= $kStepIdx + 1 ?></div>
                        <div>
                            <div class="step-label-num">Bagian <?= $kStepIdx + 1 ?></div>
                            <div class="step-title"><?= esc($kStep['nama_kategori']) ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>

                <div class="mt-4 pt-3 border-top">
                    <div class="d-flex justify-content-between align-items-center mb-1" style="font-size: 0.8rem;">
                        <span class="text-muted">Status Form</span>
                        <span class="fw-bold text-success">Siap Diisi</span>
                    </div>
                    <div class="progress-noor-track">
                        <div class="progress-noor-bar bg-noor-emerald" style="width: 100%;"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Main Content: Form Questions (Image 3 Right Column) -->
        <div class="col-lg-9">
            <!-- Attendance Integration Summary Card (Hanya untuk Leadership) -->
            <?php if (!in_array(session()->get('role'), ['guru', 'admin'])): ?>
                <div class="card-noor mb-4" style="background: #f0fdf4; border-color: #a7f3d0;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-bold text-success mb-1" style="font-size: 0.95rem;">
                                <i class="bi bi-clock-history me-2"></i>Kehadiran & Ketepatan Waktu (Integrasi Pilar 5 Presensi)
                            </div>
                            <small class="text-muted">Total Kehadiran: <strong><?= $rekapPresensi['total_hadir'] ?></strong> / <strong><?= $rekapPresensi['total_hari'] ?> Hari Efektif</strong> (<?= $rekapPresensi['persentase'] ?>%)</small>
                        </div>
                        <div>
                            <span class="badge-mint-pill fs-6 px-3 py-2">Skor Presensi: <?= $skorPresensi['skor_skala_5'] ?> / 5.0</span>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Pillars Questions Accordion / List -->
            <?php foreach ($kategoris as $katIdx => $kat): ?>
                <div class="card-noor mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                        <div class="d-flex align-items-center gap-2">
                            <span class="num-step-box"><?= $katIdx + 1 ?></span>
                            <h5 class="fw-bold text-dark mb-0"><?= esc($kat['nama_kategori']) ?></h5>
                        </div>
                        <div>
                            <span class="badge-mint-pill">Bobot: <?= $kat['bobot_persen'] ?>%</span>
                        </div>
                    </div>

                    <p class="text-muted" style="font-size: 0.85rem; mb-3"><?= esc($kat['deskripsi']) ?></p>

                    <div class="d-flex flex-column gap-4">
                        <?php
                        $inds = $indikatorsPerKategori[$kat['id']] ?? [];
                        foreach ($inds as $iIdx => $ind):
                            $savedScore = isset($existingDetails[$ind['id']]) ? $existingDetails[$ind['id']]['skor_nilai'] : 4;
                        ?>
                            <div class="p-3 rounded-4 border bg-light-subtle">
                                <div class="fw-semibold text-dark mb-1" style="font-size: 0.92rem;">
                                    <span class="text-success fw-bold me-1">[<?= esc($ind['sub_aspek']) ?>]</span> <?= esc($ind['pertanyaan_indikator']) ?>
                                </div>
                                
                                <div class="row g-2 mt-2">
                                    <?php 
                                    $scaleLabels = [
                                        1 => ['title' => 'Kurang', 'sub' => '< 70% Standar'],
                                        2 => ['title' => 'Cukup', 'sub' => '70% - 79% Standar'],
                                        3 => ['title' => 'Baik', 'sub' => '80% - 89% Standar'],
                                        4 => ['title' => 'Sangat Baik', 'sub' => '90% - 95% Standar'],
                                        5 => ['title' => 'Ihsan / Pakar', 'sub' => '> 95% Standar Master']
                                    ];
                                    for ($val = 1; $val <= 5; $val++): 
                                    ?>
                                        <div class="col">
                                            <label class="form-option-card <?= $savedScore == $val ? 'selected' : '' ?>">
                                                <input type="radio" name="scores[<?= $ind['id'] ?>]" value="<?= $val ?>" <?= $savedScore == $val ? 'checked' : '' ?> required class="d-none">
                                                <div class="form-option-header">
                                                    <div class="radio-indicator"></div>
                                                    <div class="form-option-title" style="font-size: 0.82rem;"><?= $scaleLabels[$val]['title'] ?></div>
                                                </div>
                                                <div class="form-option-sub" style="font-size: 0.7rem;"><?= $val ?> Point (<?= $scaleLabels[$val]['sub'] ?>)</div>
                                            </label>
                                        </div>
                                    <?php endfor; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>

            <!-- Feedback & Reflection Text Areas (Hanya untuk Kepsek / Leadership) -->
            <?php if (!in_array(session()->get('role'), ['guru', 'admin'])): ?>
                <div class="card-noor mb-4">
                    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-chat-square-text text-success me-2"></i>Catatan Umpan Balik & Refleksi</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-secondary" style="font-size: 0.85rem;">Catatan Observer / Reviewer</label>
                            <textarea name="observer_comments" class="form-control rounded-3" rows="4" placeholder="Tuliskan apresiasi, masukan, dan saran perbaikan untuk pendidik..."><?= esc($existing['observer_comments'] ?? '') ?></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-secondary" style="font-size: 0.85rem;">Catatan Refleksi Diri Guru</label>
                            <textarea name="teacher_reflection" class="form-control rounded-3" rows="4" placeholder="Tuliskan komitmen perbaikan diri dan rencana pengembangan kompetensi..."><?= esc($existing['teacher_reflection'] ?? '') ?></textarea>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Action Footer (Image 3 Style Footer) -->
            <div class="d-flex justify-content-between align-items-center mb-5 p-3 card-noor bg-white">
                <a href="<?= base_url('/penilaian') ?>" class="text-muted fw-bold text-decoration-none">
                    Simpan Draf
                </a>
                <button type="submit" class="btn-noor-primary px-5 py-2.5 fs-6">
                    Simpan & Kalkulasi Nilai KPI Total <i class="bi bi-arrow-right"></i>
                </button>
            </div>
        </div>
    </div>
</form>

<script>
    // Radio Option Box Auto-Selector
    document.querySelectorAll('.form-option-card').forEach(card => {
        card.addEventListener('click', function() {
            const input = this.querySelector('input');
            if (input.type === 'radio') {
                const name = input.name;
                document.querySelectorAll(`input[name="${name}"]`).forEach(r => {
                    r.closest('.form-option-card')?.classList.remove('selected');
                });
                input.checked = true;
                this.classList.add('selected');
            } else if (input.type === 'checkbox') {
                input.checked = !input.checked;
                this.classList.toggle('selected', input.checked);
            }
        });
    });
</script>

<?= $this->endSection() ?>
