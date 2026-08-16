<?php
require_once __DIR__ . '/config/db.php';

// Hapus semua data session lalu hancurkan session-nya
$_SESSION = array();
session_destroy();

header("Location: login.php");
exit;
?>
