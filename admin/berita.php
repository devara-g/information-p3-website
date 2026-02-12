<?php
session_start();
include '../database/conn.php';

$total_berita = (int) mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM berita"))[0];

// PROSES EDIT - UPDATE DATA
if (isset($_POST['edit'])) {
    $id = (int) $_POST['id'];
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    $tanggal = mysqli_real_escape_string($conn, $_POST['tanggal']);
    $penulis = mysqli_real_escape_string($conn, $_POST['penulis']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);

    // Cek apakah upload file baru
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
        $file_type = $_FILES['foto']['type'];
        $file_size = $_FILES['foto']['size'];

        if (in_array($file_type, $allowed_types) && $file_size <= 5 * 1024 * 1024) {
            // Hapus foto lama
            $query_foto_lama = mysqli_query($conn, "SELECT foto FROM berita WHERE id = $id");
            $data_foto_lama = mysqli_fetch_assoc($query_foto_lama);
            if (!empty($data_foto_lama['foto'])) {
                $file_path = '../' . $data_foto_lama['foto'];
                if (file_exists($file_path)) {
                    unlink($file_path);
                }
            }

            // Upload foto baru
            $target_dir = '../upload/img/';
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            $extension = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
            $filename = time() . '_' . uniqid() . '.' . $extension;
            $upload_path = $target_dir . $filename;

            if (move_uploaded_file($_FILES['foto']['tmp_name'], $upload_path)) {
                $foto = 'upload/img/' . $filename;
                // Update dengan foto baru
                $sql = "UPDATE berita SET 
                        judul = '$judul', 
                        kategori = '$kategori', 
                        tanggal = '$tanggal', 
                        penulis = '$penulis', 
                        deskripsi = '$deskripsi', 
                        foto = '$foto' 
                        WHERE id = $id";
            } else {
                $error = "Gagal mengupload file";
            }
        } else {
            $error = "File tidak valid (max 5MB, format: JPG, PNG, GIF)";
        }
    } else {
        // Update tanpa foto
        $sql = "UPDATE berita SET 
                judul = '$judul', 
                kategori = '$kategori', 
                tanggal = '$tanggal', 
                penulis = '$penulis', 
                deskripsi = '$deskripsi' 
                WHERE id = $id";
    }

    if (!isset($error)) {
        if (mysqli_query($conn, $sql)) {
            header("Location: berita.php?edit=1");
            exit;
        } else {
            $error = "Gagal mengupdate berita: " . mysqli_error($conn);
        }
    }
}

// PROSES HAPUS - LANGSUNG DI SINI
if (isset($_GET['aksi']) && $_GET['aksi'] == 'hapus' && isset($_GET['id'])) {
    $id = (int) $_GET['id'];

    // Ambil data foto dulu untuk dihapus filenya
    $query_foto = mysqli_query($conn, "SELECT foto FROM berita WHERE id = $id");
    $data_foto = mysqli_fetch_assoc($query_foto);

    if ($data_foto) {
        // Hapus file foto jika ada
        if (!empty($data_foto['foto'])) {
            $file_path = '../' . $data_foto['foto'];
            if (file_exists($file_path)) {
                unlink($file_path); // Hapus file dari server
            }
        }

        // Hapus data dari database
        $sql = "DELETE FROM berita WHERE id = $id";
        if (mysqli_query($conn, $sql)) {
            $pesan = "Berita berhasil dihapus!";
            $pesan_type = "success";
        } else {
            $pesan = "Gagal menghapus berita: " . mysqli_error($conn);
            $pesan_type = "error";
        }
    }

    // Redirect ke halaman yang sama tanpa parameter GET
    header("Location: berita.php?hapus=" . ($pesan_type == 'success' ? '1' : '0'));
    exit;
}

if (isset($_POST['submit'])) {
    // Sanitasi input
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    $tanggal = mysqli_real_escape_string($conn, $_POST['tanggal']);
    $penulis = mysqli_real_escape_string($conn, $_POST['penulis']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);

    // Handle file upload
    $foto = null;
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
        $file_type = $_FILES['foto']['type'];
        $file_size = $_FILES['foto']['size'];

        // Validasi tipe file
        if (in_array($file_type, $allowed_types)) {
            // Validasi ukuran file (max 5MB)
            if ($file_size <= 5 * 1024 * 1024) {
                // Buat folder jika belum ada
                $target_dir = '../upload/img/';
                if (!file_exists($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }

                // Buat nama file unik
                $extension = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
                $filename = time() . '_' . uniqid() . '.' . $extension;
                $upload_path = $target_dir . $filename;

                if (move_uploaded_file($_FILES['foto']['tmp_name'], $upload_path)) {
                    // Simpan path relatif untuk database
                    $foto = 'upload/img/' . $filename;
                } else {
                    $error = "Gagal mengupload file";
                }
            } else {
                $error = "Ukuran file maksimal 5MB";
            }
        } else {
            $error = "Tipe file harus JPG, PNG, atau GIF";
        }
    }

    if (!isset($error)) {
        // Query INSERT sesuai struktur tabel
        $sql = "INSERT INTO berita (judul, kategori, tanggal, penulis, deskripsi, foto) 
                VALUES ('$judul', '$kategori', '$tanggal', '$penulis', '$deskripsi', '$foto')";

        if (mysqli_query($conn, $sql)) {
            header("Location: berita.php?success=1");
            exit;
        } else {
            $error = "Gagal menambahkan berita: " . mysqli_error($conn);
        }
    }
}

// Ambil data untuk edit jika ada parameter edit
$edit_data = null;
if (isset($_GET['edit_id'])) {
    $edit_id = (int) $_GET['edit_id'];
    $query_edit = mysqli_query($conn, "SELECT * FROM berita WHERE id = $edit_id");
    $edit_data = mysqli_fetch_assoc($query_edit);
}
?>
<?php
$title = "Manajemen Berita";
include 'layout/header.php';
?>

<style>
    /* Internal CSS for Berita Management */
    .berita-container {
        padding: 20px;
    }

    .header-panel {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        margin-bottom: 25px;
    }

    .header-panel h1 {
        font-size: 1.8rem;
        color: var(--primary);
        margin: 0;
    }

    .btn-add {
        background: var(--primary);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
        transition: 0.3s;
    }

    .btn-add:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
    }

    .table-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .table-header {
        padding: 20px;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .table-header h3 {
        margin: 0;
        color: var(--dark);
    }

    .search-input {
        padding: 8px 15px;
        border: 1px solid #ddd;
        border-radius: 8px;
        width: 250px;
        outline: none;
    }

    .search-input:focus {
        border-color: var(--primary);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        min-width: 800px;
    }

    th {
        background: #f8fafc;
        padding: 15px 20px;
        text-align: left;
        color: var(--gray);
        font-weight: 600;
        font-size: 0.9rem;
    }

    td {
        padding: 15px 20px;
        border-bottom: 1px solid #f1f5f9;
        color: var(--dark);
        font-size: 0.95rem;
    }

    .news-thumb {
        width: 80px;
        height: 60px;
        border-radius: 6px;
        object-fit: cover;
        border: 1px solid #eee;
    }

    .no-image {
        width: 80px;
        height: 60px;
        background: #f5f5f5;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #999;
        font-size: 12px;
        border: 1px dashed #ddd;
    }

    .badge {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
    }

    .badge-info {
        background: #e0f2fe;
        color: #0369a1;
    }

    .badge-success {
        background: #dcfce7;
        color: #15803d;
    }

    .badge-warning {
        background: #fff3cd;
        color: #856404;
    }

    .action-btns {
        display: flex;
        gap: 8px;
    }

    .btn-action {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        transition: 0.2s;
    }

    .btn-edit {
        background: #fef3c7;
        color: #d97706;
    }

    .btn-delete {
        background: #fee2e2;
        color: #dc2626;
    }

    .btn-action:hover {
        transform: scale(1.1);
    }

    /* Alert Styling */
    .alert-msg {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 18px 24px;
        border-radius: 14px;
        margin-bottom: 20px;
        font-weight: 600;
        font-size: 0.95rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        animation: alertSlideIn 0.4s ease-out;
        border-left: 4px solid;
    }

    .alert-msg i {
        font-size: 1.3rem;
        flex-shrink: 0;
    }

    .alert-success {
        background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
        color: #15803d;
        border-left-color: #22c55e;
    }

    .alert-error {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        color: #dc2626;
        border-left-color: #ef4444;
    }

    @keyframes alertSlideIn {
        from {
            opacity: 0;
            transform: translateY(-12px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Form Section */
    .form-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(11, 45, 114, 0.4);
        backdrop-filter: blur(10px);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 2000;
        padding: 20px;
        overflow-y: auto;
    }

    .form-card {
        background: white;
        width: 100%;
        max-width: 700px;
        border-radius: 20px;
        padding: 40px;
        position: relative;
        box-shadow: 0 25px 70px rgba(0, 0, 0, 0.15);
        animation: formSlideUp 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
        max-height: 90vh;
        overflow-y: auto;
    }

    @keyframes formSlideUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .form-card h2 {
        margin-bottom: 25px;
        color: var(--primary);
        font-weight: 800;
        font-size: 1.8rem;
        border-bottom: 3px solid var(--accent);
        display: inline-block;
        padding-bottom: 5px;
    }

    .close-form {
        position: absolute;
        top: 20px;
        right: 20px;
        font-size: 1.5rem;
        cursor: pointer;
        color: #999;
        transition: 0.3s;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #f5f5f5;
    }

    .close-form:hover {
        color: var(--primary);
        background: #e0e0e0;
        transform: rotate(90deg);
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: var(--dark);
        font-size: 0.95rem;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        outline: none;
        transition: all 0.3s ease;
        background: #f8fafc;
        font-size: 0.95rem;
        font-family: inherit;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        border-color: #0992c2;
        background: white;
        box-shadow: 0 0 0 4px rgba(9, 146, 194, 0.1);
    }

    /* File Upload Styling */
    .file-upload-wrapper {
        position: relative;
        width: 100%;
    }

    .file-upload-input {
        position: absolute;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
        z-index: 2;
    }

    .file-upload-label {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 25px;
        background: #f8fafc;
        border: 2px dashed #cbd5e0;
        border-radius: 12px;
        color: #64748b;
        transition: all 0.3s ease;
        text-align: center;
    }

    .file-upload-label i {
        font-size: 2rem;
        margin-bottom: 10px;
        color: var(--primary);
    }

    .file-upload-label:hover {
        border-color: var(--primary);
        background: #f1f5f9;
    }

    .file-name {
        margin-top: 10px;
        font-size: 0.85rem;
        color: var(--primary);
        font-weight: 500;
        word-break: break-all;
    }

    .btn-submit {
        background: linear-gradient(135deg, #0b2d72 0%, #0992c2 100%);
        color: white;
        border: none;
        padding: 14px;
        width: 100%;
        border-radius: 12px;
        font-weight: 700;
        font-size: 1rem;
        cursor: pointer;
        margin-top: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        box-shadow: 0 10px 25px rgba(11, 45, 114, 0.2);
        transition: all 0.3s ease;
        letter-spacing: 0.5px;
    }

    .btn-submit:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 35px rgba(11, 45, 114, 0.3);
        filter: brightness(1.1);
    }

    .btn-submit:active {
        transform: translateY(-1px);
    }

    .grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    /* Delete Confirmation Modal */
    .delete-confirm-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(8px);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 10000;
        padding: 20px;
        overflow-y: auto;
    }

    .delete-confirm-modal {
        background: white;
        border-radius: 20px;
        padding: 32px;
        max-width: 420px;
        width: 100%;
        text-align: center;
        box-shadow: 0 25px 70px rgba(0, 0, 0, 0.2);
        animation: alertPop 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    .delete-confirm-icon {
        width: 72px;
        height: 72px;
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        color: #ef4444;
        font-size: 2rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        border: 3px solid rgba(239, 68, 68, 0.2);
    }

    .delete-confirm-modal h3 {
        font-size: 1.25rem;
        color: var(--primary);
        margin: 0 0 10px;
        font-weight: 700;
    }

    .delete-confirm-modal p {
        color: #64748b;
        font-size: 0.95rem;
        margin: 0 0 28px;
        line-height: 1.5;
    }

    .delete-confirm-btns {
        display: flex;
        gap: 12px;
        justify-content: center;
        flex-wrap: wrap;
    }

    .delete-confirm-btns .btn-cancel-delete {
        padding: 12px 24px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.95rem;
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid #e2e8f0;
        background: white;
        color: #64748b;
    }

    .delete-confirm-btns .btn-cancel-delete:hover {
        background: #f1f5f9;
        border-color: #cbd5e1;
        color: #475569;
    }

    .delete-confirm-btns .btn-confirm-delete {
        padding: 12px 24px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.95rem;
        cursor: pointer;
        transition: all 0.3s ease;
        border: none;
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        box-shadow: 0 8px 20px rgba(239, 68, 68, 0.35);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .delete-confirm-btns .btn-confirm-delete:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 28px rgba(239, 68, 68, 0.45);
        color: white;
    }

    /* Preview Gambar */
    .image-preview {
        margin-top: 15px;
        display: none;
        border-radius: 10px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        max-width: 200px;
    }

    .image-preview img {
        width: 100%;
        height: auto;
        display: block;
    }

    @media (max-width: 768px) {
        .grid-2 {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .form-overlay,
        .delete-confirm-overlay {
            align-items: flex-start;
            padding-top: 40px;
            padding-bottom: 40px;
        }

        .form-card {
            width: 95%;
            padding: 25px 20px;
            max-height: none;
            margin-bottom: 20px;
        }

        .form-card h2 {
            font-size: 1.5rem;
            margin-bottom: 20px;
        }

        .alert-msg {
            padding: 14px 18px;
            font-size: 0.85rem;
            gap: 10px;
        }
    }
</style>

<div class="berita-container">
    <?php if (isset($_GET['success'])): ?>
        <div class="alert-msg alert-success">
            <i class="fas fa-check-circle"></i>
            <span>Berita berhasil ditambahkan!</span>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['edit']) && $_GET['edit'] == '1'): ?>
        <div class="alert-msg alert-success">
            <i class="fas fa-check-circle"></i>
            <span>Berita berhasil diperbarui!</span>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['hapus']) && $_GET['hapus'] == '1'): ?>
        <div class="alert-msg alert-success">
            <i class="fas fa-check-circle"></i>
            <span>Berita berhasil dihapus!</span>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['hapus']) && $_GET['hapus'] == '0'): ?>
        <div class="alert-msg alert-error">
            <i class="fas fa-exclamation-circle"></i>
            <span>Gagal menghapus berita!</span>
        </div>
    <?php endif; ?>

    <?php if (isset($error)): ?>
        <div class="alert-msg alert-error">
            <i class="fas fa-exclamation-circle"></i>
            <span><?= $error ?></span>
        </div>
    <?php endif; ?>

    <div class="header-panel stagger-item stagger-1">
        <div>
            <h1>Manajemen Berita</h1>
            <p style="color: var(--gray);">Tampilkan dan kelola semua artikel berita sekolah.</p>
        </div>
        <button class="btn-add" onclick="toggleForm('add')">
            <i class="fas fa-plus"></i> Tambah Berita
        </button>
    </div>

    <!-- Output Table -->
    <div class="table-card stagger-item stagger-2">
        <div class="table-header">
            <h3>Daftar Berita</h3>
            <div style="display: flex; gap: 10px;">
                <input type="text" class="search-input" id="searchInput" placeholder="Cari berita...">
                <button onclick="resetSearch()" class="btn-action" style="background: #f1f5f9; color: #64748b; width: auto; padding: 0 15px;">
                    <i class="fas fa-times"></i> Reset
                </button>
            </div>
        </div>
        <div style="overflow-x: auto;">
            <table id="newsTable">
                <thead>
                    <tr>
                        <th>Gambar</th>
                        <th>Judul Berita</th>
                        <th>Kategori</th>
                        <th>Tanggal</th>
                        <th>Penulis</th>
                        <th style="text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="newsTableBody">
                    <?php
                    $sql = "SELECT * FROM berita ORDER BY tanggal DESC";
                    $result = mysqli_query($conn, $sql);
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {

                            // Set badge color based on category
                            $badge_class = 'badge-info';
                            if (strtolower($row['kategori']) == 'prestasi') {
                                $badge_class = 'badge-success';
                            } elseif (strtolower($row['kategori']) == 'pengumuman') {
                                $badge_class = 'badge-warning';
                            }
                    ?>
                            <tr>
                                <td>
                                    <?php if ($row['foto'] && file_exists('../' . $row['foto'])): ?>
                                        <img src="../<?= htmlspecialchars($row['foto']) ?>" class="news-thumb" alt="Thumbnail">
                                    <?php else: ?>
                                        <div class="no-image">
                                            <i class="fas fa-image"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td style="font-weight: 500;"><?= htmlspecialchars($row['judul']) ?></td>
                                <td><span class="badge <?= $badge_class ?>"><?= htmlspecialchars($row['kategori']) ?></span></td>
                                <td><?= date('d/m/Y', strtotime($row['tanggal'])) ?></td>
                                <td><?= htmlspecialchars($row['penulis']) ?></td>
                                <td>
                                    <div class="action-btns">
                                        <button class="btn-action btn-edit" onclick="editNews(<?= $row['id'] ?>)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn-action btn-delete" onclick="showDeleteAlert(<?= $row['id'] ?>, '<?= htmlspecialchars(addslashes($row['judul'])) ?>')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php
                        }
                    } else {
                        ?>
                        <tr id="noDataRow">
                            <td colspan="6" style="text-align: center; padding: 40px;">
                                <i class="fas fa-newspaper" style="font-size: 48px; color: #ccc; margin-bottom: 15px;"></i>
                                <p style="color: #999; font-size: 1.1rem;">Belum ada berita</p>
                                <button onclick="toggleForm('add')" style="background: var(--primary); color: white; border: none; padding: 10px 20px; border-radius: 8px; margin-top: 10px; cursor: pointer;">
                                    <i class="fas fa-plus"></i> Tambah Berita Sekarang
                                </button>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Form for Add/Edit -->
<div class="form-overlay" id="formOverlay">
    <div class="form-card">
        <i class="fas fa-times close-form" onclick="toggleForm('close')"></i>
        <h2 id="formTitle">Tambah Berita Baru</h2>
        <form id="beritaForm" action="berita.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" id="beritaId">

            <div class="form-group">
                <label><i class="fas fa-heading"></i> Judul Berita</label>
                <input type="text" name="judul" id="judul" required placeholder="Masukkan judul artikel">
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label><i class="fas fa-tag"></i> Kategori</label>
                    <select name="kategori" id="kategori" required>
                        <option value="">Pilih Kategori</option>
                        <option value="kegiatan">Kegiatan</option>
                        <option value="pengumuman">Pengumuman</option>
                        <option value="prestasi">Prestasi</option>
                    </select>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-calendar"></i> Tanggal</label>
                    <input type="date" name="tanggal" id="tanggal" value="<?= date('Y-m-d') ?>" required>
                </div>
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label><i class="fas fa-user"></i> Penulis</label>
                    <input type="text" name="penulis" id="penulis" required placeholder="Nama penulis">
                </div>

                <div class="form-group">
                    <label><i class="fas fa-image"></i> Upload Gambar</label>
                    <div class="file-upload-wrapper">
                        <input type="file" name="foto" class="file-upload-input" id="fotoInput" accept="image/*">
                        <div class="file-upload-label">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <span>Klik atau seret gambar ke sini</span>
                            <span class="file-name" id="fileName"></span>
                            <small style="margin-top: 8px; color: #666;">Format: JPG, PNG, GIF (Max. 5MB)</small>
                        </div>
                    </div>
                    <div class="image-preview" id="imagePreview">
                        <img src="" alt="Preview">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label><i class="fas fa-align-left"></i> Isi Berita</label>
                <textarea name="deskripsi" id="deskripsi" rows="8" required placeholder="Tulis isi berita di sini..."></textarea>
            </div>

            <div id="formActions">
                <button type="submit" name="submit" id="submitBtn" class="btn-submit">
                    <i class="fas fa-save"></i> Simpan & Publikasikan Berita
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="delete-confirm-overlay" id="deleteAlertOverlay" onclick="if(event.target===this) hideDeleteAlert()">
    <div class="delete-confirm-modal" onclick="event.stopPropagation()">
        <div class="delete-confirm-icon">
            <i class="fas fa-trash-alt"></i>
        </div>
        <h3>Hapus Berita?</h3>
        <p id="deleteAlertMessage">Yakin ingin menghapus berita "<span id="deleteNewsTitle"></span>"? Tindakan ini tidak dapat dibatalkan.</p>
        <div class="delete-confirm-btns">
            <button type="button" class="btn-cancel-delete" onclick="hideDeleteAlert()">Batal</button>
            <a href="#" class="btn-confirm-delete" id="confirmDeleteBtn">
                <i class="fas fa-trash"></i> Hapus
            </a>
        </div>
    </div>
</div>

<script>
    // State untuk menyimpan ID yang akan dihapus
    let deleteId = null;

    // Toggle Form Modal
    function toggleForm(type, data = null) {
        const overlay = document.getElementById('formOverlay');
        const form = document.getElementById('beritaForm');
        const title = document.getElementById('formTitle');
        const submitBtn = document.getElementById('submitBtn');

        if (type === 'close' || (type !== 'add' && type !== 'edit' && overlay.style.display === 'flex')) {
            overlay.style.display = 'none';
            document.body.style.overflow = ''; // Unlock background scroll
            form.reset();
            const preview = document.getElementById('imagePreview');
            if (preview) preview.style.display = 'none';
            const fileName = document.getElementById('fileName');
            if (fileName) fileName.innerHTML = '';

            // If it was an edit from URL, clear it
            if (window.location.search.includes('edit_id')) {
                window.history.replaceState({}, document.title, window.location.pathname);
            }
            return;
        }

        overlay.style.display = 'flex';
        document.body.style.overflow = 'hidden'; // Lock background scroll

        if (type === 'add') {
            title.innerHTML = 'Tambah Berita Baru';
            form.action = 'berita.php';
            submitBtn.name = 'submit';
            submitBtn.innerHTML = '<i class="fas fa-save"></i> Simpan & Publikasikan Berita';
            form.reset();
            document.getElementById('beritaId').value = '';
            const preview = document.getElementById('imagePreview');
            if (preview) preview.style.display = 'none';
        } else if (type === 'edit') {
            title.innerHTML = 'Edit Berita';
            form.action = 'berita.php';
            submitBtn.name = 'edit';
            submitBtn.innerHTML = '<i class="fas fa-edit"></i> Update Berita';

            // Isi form dengan data yang ada
            if (data) {
                document.getElementById('beritaId').value = data.id || '';
                document.getElementById('judul').value = data.judul || '';
                document.getElementById('kategori').value = data.kategori || '';
                document.getElementById('tanggal').value = data.tanggal || '';
                document.getElementById('penulis').value = data.penulis || '';
                document.getElementById('deskripsi').value = data.deskripsi || '';

                // Tampilkan preview gambar jika ada
                if (data.foto) {
                    const preview = document.getElementById('imagePreview');
                    const previewImg = preview.querySelector('img');
                    previewImg.src = '../' + data.foto;
                    preview.style.display = 'block';
                }
            }
        }
    }

    // Scroll hint for mobile
    document.addEventListener('DOMContentLoaded', function() {
        const tableWrapper = document.querySelector('.table-card > div[style*="overflow-x: auto"]');
        if (tableWrapper && window.innerWidth < 768) {
            const hint = document.createElement('div');
            hint.className = 'mobile-scroll-hint';
            hint.style.cssText = 'text-align: center; padding: 10px; color: #94a3b8; font-size: 0.75rem; border-top: 1px solid #f1f5f9; display: none;';
            hint.innerHTML = '<i class="fas fa-arrows-alt-h"></i> Geser tabel untuk melihat detail';
            tableWrapper.parentNode.appendChild(hint);

            if (tableWrapper.scrollWidth > tableWrapper.clientWidth) {
                hint.style.display = 'block';
            }
        }
    });

    // Edit news - Fetch data via AJAX
    function editNews(id) {
        // Redirect ke halaman yang sama dengan parameter edit_id
        window.location.href = 'berita.php?edit_id=' + id;
    }

    // Show custom delete alert
    function showDeleteAlert(id, judul) {
        deleteId = id;
        document.getElementById('deleteNewsTitle').textContent = judul;
        document.getElementById('deleteAlertOverlay').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    // Hide delete alert
    function hideDeleteAlert() {
        document.getElementById('deleteAlertOverlay').style.display = 'none';
        document.body.style.overflow = '';
        deleteId = null;
    }

    // Confirm delete
    document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
        if (deleteId) {
            window.location.href = 'berita.php?aksi=hapus&id=' + deleteId;
        }
    });

    // Show file name when selected
    document.getElementById('fotoInput')?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        const fileName = file?.name;
        document.getElementById('fileName').innerHTML = fileName || '';

        // Preview image
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('imagePreview');
                const previewImg = preview.querySelector('img');
                previewImg.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(file);
        }
    });

    // Search functionality - REAL TIME
    document.getElementById('searchInput')?.addEventListener('keyup', function() {
        const searchValue = this.value.toLowerCase().trim();
        const tableRows = document.querySelectorAll('#newsTable tbody tr');
        let visibleCount = 0;

        tableRows.forEach(row => {
            // Skip row "Belum ada berita"
            if (row.id === 'noDataRow') return;

            const judul = row.cells[1]?.textContent.toLowerCase() || '';
            const kategori = row.cells[2]?.textContent.toLowerCase() || '';
            const penulis = row.cells[4]?.textContent.toLowerCase() || '';

            if (judul.includes(searchValue) || kategori.includes(searchValue) || penulis.includes(searchValue)) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        // Tampilkan pesan jika tidak ada hasil
        const noDataRow = document.getElementById('noDataRow');
        if (noDataRow) {
            if (visibleCount === 0 && tableRows.length > 1) {
                if (!noDataRow) {
                    const tbody = document.getElementById('newsTableBody');
                    const newRow = document.createElement('tr');
                    newRow.id = 'noDataRow';
                    newRow.innerHTML = '<td colspan="6" style="text-align: center; padding: 40px;">' +
                        '<i class="fas fa-search" style="font-size: 48px; color: #ccc; margin-bottom: 15px;"></i>' +
                        '<p style="color: #999; font-size: 1.1rem;">Tidak ada berita yang ditemukan</p>' +
                        '</td>';
                    tbody.appendChild(newRow);
                }
            } else {
                if (noDataRow && visibleCount > 0) {
                    noDataRow.remove();
                }
            }
        }
    });

    // Reset search
    function resetSearch() {
        const searchInput = document.getElementById('searchInput');
        searchInput.value = '';
        searchInput.dispatchEvent(new Event('keyup'));
    }

    // Auto-hide alert after 3 seconds
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert-msg');
        alerts.forEach(alert => {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        });
    }, 3000);

    // Show form if there are validation errors
    <?php if (isset($error)): ?>
        toggleForm('add');
    <?php endif; ?>

    // Show edit form if edit_id is set
    <?php if ($edit_data): ?>
        window.onload = function() {
            toggleForm('edit', <?= json_encode($edit_data) ?>);
        }
    <?php endif; ?>
</script>

<?php include 'layout/footer.php'; ?>