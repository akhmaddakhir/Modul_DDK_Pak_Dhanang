<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if (isset($_level_akses)) {
    if ($_SESSION['level'] != $_level_akses) {
        echo "Akses ditolak!";
        exit();
    }
}


?>