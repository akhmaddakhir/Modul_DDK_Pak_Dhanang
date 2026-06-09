<?php
session_start();
include "koneksi.php";
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    //ambil data user
    $stmt = $conn->prepare("SELECT * FROM tb_user WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    if ($data) {
    if (password_verify($password, $data['password'])) {
        $_SESSION['username'] = $data['username'];
        $_SESSION['level'] = $data['level'];
        
        
        if ($data['level'] == "admin") {
            header("Location: admin.php");
        } else if ($data['level'] == "user") {
            header("Location: user.php");
        }
      exit();
    } else {
        echo "Password salah!";
    }
}else {
    echo "Username tidak ditemukan!";
}
}





?>