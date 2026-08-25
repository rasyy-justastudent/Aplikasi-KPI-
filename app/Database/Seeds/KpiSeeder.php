<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class KpiSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();

        // 1. Seed Kategori KPIs
        $kategoriData = [
            [
                'kode_kategori' => 'OBS_KELAS',
                'nama_kategori' => 'Observasi Kelas & Supervisi Pembelajaran',
                'bobot_persen' => 25.00,
                'deskripsi'    => 'Penilaian supervisi KBM langsung oleh Observer/Koordinator Bidang (20 Indikator A-G).'
            ],
            [
                'kode_kategori' => 'PROFESIONAL',
                'nama_kategori' => 'KPI Kompetensi Profesional Guru',
                'bobot_persen' => 25.00,
                'deskripsi'    => 'Penguasaan materi, pengembangan diri, penguasaan Bahasa Inggris, dan integrasi teknologi/AI.'
            ],
            [
                'kode_kategori' => 'KEPRIBADIAN',
                'nama_kategori' => 'Kompetensi Kepribadian, Kedisiplinan & Kematangan Emosi',
                'bobot_persen' => 20.00,
                'deskripsi'    => 'Etika & keteladanan, kedisiplinan KBM, partisipasi rapat dinas, serta kematangan emosi.'
            ],
            [
                'kode_kategori' => 'SOSIAL_360',
                'nama_kategori' => 'KPI Kompetensi Sosial & Penilaian Rekan Sejawat 360°',
                'bobot_persen' => 15.00,
                'deskripsi'    => 'Hubungan antar personal dengan sejawat, komunikasi orang tua, dan kontribusi non-pengajaran.'
            ],
            [
                'kode_kategori' => 'EVAL_METODE',
                'nama_kategori' => 'Variasi Metode Penilaian & Presensi Kerja',
                'bobot_persen' => 15.00,
                'deskripsi'    => 'Proporsi metode evaluasi (Formatif, Sumatif, Otentik) dan persentase presensi harian KBM.'
            ],
        ];
        $db->table('kategori_kpis')->ignore(true)->insertBatch($kategoriData);

        // Fetch category IDs
        $obsId = $db->table('kategori_kpis')->where('kode_kategori', 'OBS_KELAS')->get()->getRow()->id;
        $profId = $db->table('kategori_kpis')->where('kode_kategori', 'PROFESIONAL')->get()->getRow()->id;
        $kepId = $db->table('kategori_kpis')->where('kode_kategori', 'KEPRIBADIAN')->get()->getRow()->id;
        $sosId = $db->table('kategori_kpis')->where('kode_kategori', 'SOSIAL_360')->get()->getRow()->id;
        $evalId = $db->table('kategori_kpis')->where('kode_kategori', 'EVAL_METODE')->get()->getRow()->id;

        // 2. Seed Indikator KPIs
        $indikatorData = [];

        // Pilar 1: Observasi Kelas (20 Indikator)
        $obsItems = [
            ['A. Perencanaan Pembelajaran', 'Kesesuaian modul ajar dengan standar kurikulum MI Al-Husna', 'scale_1_5', 'Kurikulum terstandar'],
            ['A. Perencanaan Pembelajaran', 'Ketersediaan dan kejelasan bahan ajar yang menarik', 'scale_1_5', 'Bahan ajar interaktif'],
            ['A. Perencanaan Pembelajaran', 'Variasi metode pembelajaran yang direncanakan', 'scale_1_5', 'Variatif'],
            ['B. Pelaksanaan Pembelajaran', 'Penggunaan variasi media belajar saat KBM berlangsung', 'scale_1_5', 'Media beragam'],
            ['B. Pelaksanaan Pembelajaran', 'Penggunaan bahasa yang santun, jelas, dan mudah dipahami siswa', 'scale_1_5', 'Komunikasi efektif'],
            ['B. Pelaksanaan Pembelajaran', 'Mendorong keaktifan dan partisipasi peserta didik', 'scale_1_5', 'Siswa aktif'],
            ['C. Penguasaan Materi', 'Penyampaian materi akurat tanpa adanya miskonsepsi', 'scale_1_5', 'Bebas miskonsepsi'],
            ['C. Penguasaan Materi', 'Mengaitkan materi dengan kehidupan sehari-hari (Kontekstual)', 'scale_1_5', 'Pembelajaran kontekstual'],
            ['C. Penguasaan Materi', 'Merespon pertanyaan siswa dengan tepat dan jelas', 'scale_1_5', 'Responsif'],
            ['C. Penguasaan Materi', 'Memberikan bimbingan verbal/visual saat siswa mengalami kesulitan', 'scale_1_5', 'Bimbingan intensif'],
            ['D. Interaksi dengan Siswa', 'Menciptakan hubungan ramah, positif, dan penuh kasih sayang', 'scale_1_5', 'Hubungan positif'],
            ['D. Interaksi dengan Siswa', 'Memberikan apresiasi dan motivasi kepada siswa', 'scale_1_5', 'Motivasi tinggi'],
            ['D. Interaksi dengan Siswa', 'Mendengarkan dan merespon pendapat siswa secara adil', 'scale_1_5', 'Inklusif'],
            ['E. Pengelolaan Kelas', 'Menciptakan suasana kelas yang kondusif untuk belajar', 'scale_1_5', 'Kondusif'],
            ['E. Pengelolaan Kelas', 'Mengontrol perilaku siswa dengan pendekatan positif', 'scale_1_5', 'Disiplin positif'],
            ['E. Pengelolaan Kelas', 'Konsistensi penegakan aturan kelas', 'scale_1_5', 'Konsisten'],
            ['F. Evaluasi dan Tindak Lanjut', 'Penggunaan berbagai alat penilaian selama KBM (lisan, tugas, tes)', 'scale_1_5', 'Evaluasi beragam'],
            ['F. Evaluasi dan Tindak Lanjut', 'Memberikan umpan balik langsung (feedback) atas hasil belajar siswa', 'scale_1_5', 'Feedback konstruktif'],
            ['G. Pengelolaan Diskusi', 'Mengarahkan diskusi kelompok secara efektif', 'scale_1_5', 'Diskusi terarah'],
            ['G. Pengelolaan Diskusi', 'Memberikan instruksi kerja kelompok yang jelas didukung visual', 'scale_1_5', 'Instruksi jelas'],
        ];

        $urutan = 1;
        foreach ($obsItems as $item) {
            $indikatorData[] = [
                'kategori_id' => $obsId,
                'kode_indikator' => 'OBS_' . sprintf('%02d', $urutan),
                'sub_aspek' => $item[0],
                'pertanyaan_indikator' => $item[1],
                'tipe_jawaban' => $item[2],
                'target_standar' => $item[3],
                'urutan' => $urutan++,
            ];
        }

        // Pilar 2: Profesional (4 sub-aspek x 5 Butir = 20 Indikator)
        $profItems = [
            ['Penguasaan Materi', 'Pemahaman mendalam tentang bidang studi yang diampu', 'scale_1_5', 'Skor UKG >= 85%'],
            ['Penguasaan Materi', 'Kemampuan mengurai konsep kompleks menjadi sederhana', 'scale_1_5', 'Sederhana & mudah'],
            ['Penguasaan Materi', 'Mengintegrasikan nilai keislaman dan kebangsaan dalam materi', 'scale_1_5', 'Integratif'],
            ['Penguasaan Materi', 'Keakuratan data dan fakta dalam penyampaian materi', 'scale_1_5', 'Akurat'],
            ['Penguasaan Materi', 'Pencapaian skor evaluasi pemahaman materi (UKG Internal)', 'scale_1_5', 'Target >= 85%'],

            ['Pengembangan Diri', 'Inisiatif mengikuti seminar, workshop, atau pelatihan mandiri', 'scale_1_5', 'Target 25 Jam/Tahun'],
            ['Pengembangan Diri', 'Implementasi hasil pelatihan ke dalam proses pembelajaran kelas', 'scale_1_5', 'Terapkan inovasi'],
            ['Pengembangan Diri', 'Pembuatan karya inovatif, PTK, atau artikel ilmiah pendidikan', 'scale_1_5', 'Minimal 1 karya'],
            ['Pengembangan Diri', 'Berbagi pengalaman / best practices dengan rekan sejawat', 'scale_1_5', 'Aktif berbagi'],
            ['Pengembangan Diri', 'Pemenuhan jumlah jam pelatihan terverifikasi (Sertifikat)', 'scale_1_5', '>= 25 JP'],

            ['Pengembangan Bahasa Inggris', 'Penggunaan Bahasa Inggris harian dalam komunikasi sekolah', 'scale_1_5', 'Komunikasi harian'],
            ['Pengembangan Bahasa Inggris', 'Proporsi penggunaan instruksi kelas berbahasa Inggris', 'scale_1_5', 'Target >= 40%'],
            ['Pengembangan Bahasa Inggris', 'Kelancaran dan ketepatan pengucapan (fluency & pronunciation)', 'scale_1_5', 'Lancar & Tepat'],
            ['Pengembangan Bahasa Inggris', 'Motivasi dan pembiasaan siswa untuk merespon dalam Bahasa Inggris', 'scale_1_5', 'Siswa aktif B.Inggris'],
            ['Pengembangan Bahasa Inggris', 'Peningkatan sertifikasi / kompetensi Bahasa Inggris', 'scale_1_5', 'Sertifikat terverifikasi'],

            ['Integrasi Teknologi', 'Pemanfaatan Google Workspace / LMS dalam manajemen kelas', 'scale_1_5', 'Aktif LMS/Google'],
            ['Integrasi Teknologi', 'Penggunaan media pembelajaran digital interaktif (Quizizz, Canva, dll)', 'scale_1_5', 'Media digital'],
            ['Integrasi Teknologi', 'Persentase sesi pembelajaran yang memanfaatkan perangkat digital', 'scale_1_5', 'Target >= 75%'],
            ['Integrasi Teknologi', 'Pemanfaatan teknologi AI untuk persiapan & materi pembelajaran', 'scale_1_5', 'Pemanfaatan AI'],
            ['Integrasi Teknologi', 'Kemandirian penanganan dan perawatan alat digital kelas', 'scale_1_5', 'Mandiri perangkat'],
        ];

        $urutan = 1;
        foreach ($profItems as $item) {
            $indikatorData[] = [
                'kategori_id' => $profId,
                'kode_indikator' => 'PROF_' . sprintf('%02d', $urutan),
                'sub_aspek' => $item[0],
                'pertanyaan_indikator' => $item[1],
                'tipe_jawaban' => $item[2],
                'target_standar' => $item[3],
                'urutan' => $urutan++,
            ];
        }

        // Pilar 3: Kepribadian & Kedisiplinan (4 sub-aspek x 5 Butir = 20 Indikator)
        $kepItems = [
            ['Etika dan Keteladanan', 'Kejujuran dalam tugas akademik dan administrasi sekolah', 'scale_1_5', '0 Pelanggaran Etika'],
            ['Etika dan Keteladanan', 'Integritas dan pemeliharaan kerahasiaan data siswa/sekolah', 'scale_1_5', 'Integritas tinggi'],
            ['Etika dan Keteladanan', 'Penampilan rapi, sopan, dan sesuai tata tertib busana sekolah', 'scale_1_5', 'Rapi & Sopan'],
            ['Etika dan Keteladanan', 'Tanggung jawab moral dalam mendidik dan membimbing siswa', 'scale_1_5', 'Tanggung jawab moral'],
            ['Etika dan Keteladanan', 'Menjadi pendorong dan teladan akhlakul karimah bagi siswa', 'scale_1_5', 'Role Model'],

            ['Kedisiplinan KBM', 'Persentase kehadiran harian KBM di sekolah', 'scale_1_5', 'Target >= 90%'],
            ['Kedisiplinan KBM', 'Ketepatan waktu mulai mengajar di kelas', 'scale_1_5', 'Maks keterlambatan 1x/bln'],
            ['Kedisiplinan KBM', 'Ketepatan waktu mengakhiri KBM sesuai jadwal', 'scale_1_5', 'Disiplin jadwal'],
            ['Kedisiplinan KBM', 'Prosedur pengajuan izin mengajar secara terencana', 'scale_1_5', 'Izin terencana'],
            ['Kedisiplinan KBM', 'Pelaksanaan tugas inval / pengisian kelas pengganti saat hadir', 'scale_1_5', 'Tanggung jawab inval'],

            ['Partisipasi Rapat', 'Kehadiran dalam rapat dinas dan evaluasi sekolah', 'scale_1_5', 'Target >= 90%'],
            ['Partisipasi Rapat', 'Ketepatan waktu hadir pada rapat dinas', 'scale_1_5', 'Tepat waktu'],
            ['Partisipasi Rapat', 'Fokus dan perhatian selama rapat (tidak sibuk dengan gawai)', 'scale_1_5', 'Fokus'],
            ['Partisipasi Rapat', 'Kontribusi aktif menyampaikan masukan konstruktif dalam rapat', 'scale_1_5', 'Aktif memberi masukan'],
            ['Partisipasi Rapat', 'Komitmen melaksanakan hasil keputusan rapat dinas', 'scale_1_5', 'Komitmen tinggi'],

            ['Kematangan Emosi', 'Pengendalian diri di kelas (sabar, anti hukuman fisik/verbal)', 'scale_1_5', 'Skor >= 4.0'],
            ['Kematangan Emosi', 'Stabilitas emosi di bawah tekanan beban kerja atau perubahan jadwal', 'scale_1_5', 'Stabil'],
            ['Kematangan Emosi', 'Keterbukaan menerima kritik dan masukan dari atasan/rekan', 'scale_1_5', 'Terbuka'],
            ['Kematangan Emosi', 'Penyelesaian konflik dengan kepala dingin dan objektif', 'scale_1_5', 'Bijaksana'],
            ['Kematangan Emosi', 'Pemisahan urusan pribadi dari tugas profesional sekolah', 'scale_1_5', 'Profesional'],
        ];

        $urutan = 1;
        foreach ($kepItems as $item) {
            $indikatorData[] = [
                'kategori_id' => $kepId,
                'kode_indikator' => 'KEP_' . sprintf('%02d', $urutan),
                'sub_aspek' => $item[0],
                'pertanyaan_indikator' => $item[1],
                'tipe_jawaban' => $item[2],
                'target_standar' => $item[3],
                'urutan' => $urutan++,
            ];
        }

        // Pilar 4: Penilaian Rekan Sejawat 360° (3 sub-aspek x 5 Butir = 15 Indikator)
        $sosItems = [
            ['Hubungan Antar Personal (Rekan Kerja)', 'Seberapa proaktif guru dalam menawarkan bantuan atau bekerja sama dengan rekan sejawat untuk menyelesaikan tugas bersama di sekolah?', 'scale_1_5', 'Saling membantu'],
            ['Hubungan Antar Personal (Rekan Kerja)', 'Bagaimana kualitas interaksi, etika, dan komunikasi sehari-hari guru dengan sesama rekan kerja di lingkungan sekolah?', 'scale_1_5', 'Etika baik'],
            ['Hubungan Antar Personal (Rekan Kerja)', 'Seberapa terbuka dan profesional guru dalam menerima serta memberikan umpan balik (feedback) yang konstruktif kepada rekan kerja?', 'scale_1_5', 'Feedback positif'],
            ['Hubungan Antar Personal (Rekan Kerja)', 'Seberapa aktif dan positif partisipasi guru dalam forum diskusi, kerja kelompok, atau rapat internal di sekolah?', 'scale_1_5', 'Partisipatif'],
            ['Hubungan Antar Personal (Rekan Kerja)', 'Sejauh mana guru mampu memelihara suasana kerja yang harmonis dan mengelola perbedaan pendapat dengan rekan sejawat secara bijak?', 'scale_1_5', 'Harmonis'],

            ['Komunikasi dengan Orang Tua', 'Seberapa responsif dan terbuka guru dalam menanggapi pertanyaan, masukan, atau keluhan dari orang tua siswa?', 'scale_1_5', 'Responsif'],
            ['Komunikasi dengan Orang Tua', 'Bagaimana tingkat kejelasan, kesantunan, dan empati guru saat menyampaikan informasi perkembangan siswa kepada orang tua?', 'scale_1_5', 'Empati tinggi'],
            ['Komunikasi dengan Orang Tua', 'Seberapa aktif guru dalam mendorong kehadiran dan partisipasi orang tua saat ada pertemuan atau program sekolah?', 'scale_1_5', 'Kerjasama baik'],
            ['Komunikasi dengan Orang Tua', 'Sejauh mana guru mampu memberikan solusi atau saran yang konstruktif saat berdiskusi dengan orang tua mengenai kendala siswa?', 'scale_1_5', 'Solutif'],
            ['Komunikasi dengan Orang Tua', 'Seberapa rutin dan konsisten guru memberikan pembaruan informasi kepada orang tua terkait kegiatan sekolah dan proses belajar siswa?', 'scale_1_5', 'Informatif berkala'],

            ['Kontribusi Sekolah (Keterlibatan Non-Pengajaran)', 'Seberapa aktif guru mengambil peran, tanggung jawab, atau inisiatif dalam kepanitiaan acara dan kegiatan sekolah?', 'scale_1_5', 'Aktif kepanitiaan'],
            ['Kontribusi Sekolah (Keterlibatan Non-Pengajaran)', 'Bagaimana tingkat dedikasi guru saat ditugaskan menjadi pembina ekstrakurikuler, wali kelas, atau pengawas program khusus siswa?', 'scale_1_5', 'Dedikasi tinggi'],
            ['Kontribusi Sekolah (Keterlibatan Non-Pengajaran)', 'Seberapa besar kontribusi ide atau gagasan inovatif yang diberikan guru untuk kemajuan proyek dan pengembangan sekolah?', 'scale_1_5', 'Inovatif'],
            ['Kontribusi Sekolah (Keterlibatan Non-Pengajaran)', 'Sejauh mana guru bersedia meluangkan waktu di luar jam mengajar utama untuk mendukung kelancaran kegiatan non-akademik sekolah?', 'scale_1_5', 'Loyalitas tinggi'],
            ['Kontribusi Sekolah (Keterlibatan Non-Pengajaran)', 'Seberapa konsisten guru dalam memenuhi atau melampaui target keterlibatan minimal pada program non-pengajaran setiap tahunnya?', 'scale_1_5', 'Target tercapai'],
        ];

        $urutan = 1;
        foreach ($sosItems as $item) {
            $indikatorData[] = [
                'kategori_id' => $sosId,
                'kode_indikator' => 'SOS_' . sprintf('%02d', $urutan),
                'sub_aspek' => $item[0],
                'pertanyaan_indikator' => $item[1],
                'tipe_jawaban' => $item[2],
                'target_standar' => $item[3],
                'urutan' => $urutan++,
            ];
        }

        // Pilar 5: Metode & Presensi (3 Indikator utama)
        $evalItems = [
            ['Variasi Metode Penilaian', 'Proporsi penerapan metode penilaian Formatif, Sumatif, dan Otentik', 'scale_1_5', 'Terintegrasi 3 Metode'],
            ['Kelengkapan Rubrik Modul Ajar', 'Status kelengkapan instrumen & rubrik penilaian tertulis di Modul Ajar', 'scale_1_5', 'Rubrik Lengkap'],
            ['Presensi Kerja & Kehadiran', 'Persentase rekapitulasi kehadiran harian KBM dan kegiatan sekolah', 'scale_1_5', 'Target >= 90%'],
        ];

        $urutan = 1;
        foreach ($evalItems as $item) {
            $indikatorData[] = [
                'kategori_id' => $evalId,
                'kode_indikator' => 'EVAL_' . sprintf('%02d', $urutan),
                'sub_aspek' => $item[0],
                'pertanyaan_indikator' => $item[1],
                'tipe_jawaban' => $item[2],
                'target_standar' => $item[3],
                'urutan' => $urutan++,
            ];
        }

        $db->table('penilaian_details')->emptyTable();
        $db->table('indikator_kpis')->emptyTable();
        $db->table('indikator_kpis')->insertBatch($indikatorData);

        // 4. Clean existing users, gurus, and periodes, then seed Test Accounts per Role
        $db->table('bukti_portofolios')->emptyTable();
        $db->table('presensi_gurus')->emptyTable();
        $db->table('penilaian_details')->emptyTable();
        $db->table('penilaian_kpis')->emptyTable();
        $db->table('periodes')->emptyTable();
        $db->table('gurus')->emptyTable();
        $db->table('users')->emptyTable();

        // 3. Seed Periodes
        $periodeData = [
            [
                'tahun_pelajaran' => '2026-2027',
                'semester' => 'Ganjil',
                'status' => 'open',
                'tgl_mulai' => '2026-07-15',
                'tgl_selesai' => '2026-12-20',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'tahun_pelajaran' => '2026-2027',
                'semester' => 'Genap',
                'status' => 'draft',
                'tgl_mulai' => '2027-01-05',
                'tgl_selesai' => '2027-06-25',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        ];
        $db->table('periodes')->insertBatch($periodeData);

        $defaultPassword = password_hash('password123', PASSWORD_BCRYPT);

        $testUsers = [
            [
                'username'      => 'admin',
                'email'         => 'admin@mialhusna.sch.id',
                'password_hash' => $defaultPassword,
                'nama_lengkap'  => 'Administrator Tata Usaha',
                'role'          => 'admin',
                'is_active'     => 1,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
                'is_guru'       => true,
                'nip'           => '197001012000011001',
                'posisi'        => 'Admin TU',
            ],
            [
                'username'      => 'kepsek',
                'email'         => 'kepsek@mialhusna.sch.id',
                'password_hash' => $defaultPassword,
                'nama_lengkap'  => 'Dr. H. Ahmad Kepsek, M.Pd',
                'role'          => 'kepsek',
                'is_active'     => 1,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
                'is_guru'       => true,
                'nip'           => '197501012000031001',
                'posisi'        => 'Kepala Sekolah',
            ],
            [
                'username'      => 'guru',
                'email'         => 'guru@mialhusna.sch.id',
                'password_hash' => $defaultPassword,
                'nama_lengkap'  => 'Ahmad Padil, S.Pd',
                'role'          => 'guru',
                'is_active'     => 1,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
                'is_guru'       => true,
                'nip'           => '199004252015011004',
                'posisi'        => 'Wali kelas',
            ],
        ];

        foreach ($testUsers as $u) {
            $isGuru = $u['is_guru'];
            $nip = $u['nip'] ?? null;
            $posisi = $u['posisi'] ?? null;

            unset($u['is_guru'], $u['nip'], $u['posisi']);

            $db->table('users')->insert($u);
            $userId = $db->insertID();

            if ($isGuru) {
                $db->table('gurus')->insert([
                    'user_id'               => $userId,
                    'nip_nik'               => $nip,
                    'nama_guru'             => $u['nama_lengkap'],
                    'posisi'                => $posisi,
                    'bidang_studi'          => null,
                    'tingkatan_level'       => null,
                    'target_ukg_persen'     => 85.00,
                    'target_jam_pelatihan'  => 25,
                    'target_english_persen' => 40.00,
                    'target_digital_persen' => 75.00,
                    'created_at'            => date('Y-m-d H:i:s'),
                    'updated_at'            => date('Y-m-d H:i:s'),
                ]);
            }
        }
    }
}
