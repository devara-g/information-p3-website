<?php
$title = "Manajemen Guru & Staff";
include 'layout/header.php';
?>

<style>
    /* Internal CSS for Guru/Staff Management */
    .guru-container {
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
        min-width: 900px;
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

    .guru-thumb {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #f0f0f0;
    }

    .badge {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
    }

    .badge-primary {
        background: #e0f2fe;
        color: #0369a1;
    }

    .badge-secondary {
        background: #f1f5f9;
        color: #64748b;
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
        width: 650px;
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
        .guru-container {
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

        .guru-thumb {
            width: 35px;
            height: 35px;
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

<div class="guru-container">
    <div class="header-panel stagger-item stagger-1">
        <div>
            <h1>Data Guru & Staff</h1>
            <p style="color: var(--gray);">Kelola informasi tenaga pendidik dan kependidikan.</p>
        </div>
        <button class="btn-add" onclick="toggleForm(true)">
            <i class="fas fa-plus"></i> Tambah Data
        </button>
    </div>

    <!-- Output Table -->
    <div class="table-card stagger-item stagger-2">
        <div class="table-header">
            <h3>Daftar Guru & Staff</h3>
            <input type="text" class="search-input" placeholder="Cari nama atau NIP...">
        </div>
        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>Nama Lengkap</th>
                        <th>NIP / Identitas</th>
                        <th>Jabatan</th>
                        <th>Mata Pelajaran / Tugas</th>
                        <th style="text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><img src="../img/kepsek.jpg" class="guru-thumb"></td>
                        <td style="font-weight: 600;">Drs. Indra Robriandri, M.Si</td>
                        <td>197205141998031005</td>
                        <td><span class="badge badge-primary">Kepala Sekolah</span></td>
                        <td>Manajemen Pendidikan</td>
                        <td>
                            <div class="action-btns">
                                <button class="btn-action btn-edit" title="Edit"><i class="fas fa-edit"></i></button>
                                <button class="btn-action btn-delete" title="Hapus"><i class="fas fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div style="width: 45px; height: 45px; border-radius: 50%; background: #f0f4f8; display: flex; align-items: center; justify-content: center; color: var(--primary);"><i class="fas fa-user"></i></div>
                        </td>
                        <td style="font-weight: 600;">Siti Aminah, S.Pd</td>
                        <td>198506202010012015</td>
                        <td><span class="badge badge-secondary">Guru Mata Pelajaran</span></td>
                        <td>Bahasa Indonesia</td>
                        <td>
                            <div class="action-btns">
                                <button class="btn-action btn-edit" title="Edit"><i class="fas fa-edit"></i></button>
                                <button class="btn-action btn-delete" title="Hapus"><i class="fas fa-trash"></i></button>
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
        <h2>Input Data Guru/Staff</h2>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Nama Lengkap & Gelar</label>
                <input type="text" name="nama" required placeholder="Contoh: Siti Aminah, S.Pd">
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="form-group">
                    <label>NIP / NUPTK</label>
                    <input type="text" name="nip" placeholder="Masukkan nomor identitas">
                </div>
                <div class="form-group">
                    <label>Jabatan</label>
                    <select name="jabatan" required>
                        <option value="">Pilih Jabatan</option>
                        <option>Kepala Sekolah</option>
                        <option>Wakil Kepala Sekolah</option>
                        <option>Guru Mata Pelajaran</option>
                        <option>Staff Tata Usaha</option>
                        <option>Staff Perpustakaan</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label>Mata Pelajaran / Bidang Tugas</label>
                <input type="text" name="mapel" placeholder="Contoh: Matematika / Administrasi">
            </div>
            <div class="form-group">
                <label>Foto Profil</label>
                <input type="file" name="foto">
            </div>
            <button type="submit" class="btn-submit">
                <i class="fas fa-save"></i> Simpan Data Guru & Staff
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