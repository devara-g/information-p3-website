<?php
if (!isset($conn)) {
    $conn = new mysqli("localhost", "root", "", "p3p2");

    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }
}
?>