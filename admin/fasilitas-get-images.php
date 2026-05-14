<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") { header("HTTP/1.1 403 Forbidden"); exit; }
include '../database/conn.php';

$id = (int)($_GET['id'] ?? 0);
$images = [];
if ($id > 0) {
    $result = $conn->query("SELECT id, filename, sort_order FROM fasilitas_images WHERE fasilitas_id = $id ORDER BY sort_order ASC");
    while ($row = $result->fetch_assoc()) {
        $images[] = $row;
    }
}
header('Content-Type: application/json');
echo json_encode($images);
?>
