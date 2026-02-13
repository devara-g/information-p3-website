<?php
include "../database/conn.php";

// ============ PERBAIKAN 1: Proses Hapus dengan Prepared Statement ============
// Memperbaiki query hapus yang sebelumnya tidak konsisten dan menambahkan redirect
if (isset($_GET['aksi']) && $_GET['aksi'] == 'hapus' && isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    if ($id > 0) {
        // Hapus foto jika ada
        $query_foto = mysqli_query($conn, "SELECT foto FROM agenda WHERE id = $id");
        $data_foto = mysqli_fetch_assoc($query_foto);
        if (!empty($data_foto['foto'])) {
            $file_path = '../' . $data_foto['foto'];
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }

        // Menggunakan prepared statement untuk keamanan
        $stmt = mysqli_prepare($conn, "DELETE FROM agenda WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);

        if (mysqli_stmt_execute($stmt)) {
            // Redirect dengan parameter success
            header("Location: agenda.php?status=success&message=Agenda berhasil dihapus");
            exit();
        } else {
            header("Location: agenda.php?status=error&message=Gagal menghapus agenda");
            exit();
        }
        mysqli_stmt_close($stmt);
    }
}

// ============ PERBAIKAN 2: Proses Tambah Data dengan Prepared Statement ============
// Menambahkan handler untuk form tambah agenda
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['aksi']) && $_POST['aksi'] == 'tambah') {
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $tanggal = mysqli_real_escape_string($conn, $_POST['tanggal']);
    $waktu = mysqli_real_escape_string($conn, $_POST['waktu']);
    $lokasi = mysqli_real_escape_string($conn, $_POST['lokasi']);
    $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);
    $status = 'akan datang'; // Default status

    // Handle file upload
    $foto = null;
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
        $file_type = $_FILES['foto']['type'];
        $file_size = $_FILES['foto']['size'];

        if (in_array($file_type, $allowed_types) && $file_size <= 5 * 1024 * 1024) {
            $target_dir = '../upload/img/';
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            $extension = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
            $filename = 'agenda_' . time() . '_' . uniqid() . '.' . $extension;
            $upload_path = $target_dir . $filename;

            if (move_uploaded_file($_FILES['foto']['tmp_name'], $upload_path)) {
                $foto = 'upload/img/' . $filename;
            }
        }
    }

    // Prepared statement untuk INSERT
    $stmt = mysqli_prepare($conn, "INSERT INTO agenda (judul, tanggal, waktu, lokasi, deskripsi, status, foto) VALUES (?, ?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sssssss", $judul, $tanggal, $waktu, $lokasi, $keterangan, $status, $foto);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: agenda.php?status=success&message=Agenda berhasil ditambahkan");
        exit();
    } else {
        $error_message = "Gagal menambahkan agenda: " . mysqli_error($conn);
    }
    mysqli_stmt_close($stmt);
}

// ============ PERBAIKAN 3: Proses Edit Data dengan Prepared Statement ============
// Menambahkan handler untuk form edit agenda
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['aksi']) && $_POST['aksi'] == 'edit') {
    $id = (int) $_POST['id'];
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $tanggal = mysqli_real_escape_string($conn, $_POST['tanggal']);
    $waktu = mysqli_real_escape_string($conn, $_POST['waktu']);
    $lokasi = mysqli_real_escape_string($conn, $_POST['lokasi']);
    $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']); // Fix field name from deskripsi
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    // Handle file upload
    $sql_update = "UPDATE agenda SET judul=?, tanggal=?, waktu=?, lokasi=?, deskripsi=?, status=? WHERE id=?";
    $bind_types = "ssssssi";
    $bind_params = [$judul, $tanggal, $waktu, $lokasi, $keterangan, $status, $id];

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
        $file_type = $_FILES['foto']['type'];
        $file_size = $_FILES['foto']['size'];

        if (in_array($file_type, $allowed_types) && $file_size <= 5 * 1024 * 1024) {
            // Hapus foto lama
            $query_foto_lama = mysqli_query($conn, "SELECT foto FROM agenda WHERE id = $id");
            $data_foto_lama = mysqli_fetch_assoc($query_foto_lama);
            if (!empty($data_foto_lama['foto'])) {
                $file_path = '../' . $data_foto_lama['foto'];
                if (file_exists($file_path)) {
                    unlink($file_path);
                }
            }

            $target_dir = '../upload/img/';
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            $extension = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
            $filename = 'agenda_' . time() . '_' . uniqid() . '.' . $extension;
            $upload_path = $target_dir . $filename;

            if (move_uploaded_file($_FILES['foto']['tmp_name'], $upload_path)) {
                $foto_new = 'upload/img/' . $filename;
                $sql_update = "UPDATE agenda SET judul=?, tanggal=?, waktu=?, lokasi=?, deskripsi=?,    status=?, foto=? WHERE id=?";
                $bind_types = "sssssssi";
                $bind_params = [$judul, $tanggal, $waktu, $lokasi, $keterangan, $status, $foto_new, $id];
            }
        }
    }

    // Prepared statement untuk UPDATE
    $stmt = mysqli_prepare($conn, $sql_update);
    mysqli_stmt_bind_param($stmt, $bind_types, ...$bind_params);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: agenda.php?status=success&message=Agenda berhasil diperbarui");
        exit();
    } else {
        $error_message = "Gagal memperbarui agenda: " . mysqli_error($conn);
    }
    mysqli_stmt_close($stmt);
}

// ============ PERBAIKAN 4: Fungsi untuk Mendapatkan Data Agenda ============
// Menambahkan fungsi untuk mengambil data agenda berdasarkan ID (untuk edit)
function getAgendaById($conn, $id)
{
    $stmt = mysqli_prepare($conn, "SELECT * FROM agenda WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($result);
}
?>

<?php
$title = "Manajemen Agenda";
include 'layout/header.php';
?>

<style>
    /* Internal CSS for Agenda Management */
    /* Custom Scrollbar for Main Page & Content */
    html {
        overflow-y: scroll;
        scrollbar-gutter: stable;
    }

    ::-webkit-scrollbar {
        width: 12px;
        height: 10px;
    }

    ::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb {
        background-color: #94a3b8;
        border-radius: 20px;
        border: 3px solid #f1f5f9;
        /* Makes the thumb look thinner */
        transition: 0.3s;
    }

    ::-webkit-scrollbar-thumb:hover {
        background-color: #64748b;
    }

    /* Target the table horizontal scroll specifically */
    .table-card div::-webkit-scrollbar {
        height: 8px;
    }

    /* Distinct scrollbar for Form Body to differentiate from page scroll */
    .form-body::-webkit-scrollbar {
        width: 8px;
    }

    .form-body::-webkit-scrollbar-track {
        background: #f8fafc;
    }

    .form-body::-webkit-scrollbar-thumb {
        background-color: #cbd5e0;
        border-radius: 10px;
        border: 2px solid #f8fafc;
    }

    .agenda-container {
        padding: 20px;
        min-height: calc(100vh - 100px);
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

    /* ============ PERBAIKAN 6: Style untuk Search Box ============ */
    .search-input {
        padding: 8px 15px;
        border: 1px solid #ddd;
        border-radius: 8px;
        width: 250px;
        outline: none;
        transition: all 0.3s;
    }

    .search-input:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(11, 45, 114, 0.1);
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

    /* ============ PERBAIKAN 7: Style untuk Badge Status ============ */
    .badge {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
    }

    .badge-upcoming {
        background: #dcfce7;
        color: #15803d;
    }

    .badge-today {
        background: #fef3c7;
        color: #d97706;
    }

    .badge-completed {
        background: #e0f2fe;
        color: #0369a1;
    }

    .badge-cancelled {
        background: #fee2e2;
        color: #b91c1c;
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

    /* Additional styles for image upload */
    .file-upload-wrapper {
        position: relative;
        width: 100%;
        margin-top: 10px;
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

    .agenda-thumb {
        width: 60px;
        height: 45px;
        border-radius: 6px;
        object-fit: cover;
        border: 1px solid #eee;
    }

    .no-thumb {
        width: 60px;
        height: 45px;
        background: #f1f5f9;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
        font-size: 0.7rem;
        border: 1px dashed #cbd5e0;
    }

    /* ============ PERBAIKAN 8: Style untuk Modal Form ============ */
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
    }

    .form-card {
        background: white;
        width: 100%;
        max-width: 520px;
        border-radius: 20px;
        padding: 0;
        position: relative;
        box-shadow: 0 25px 70px rgba(0, 0, 0, 0.15);
        animation: modalBounce 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
        max-height: 90vh;
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }

    @keyframes modalBounce {
        from {
            opacity: 0;
            transform: scale(0.9) translateY(30px);
        }

        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }

    .form-header {
        padding: 25px 30px;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .form-header h2 {
        margin: 0;
        color: var(--primary);
        font-weight: 800;
        font-size: 1.4rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .form-header h2 i {
        width: 38px;
        height: 38px;
        background: rgba(11, 45, 114, 0.1);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
    }

    .close-form {
        font-size: 1.2rem;
        cursor: pointer;
        color: #999;
        transition: 0.3s;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: white;
        border: 1px solid #e2e8f0;
    }

    .close-form:hover {
        color: #ef4444;
        transform: rotate(90deg);
        background: #fee2e2;
        border-color: #fecaca;
    }

    .form-body {
        padding: 25px 30px;
        overflow-y: auto;
        flex: 1;
    }

    .form-footer {
        padding: 20px 30px;
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    .close-form:hover {
        color: var(--primary);
        transform: rotate(90deg);
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: var(--dark);
        font-size: 0.9rem;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid var(--gray-light);
        border-radius: 12px;
        outline: none;
        transition: all 0.3s ease;
        background: #f8fafc;
        font-size: 0.95rem;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        border-color: var(--primary-light);
        background: white;
        box-shadow: 0 0 0 4px rgba(9, 146, 194, 0.1);
    }

    .btn-submit {
        background: var(--gradient-primary);
        color: white;
        border: none;
        padding: 12px 25px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 0.95rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        box-shadow: 0 8px 15px rgba(11, 45, 114, 0.15);
        transition: all 0.3s ease;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(11, 45, 114, 0.25);
    }

    /* ============ PERBAIKAN 9: Style untuk Loading State ============ */
    .loading {
        display: inline-block;
        width: 20px;
        height: 20px;
        border: 3px solid rgba(255, 255, 255, .3);
        border-radius: 50%;
        border-top-color: #fff;
        animation: spin 1s ease-in-out infinite;
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

    .btn-cancel-delete {
        padding: 11px 25px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: none;
        background: #f1f5f9;
        color: #475569;
        display: flex;
        align-items: center;
        gap: 10px;
        letter-spacing: 0.3px;
        text-decoration: none;
    }

    .btn-cancel-delete:hover {
        background: #e2e8f0;
        color: #0f172a;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
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

    @keyframes alertPop {
        from {
            opacity: 0;
            transform: scale(0.8);
        }

        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    /* Responsive Media Queries */
    @media (max-width: 768px) {
        .agenda-container {
            padding: 10px;
        }

        .header-panel {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
            padding: 15px;
        }

        .header-panel h1 {
            font-size: 1.4rem;
        }

        .alert-msg {
            padding: 14px 18px;
            font-size: 0.85rem;
            gap: 10px;
        }

        .alert-msg i {
            font-size: 1.1rem;
        }

        .btn-add {
            width: 100%;
            justify-content: center;
        }

        .table-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }

        .search-input {
            width: 100%;
        }

        th,
        td {
            padding: 12px 10px;
            font-size: 0.85rem;
        }

        .form-overlay,
        .delete-confirm-overlay {
            align-items: center;
            padding: 20px;
        }

        .form-card {
            width: 95%;
            padding: 25px 20px;
            max-height: 90vh;
            overflow-y: auto;
            margin-bottom: 0;
        }

        .form-card h2 {
            font-size: 1.5rem;
            margin-bottom: 20px;
        }

        div[style*="grid-template-columns: 1fr 1fr"] {
            grid-template-columns: 1fr !important;
            gap: 0 !important;
        }
    }
</style>

<div class="agenda-container">
    <!-- ============ PERBAIKAN 10: Tampilkan Notifikasi ============ -->
    <?php if (isset($_GET['status'])): ?>
        <div class="alert-msg alert-<?php echo $_GET['status'] == 'success' ? 'success' : 'error'; ?>">
            <i class="fas fa-<?php echo $_GET['status'] == 'success' ? 'check-circle' : 'exclamation-circle'; ?>"></i>
            <span><?php echo htmlspecialchars($_GET['message'] ?? ($_GET['status'] == 'success' ? 'Operasi berhasil!' : 'Terjadi kesalahan!')); ?></span>
        </div>
    <?php endif; ?>

    <?php if (isset($error_message)): ?>
        <div class="alert-msg alert-error">
            <i class="fas fa-exclamation-circle"></i>
            <span><?php echo htmlspecialchars($error_message); ?></span>
        </div>
    <?php endif; ?>

    <div class="header-panel stagger-item stagger-1">
        <div>
            <h1>Manajemen Agenda</h1>
            <p style="color: var(--gray);">Kelola jadwal kegiatan, rapat, dan acara sekolah.</p>
        </div>
        <button class="btn-add" onclick="openAddForm()">
            <i class="fas fa-plus"></i> Tambah Agenda
        </button>
    </div>

    <!-- Output Table -->
    <div class="table-card stagger-item stagger-2">
        <div class="table-header">
            <h3>Jadwal Agenda</h3>
            <input type="text" class="search-input" id="searchInput" placeholder="Cari agenda..." onkeyup="searchTable()">
        </div>
        <div style="overflow-x: auto;">
            <table id="agendaTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Gambar</th>
                        <th>Judul Kegiatan</th>
                        <th>Tanggal</th>
                        <th>Waktu</th>
                        <th>Lokasi</th>
                        <th>Status</th>
                        <th style="text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // ============ PERBAIKAN 11: Query dengan ORDER BY yang benar ============
                    $no = 1;
                    $data = mysqli_query($conn, "SELECT * FROM agenda ORDER BY 
                        CASE 
                            WHEN tanggal >= CURDATE() THEN 1
                            ELSE 2
                        END,
                        tanggal ASC, waktu ASC") or die(mysqli_error($conn));

                    if (mysqli_num_rows($data) > 0) {
                        while ($row = mysqli_fetch_array($data)) {
                            // ============ PERBAIKAN 12: Tentukan status & badge ============
                            $kat_db = strtolower($row['status']);
                            $badge_class = 'badge-upcoming';
                            $status_text = $row['status'];

                            if ($kat_db == 'hari ini') {
                                $badge_class = 'badge-today';
                                $status_text = 'Hari Ini';
                            } elseif ($kat_db == 'selesai') {
                                $badge_class = 'badge-completed';
                                $status_text = 'Selesai';
                            } elseif ($kat_db == 'di batalkan') {
                                $badge_class = 'badge-cancelled';
                                $status_text = 'Dibatalkan';
                            } else {
                                $badge_class = 'badge-upcoming';
                                $status_text = 'Akan Datang';
                            }
                    ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td>
                                    <?php if (!empty($row['foto']) && file_exists('../' . $row['foto'])): ?>
                                        <img src="../<?= $row['foto'] ?>" class="agenda-thumb" alt="Agenda">
                                    <?php else: ?>
                                        <div class="no-thumb"><i class="fas fa-image"></i></div>
                                    <?php endif; ?>
                                </td>
                                <td style="font-weight: 500;"><?= htmlspecialchars($row['judul']) ?></td>
                                <td><?= date('d M Y', strtotime($row['tanggal'])) ?></td>
                                <td><?= htmlspecialchars($row['waktu']) ?></td>
                                <td><?= htmlspecialchars($row['lokasi']) ?></td>
                                <td><span class="badge <?= $badge_class ?>"><?= htmlspecialchars($status_text) ?></span></td>
                                <td>
                                    <div class="action-btns">
                                        <button class="btn-action btn-edit" title="Edit"
                                            onclick='openEditForm(<?= json_encode($row) ?>)'>
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn-action btn-delete" title="Hapus"
                                            onclick="showDeleteModal(<?= $row['id'] ?>, '<?= htmlspecialchars(addslashes($row['judul'])) ?>')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 40px;">
                                <i class="fas fa-calendar-times" style="font-size: 48px; color: #ccc; margin-bottom: 10px;"></i>
                                <br>
                                Belum ada agenda. Silakan tambah agenda baru.
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <div class="mobile-scroll-hint" style="display: none; text-align: center; padding: 10px; color: #94a3b8; font-size: 0.75rem; border-top: 1px solid #f1f5f9;">
            <i class="fas fa-arrows-alt-h"></i> Geser tabel untuk melihat detail
        </div>
    </div>
</div>

<script>
    // Show scroll hint on mobile if table is overflowing
    document.addEventListener('DOMContentLoaded', function() {
        const tableWrapper = document.querySelector('.table-card > div[style*="overflow-x: auto"]');
        const hint = document.querySelector('.mobile-scroll-hint');
        if (tableWrapper && hint && window.innerWidth < 768) {
            if (tableWrapper.scrollWidth > tableWrapper.clientWidth) {
                hint.style.display = 'block';
            }
        }
    });
</script>

<!-- ============ PERBAIKAN 13: Modal Form untuk Tambah/Edit ============ -->
<div class="form-overlay" id="formOverlay">
    <div class="form-card">
        <div class="form-header">
            <h2 id="formTitle"><i class="fas fa-calendar-alt"></i> Tambah Agenda</h2>
            <div class="close-form" onclick="closeForm()"><i class="fas fa-times"></i></div>
        </div>
        <form id="agendaForm" action="" method="POST" enctype="multipart/form-data" style="display: contents;">
            <div class="form-body">
                <input type="hidden" name="aksi" id="formAksi" value="tambah">
                <input type="hidden" name="id" id="agendaId" value="">

                <div class="form-group">
                    <label>Judul Kegiatan <span style="color: red;">*</span></label>
                    <input type="text" name="judul" id="judul" required placeholder="Contoh: Rapat Mingguan">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div class="form-group">
                        <label>Tanggal Pelaksanaan <span style="color: red;">*</span></label>
                        <input type="date" name="tanggal" id="tanggal" required>
                    </div>
                    <div class="form-group">
                        <label>Waktu</label>
                        <input type="text" name="waktu" id="waktu" placeholder="Contoh: 08:00 - 10:00">
                    </div>
                </div>

                <div class="form-group">
                    <label>Lokasi</label>
                    <input type="text" name="lokasi" id="lokasi" placeholder="Contoh: Aula Sekolah">
                </div>

                <div class="form-group">
                    <label>Keterangan Tambahan</label>
                    <textarea name="keterangan" id="keterangan" rows="4" placeholder="Detail kegiatan..."></textarea>
                </div>

                <div class="form-group">
                    <label>Foto Agenda</label>
                    <div class="file-upload-wrapper">
                        <input type="file" name="foto" id="foto" class="file-upload-input" onchange="previewImage(this)">
                        <div class="file-upload-label" id="uploadLabelArea">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <span>Pilih foto atau tarik ke sini</span>
                            <p style="font-size: 0.8rem; margin-top: 5px;">Format: JPG, PNG, GIF (Maks. 5MB)</p>
                        </div>
                    </div>
                    <div id="imagePreview" class="image-preview">
                        <img src="" id="previewImg" alt="Preview">
                    </div>
                </div>

                <div class="form-group" id="statusGroup" style="display: none;">
                    <label>Status</label>
                    <select name="status" id="status">
                        <option value="akan datang">Akan Datang</option>
                        <option value="hari ini">Hari Ini</option>
                        <option value="selesai">Selesai</option>
                        <option value="di batalkan">Dibatalkan</option>
                    </select>
                </div>
            </div>

            <div class="form-footer">
                <button type="button" class="btn-cancel-delete" onclick="closeForm()">
                    <i class="fas fa-times"></i> Batal
                </button>
                <button type="submit" class="btn-submit" id="submitBtn">
                    <i class="fas fa-save"></i> <span id="btnText">Simpan Agenda</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="delete-confirm-overlay" id="deleteOverlay" onclick="if(event.target===this) closeDeleteForm()">
    <div class="delete-confirm-modal" onclick="event.stopPropagation()">
        <div class="delete-confirm-icon">
            <i class="fas fa-trash-alt"></i>
        </div>
        <h3>Hapus Agenda?</h3>
        <p id="deleteMessage">Yakin ingin menghapus agenda ini? Tindakan ini tidak dapat dibatalkan.</p>
        <div class="delete-confirm-btns">
            <button type="button" class="btn-cancel-delete" onclick="closeDeleteForm()">Batal</button>
            <a href="#" class="btn-confirm-delete" id="deleteConfirmBtn">
                <i class="fas fa-trash"></i> Hapus
            </a>
        </div>
    </div>
</div>

<script>
    // State untuk menyimpan ID yang akan dihapus
    let deleteId = null;

    // Standardized Form Functions
    function openAddForm() {
        const overlay = document.getElementById('formOverlay');
        const form = document.getElementById('agendaForm');
        document.getElementById('formTitle').innerHTML = '<i class="fas fa-calendar-plus"></i> Tambah Agenda Baru';
        document.getElementById('btnText').innerText = 'Simpan Agenda';
        document.getElementById('formAksi').value = 'tambah';
        document.getElementById('statusGroup').style.display = 'none';
        form.reset();
        document.getElementById('agendaId').value = '';
        document.getElementById('imagePreview').style.display = 'none';
        overlay.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function openEditForm(data) {
        const overlay = document.getElementById('formOverlay');
        const form = document.getElementById('agendaForm');
        document.getElementById('formTitle').innerHTML = '<i class="fas fa-edit"></i> Edit Agenda';
        document.getElementById('btnText').innerText = 'Update Agenda';
        document.getElementById('formAksi').value = 'edit';
        document.getElementById('statusGroup').style.display = 'block';

        document.getElementById('agendaId').value = data.id || '';
        document.getElementById('judul').value = data.judul || '';
        document.getElementById('tanggal').value = data.tanggal || '';
        document.getElementById('waktu').value = data.waktu || '';
        document.getElementById('lokasi').value = data.lokasi || '';
        document.getElementById('keterangan').value = data.deskripsi || '';
        document.getElementById('status').value = data.status || 'akan datang';

        // Preview image if exists
        const preview = document.getElementById('imagePreview');
        const previewImg = document.getElementById('previewImg');
        if (data.foto) {
            previewImg.src = '../' + data.foto;
            preview.style.display = 'block';
        } else {
            preview.style.display = 'none';
        }

        overlay.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeForm() {
        document.getElementById('formOverlay').style.display = 'none';
        document.body.style.overflow = '';
    }

    function showDeleteModal(id, judul) {
        deleteId = id;
        document.getElementById('deleteMessage').innerHTML = `Yakin ingin menghapus agenda <strong>"${judul}"</strong>?<br>Tindakan ini tidak dapat dibatalkan.`;
        document.getElementById('deleteConfirmBtn').href = `?aksi=hapus&id=${id}`;
        document.getElementById('deleteOverlay').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeDeleteForm() {
        document.getElementById('deleteOverlay').style.display = 'none';
        document.body.style.overflow = '';
    }

    function previewImage(input) {
        const file = input.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('imagePreview');
                const previewImg = document.getElementById('previewImg');
                previewImg.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(file);

            // Update label
            const labelArea = document.getElementById('uploadLabelArea');
            if (labelArea) {
                labelArea.querySelector('span').innerText = file.name;
            }
        }
    }

    // REAL TIME SEARCH
    function searchTable() {
        const input = document.getElementById('searchInput');
        const filter = input.value.toUpperCase();
        const table = document.getElementById('agendaTable');
        const tr = table.getElementsByTagName('tr');

        for (let i = 1; i < tr.length; i++) {
            let found = false;
            const td = tr[i].getElementsByTagName('td');
            // Skip "Belum ada agenda" row
            if (tr[i].cells.length < 2) continue;

            for (let j = 0; j < td.length - 1; j++) {
                if (td[j]) {
                    const textValue = td[j].textContent || td[j].innerText;
                    if (textValue.toUpperCase().indexOf(filter) > -1) {
                        found = true;
                        break;
                    }
                }
            }
            tr[i].style.display = found ? '' : 'none';
        }
    }

    // Initialization
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-hide alert after 3 seconds
        const alert = document.querySelector('.alert-msg');
        if (alert) {
            setTimeout(() => {
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-20px)';
                setTimeout(() => alert.remove(), 400);
            }, 3000);
        }

        // Close modal on outside click
        window.onclick = function(event) {
            if (event.target == document.getElementById('formOverlay')) closeForm();
            if (event.target == document.getElementById('deleteOverlay')) closeDeleteForm();
        }
    });
</script>

<?php include 'layout/footer.php'; ?>