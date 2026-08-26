<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-lg-11">
        <!-- Hero Header Banner (Noor Academy Style) -->
        <div class="row g-4 mb-4">
            <div class="col-12">
                <div class="hero-banner-card" style="background: #eef7f3; color: var(--noor-text-main); border: 1px solid var(--noor-mint-border); box-shadow: none;">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                        <div>
                            <h1 class="hero-title" style="color: var(--noor-emerald-dark);">Form Evaluasi Variasi Metode Penilaian Pembelajaran</h1>
                            <p class="hero-subtitle mb-0 text-muted">
                                Pengisian instrumen metode penilaian (Integrasi Pilar 1 Pedagogik) untuk Periode: <strong>TP <?= esc($activePeriode['tahun_pelajaran']) ?> — <?= esc($activePeriode['semester']) ?></strong>
                            </p>
                        </div>
                        <div>
                            <a href="<?= base_url('/dashboard') ?>" class="btn-noor-secondary">
                                <i class="bi bi-arrow-left"></i> Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php
        $savedJenis = json_decode($existing['metode_jenis'] ?? '[]', true) ?? [];
        ?>

        <form action="<?= base_url('/evaluasi-metode/save') ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <div class="card-noor mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                    <div class="d-flex align-items-center gap-2">
                        <span class="num-step-box">1</span>
                        <h5 class="fw-bold text-dark mb-0">Form Evaluasi Variasi Metode Penilaian Pembelajaran (Pilar 1: Pedagogik)</h5>
                    </div>
                    <span class="badge-mint-pill">Pilar 1 Pedagogik</span>
                </div>

                <div class="d-flex flex-column gap-4">
                    <!-- 1. Pilih Jenis Metode Penilaian -->
                    <div>
                        <label class="form-label fw-bold text-dark fs-6">1. Pilih jenis metode penilaian yang telah Anda rancang dan terapkan di kelas selama satu semester ini:</label>
                        <div class="row g-3 mt-1">
                            <div class="col-md-6">
                                <label class="form-option-card <?= in_array('formatif', $savedJenis) ? 'selected' : '' ?>">
                                    <input type="checkbox" name="metode_jenis[]" value="formatif" <?= in_array('formatif', $savedJenis) ? 'checked' : '' ?> class="d-none">
                                    <div class="form-option-header">
                                        <div class="radio-indicator"></div>
                                        <div class="form-option-title">Penilaian Formatif</div>
                                    </div>
                                    <div class="form-option-sub">Kuis singkat, observasi, tanya jawab lisan, peer assessment</div>
                                </label>
                            </div>

                            <div class="col-md-6">
                                <label class="form-option-card <?= in_array('sumatif', $savedJenis) ? 'selected' : '' ?>">
                                    <input type="checkbox" name="metode_jenis[]" value="sumatif" <?= in_array('sumatif', $savedJenis) ? 'checked' : '' ?> class="d-none">
                                    <div class="form-option-header">
                                        <div class="radio-indicator"></div>
                                        <div class="form-option-title">Penilaian Sumatif</div>
                                    </div>
                                    <div class="form-option-sub">Ulangan harian, UTS, UAS, ujian praktik akhir</div>
                                </label>
                            </div>

                            <div class="col-md-6">
                                <label class="form-option-card <?= in_array('otentik', $savedJenis) ? 'selected' : '' ?>">
                                    <input type="checkbox" name="metode_jenis[]" value="otentik" <?= in_array('otentik', $savedJenis) ? 'checked' : '' ?> class="d-none">
                                    <div class="form-option-header">
                                        <div class="radio-indicator"></div>
                                        <div class="form-option-title">Penilaian Otentik</div>
                                    </div>
                                    <div class="form-option-sub">Proyek, portofolio, unjuk kerja, pemecahan masalah nyata</div>
                                </label>
                            </div>

                            <div class="col-md-6">
                                <label class="form-option-card <?= in_array('lainnya', $savedJenis) ? 'selected' : '' ?>">
                                    <input type="checkbox" name="metode_jenis[]" value="lainnya" <?= in_array('lainnya', $savedJenis) ? 'checked' : '' ?> class="d-none">
                                    <div class="form-option-header">
                                        <div class="radio-indicator"></div>
                                        <div class="form-option-title">Lainnya</div>
                                    </div>
                                    <div class="form-option-sub">Metode penilaian inovatif lainnya</div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Persentase Proporsi -->
                    <div>
                        <label class="form-label fw-bold text-dark fs-6">2. Tuliskan perkiraan persentase proporsi penggunaan metode penilaian yang Anda terapkan:</label>
                        <input type="text" name="metode_proporsi" class="form-control form-control-lg rounded-3" placeholder="Contoh: Formatif 50%, Sumatif 25%, Otentik 25%" value="<?= esc($existing['metode_proporsi'] ?? '') ?>">
                        <small class="text-muted">Contoh penulisan: Formatif 50%, Sumatif 25%, Otentik 25%</small>
                    </div>

                    <!-- 3. Contoh Spesifik Penyesuaian -->
                    <div>
                        <label class="form-label fw-bold text-dark fs-6">3. Berdasarkan variasi metode penilaian yang telah Anda gunakan, berikan satu contoh spesifik penyesuaian:</label>
                        <div class="position-relative">
                            <textarea name="metode_contoh_penyesuaian" class="form-control rounded-3 p-3" rows="4" placeholder="Tuliskan penjelasan spesifik penyesuaian metode penilaian Anda..."><?= esc($existing['metode_contoh_penyesuaian'] ?? '') ?></textarea>
                            <span class="position-absolute bottom-0 end-0 p-2 text-muted" style="font-size: 0.75rem;">0 / 500</span>
                        </div>
                    </div>

                    <!-- 4. Lampiran Instrumen / Rubrik Modul Ajar -->
                    <div>
                        <label class="form-label fw-bold text-dark fs-6">4. Apakah Anda telah melampirkan instrumen atau rubrik untuk minimal 3 metode penilaian yang berbeda tersebut di dalam dokumen RPP/Modul Ajar?</label>
                        <div class="row g-3 mt-1">
                            <div class="col-md-4">
                                <label class="form-option-card <?= ($existing['metode_rubrik_status'] ?? '') === 'lengkap' ? 'selected' : '' ?>">
                                    <input type="radio" name="metode_rubrik_status" value="lengkap" <?= ($existing['metode_rubrik_status'] ?? '') === 'lengkap' ? 'checked' : '' ?> class="d-none">
                                    <div class="form-option-header">
                                        <div class="radio-indicator"></div>
                                        <div class="form-option-title">Ya (Lengkap)</div>
                                    </div>
                                    <div class="form-option-sub">Ketiga jenis penilaian tercantum lengkap beserta rubriknya.</div>
                                </label>
                            </div>
                            <div class="col-md-4">
                                <label class="form-option-card <?= ($existing['metode_rubrik_status'] ?? '') === 'sebagian' ? 'selected' : '' ?>">
                                    <input type="radio" name="metode_rubrik_status" value="sebagian" <?= ($existing['metode_rubrik_status'] ?? '') === 'sebagian' ? 'checked' : '' ?> class="d-none">
                                    <div class="form-option-header">
                                        <div class="radio-indicator"></div>
                                        <div class="form-option-title">Sebagian</div>
                                    </div>
                                    <div class="form-option-sub">Baru 1-2 jenis penilaian yang memiliki instrumen dan rubrik tertulis.</div>
                                </label>
                            </div>
                            <div class="col-md-4">
                                <label class="form-option-card <?= ($existing['metode_rubrik_status'] ?? '') === 'belum' ? 'selected' : '' ?>">
                                    <input type="radio" name="metode_rubrik_status" value="belum" <?= ($existing['metode_rubrik_status'] ?? '') === 'belum' ? 'checked' : '' ?> class="d-none">
                                    <div class="form-option-header">
                                        <div class="radio-indicator"></div>
                                        <div class="form-option-title">Tidak / Belum</div>
                                    </div>
                                    <div class="form-option-sub">Penilaian dilakukan namun instrumen belum didokumentasikan secara resmi.</div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- 5. Unggah File PDF -->
                    <div>
                        <label class="form-label fw-bold text-dark fs-6">5. Unggah 1 file PDF gabungan contoh instrumen dan rubrik penilaian (Maks. 100 MB):</label>
                        <div class="file-upload-drag-box" onclick="document.getElementById('pdfMetodeInput').click();">
                            <div class="file-upload-icon-circle"><i class="bi bi-cloud-arrow-up"></i></div>
                            <div class="fw-bold text-dark mb-1">Tarik dan lepas file PDF di sini</div>
                            <div class="text-muted" style="font-size: 0.82rem;">atau <span class="text-success fw-bold text-decoration-underline">Pilih File PDF</span> dari perangkat Anda</div>
                            <input type="file" id="pdfMetodeInput" name="metode_file_pdf" class="d-none" accept=".pdf">
                        </div>
                        <?php if (!empty($existing['metode_file_pdf'])): ?>
                            <div class="mt-2 text-start">
                                <a href="<?= base_url($existing['metode_file_pdf']) ?>" target="_blank" class="btn btn-sm btn-outline-success rounded-pill">
                                    <i class="bi bi-file-earmark-pdf me-1"></i> Lihat Berkas PDF yang Telah Diunggah
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="text-end mt-4 pt-3 border-top">
                    <button type="submit" class="btn-noor-primary px-5 py-2.5 fs-6">
                        <i class="bi bi-save me-2"></i> Simpan Form Evaluasi Metode Penilaian
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
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
