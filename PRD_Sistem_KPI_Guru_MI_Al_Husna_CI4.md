# PRODUCT REQUIREMENT DOCUMENT (PRD)
## SISTEM INFORMASI EVALUASI KINERJA (KPI) GURU & PENETAPAN TINGKATAN LEVEL
### MI AL-HUSNA — TAHUN PELAJARAN 2026-2027
**Platform:** CodeIgniter 4 (CI4) | PHP 8.1+ | MySQL 8.0 | Bootstrap 5 / Tailwind CSS

---

## 1. PENDAHULUAN & LATAR BELAKANG
Dokumen ini merinci spesifikasi teknis dan fungsional pengembangan **Sistem Informasi Manajemen KPI (Key Performance Indicator) Guru MI Al-Husna** untuk Tahun Pelajaran 2026-2027. Sistem ini mengintegrasikan seluruh instrumen evaluasi kinerja berbasis Google Forms/Docs yang digunakan sekolah menjadi satu aplikasi terpusat berbasis **CodeIgniter 4 (CI4)**.

Sistem ini berfungsi untuk:
1. Menilai kinerja guru secara objektif, transparan, dan akuntabel melalui pendekatan penilaian multi-sumber (360° Review: Diri Sendiri, Rekan Sejawat, Observer/Koordinator Bidang, Wakil Kepala Sekolah, dan Kepala Sekolah).
2. Mengklasifikasikan guru ke dalam **4 Tingkatan Level Karir Pendidik** (*Early Career Teacher, Developing, Proficient, Expert*).
3. Mengotomatisasi rekapitulasi presensi harian KBM, absensi rapat dinas, observasi supervisi kelas, dan unggahan portofolio sertifikat.
4. Menghasilkan **Rapor KPI Individual Guru (PDF)** dan **Laporan Rekapitulasi Sekolah (Excel)** untuk evaluasi pembinaan dan penentuan kebijakan sekolah.

---

## 2. PENGGUNA SISTEM (USER ROLES & PERMISSIONS)

| Role | Deskripsi & Hak Akses |
| :--- | :--- |
| **Guru (Pendidik)** | Mengisi evaluasi diri (*Self-Assessment*), mengunggah portofolio (Sertifikat Pelatihan, Sertifikat Bahasa Inggris, PDF Rubrik RPP 3 Metode Penilaian), melihat riwayat presensi harian & rapat, serta mengunduh Rapor KPI pribadi setelah disahkan. |
| **Rekan Sejawat (Peer Reviewer)** | Mengisi form penilaian *KPI Kompetensi Sosial Guru* (Hubungan Antar Personal, Komunikasi, Kontribusi) untuk rekan guru satu kelas / satu tingkat. |
| **Koordinator Bidang / Observer** | Melakukan observasi kelas (*Class Observation Form* - English/Umum) saat KBM berlangsung dan memberikan skor 1-5 beserta *Observer Comments*. |
| **Wakil Kepala Sekolah (Waka)** | Menilai aspek *Kompetensi Profesional, Kepribadian, Kematangan Emosi, Kedisiplinan KBM & Rapat*, serta memvalidasi kelengkapan berkas modul ajar. |
| **Kepala Sekolah** | Memberikan penilaian final, menyetujui (*approval*) hasil perhitungan nilai akhir KPI, menandatangani Rapor KPI digital, dan menetapkan Tingkatan Level Guru. |
| **Admin Tata Usaha (TU)** | Mengelola master data (48 Guru MI Al-Husna, 5 Jabatan/Posisi, Tahun Pelajaran/Periode), menginput presensi harian KBM, dan mengelola absensi rapat guru. |

---

## 3. STRUKTUR 4 TINGKATAN LEVEL GURU MI AL-HUSNA

Berdasarkan dokumen resmi MI Al-Husna TP 2026-2027, nilai akhir KPI guru akan menentukan tingkatan level karir pendidik sebagai berikut:

```
+---------------------------------------------------------------------------------------+
|                       TINGKATAN LEVEL GURU MI AL-HUSNA                                |
+---------------------------------------------------------------------------------------+
| Tingkat 1: Guru Pemula (Early Career Teacher - ECT)  [Skor Akhir < 70.0]              |
| -> Pemahaman dasar metode & kurikulum; Manajemen kelas terbatas;                     |
| -> Butuh bimbingan intensif mentor; Kesulitan merencanakan/menilai pembelajaran.      |
+---------------------------------------------------------------------------------------+
| Tingkat 2: Guru Berkembang (Developing)              [Skor Akhir 70.0 - 79.9]         |
| -> Fondasi kuat kurikulum; Manajemen kelas mulai berkembang;                         |
| -> Butuh dukungan moderat tugas kompleks; Perencanaan mampu tapi butuh perbaikan.     |
+---------------------------------------------------------------------------------------+
| Tingkat 3: Guru Mahir (Proficient)                   [Skor Akhir 80.0 - 89.9]         |
| -> Kompetensi kuat; Manajemen kelas efektif & kondusif;                               |
| -> Mandiri dengan sedikit dukungan; Perencanaan matang, efektif, dan terstruktur.     |
+---------------------------------------------------------------------------------------+
| Tingkat 4: Guru Ahli (Expert)                        [Skor Akhir 90.0 - 100.0]        |
| -> Pakar teori pengajaran; Manajemen kelas luar biasa;                                |
| -> Inovatif tren pendidikan terbaru; Berperan sebagai Mentor bagi guru lain.          |
+---------------------------------------------------------------------------------------+
```

---

## 4. MATRIKS 5 PILAR EVALUASI & INSTRUMEN GOOGLE FORM TERPADU

Setiap pilar memiliki bobot spesifik yang diakumulasikan ke dalam **Skor Total 100%**:

```
Total Skor KPI = (Pilar1 x 25%) + (Pilar2 x 25%) + (Pilar3 x 20%) + (Pilar4 x 15%) + (Pilar5 x 15%)
```

### PILAR 1: Observasi Kelas & Supervisi Pembelajaran (Bobot: 25%)
*Sumber Instrumen: MI AL-HUSNA FORM OBSERVASI KELAS (UMUM) & ENGLISH CLASS OBSERVATION*
* **Skala Penilaian:** 1 (Sangat Kurang), 2 (Kurang Baik), 3 (Cukup Baik), 4 (Baik/Konsisten), 5 (Sangat Baik/Inovatif), NA (*Not Applicable*).
* **Aspek yang Dinilai (Total 20 Indikator):**
  1. **A. Perencanaan Pembelajaran (3 Butir):** Kesesuaian standar kurikulum, bahan ajar menarik, variasi metode pembelajaran.
  2. **B. Pelaksanaan Pembelajaran (3 Butir):** Variasi media belajar, bahasa mudah dipahami, keaktifan siswa.
  3. **C. Penguasaan Materi (4 Butir):** Akurat tanpa miskonsepsi, kontekstual kehidupan sehari-hari, respon pertanyaan tepat, dukungan verbal/visual saat siswa kesulitan.
  4. **D. Interaksi dengan Siswa (3 Butir):** Hubungan ramah/positif, motivasi, respon terhadap pertanyaan siswa.
  5. **E. Pengelolaan Kelas (3 Butir):** Suasana kondusif, kontrol perilaku positif, penegakan aturan kelas.
  6. **F. Evaluasi dan Tindak Lanjut (2 Butir):** Berbagai alat penilaian (tes, tugas, lisan), tindak lanjut evaluasi.
  7. **G. Pengelolaan Diskusi & Kolaborasi (2 Butir):** Diskusi terarah & kesempatan bicara, instruksi jelas didukung visual.
* **Fitur Input Catatan:** *Observer Feedback/Comments* & *Teacher Reflection Comment*.

---

### PILAR 2: KPI Kompetensi Profesional Guru (Bobot: 25%)
*Sumber Instrumen: Form KPI Kompetensi Professional Guru*
* **Skala Penilaian:** 1 (Sangat Kurang) s/d 5 (Sangat Baik / Selalu)
* **Aspek & Target Standar MI Al-Husna:**
  1. **Penguasaan Materi (5 Butir):** Pemahaman mendalam bidang studi, penguraian konsep kompleks, kontekstualisasi, bebas miskonsepsi, dan **Skor UKG Internal $ge 85%$**.
  2. **Pengembangan Diri (5 Butir + Upload Sertifikat):** Inisiatif seminar/workshop, implementasi hasil pelatihan, karya inovatif/PTK/artikel ilmiah, berbagi *best practices*, dan **Pencapaian Minimal 25 Jam Pelatihan/Tahun**.
  3. **Pengembangan Bahasa Inggris (5 Butir + Upload Sertifikat):** Komunikasi bahasa Inggris harian di sekolah, **Proporsi Minimal 40% Instruksi Kelas Berbahasa Inggris**, *fluency/pronunciation*, motivasi siswa berbahasa Inggris, dan ekosistem bilingual.
  4. **Integrasi Teknologi (5 Butir):** Pemanfaatan Google Workspace/LMS, media ajar digital interaktif, **$ge 75%$ Sesi Pembelajaran Terintegrasi Digital**, pemanfaatan AI untuk edukasi, dan kemandirian perangkat teknologi kelas.

---

### PILAR 3: Kompetensi Kepribadian, Kedisiplinan & Kematangan Emosi (Bobot: 20%)
*Sumber Instrumen: Form Kompetensi Kepribadian Guru & Form Kematangan Emosi Guru*
* **Aspek & Target Standar MI Al-Husna:**
  1. **Etika, Moral, dan Keteladanan (Target: 0 Kasus Pelanggaran Etika):** Kejujuran akademik & administratif, integritas & privasi data, penampilan profesional, tanggung jawab moral, dan *Role Model*.
  2. **Kedisiplinan KBM (Target: Kehadiran $ge 90%$, Keterlambatan Maks. 1x/Bulan):** Persentase hadir harian, ketepatan mulai KBM, ketepatan mengakhiri KBM, prosedur izin terencana, dan tanggung jawab kelas pengganti (Inval).
  3. **Partisipasi Rapat & Kegiatan (Target: Kehadiran $ge 90%$):** Kehadiran rapat dinas, ketepatan waktu hadir rapat, fokus & perhatian (tidak sibuk gawai), kontribusi aktif diskusi, dan komitmen tindak lanjut hasil rapat.
  4. **Kematangan Emosi & Kedewasaan (Target: Rata-rata Skor $ge 4.0/5.0$):** Pengendalian diri di kelas (sabar, anti hukuman fisik/verbal tidak pantas), stabilitas di bawah tekanan beban kerja/perubahan jadwal, keterbukaan menerima kritik atasan/rekan tanpa reaktif, penyelesaian konflik kepala dingin, dan pemisahan urusan pribadi dari profesional.

---

### PILAR 4: KPI Kompetensi Sosial & Penilaian Rekan Sejawat 360° (Bobot: 15%)
*Sumber Instrumen: Form KPI Kompetensi Sosial Guru*
* **Penilai:** Rekan kerja sekelas, Rekan kerja, Wakil Kepala Sekolah, Kepala Sekolah.
* **Aspek yang Dinilai:**
  1. **Hubungan Antar Personal (5 Butir):** Proaktif menawarkan bantuan, kualitas etika komunikasi kerja, keterbukaan memberi/menerima feedback, partisipasi forum internal, dan menjaga keharmonisan kerja.
  2. **Komunikasi dengan Orang Tua (5 Butir):** Responsif keluhan orang tua, kejelasan & empati perkembangan siswa, mendorong kehadiran acara sekolah, solusi kendala siswa, dan update rutin proses belajar.
  3. **Kontribusi Non-Pengajaran (5 Butir):** Kepanitiaan acara sekolah, dedikasi pembina ekskul / wali kelas / pengawas khusus, ide inovatif proyek sekolah, kerelaan waktu non-akademik, dan pemenuhan target tahunan.

---

### PILAR 5: Form Evaluasi Variasi Metode Penilaian & Presensi Kerja (Bobot: 15%)
*Sumber Instrumen: Form Evaluasi dan Penilaian Guru & Form Absensi Kerja*
* **Variasi Metode Penilaian (Formatif, Sumatif, Otentik):**
  * Input proporsi persentase (contoh: Formatif 50%, Sumatif 25%, Otentik 25%).
  * Deskripsi contoh penyesuaian tingkat kesulitan materi.
  * Status kelengkapan instrumen/rubrik tertulis di Modul Ajar (Lengkap 3 metode / Sebagian 1-2 / Belum ada).
  * Upload 1 berkas PDF gabungan instrumen & rubrik penilaian (Maks. 100 MB).
* **Kalkulasi Otomatis Presensi:**
  * Log harian: Hadir, Tidak Hadir, Ijin, Sakit, Pulang Lebih Awal.
  * Formula Konversi Skor Presensi:
    ```
    Persentase Hadir = (Jumlah Hadir / Total Hari Efektif) x 100%
    Skor Presensi = Persentase Hadir / 20  (Dikonversi ke skala 1 - 5)
    ```

---

## 5. SKEMA BASIS DATA RELASIONAL (MYSQL 8.0 DDL)

Berikut DDL lengkap MySQL yang siap dijalankan pada migration CodeIgniter 4:

```sql
-- 1. Tabel Master Users & Auth
CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(150) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(150) NOT NULL,
    role ENUM('admin_tu', 'guru', 'koordinator', 'waka', 'kepsek') DEFAULT 'guru',
    is_active TINYINT(1) DEFAULT 1,
    created_at DATETIME NULL,
    updated_at DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Tabel Master Posisi & Guru MI Al-Husna (48 Tenaga Pendidik)
CREATE TABLE gurus (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NULL,
    nip_nik VARCHAR(50) NULL UNIQUE,
    nama_guru VARCHAR(150) NOT NULL,
    posisi ENUM('Wakil Kepala Sekolah', 'Wali kelas', 'Koordinator Bidang', 'Guru Bidang Studi', 'Guru Al-Qur'an') NOT NULL DEFAULT 'Guru Bidang Studi',
    bidang_studi VARCHAR(100) NULL,
    tingkatan_level ENUM('ECT', 'DEV', 'PROF', 'EXP') DEFAULT 'ECT',
    target_ukg_persen DECIMAL(5,2) DEFAULT 85.00,
    target_jam_pelatihan INT DEFAULT 25,
    target_english_persen DECIMAL(5,2) DEFAULT 40.00,
    target_digital_persen DECIMAL(5,2) DEFAULT 75.00,
    created_at DATETIME NULL,
    updated_at DATETIME NULL,
    CONSTRAINT fk_guru_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Tabel Periode Penilaian / Tahun Pelajaran
CREATE TABLE periodes (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tahun_pelajaran VARCHAR(20) NOT NULL, -- '2026-2027'
    semester ENUM('Ganjil', 'Genap') NOT NULL,
    status ENUM('draft', 'open', 'review', 'closed') DEFAULT 'draft',
    tgl_mulai DATE NOT NULL,
    tgl_selesai DATE NOT NULL,
    created_at DATETIME NULL,
    updated_at DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Tabel Kategori Pilar KPI
CREATE TABLE kategori_kpis (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    kode_kategori VARCHAR(20) NOT NULL UNIQUE, -- 'OBS_KELAS', 'PROFESIONAL', 'KEPRIBADIAN', 'SOSIAL_360', 'EVAL_METODE'
    nama_kategori VARCHAR(150) NOT NULL,
    bobot_persen DECIMAL(5,2) NOT NULL, -- 25.00, 25.00, 20.00, 15.00, 15.00
    deskripsi TEXT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Tabel Indikator Butir Soal Evaluasi
CREATE TABLE indikator_kpis (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    kategori_id INT UNSIGNED NOT NULL,
    kode_indikator VARCHAR(20) NOT NULL,
    sub_aspek VARCHAR(150) NOT NULL,
    pertanyaan_indikator TEXT NOT NULL,
    tipe_jawaban ENUM('scale_1_5', 'text', 'file_upload', 'select', 'multiselect') DEFAULT 'scale_1_5',
    target_standar VARCHAR(255) NULL,
    urutan INT DEFAULT 1,
    CONSTRAINT fk_indikator_kategori FOREIGN KEY (kategori_id) REFERENCES kategori_kpis(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Tabel Penilaian KPI (Header)
CREATE TABLE penilaian_kpis (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    periode_id INT UNSIGNED NOT NULL,
    guru_id INT UNSIGNED NOT NULL,
    penilai_id INT UNSIGNED NOT NULL,
    jenis_penilaian ENUM('self', 'peer', 'observer_kelas', 'waka_kepsek') NOT NULL,
    skor_pilar_1 DECIMAL(5,2) DEFAULT 0.00,
    skor_pilar_2 DECIMAL(5,2) DEFAULT 0.00,
    skor_pilar_3 DECIMAL(5,2) DEFAULT 0.00,
    skor_pilar_4 DECIMAL(5,2) DEFAULT 0.00,
    skor_pilar_5 DECIMAL(5,2) DEFAULT 0.00,
    nilai_akhir_total DECIMAL(5,2) DEFAULT 0.00,
    predikat_level ENUM('ECT', 'DEV', 'PROF', 'EXP') DEFAULT 'ECT',
    status ENUM('draft', 'submitted', 'reviewed', 'approved') DEFAULT 'draft',
    observer_comments TEXT NULL,
    teacher_reflection TEXT NULL,
    approved_by INT UNSIGNED NULL,
    approved_at DATETIME NULL,
    created_at DATETIME NULL,
    updated_at DATETIME NULL,
    CONSTRAINT fk_penilaian_periode FOREIGN KEY (periode_id) REFERENCES periodes(id) ON DELETE CASCADE,
    CONSTRAINT fk_penilaian_guru FOREIGN KEY (guru_id) REFERENCES gurus(id) ON DELETE CASCADE,
    CONSTRAINT fk_penilaian_penilai FOREIGN KEY (penilai_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. Tabel Detail Jawaban Penilaian Tiap Butir
CREATE TABLE penilaian_details (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    penilaian_id INT UNSIGNED NOT NULL,
    indikator_id INT UNSIGNED NOT NULL,
    skor_nilai TINYINT UNSIGNED NULL, -- 1 s/d 5
    jawaban_text TEXT NULL,
    file_path VARCHAR(255) NULL,
    CONSTRAINT fk_detail_penilaian FOREIGN KEY (penilaian_id) REFERENCES penilaian_kpis(id) ON DELETE CASCADE,
    CONSTRAINT fk_detail_indikator FOREIGN KEY (indikator_id) REFERENCES indikator_kpis(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. Tabel Log Presensi Harian KBM & Rapat
CREATE TABLE presensi_gurus (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    guru_id INT UNSIGNED NOT NULL,
    tanggal DATE NOT NULL,
    jenis_kegiatan ENUM('kbm_harian', 'rapat_dinas', 'upacara_resmi') DEFAULT 'kbm_harian',
    status_kehadiran ENUM('Hadir', 'Tidak Hadir', 'Ijin', 'Sakit', 'Pulang', 'Pulang lebih awal') NOT NULL,
    jam_masuk TIME NULL,
    jam_pulang TIME NULL,
    agenda_kegiatan VARCHAR(255) NULL,
    keterangan TEXT NULL,
    created_at DATETIME NULL,
    CONSTRAINT fk_presensi_guru FOREIGN KEY (guru_id) REFERENCES gurus(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. Tabel Portofolio Bukti Kinerja & Sertifikat
CREATE TABLE bukti_portofolios (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    guru_id INT UNSIGNED NOT NULL,
    periode_id INT UNSIGNED NOT NULL,
    jenis_dokumen ENUM('sertifikat_pelatihan', 'sertifikat_bahasa_inggris', 'pdf_rubrik_rpp_3metode', 'karya_ptk') NOT NULL,
    judul_dokumen VARCHAR(200) NOT NULL,
    jumlah_jam_jp INT DEFAULT 0,
    file_url VARCHAR(255) NOT NULL,
    status_validasi ENUM('pending', 'valid', 'invalid') DEFAULT 'pending',
    catatan_validator TEXT NULL,
    created_at DATETIME NULL,
    CONSTRAINT fk_portofolio_guru FOREIGN KEY (guru_id) REFERENCES gurus(id) ON DELETE CASCADE,
    CONSTRAINT fk_portofolio_periode FOREIGN KEY (periode_id) REFERENCES periodes(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

## 6. IMPLEMENTASI CODEIGNITER 4 (SERVICE & CONTROLLER)

### Service Layer: `app/Services/KpiCalculatorService.php`
Algoritma pembobotan otomatis dan penentuan Tingkatan Level:

```php
<?php

namespace AppServices;

class KpiCalculatorService
{
    /**
     * Menghitung Nilai Akhir KPI dan Mengklasifikasikan Tingkatan Level Guru
     * 
     * @param float $skorObsKelas    (Skala 1-5, Bobot 25%)
     * @param float $skorProfesional (Skala 1-5, Bobot 25%)
     * @param float $skorKepribadian (Skala 1-5, Bobot 20%)
     * @param float $skorSosial360   (Skala 1-5, Bobot 15%)
     * @param float $skorEvalMetode  (Skala 1-5, Bobot 15%)
     * @return array
     */
    public function hitungNilaiAkhir(
        float $skorObsKelas,
        float $skorProfesional,
        float $skorKepribadian,
        float $skorSosial360,
        float $skorEvalMetode
    ): array {
        // Konversi skala 1-5 ke skala 0-100 (Nilai / 5 * 100)
        $p1 = ($skorObsKelas / 5.0) * 100.0;
        $p2 = ($skorProfesional / 5.0) * 100.0;
        $p3 = ($skorKepribadian / 5.0) * 100.0;
        $p4 = ($skorSosial360 / 5.0) * 100.0;
        $p5 = ($skorEvalMetode / 5.0) * 100.0;

        // Hitung total terbobot
        $totalSkor = ($p1 * 0.25) + ($p2 * 0.25) + ($p3 * 0.20) + ($p4 * 0.15) + ($p5 * 0.15);
        $totalSkor = round($totalSkor, 2);

        // Tentukan Tingkatan Level MI Al-Husna
        if ($totalSkor >= 90.0) {
            $levelCode = 'EXP';
            $levelName = 'Tingkat 4: Guru Ahli (Expert)';
            $rekomendasi = 'Diberikan mandat sebagai Mentor Pendidik, Koordinator Kurikulum, & Inovator Model Ajar.';
        } elseif ($totalSkor >= 80.0) {
            $levelCode = 'PROF';
            $levelName = 'Tingkat 3: Guru Mahir (Proficient)';
            $rekomendasi = 'Mandiri & efektif. Diikutsertakan dalam lokakarya riset pembelajaran tingkat lanjut.';
        } elseif ($totalSkor >= 70.0) {
            $levelCode = 'DEV';
            $levelName = 'Tingkat 2: Guru Berkembang (Developing)';
            $rekomendasi = 'Memerlukan pendampingan moderat pada perencanaan kurikulum dan manajemen KBM.';
        } else {
            $levelCode = 'ECT';
            $levelName = 'Tingkat 1: Guru Pemula (Early Career Teacher)';
            $rekomendasi = 'Wajib mengikuti program mentoring intensif & supervisi kelas berkala oleh Waka.';
        }

        return [
            'skor_pilar_1'     => round($p1, 2),
            'skor_pilar_2'     => round($p2, 2),
            'skor_pilar_3'     => round($p3, 2),
            'skor_pilar_4'     => round($p4, 2),
            'skor_pilar_5'     => round($p5, 2),
            'nilai_akhir'      => $totalSkor,
            'level_code'       => $levelCode,
            'level_name'       => $levelName,
            'rekomendasi'      => $rekomendasi
        ];
    }
}
```

---

## 7. DAFTAR 48 TENAGA PENDIDIK MI AL-HUSNA (MASTER ROSTER)

Master data nama pendidik yang terdaftar di sistem:
1. Ahmad Padil
2. Nurul Choiriyah
3. Putri Diniyati Hasanah
4. Bahrul Ulum
5. Nyi Royatul Fajariah
6. Bagus Saputro
7. Diny Pratiwi
8. Resa Nuraini
9. Ahmad Faridhi
10. Irfan Sidiq, S.Pd
11. Alifian Khafif Augusti
12. Nurul Hidayah
13. Hajjah Ukhti Zumara
14. Miftakhur Rohmah
15. Juliana Safitri
16. Maimunah
17. Raffi Affan Yafi
18. Invita Fadhilatus Tsalis
19. Siti Murtafiah
20. Muhasan
21. Novita Sari
22. Ida Farida
23. Hidayatul Mustaqimah
24. Nuraini
25. Rini Utari
26. Yunniar Andriani
27. Nida Fikria Sya'bana
28. Siti Faizah
29. Ratna Aprillia Safitri
30. Ade Husnun Muflihah
31. Diah Cahya Khodijah
32. Silmi Maulina
33. Mariyam
34. Shalsa Fikriya
35. Riski Sirma B
36. Shifany Fikriya
37. Maswanih
38. Rista Rosmeri
39. Tri Rosiana Wati Dewi
40. Sri Wahyuni
41. Sifafauziah
42. Yunita Saridin
43. Puad Baidilah
44. Azritamara Shalsadila
45. Nur Indah Sari
46. Siti Azizah
47. Mimi Saumi
48. Siti Nur Afifah
49. Nurul Jalaliyah
50. Sholikhah Ari Utami
