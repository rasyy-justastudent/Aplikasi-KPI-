<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateKpiTables extends Migration
{
    public function up()
    {
        // 1. Users
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'auto_increment' => true],
            'username' => ['type' => 'VARCHAR', 'constraint' => 100, 'unique' => true],
            'email' => ['type' => 'VARCHAR', 'constraint' => 150, 'unique' => true],
            'password_hash' => ['type' => 'VARCHAR', 'constraint' => 255],
            'nama_lengkap' => ['type' => 'VARCHAR', 'constraint' => 150],
            'role' => [
                'type' => 'ENUM',
                'constraint' => ['admin_tu', 'guru', 'koordinator', 'waka', 'kepsek'],
                'default' => 'guru',
            ],
            'is_active' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('users', true);

        // 2. Gurus
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'auto_increment' => true],
            'user_id' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'null' => true],
            'nip_nik' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true, 'unique' => true],
            'nama_guru' => ['type' => 'VARCHAR', 'constraint' => 150],
            'posisi' => [
                'type' => 'ENUM',
                'constraint' => ['Wakil Kepala Sekolah', 'Wali kelas', 'Koordinator Bidang', 'Guru Bidang Studi', "Guru Al-Qur'an"],
                'null' => true,
                'default' => null,
            ],
            'bidang_studi' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'tingkatan_level' => [
                'type' => 'ENUM',
                'constraint' => ['ECT', 'DEV', 'PROF', 'EXP'],
                'null' => true,
                'default' => null,
            ],
            'target_ukg_persen' => ['type' => 'DECIMAL', 'constraint' => '5,2', 'default' => 85.00],
            'target_jam_pelatihan' => ['type' => 'INT', 'constraint' => 11, 'default' => 25],
            'target_english_persen' => ['type' => 'DECIMAL', 'constraint' => '5,2', 'default' => 40.00],
            'target_digital_persen' => ['type' => 'DECIMAL', 'constraint' => '5,2', 'default' => 75.00],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'SET NULL');
        $this->forge->createTable('gurus', true);

        // 3. Periodes
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'auto_increment' => true],
            'tahun_pelajaran' => ['type' => 'VARCHAR', 'constraint' => 20],
            'semester' => ['type' => 'ENUM', 'constraint' => ['Ganjil', 'Genap']],
            'status' => ['type' => 'ENUM', 'constraint' => ['draft', 'open', 'review', 'closed'], 'default' => 'draft'],
            'tgl_mulai' => ['type' => 'DATE'],
            'tgl_selesai' => ['type' => 'DATE'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('periodes', true);

        // 4. Kategori KPIs
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'auto_increment' => true],
            'kode_kategori' => ['type' => 'VARCHAR', 'constraint' => 20, 'unique' => true],
            'nama_kategori' => ['type' => 'VARCHAR', 'constraint' => 150],
            'bobot_persen' => ['type' => 'DECIMAL', 'constraint' => '5,2'],
            'deskripsi' => ['type' => 'TEXT', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('kategori_kpis', true);

        // 5. Indikator KPIs
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'auto_increment' => true],
            'kategori_id' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true],
            'kode_indikator' => ['type' => 'VARCHAR', 'constraint' => 20],
            'sub_aspek' => ['type' => 'VARCHAR', 'constraint' => 150],
            'pertanyaan_indikator' => ['type' => 'TEXT'],
            'tipe_jawaban' => [
                'type' => 'ENUM',
                'constraint' => ['scale_1_5', 'text', 'file_upload', 'select', 'multiselect'],
                'default' => 'scale_1_5',
            ],
            'target_standar' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'urutan' => ['type' => 'INT', 'constraint' => 11, 'default' => 1],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('kategori_id', 'kategori_kpis', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('indikator_kpis', true);

        // 6. Penilaian KPIs
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'auto_increment' => true],
            'periode_id' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true],
            'guru_id' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true],
            'penilai_id' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true],
            'jenis_penilaian' => [
                'type' => 'ENUM',
                'constraint' => ['self', 'peer', 'observer_kelas', 'waka_kepsek'],
            ],
            'skor_pilar_1' => ['type' => 'DECIMAL', 'constraint' => '5,2', 'default' => 0.00],
            'skor_pilar_2' => ['type' => 'DECIMAL', 'constraint' => '5,2', 'default' => 0.00],
            'skor_pilar_3' => ['type' => 'DECIMAL', 'constraint' => '5,2', 'default' => 0.00],
            'skor_pilar_4' => ['type' => 'DECIMAL', 'constraint' => '5,2', 'default' => 0.00],
            'skor_pilar_5' => ['type' => 'DECIMAL', 'constraint' => '5,2', 'default' => 0.00],
            'nilai_akhir_total' => ['type' => 'DECIMAL', 'constraint' => '5,2', 'default' => 0.00],
            'predikat_level' => [
                'type' => 'ENUM',
                'constraint' => ['ECT', 'DEV', 'PROF', 'EXP'],
                'null' => true,
                'default' => null,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['draft', 'submitted', 'reviewed', 'approved'],
                'default' => 'draft',
            ],
            'observer_comments' => ['type' => 'TEXT', 'null' => true],
            'teacher_reflection' => ['type' => 'TEXT', 'null' => true],
            'approved_by' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'null' => true],
            'approved_at' => ['type' => 'DATETIME', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('periode_id', 'periodes', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('guru_id', 'gurus', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('penilai_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('penilaian_kpis', true);

        // 7. Penilaian Details
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'auto_increment' => true],
            'penilaian_id' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true],
            'indikator_id' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true],
            'skor_nilai' => ['type' => 'TINYINT', 'constraint' => 3, 'unsigned' => true, 'null' => true],
            'jawaban_text' => ['type' => 'TEXT', 'null' => true],
            'file_path' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('penilaian_id', 'penilaian_kpis', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('indikator_id', 'indikator_kpis', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('penilaian_details', true);

        // 8. Presensi Gurus
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'auto_increment' => true],
            'guru_id' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true],
            'tanggal' => ['type' => 'DATE'],
            'jenis_kegiatan' => [
                'type' => 'ENUM',
                'constraint' => ['kbm_harian', 'rapat_dinas', 'upacara_resmi'],
                'default' => 'kbm_harian',
            ],
            'status_kehadiran' => [
                'type' => 'ENUM',
                'constraint' => ['Hadir', 'Tidak Hadir', 'Ijin', 'Sakit', 'Pulang', 'Pulang lebih awal'],
            ],
            'jam_masuk' => ['type' => 'TIME', 'null' => true],
            'jam_pulang' => ['type' => 'TIME', 'null' => true],
            'agenda_kegiatan' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'keterangan' => ['type' => 'TEXT', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('guru_id', 'gurus', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('presensi_gurus', true);

        // 9. Bukti Portofolios
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'auto_increment' => true],
            'guru_id' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true],
            'periode_id' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true],
            'jenis_dokumen' => [
                'type' => 'ENUM',
                'constraint' => ['sertifikat_pelatihan', 'sertifikat_bahasa_inggris', 'pdf_rubrik_rpp_3metode', 'karya_ptk'],
            ],
            'judul_dokumen' => ['type' => 'VARCHAR', 'constraint' => 200],
            'jumlah_jam_jp' => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'file_url' => ['type' => 'VARCHAR', 'constraint' => 255],
            'status_validasi' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'valid', 'invalid'],
                'default' => 'pending',
            ],
            'catatan_validator' => ['type' => 'TEXT', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('guru_id', 'gurus', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('periode_id', 'periodes', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('bukti_portofolios', true);
    }

    public function down()
    {
        $this->forge->dropTable('bukti_portofolios', true);
        $this->forge->dropTable('presensi_gurus', true);
        $this->forge->dropTable('penilaian_details', true);
        $this->forge->dropTable('penilaian_kpis', true);
        $this->forge->dropTable('indikator_kpis', true);
        $this->forge->dropTable('kategori_kpis', true);
        $this->forge->dropTable('periodes', true);
        $this->forge->dropTable('gurus', true);
        $this->forge->dropTable('users', true);
    }
}
