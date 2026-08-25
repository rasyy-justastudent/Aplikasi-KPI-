<?php
$zip = new ZipArchive;
$res = $zip->open('SISTEMKPIGURU.zip'); // ganti dengan nama file .zip kamu
if ($res === TRUE) {
    $zip->extractTo(__DIR__);
    $zip->close();
    echo 'Ekstrak berhasil!';
} else {
    echo 'Gagal mengekstrak.';
}
?>