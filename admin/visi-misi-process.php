<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") { header("Location: login.php"); exit; }
include '../database/conn.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';
$type = $_POST['type'] ?? $_GET['type'] ?? '';

// === VISI ===
if ($type == 'visi' && $action == 'save') {
    $id = (int)($_POST['id'] ?? 0);
    $badge_text = $conn->real_escape_string(strip_tags(trim($_POST['badge_text'])));
    $badge_icon = $conn->real_escape_string(strip_tags(trim($_POST['badge_icon'])));
    $judul_section = $conn->real_escape_string(strip_tags(trim($_POST['judul_section'])));
    $deskripsi_section = $conn->real_escape_string(strip_tags(trim($_POST['deskripsi_section'])));
    $isi_visi = $conn->real_escape_string(strip_tags(trim($_POST['isi_visi'])));
    if (empty($isi_visi)) { header("Location: visi-misi.php?msg=error&text=Isi visi wajib diisi"); exit; }
    if ($id > 0) {
        $conn->query("UPDATE visi SET badge_text='$badge_text', badge_icon='$badge_icon', judul_section='$judul_section', deskripsi_section='$deskripsi_section', isi_visi='$isi_visi' WHERE id=$id");
    } else {
        $conn->query("INSERT INTO visi (badge_text,badge_icon,judul_section,deskripsi_section,isi_visi) VALUES ('$badge_text','$badge_icon','$judul_section','$deskripsi_section','$isi_visi')");
    }
    header("Location: visi-misi.php?msg=success&text=Data visi berhasil disimpan"); exit;
}

// === MISI ===
if ($type == 'misi' && $action == 'save') {
    $id = (int)($_POST['id'] ?? 0);
    $nomor = (int)$_POST['nomor'];
    $judul = $conn->real_escape_string(strip_tags(trim($_POST['judul'])));
    $deskripsi = $conn->real_escape_string(strip_tags(trim($_POST['deskripsi'])));
    $icon = $conn->real_escape_string(strip_tags(trim($_POST['icon'])));
    $icon_bg = $conn->real_escape_string(strip_tags(trim($_POST['icon_bg'])));
    $icon_color = $conn->real_escape_string(strip_tags(trim($_POST['icon_color'])));
    $sort_order = (int)$_POST['sort_order'];
    if (empty($judul) || empty($deskripsi)) { header("Location: visi-misi.php?msg=error&text=Judul dan deskripsi misi wajib diisi"); exit; }
    if ($id > 0) {
        $conn->query("UPDATE misi SET nomor=$nomor, judul='$judul', deskripsi='$deskripsi', icon='$icon', icon_bg='$icon_bg', icon_color='$icon_color', sort_order=$sort_order WHERE id=$id");
    } else {
        $conn->query("INSERT INTO misi (nomor,judul,deskripsi,icon,icon_bg,icon_color,sort_order) VALUES ($nomor,'$judul','$deskripsi','$icon','$icon_bg','$icon_color',$sort_order)");
    }
    header("Location: visi-misi.php?msg=success&text=Data misi berhasil disimpan"); exit;
}
if ($type == 'misi' && $action == 'delete') {
    $id = (int)($_GET['id'] ?? 0);
    if ($id > 0) $conn->query("DELETE FROM misi WHERE id=$id");
    header("Location: visi-misi.php?msg=success&text=Data misi berhasil dihapus"); exit;
}

// === MISI SECTION HEADER ===
if ($type == 'misi_section' && $action == 'save') {
    $badge_text = $conn->real_escape_string(strip_tags(trim($_POST['badge_text'])));
    $badge_icon = $conn->real_escape_string(strip_tags(trim($_POST['badge_icon'])));
    $judul_section = $conn->real_escape_string(strip_tags(trim($_POST['judul_section'])));
    $deskripsi_section = $conn->real_escape_string(strip_tags(trim($_POST['deskripsi_section'])));
    $conn->query("UPDATE misi SET badge_text='$badge_text', badge_icon='$badge_icon', judul_section='$judul_section', deskripsi_section='$deskripsi_section'");
    header("Location: visi-misi.php?msg=success&text=Header section misi berhasil diupdate"); exit;
}

// === 7K ===
if ($type == '7k' && $action == 'save') {
    $id = (int)($_POST['id'] ?? 0);
    $nomor = (int)$_POST['nomor'];
    $nama = $conn->real_escape_string(trim($_POST['nama']));
    $icon = $conn->real_escape_string(trim($_POST['icon']));
    $warna = $conn->real_escape_string(strip_tags(trim($_POST['warna'])));
    $sort_order = (int)$_POST['sort_order'];
    if (empty($nama)) { header("Location: visi-misi.php?msg=error&text=Nama program 7K wajib diisi"); exit; }
    if ($id > 0) {
        $conn->query("UPDATE program_7k SET nomor=$nomor, nama='$nama', icon='$icon', warna='$warna', sort_order=$sort_order WHERE id=$id");
    } else {
        $conn->query("INSERT INTO program_7k (nomor,nama,icon,warna,sort_order) VALUES ($nomor,'$nama','$icon','$warna',$sort_order)");
    }
    header("Location: visi-misi.php?msg=success&text=Data program 7K berhasil disimpan"); exit;
}
if ($type == '7k' && $action == 'delete') {
    $id = (int)($_GET['id'] ?? 0);
    if ($id > 0) $conn->query("DELETE FROM program_7k WHERE id=$id");
    header("Location: visi-misi.php?msg=success&text=Data program 7K berhasil dihapus"); exit;
}

// === 7K SECTION HEADER ===
if ($type == '7k_section' && $action == 'save') {
    $badge_text = $conn->real_escape_string(strip_tags(trim($_POST['badge_text'])));
    $badge_icon = $conn->real_escape_string(strip_tags(trim($_POST['badge_icon'])));
    $judul_section = $conn->real_escape_string(strip_tags(trim($_POST['judul_section'])));
    $deskripsi_section = $conn->real_escape_string(strip_tags(trim($_POST['deskripsi_section'])));
    $conn->query("UPDATE program_7k SET badge_text='$badge_text', badge_icon='$badge_icon', judul_section='$judul_section', deskripsi_section='$deskripsi_section'");
    header("Location: visi-misi.php?msg=success&text=Header section 7K berhasil diupdate"); exit;
}

header("Location: visi-misi.php");
exit;
?>
