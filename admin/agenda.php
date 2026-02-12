<?php
include "../database/conn.php";

// ============ PERBAIKAN 1: Proses Hapus dengan Prepared Statement ============
// Memperbaiki query hapus yang sebelumnya tidak konsisten dan menambahkan redirect
if (isset($_GET['aksi']) && $_GET['aksi'] == 'hapus' && isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    if ($id > 0) {
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
    $status = 'Akan Datang'; // Default status

    // Prepared statement untuk INSERT
    $stmt = mysqli_prepare($conn, "INSERT INTO agenda (judul, tanggal, waktu, lokasi, deskripsi, status) VALUES (?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "ssssss", $judul, $tanggal, $waktu, $lokasi, $keterangan, $status);

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
    $keterangan = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    // Prepared statement untuk UPDATE
    $stmt = mysqli_prepare($conn, "UPDATE agenda SET judul=?, tanggal=?, waktu=?, lokasi=?, deskripsi=?, status=? WHERE id=?");
    mysqli_stmt_bind_param($stmt, "ssssssi", $judul, $tanggal, $waktu, $lokasi, $keterangan, $status, $id);

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
    .agenda-container {
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
        overflow-y: auto;
        /* Allow overlay to scroll if modal is tall */
    }

    .form-card {
        background: white;
        width: 100%;
        max-width: 650px;
        border-radius: 20px;
        padding: 40px;
        position: relative;
        box-shadow: 0 25px 70px rgba(0, 0, 0, 0.15);
        animation: formSlideUp 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
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
            align-items: flex-start;
            /* Better for tall modals on short screens */
            padding-top: 40px;
            padding-bottom: 40px;
        }

        .form-card {
            width: 95%;
            padding: 25px 20px;
            max-height: none;
            /* Let the overlay handle scrolling */
            margin-bottom: 20px;
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
                            // ============ PERBAIKAN 12: Tentukan status berdasarkan tanggal ============
                            $tanggal_sekarang = date('Y-m-d');
                            $badge_class = 'badge-upcoming';
                            $status_text = $row['status'];

                            if ($row['tanggal'] == $tanggal_sekarang) {
                                $badge_class = 'badge-today';
                                $status_text = 'Hari Ini';
                            } elseif ($row['tanggal'] < $tanggal_sekarang) {
                                $badge_class = 'badge-completed';
                                $status_text = 'Selesai';
                            }
                    ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td style="font-weight: 500;"><?= htmlspecialchars($row['judul']) ?></td>
                                <td><?= date('d M Y', strtotime($row['tanggal'])) ?></td>
                                <td><?= htmlspecialchars($row['waktu']) ?></td>
                                <td><?= htmlspecialchars($row['lokasi']) ?></td>
                                <td><span class="badge <?= $badge_class ?>"><?= htmlspecialchars($status_text) ?></span></td>
                                <td>
                                    <div class="action-btns">
                                        <button class="btn-action btn-edit" onclick="openEditForm(<?= $row['id'] ?>)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn-action btn-delete" onclick="confirmDelete(<?= $row['id'] ?>, '<?= htmlspecialchars(addslashes($row['judul'])) ?>')">
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
        <i class="fas fa-times close-form" onclick="closeForm()"></i>
        <h2 id="formTitle">Input Agenda Baru</h2>
        <form id="agendaForm" action="" method="POST">
            <input type="hidden" name="aksi" id="formAksi" value="tambah">
            <input type="hidden" name="id" id="agendaId" value="">

            <div class="form-group">
                <label>Judul Kegiatan <span style="color: red;">*</span></label>
                <input type="text" name="judul" id="judul" required placeholder="Contoh: Rapat Mingguan">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="form-group">
                    <label>Tanggal Pelaksanaan <span style="color: red;">*</span></label>
                    <input type="date" name="tanggal" id="tanggal" required min="<?= date('Y-m-d') ?>">
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

            <div class="form-group" id="statusGroup" style="display: none;">
                <label>Status</label>
                <select name="status" id="status">
                    <option value="Akan Datang">Akan Datang</option>
                    <option value="Hari Ini">Hari Ini</option>
                    <option value="Selesai">Selesai</option>
                    <option value="Dibatalkan">Dibatalkan</option>
                </select>
            </div>

            <button type="submit" class="btn-submit" id="submitBtn">
                <i class="fas fa-save"></i> <span id="btnText">Simpan Agenda & Jadwal</span>
            </button>
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
    // ============ PERBAIKAN 15: Fungsi untuk Membuka Form Tambah ============
    function openAddForm() {
        document.getElementById('formTitle').innerText = 'Input Agenda Baru';
        document.getElementById('formAksi').value = 'tambah';
        document.getElementById('btnText').innerText = 'Simpan Agenda & Jadwal';
        document.getElementById('agendaForm').reset();
        document.getElementById('agendaId').value = '';
        document.getElementById('statusGroup').style.display = 'none';
        document.getElementById('formOverlay').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    // ============ PERBAIKAN 16: Fungsi untuk Membuka Form Edit ============
    function openEditForm(id) {
        // Tampilkan loading state
        const btn = event.currentTarget;
        const originalHtml = btn.innerHTML;
        btn.innerHTML = '<span class="loading"></span>';
        btn.disabled = true;

        // Fetch data dari server
        fetch('get_agenda.php?id=' + id)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('formTitle').innerText = 'Edit Agenda';
                    document.getElementById('formAksi').value = 'edit';
                    document.getElementById('btnText').innerText = 'Update Agenda';
                    document.getElementById('agendaId').value = data.data.id;
                    document.getElementById('judul').value = data.data.judul;
                    document.getElementById('tanggal').value = data.data.tanggal;
                    document.getElementById('waktu').value = data.data.waktu;
                    document.getElementById('lokasi').value = data.data.lokasi;
                    document.getElementById('keterangan').value = data.data.keterangan;
                    document.getElementById('status').value = data.data.status;
                    document.getElementById('statusGroup').style.display = 'block';
                    document.getElementById('formOverlay').style.display = 'flex';
                    document.body.style.overflow = 'hidden';
                } else {
                    alert('Gagal mengambil data agenda');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengambil data');
            })
            .finally(() => {
                btn.innerHTML = originalHtml;
                btn.disabled = false;
            });
    }

    // ============ PERBAIKAN 17: Fungsi untuk Konfirmasi Hapus ============
    function confirmDelete(id, judul) {
        document.getElementById('deleteMessage').innerHTML = `Apakah Anda yakin ingin menghapus agenda:<br><strong>"${judul}"</strong>?`;
        document.getElementById('deleteConfirmBtn').href = `?aksi=hapus&id=${id}`;
        document.getElementById('deleteOverlay').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeDeleteForm() {
        document.getElementById('deleteOverlay').style.display = 'none';
        document.body.style.overflow = '';
    }

    // ============ PERBAIKAN 18: Fungsi untuk Menutup Form ============
    function closeForm() {
        document.getElementById('formOverlay').style.display = 'none';
        document.getElementById('agendaForm').reset();
        document.getElementById('statusGroup').style.display = 'none';
        document.body.style.overflow = '';
    }

    // ============ PERBAIKAN 19: Validasi Form Sebelum Submit ============
    document.getElementById('agendaForm').addEventListener('submit', function(e) {
        const judul = document.getElementById('judul').value.trim();
        const tanggal = document.getElementById('tanggal').value;

        if (judul === '') {
            e.preventDefault();
            alert('Judul kegiatan harus diisi!');
            return false;
        }

        if (tanggal === '') {
            e.preventDefault();
            alert('Tanggal pelaksanaan harus diisi!');
            return false;
        }

        // Tampilkan loading pada button submit
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.innerHTML = '<span class="loading"></span> Menyimpan...';
        submitBtn.disabled = true;
    });

    // ============ PERBAIKAN 20: Fungsi Pencarian Real-time ============
    function searchTable() {
        const input = document.getElementById('searchInput');
        const filter = input.value.toUpperCase();
        const table = document.getElementById('agendaTable');
        const tr = table.getElementsByTagName('tr');

        for (let i = 1; i < tr.length; i++) {
            let found = false;
            const td = tr[i].getElementsByTagName('td');

            for (let j = 0; j < td.length - 1; j++) { // -1 untuk skip kolom aksi
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

    // ============ PERBAIKAN 21: Set Tanggal Minimum ============
    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date().toISOString().split('T')[0];
        const tanggalInput = document.getElementById('tanggal');
        if (tanggalInput) {
            tanggalInput.setAttribute('min', today);
        }

        // Tutup modal jika klik di luar
        window.onclick = function(event) {
            const formOverlay = document.getElementById('formOverlay');
            const deleteOverlay = document.getElementById('deleteOverlay');

            if (event.target == formOverlay) {
                closeForm();
            }
            if (event.target == deleteOverlay) {
                closeDeleteForm();
            }
        }
    });

    // ============ PERBAIKAN 22: Auto-hide Alert ============
    setTimeout(function() {
        const alerts = document.getElementsByClassName('alert-msg');
        for (let alert of alerts) {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(() => {
                alert.style.display = 'none';
            }, 500);
        }
    }, 3000);
</script>

<?php include 'layout/footer.php'; ?>