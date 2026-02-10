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

    /* Form Section */
    .form-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 2000;
    }

    .form-card {
        background: white;
        width: 600px;
        border-radius: 12px;
        padding: 30px;
        position: relative;
    }

    .form-card h2 {
        margin-bottom: 20px;
        color: var(--primary);
    }

    .close-form {
        position: absolute;
        top: 20px;
        right: 20px;
        font-size: 1.5rem;
        cursor: pointer;
        color: #999;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: 500;
        color: var(--gray);
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 6px;
        outline: none;
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

    .btn-submit:active {
        transform: translateY(-1px);
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
            font-size: 1.5rem;
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

        .form-card {
            width: 95%;
            padding: 20px;
            max-height: 90vh;
            overflow-y: auto;
        }

        .form-card h2 {
            font-size: 1.5rem;
        }

        div[style*="grid-template-columns: 1fr 1fr"] {
            grid-template-columns: 1fr !important;
        }
    }
</style>

<div class="agenda-container">
    <div class="header-panel stagger-item stagger-1">
        <div>
            <h1>Manajemen Agenda</h1>
            <p style="color: var(--gray);">Kelola jadwal kegiatan, rapat, dan acara sekolah.</p>
        </div>
        <button class="btn-add" onclick="toggleForm(true)">
            <i class="fas fa-plus"></i> Tambah Agenda
        </button>
    </div>

    <!-- Output Table -->
    <div class="table-card stagger-item stagger-2">
        <div class="table-header">
            <h3>Jadwal Agenda</h3>
            <input type="text" class="search-input" placeholder="Cari agenda...">
        </div>
        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>Judul Kegiatan</th>
                        <th>Tanggal</th>
                        <th>Waktu</th>
                        <th>Lokasi</th>
                        <th>Status</th>
                        <th style="text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="font-weight: 500;">Ujian Tengah Semester Ganjil</td>
                        <td>24 Feb 2026</td>
                        <td>07:30 - Selesai</td>
                        <td>Ruang Kelas</td>
                        <td><span class="badge badge-upcoming">Akan Datang</span></td>
                        <td>
                            <div class="action-btns">
                                <button class="btn-action btn-edit"><i class="fas fa-edit"></i></button>
                                <button class="btn-action btn-delete"><i class="fas fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-weight: 500;">Rapat Koordinasi Guru</td>
                        <td>15 Feb 2026</td>
                        <td>13:00 - 15:00</td>
                        <td>Ruang Guru</td>
                        <td><span class="badge badge-today">Segera</span></td>
                        <td>
                            <div class="action-btns">
                                <button class="btn-action btn-edit"><i class="fas fa-edit"></i></button>
                                <button class="btn-action btn-delete"><i class="fas fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Form -->
<div class="form-overlay" id="formOverlay">
    <div class="form-card">
        <i class="fas fa-times close-form" onclick="toggleForm(false)"></i>
        <h2>Input Agenda Baru</h2>
        <form action="" method="POST">
            <div class="form-group">
                <label>Judul Kegiatan</label>
                <input type="text" name="judul" required placeholder="Contoh: Rapat Mingguan">
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="form-group">
                    <label>Tanggal Pelaksanaan</label>
                    <input type="date" name="tanggal" required>
                </div>
                <div class="form-group">
                    <label>Waktu</label>
                    <input type="text" name="waktu" placeholder="Contoh: 08:00 - 10:00">
                </div>
            </div>
            <div class="form-group">
                <label>Lokasi</label>
                <input type="text" name="lokasi" placeholder="Contoh: Aula Sekolah">
            </div>
            <div class="form-group">
                <label>Keterangan Tambahan</label>
                <textarea name="keterangan" rows="4" placeholder="Detail kegiatan..."></textarea>
            </div>
            <button type="submit" class="btn-submit">
                <i class="fas fa-save"></i> Simpan Agenda & Jadwal
            </button>
        </form>
    </div>
</div>

<script>
    function toggleForm(show) {
        const overlay = document.getElementById('formOverlay');
        overlay.style.display = show ? 'flex' : 'none';
    }
</script>

<?php include 'layout/footer.php'; ?>