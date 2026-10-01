<?php
session_start();

// Baca pengaturan
$config = json_decode(file_get_contents(__DIR__ . '/config.json'), true);

// Ambil IP pengunjung
function getIp() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) return $_SERVER['HTTP_CLIENT_IP'];
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        return trim($arr[0]);
    }
    return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
}

// Deteksi bahasa
function detectLang() {
    global $config;
    $http = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '';
    if (strpos($http, 'id') !== false) return 'id';
    if (strpos($http, 'ja') !== false) return 'jp';
    if (strpos($http, 'ko') !== false) return 'kr';
    if (strpos($http, 'zh') !== false) return 'cn';
    if (strpos($http, 'es') !== false) return 'es';
    if (strpos($http, 'fr') !== false) return 'fr';
    if (strpos($http, 'de') !== false) return 'de';
    return $config['default_lang'];
}

// Simpan ke log
function simpanLog($jenis, $data) {
    $jalur = __DIR__ . "/logs/{$jenis}.txt";
    $waktu = date('Y-m-d H:i:s');
    $baris = "[{$waktu}] {$data}" . PHP_EOL;
    file_put_contents($jalur, $baris, FILE_APPEND);
}