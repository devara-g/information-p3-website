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
        max-width: 500px;
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

        .form-overlay {
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

        div[style*="grid-template-columns: 1fr 1fr"] {
            grid-template-columns: 1fr !important;
        }
    }

    /* Premium File Upload Styling */
    .file-upload-wrapper {
        position: relative;
        width: 100%;
    }

    .file-upload-input {
        position: absolute;
        top: 0;
        left: 0;
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
        padding: 20px;
        background: #f8fafc;
        border: 2px dashed var(--gray-light);
        border-radius: 12px;
        transition: all 0.3s ease;
        min-height: 100px;
        text-align: center;
    }

    .file-upload-wrapper:hover .file-upload-label {
        border-color: var(--primary-light);
        background: rgba(9, 146, 194, 0.05);
    }

    .file-upload-label i {
        font-size: 2rem;
        color: var(--primary-light);
        margin-bottom: 10px;
        transition: transform 0.3s ease;
    }

    .file-upload-wrapper:hover .file-upload-label i {
        transform: translateY(-5px);
    }

    .file-upload-label span {
        font-size: 0.9rem;
        color: var(--gray);
        font-weight: 500;
    }

    .file-upload-label .file-name {
        margin-top: 5px;
        color: var(--primary);
        font-weight: 600;
        display: none;
    }
</style>

<div class="guru-container">
    <div class="header-panel stagger-item stagger-1">
        <div>
            <h1>Data Guru & Staff</h1>
            <p style="color: var(--gray);">Kelola informasi tenaga pendidik dan kependidikan.</p>
        </div>
        <button class="btn-add" onclick="openAddForm()">
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
        <div class="form-header">
            <h2 id="formTitle"><i class="fas fa-user-tie"></i> Input Guru/Staff</h2>
            <div class="close-form" onclick="closeForm()"><i class="fas fa-times"></i></div>
        </div>
        <form action="" method="POST" enctype="multipart/form-data" style="display: contents;">
            <div class="form-body">
                <div class="form-group">
                    <label>Nama Lengkap & Gelar</label>
                    <input type="text" name="nama" id="nama" required placeholder="Contoh: Siti Aminah, S.Pd">
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div class="form-group">
                        <label>NIP / NUPTK</label>
                        <input type="text" name="nip" id="nip" placeholder="Masukkan nomor identitas">
                    </div>
                    <div class="form-group">
                        <label>Jabatan</label>
                        <select name="jabatan" id="jabatan" required>
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
                    <input type="text" name="mapel" id="mapel" placeholder="Contoh: Matematika / Administrasi">
                </div>
                <div class="form-group">
                    <label>Foto Profil</label>
                    <div class="file-upload-wrapper">
                        <input type="file" name="foto" class="file-upload-input" id="fotoInput" onchange="previewFile(this)">
                        <div class="file-upload-label">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <span>Klik atau seret foto ke sini</span>
                            <span class="file-name" id="fileName"></span>
                        </div>
                    </div>
                </div>
                <div id="imagePreview" style="display: none; margin-top: 15px; border-radius: 50%; width: 100px; height: 100px; overflow: hidden; border: 2px solid #e2e8f0; margin-left: auto; margin-right: auto;">
                    <img src="" id="previewImg" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
            </div>
            <div class="form-footer">
                <button type="button" class="btn-cancel-delete" onclick="closeForm()">
                    <i class="fas fa-times"></i> Batal
                </button>
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i> <span id="btnText">Simpan Data Guru</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openAddForm() {
        const overlay = document.getElementById('formOverlay');
        overlay.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeForm() {
        const overlay = document.getElementById('formOverlay');
        overlay.style.display = 'none';
        document.body.style.overflow = '';
    }

    function previewFile(input) {
        const file = input.files[0];
        if (file) {
            document.getElementById('fileName').textContent = file.name;
            document.getElementById('fileName').style.display = 'block';

            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('imagePreview').style.display = 'block';
                document.getElementById('previewImg').src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    }
</script>

<?php include 'layout/footer.php'; ?>