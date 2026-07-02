<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Content-Type: application/json");

include "../config/koneksi.php";
include "../helper/response.php";

$id = isset($_POST['id']) ? intval($_POST['id']) : null;

if (empty($id)) {
    response(false, "ID wajib dikirim");
    exit;
}

// Cek apakah user ada
$checkStmt = $conn->prepare("SELECT id FROM users WHERE id = ?");
$checkStmt->bind_param("i", $id);
$checkStmt->execute();
$checkRes = $checkStmt->get_result();
if (!$checkRes || $checkRes->num_rows == 0) {
    $checkStmt->close();
    response(false, "User tidak ditemukan");
    exit;
}
$checkStmt->close();

// Hapus user
$delStmt = $conn->prepare("DELETE FROM users WHERE id = ?");
$delStmt->bind_param("i", $id);

if ($delStmt->execute()) {
    response(true, "User berhasil dihapus");
} else {
    response(false, "Hapus gagal: " . $conn->error);
}

$delStmt->close();
$conn->close();