<?php
require 'vendor/autoload.php';
$app = Config\Services::codeigniter();
$app->initialize();
$db = \Config\Database::connect();
$rows = $db->table('users')->get()->getResultArray();
foreach ($rows as $r) {
    echo "ID: " . $r['id'] . " | Username: " . $r['username'] . " | Role: " . $r['role'] . " | Nama: " . $r['nama_lengkap'] . "\n";
}
