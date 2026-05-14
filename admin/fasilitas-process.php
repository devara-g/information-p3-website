<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") { header("Location: login.php"); exit; }
include '../database/conn.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';

// ================================================================
// CREATE / UPDATE FASILITAS
// ================================================================
if ($action == 'save') {
    $id          = (int)($_POST['id'] ?? 0);
    $nama        = $conn->real_escape_string(strip_tags(trim($_POST['nama'] ?? '')));
    $deskripsi   = $conn->real_escape_string(strip_tags(trim($_POST['deskripsi'] ?? '')));
    $icon        = $conn->real_escape_string(strip_tags(trim($_POST['icon'] ?? 'fa-building')));
    $color       = $conn->real_escape_string(strip_tags(trim($_POST['color'] ?? '#3b82f6')));
    $tag         = $conn->real_escape_string(strip_tags(trim($_POST['tag'] ?? '')));
    $sort_order  = (int)($_POST['sort_order'] ?? 0);
    $is_active   = isset($_POST['is_active']) ? 1 : 0;

    // Validasi
    if (empty($nama) || empty($deskripsi)) {
        header("Location: fasilitas.php?msg=error&text=" . urlencode("Nama dan deskripsi fasilitas wajib diisi"));
        exit;
    }

    if ($id > 0) {
        // UPDATE
        $conn->query("UPDATE fasilitas SET
            nama = '$nama',
            deskripsi = '$deskripsi',
            icon = '$icon',
            color = '$color',
            tag = '$tag',
            sort_order = $sort_order,
            is_active = $is_active
            WHERE id = $id");
    } else {
        // INSERT
        $conn->query("INSERT INTO fasilitas (nama, deskripsi, icon, color, tag, sort_order, is_active)
            VALUES ('$nama', '$deskripsi', '$icon', '$color', '$tag', $sort_order, $is_active)");
        $id = $conn->insert_id;
    }

    // Handle multiple image uploads
    if (isset($_FILES['images']) && $id > 0) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
        $max_size = 3 * 1024 * 1024; // 3MB
        $upload_dir = '../upload/img/fasilitas/';

        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $file_count = count($_FILES['images']['name']);
        for ($i = 0; $i < $file_count; $i++) {
            if ($_FILES['images']['error'][$i] == 0) {
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime_type = finfo_file($finfo, $_FILES['images']['tmp_name'][$i]);
                finfo_close($finfo);

                if (!in_array($mime_type, $allowed_types)) continue;
                if ($_FILES['images']['size'][$i] > $max_size) continue;

                $ext = strtolower(pathinfo($_FILES['images']['name'][$i], PATHINFO_EXTENSION));
                $filename = 'fasilitas_' . $id . '_' . uniqid() . '.' . $ext;
                $target = $upload_dir . $filename;

                if (move_uploaded_file($_FILES['images']['tmp_name'][$i], $target)) {
                    // Get max sort order for this facility
                    $maxSort = $conn->query("SELECT COALESCE(MAX(sort_order), 0) as max_sort FROM fasilitas_images WHERE fasilitas_id = $id")->fetch_assoc()['max_sort'];
                    $newSort = $maxSort + 1;
                    $conn->query("INSERT INTO fasilitas_images (fasilitas_id, filename, sort_order) VALUES ($id, '$filename', $newSort)");
                }
            }
        }
    }

    header("Location: fasilitas.php?msg=success&text=" . urlencode("Data fasilitas berhasil disimpan"));
    exit;
}

// ================================================================
// DELETE FASILITAS
// ================================================================
if ($action == 'delete') {
    $id = (int)($_GET['id'] ?? 0);
    if ($id > 0) {
        // Delete all associated images from disk
        $images = $conn->query("SELECT filename FROM fasilitas_images WHERE fasilitas_id = $id");
        while ($img = $images->fetch_assoc()) {
            $path = '../upload/img/fasilitas/' . $img['filename'];
            if (file_exists($path)) unlink($path);
        }
        // Delete images from DB
        $conn->query("DELETE FROM fasilitas_images WHERE fasilitas_id = $id");
        // Delete facility
        $conn->query("DELETE FROM fasilitas WHERE id = $id");
    }
    header("Location: fasilitas.php?msg=success&text=" . urlencode("Fasilitas berhasil dihapus"));
    exit;
}

// ================================================================
// DELETE SINGLE IMAGE
// ================================================================
if ($action == 'delete_image') {
    $img_id = (int)($_GET['img_id'] ?? 0);
    $fas_id = (int)($_GET['fas_id'] ?? 0);
    if ($img_id > 0) {
        $imgData = $conn->query("SELECT filename FROM fasilitas_images WHERE id = $img_id")->fetch_assoc();
        if ($imgData) {
            $path = '../upload/img/fasilitas/' . $imgData['filename'];
            if (file_exists($path)) unlink($path);
            $conn->query("DELETE FROM fasilitas_images WHERE id = $img_id");
        }
    }
    header("Location: fasilitas.php?msg=success&text=" . urlencode("Gambar berhasil dihapus"));
    exit;
}

// ================================================================
// TOGGLE STATUS (AJAX-friendly)
// ================================================================
if ($action == 'toggle_status') {
    $id = (int)($_GET['id'] ?? 0);
    if ($id > 0) {
        $conn->query("UPDATE fasilitas SET is_active = NOT is_active WHERE id = $id");
    }
    header("Location: fasilitas.php?msg=success&text=" . urlencode("Status fasilitas berhasil diubah"));
    exit;
}

header("Location: fasilitas.php");
exit;
?>
