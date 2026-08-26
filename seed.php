<?php
// Standalone DB Seeder Script for Sistem KPI Guru

header('Content-Type: text/html; charset=utf-8');

echo '<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Update Data 4 Pilar & Periode KPI</title>
    <style>
        body { font-family: system-ui, -apple-system, sans-serif; background: #0f172a; color: #f8fafc; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; }
        .card { background: #1e293b; border: 1px solid #334155; border-radius: 16px; padding: 2.5rem; max-width: 520px; width: 90%; text-align: center; box-shadow: 0 10px 30px rgba(0,0,0,0.4); }
        .status-icon { font-size: 3.5rem; margin-bottom: 1rem; }
        h2 { margin: 0 0 0.5rem; font-size: 1.6rem; color: #38bdf8; }
        p { margin: 0 0 1.25rem; color: #94a3b8; font-size: 0.95rem; line-height: 1.5; }
        .btn { display: inline-block; background: #0284c7; color: #fff; text-decoration: none; padding: 12px 28px; border-radius: 30px; font-weight: 600; font-size: 0.92rem; }
    </style>
</head>
<body>
    <div class="card">';

try {
    if (file_exists(__DIR__ . '/vendor/autoload.php')) {
        defined('FCPATH') || define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR);
        defined('COMPOSER_PATH') || define('COMPOSER_PATH', __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php');
        defined('ENVIRONMENT') || define('ENVIRONMENT', $_ENV['CI_ENVIRONMENT'] ?? $_SERVER['CI_ENVIRONMENT'] ?? 'production');

        require __DIR__ . '/app/Config/Paths.php';
        $paths = new \Config\Paths();
        require $paths->systemDirectory . '/Boot.php';
        \CodeIgniter\Boot::bootWorker($paths);
        
        $db = \Config\Database::connect();
        $db->query('SET FOREIGN_KEY_CHECKS = 0;');

        // Re-seed 4 Categories
        $db->table('kategori_kpis')->emptyTable();
        $db->table('kategori_kpis')->insertBatch([
            [
                'id'            => 1,
                'kode_kategori' => 'PEDAGOGIK',
                'nama_kategori' => 'Kompetensi Pedagogik (Observasi Kelas & Evaluasi Metode)',
                'bobot_persen' => 25.00,
                'deskripsi'    => 'Penilaian supervisi KBM kelas, variasi metode penilaian (Formatif, Sumatif, Otentik), serta presensi kerja.'
            ],
            [
                'id'            => 2,
                'kode_kategori' => 'PROFESIONAL',
                'nama_kategori' => 'KPI Kompetensi Profesional Guru',
                'bobot_persen' => 25.00,
                'deskripsi'    => 'Penguasaan materi, pengembangan diri, penguasaan Bahasa Inggris, dan integrasi teknologi/AI.'
            ],
            [
                'id'            => 3,
                'kode_kategori' => 'KEPRIBADIAN',
                'nama_kategori' => 'Kompetensi Kepribadian, Kedisiplinan & Kematangan Emosi',
                'bobot_persen' => 25.00,
                'deskripsi'    => 'Etika & keteladanan, kedisiplinan KBM, partisipasi rapat dinas, serta kematangan emosi.'
            ],
            [
                'id'            => 4,
                'kode_kategori' => 'SOSIAL_360',
                'nama_kategori' => 'KPI Kompetensi Sosial & Penilaian Rekan Sejawat 360°',
                'bobot_persen' => 25.00,
                'deskripsi'    => 'Hubungan antar personal dengan sejawat, komunikasi orang tua, dan kontribusi non-pengajaran.'
            ],
        ]);

        // Re-seed Active Period
        $db->table('periodes')->emptyTable();
        $db->table('periodes')->insert([
            'tahun_pelajaran' => 'TP 2026-2027',
            'semester'        => 'Tahunan (1 Tahun Full)',
            'status'          => 'open',
            'tgl_mulai'       => '2026-07-01',
            'tgl_selesai'     => '2027-06-30',
            'created_at'      => date('Y-m-d H:i:s'),
            'updated_at'      => date('Y-m-d H:i:s'),
        ]);

        $db->query('SET FOREIGN_KEY_CHECKS = 1;');

        echo '<div class="status-icon">🌱</div>';
        echo '<h2>Update Data 4 Pilar & Database Sukses!</h2>';
        echo '<p>Kategori 4 Pilar (PEDAGOGIK 25%, PROFESIONAL 25%, KEPRIBADIAN 25%, SOSIAL_360 25%) dan Periode Tahunan telah disemaikan ke database hosting.</p>';
        echo '<a href="public/" class="btn">Buka Aplikasi KPI &rarr;</a>';
    } else {
        throw new Exception("Vendor autoload tidak ditemukan.");
    }
} catch (Throwable $e) {
    echo '<div class="status-icon">❌</div>';
    echo '<h2 style="color: #f87171;">Gagal Update DB</h2>';
    echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
}

echo '</div></body></html>';
