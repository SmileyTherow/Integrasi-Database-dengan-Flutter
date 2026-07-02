<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Content-Type: application/json");

include "../config/koneksi.php";
include "../helper/response.php";

$id = isset($_POST['id']) ? intval($_POST['id']) : null;
$username = isset($_POST['username']) ? trim($_POST['username']) : null;
$email = isset($_POST['email']) ? trim($_POST['email']) : null;

if (empty($id) || empty($username) || empty($email)) {
    response(false, "Semua field wajib diisi");
    exit;
}

// Cek apakah email sudah dipakai user lain
$checkStmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
$checkStmt->bind_param("si", $email, $id);
$checkStmt->execute();
$checkRes = $checkStmt->get_result();
if ($checkRes && $checkRes->num_rows > 0) {
    $checkStmt->close();
    response(false, "Email sudah digunakan oleh user lain");
    exit;
}
$checkStmt->close();

// Update user
$updateStmt = $conn->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
$updateStmt->bind_param("ssi", $username, $email, $id);

if ($updateStmt->execute()) {
    response(true, "User berhasil diupdate");
} else {
    response(false, "Update gagal: " . $conn->error);
}

$updateStmt->close();
$conn->close();