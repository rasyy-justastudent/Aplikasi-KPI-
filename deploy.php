<?php
// Auto Deploy Script for Sistem KPI Guru
// Downloads and extracts latest main branch from GitHub repository

$repoZipUrl  = 'https://github.com/rasyy-justastudent/Aplikasi-KPI-/archive/refs/heads/main.zip';
$tempZipPath = __DIR__ . '/deploy_latest.zip';
$targetDir   = __DIR__;

header('Content-Type: text/html; charset=utf-8');

echo '<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auto-Deploy Sistem KPI Guru</title>
    <style>
        body { font-family: system-ui, -apple-system, sans-serif; background: #0f172a; color: #f8fafc; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; }
        .card { background: #1e293b; border: 1px solid #334155; border-radius: 16px; padding: 2.5rem; max-width: 520px; width: 90%; text-align: center; box-shadow: 0 10px 30px rgba(0,0,0,0.4); }
        .status-icon { font-size: 3.5rem; margin-bottom: 1rem; }
        h2 { margin: 0 0 0.5rem; font-size: 1.6rem; color: #38bdf8; }
        p { margin: 0 0 1.25rem; color: #94a3b8; font-size: 0.95rem; line-height: 1.5; }
        .btn { display: inline-block; background: #0284c7; color: #fff; text-decoration: none; padding: 12px 28px; border-radius: 30px; font-weight: 600; font-size: 0.92rem; transition: all 0.2s; }
        .btn:hover { background: #0369a1; transform: translateY(-1px); }
        .log-box { text-align: left; background: #0f172a; border: 1px solid #1e293b; border-radius: 10px; padding: 1rem; font-family: monospace; font-size: 0.82rem; color: #a7f3d0; margin: 1.25rem 0; max-height: 160px; overflow-y: auto; line-height: 1.6; }
    </style>
</head>
<body>
    <div class="card">';

$startTime = microtime(true);
$logs = [];

try {
    // 1. Download zip from GitHub
    $logs[] = "📥 Mengunduh kode terbaru dari GitHub...";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $repoZipUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_USERAGENT, 'SistemKPIGuru-Deployer');
    $zipData = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200 || empty($zipData)) {
        throw new Exception("Gagal mengunduh file update dari GitHub (HTTP $httpCode).");
    }

    file_put_contents($tempZipPath, $zipData);
    $logs[] = "📦 File update berhasil diunduh (" . round(strlen($zipData) / 1024, 1) . " KB).";

    // 2. Extract Zip
    $logs[] = "📂 Mengekstrak dan menyinkronkan file...";
    $zip = new ZipArchive();
    if ($zip->open($tempZipPath) === true) {
        $extractDir = __DIR__ . '/deploy_tmp_' . time();
        @mkdir($extractDir, 0777, true);
        $zip->extractTo($extractDir);
        $zip->close();

        // Locate extracted subfolder e.g. Aplikasi-KPI--main
        $subDirs = glob($extractDir . '/*', GLOB_ONLYDIR);
        $sourceDir = !empty($subDirs) ? $subDirs[0] : $extractDir;

        // Copy files recursively from sourceDir to targetDir
        $dirIter = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($sourceDir, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );

        $copiedCount = 0;
        foreach ($dirIter as $item) {
            $subPath  = str_replace('\\', '/', substr($item->getPathname(), strlen($sourceDir) + 1));
            $destPath = $targetDir . '/' . $subPath;

            // Preserve .env file or create valid .env on server
            if ($subPath === '.env') {
                if (!file_exists($destPath)) {
                    $envContent = "CI_ENVIRONMENT = production\n"
                        . "database.default.hostname = localhost\n"
                        . "database.default.database = u128823797_projek\n"
                        . "database.default.username = u128823797_projek\n"
                        . "database.default.password = #Rahasia404#123\n"
                        . "database.default.DBDriver = MySQLi\n"
                        . "database.default.DBPrefix =\n"
                        . "database.default.port = 3306\n";
                    file_put_contents($destPath, $envContent);
                }
                continue;
            }

            if ($item->isDir()) {
                if (!is_dir($destPath)) {
                    @mkdir($destPath, 0777, true);
                }
            } else {
                @mkdir(dirname($destPath), 0777, true);
                @copy($item->getPathname(), $destPath);
                $copiedCount++;
            }
        }

        // Cleanup temporary zip & folders
        @unlink($tempZipPath);
        if (is_dir($extractDir)) {
            $files = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($extractDir, RecursiveDirectoryIterator::SKIP_DOTS),
                RecursiveIteratorIterator::CHILD_FIRST
            );
            foreach ($files as $fileinfo) {
                $todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
                @$todo($fileinfo->getRealPath());
            }
            @rmdir($extractDir);
        }

        // 3. Auto Run Migrations & Seeder for Database Updates
        $logs[] = "🗄️ Memeriksa & meng-update struktur database & data 4 Pilar KPI...";
        try {
            if (file_exists(__DIR__ . '/vendor/autoload.php')) {
                defined('FCPATH') || define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR);
                defined('COMPOSER_PATH') || define('COMPOSER_PATH', __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php');
                defined('ENVIRONMENT') || define('ENVIRONMENT', $_ENV['CI_ENVIRONMENT'] ?? $_SERVER['CI_ENVIRONMENT'] ?? 'production');

                require __DIR__ . '/app/Config/Paths.php';
                $paths = new \Config\Paths();
                require $paths->systemDirectory . '/Boot.php';
                \CodeIgniter\Boot::bootConsole($paths);

                $migrations = \Config\Services::migrations();
                $migrated = $migrations->latest();
                if ($migrated) {
                    $logs[] = "✨ Migrasi Database Sukses: Tabel baru / kolom baru otomatis ter-update!";
                } else {
                    $logs[] = "ℹ️ Database sudah versi terbaru.";
                }

                // Run KpiSeeder to update categories and period in database
                $seeder = \Config\Database::seeder();
                $seeder->call('App\Database\Seeds\KpiSeeder');
                $logs[] = "🌱 Seeder Sukses: Data 4 Pilar (PEDAGOGIK 25%) & Periode Tahunan otomatis ter-update di database hosting!";
            }
        } catch (\Throwable $migError) {
            $logs[] = "⚠️ Info Migrasi/Seeder DB: " . $migError->getMessage();
        }

        $elapsed = round(microtime(true) - $startTime, 2);
        $logs[] = "✅ Sukses memperbarui $copiedCount file.";
        $logs[] = "⏱️ Selesai dalam {$elapsed} detik.";

        echo '<div class="status-icon">🎉</div>';
        echo '<h2>Deploy & Auto-Migrate Berhasil!</h2>';
        echo '<p>Seluruh file kodingan & struktur tabel database di hosting telah diperbarui secara instan.</p>';
        echo '<div class="log-box">' . implode('<br>', $logs) . '</div>';
        echo '<a href="public/" class="btn">Buka Aplikasi KPI &rarr;</a>';
    } else {
        throw new Exception("Gagal membuka file ZIP update.");
    }
} catch (Exception $e) {
    if (file_exists($tempZipPath)) @unlink($tempZipPath);
    echo '<div class="status-icon">❌</div>';
    echo '<h2 style="color: #f87171;">Deploy Gagal</h2>';
    echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
    echo '<a href="deploy.php" class="btn" style="background: #e11d48;">Coba Lagi</a>';
}

echo '</div></body></html>';
