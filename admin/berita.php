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
        width: 60px;
        height: 40px;
        border-radius: 4px;
        object-fit: cover;
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

    /* Form Section (Hidden by default) */
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

    .btn-submit:active {
        transform: translateY(-1px);
    }

    /* Responsive Media Queries */
    @media (max-width: 768px) {
        .berita-container {
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

        .news-thumb {
            width: 50px;
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

<div class="berita-container">
    <div class="header-panel stagger-item stagger-1">
        <div>
            <h1>Manajemen Berita</h1>
            <p style="color: var(--gray);">Tampilkan dan kelola semua artikel berita sekolah.</p>
        </div>
        <button class="btn-add" onclick="toggleForm(true)">
            <i class="fas fa-plus"></i> Tambah Berita
        </button>
    </div>

    <!-- Output Table -->
    <div class="table-card stagger-item stagger-2">
        <div class="table-header">
            <h3>Daftar Berita</h3>
            <input type="text" class="search-input" placeholder="Cari berita...">
        </div>
        <div style="overflow-x: auto;">
            <table>
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
                <tbody>
                    <tr>
                        <td><img src="../img/berita/berita1.jpg" class="news-thumb"></td>
                        <td style="font-weight: 500;">Workshop Literasi Digital 2026</td>
                        <td><span class="badge badge-info">Kegiatan</span></td>
                        <td>12 Feb 2026</td>
                        <td>Admin</td>
                        <td>
                            <div class="action-btns">
                                <button class="btn-action btn-edit"><i class="fas fa-edit"></i></button>
                                <button class="btn-action btn-delete"><i class="fas fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><img src="../img/berita/berita2.jpg" class="news-thumb"></td>
                        <td style="font-weight: 500;">Pendaftaran Siswa Baru 2026</td>
                        <td><span class="badge badge-success">Pengumuman</span></td>
                        <td>10 Feb 2026</td>
                        <td>Staff TU</td>
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
        <h2>Input Berita Baru</h2>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Judul Berita</label>
                <input type="text" name="judul" required placeholder="Masukkan judul artikel">
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="form-group">
                    <label>Kategori</label>
                    <select name="kategori" required>
                        <option value="">Pilih Kategori</option>
                        <option value="Kegiatan">Kegiatan</option>
                        <option value="Pengumuman">Pengumuman</option>
                        <option value="Prestasi">Prestasi</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Upload Gambar Berita</label>
                    <div class="file-upload-wrapper">
                        <input type="file" name="gambar" class="file-upload-input" id="gambarInput" required>
                        <div class="file-upload-label">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <span>Klik atau seret gambar ke sini</span>
                            <span class="file-name" id="fileName"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>Isi Berita</label>
                <textarea name="isi" rows="6" placeholder="Tulis isi berita di sini..."></textarea>
            </div>
            <button type="submit" class="btn-submit">
                <i class="fas fa-save"></i> Simpan & Publikasikan Berita
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