<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

$routes->get('/', 'Auth::login');
$routes->get('/login', 'Auth::login');
$routes->post('/attempt-login', 'Auth::attemptLogin');
$routes->get('/logout', 'Auth::logout');

$routes->group('', ['filter' => 'auth'], static function ($routes) {
    // Dashboard
    $routes->get('/dashboard', 'Dashboard::index');

    // Master Data Pendidik (48 Guru)
    $routes->get('/guru', 'GuruController::index');
    $routes->get('/guru/create', 'GuruController::create');
    $routes->post('/guru/store', 'GuruController::store');
    $routes->get('/guru/edit/(:num)', 'GuruController::edit/$1');
    $routes->post('/guru/update/(:num)', 'GuruController::update/$1');
    $routes->get('/guru/delete/(:num)', 'GuruController::delete/$1');

    // Periode Penilaian
    $routes->get('/periode', 'PeriodeController::index');
    $routes->post('/periode/store', 'PeriodeController::store');
    $routes->get('/periode/status/(:num)/(:segment)', 'PeriodeController::updateStatus/$1/$2');

    // Indikator Evaluation
    $routes->get('/indikator', 'IndikatorController::index');

    // Penilaian 360 & Approval
    $routes->get('/penilaian', 'PenilaianController::index');
    $routes->get('/penilaian/input/(:num)', 'PenilaianController::input/$1');
    $routes->post('/penilaian/save', 'PenilaianController::save');
    $routes->get('/penilaian/approve/(:num)', 'PenilaianController::approve/$1');

    // Dedicated Evaluasi Metode Penilaian (Pilar 5) for Teachers
    $routes->get('/evaluasi-metode', 'PenilaianController::evaluasiMetode');
    $routes->post('/evaluasi-metode/save', 'PenilaianController::saveEvaluasiMetode');

    // Daily attendance for teachers
    $routes->get('/presensi/absen', 'PresensiController::dailyAbsenForm');
    $routes->post('/presensi/absen', 'PresensiController::saveDailyAbsen');
    $routes->post('/presensi/absen-harian', 'PresensiController::saveDailyAbsen');

    // Log Presensi Harian KBM & Rapat
    $routes->get('/presensi', 'PresensiController::index');
    $routes->post('/presensi/store', 'PresensiController::store');
    $routes->get('/presensi/delete/(:num)', 'PresensiController::delete/$1');

    // Portofolio Bukti Kinerja & Sertifikat
    $routes->get('/portofolio', 'PortofolioController::index');
    $routes->post('/portofolio/store', 'PortofolioController::store');
    $routes->post('/portofolio/validate/(:num)', 'PortofolioController::validateDoc/$1');

    // Laporan & Rapor KPI
    $routes->get('/laporan/rekap-sekolah', 'LaporanController::rekapSekolah');
    $routes->get('/laporan/rapor-guru/(:num)', 'LaporanController::raporGuru/$1');
    $routes->get('/laporan/export-csv', 'LaporanController::exportCsv');
});
