<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: /glitch');
    exit;
}

$_SESSION = [];
session_unset();
session_destroy();

header('Location: /glitch');
exit;
?>
