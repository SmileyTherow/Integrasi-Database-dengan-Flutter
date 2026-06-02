<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Content-Type: application/json");

include "../config/koneksi.php";
include "../helper/response.php";

$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

if (empty($username) || empty($email) || empty($password)) {
    response(false, "Semua field wajib diisi");
    exit;
}

$check = mysqli_query(
    $conn,
    "SELECT * FROM users WHERE email='$email'"
);

if (mysqli_num_rows($check) > 0) {
    response(false, "Email sudah digunakan");
    exit;
}

$hashPassword = password_hash($password, PASSWORD_BCRYPT);

$query = mysqli_query(
    $conn,
    "
    INSERT INTO users(username, email, password)
    VALUES('$username', '$email', '$hashPassword')
    "
);

if ($query) {
    response(true, "Register berhasil");
} else {
    response(false, "Register gagal");
}