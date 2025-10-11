<?php
$DB_HOST = '127.0.0.1';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'sembako';

$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($mysqli->connect_errno) {
    error_log('DB connect error: ' . $mysqli->connect_error);
    die('Koneksi database gagal. Periksa konfigurasi.');
}
$mysqli->set_charset('utf8mb4');
?>