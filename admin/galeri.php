<?php
include "../database/conn.php";

// Pastikan tabel galeri ada
$checkTable = mysqli_query($conn, "SHOW TABLES LIKE 'galeri'");
if (mysqli_num_rows($checkTable) == 0) {
    mysqli_query($conn, "CREATE TABLE galeri (
        id INT AUTO_INCREMENT PRIMARY KEY,
        judul VARCHAR(255) NOT NULL,
        kategori VARCHAR(100) NOT NULL,
        deskripsi TEXT,
        foto VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
}

// Pastikan kolom deskripsi ada (jika tabel sudah terlanjur dibuat tanpa deskripsi)
$colCheck = mysqli_query($conn, "SHOW COLUMNS FROM galeri LIKE 'deskripsi'");
if (mysqli_num_rows($colCheck) == 0) {
    mysqli_query($conn, "ALTER TABLE galeri ADD COLUMN deskripsi TEXT AFTER kategori");
}

// Proses Hapus
if (isset($_GET['aksi']) && $_GET['aksi'] == 'hapus' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    // Hapus file fisik
    $q = mysqli_query($conn, "SELECT foto FROM galeri WHERE id = $id");
    $data = mysqli_fetch_assoc($q);
    if ($data && !empty($data['foto'])) {
        $path = "../" . $data['foto'];
        if (file_exists($path)) unlink($path);
    }

    mysqli_query($conn, "DELETE FROM galeri WHERE id = $id");
    header("Location: galeri.php?status=success&message=Foto berhasil dihapus");
    exit;
}

// Proses Simpan (Tambah/Edit)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['aksi'])) {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $aksi = $_POST['aksi'];

    $foto = "";
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $newName = "galeri_" . time() . "_" . uniqid() . "." . $ext;
        $target = "../upload/img/" . $newName;

        if (!file_exists("../upload/img/")) mkdir("../upload/img/", 0777, true);

        if (move_uploaded_file($_FILES['foto']['tmp_name'], $target)) {
            $foto = "upload/img/" . $newName;

            // Hapus foto lama jika edit
            if ($aksi == 'edit' && $id > 0) {
                $q = mysqli_query($conn, "SELECT foto FROM galeri WHERE id = $id");
                $old = mysqli_fetch_assoc($q);
                if ($old && !empty($old['foto'])) {
                    $oldPath = "../" . $old['foto'];
                    if (file_exists($oldPath)) unlink($oldPath);
                }
            }
        }
    }

    if ($aksi == 'tambah') {
        $sql = "INSERT INTO galeri (judul, kategori, deskripsi, foto) VALUES ('$judul', '$kategori', '$deskripsi', '$foto')";
    } else {
        if ($foto != "") {
            $sql = "UPDATE galeri SET judul='$judul', kategori='$kategori', deskripsi='$deskripsi', foto='$foto' WHERE id=$id";
        } else {
            $sql = "UPDATE galeri SET judul='$judul', kategori='$kategori', deskripsi='$deskripsi' WHERE id=$id";
        }
    }

    if (mysqli_query($conn, $sql)) {
        header("Location: galeri.php?status=success&message=Data berhasil disimpan");
    } else {
        header("Location: galeri.php?status=error&message=Gagal menyimpan data");
    }
    exit;
}

$title = "Manajemen Galeri";
include 'layout/header.php';
?>
<style>
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

    .galeri-container {
        padding: 20px;
        min-height: calc(100vh - 100px);
    }

    .header-panel {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: white;
        padding: 25px;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        margin-bottom: 25px;
    }

    .header-panel h1 {
        font-size: 1.8rem;
        color: var(--primary);
        margin: 0;
        font-weight: 800;
    }

    .btn-add {
        background: var(--gradient-primary);
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 12px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 600;
        transition: 0.3s;
        box-shadow: 0 8px 20px rgba(11, 45, 114, 0.2);
    }

    .btn-add:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 25px rgba(11, 45, 114, 0.3);
    }

    .table-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 25px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .table-header {
        padding: 20px 25px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .search-wrap {
        position: relative;
        width: 300px;
    }

    .search-wrap i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
    }

    .search-wrap input {
        width: 100%;
        padding: 10px 15px 10px 40px;
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        outline: none;
        transition: 0.3s;
    }

    .search-wrap input:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(11, 45, 114, 0.1);
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th {
        background: #f8fafc;
        padding: 15px 25px;
        text-align: left;
        color: #64748b;
        font-weight: 700;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    td {
        padding: 15px 25px;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
    }

    .galeri-thumb {
        width: 100px;
        height: 65px;
        border-radius: 8px;
        object-fit: cover;
        border: 1px solid #e2e8f0;
    }

    .no-thumb {
        width: 100px;
        height: 65px;
        background: #f1f5f9;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
        border: 1px dashed #cbd5e0;
    }

    .badge {
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
    }

    .category-school {
        background: #e0f2fe;
        color: #0369a1;
    }

    .category-achievement {
        background: #dcfce7;
        color: #15803d;
    }

    .category-extracurricular {
        background: #fef3c7;
        color: #d97706;
    }

    .action-btns {
        display: flex;
        gap: 8px;
    }

    .btn-action {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        border: none;
        cursor: pointer;
        transition: 0.3s;
    }

    .btn-edit {
        background: #fef3c7;
        color: #d97706;
    }

    .btn-edit:hover {
        background: #d97706;
        color: white;
        transform: rotate(15deg);
    }

    .btn-delete {
        background: #fee2e2;
        color: #dc2626;
    }

    .btn-delete:hover {
        background: #dc2626;
        color: white;
        transform: scale(1.1);
    }

    .form-overlay {
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
        z-index: 2000;
        padding: 20px;
    }

    .form-card {
        background: white;
        width: 100%;
        max-width: 500px;
        max-height: 90vh;
        border-radius: 24px;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        box-shadow: 0 40px 100px -20px rgba(11, 45, 114, 0.3);
        animation: modalScaleUp 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    @keyframes modalScaleUp {
        from {
            opacity: 0;
            transform: scale(0.9) translateY(20px);
        }

        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }

    .form-header {
        padding: 25px 35px;
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
    }

    .close-form {
        width: 35px;
        height: 35px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
        cursor: pointer;
        transition: 0.3s;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .close-form:hover {
        color: #f43f5e;
        transform: rotate(90deg);
    }

    .form-body {
        padding: 30px 35px;
        overflow-y: auto;
        flex: 1;
    }

    .form-footer {
        padding: 20px 35px;
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: flex-end;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 10px;
        font-weight: 600;
        color: #1e293b;
        font-size: 0.95rem;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 14px 18px;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        outline: none;
        transition: 0.3s;
        background: #f8fafc;
        font-family: inherit;
        resize: vertical;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        border-color: var(--primary);
        background: white;
        box-shadow: 0 0 0 4px rgba(11, 45, 114, 0.05);
    }

    .file-upload-wrapper {
        position: relative;
        width: 100%;
        height: 140px;
        border: 2px dashed #cbd5e0;
        border-radius: 14px;
        background: #f8fafc;
        cursor: pointer;
        transition: 0.3s;
    }

    .file-upload-wrapper:hover {
        border-color: var(--primary);
        background: #f1f5f9;
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
        position: absolute;
        inset: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #64748b;
        text-align: center;
        padding: 20px;
    }

    .file-upload-label i {
        font-size: 2.5rem;
        color: var(--primary);
        margin-bottom: 12px;
    }

    .btn-submit {
        background: var(--gradient-primary);
        color: white;
        border: none;
        padding: 14px 25px;
        border-radius: 12px;
        font-weight: 700;
        cursor: pointer;
        transition: 0.3s;
        box-shadow: 0 8px 20px rgba(11, 45, 114, 0.2);
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(11, 45, 114, 0.25);
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

    .alert-msg {
        padding: 15px 25px;
        border-radius: 12px;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 15px;
        font-weight: 600;
        animation: slideDown 0.4s ease;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .alert-success {
        background: #dcfce7;
        color: #15803d;
        border-left: 5px solid #22c55e;
    }

    .alert-error {
        background: #fee2e2;
        color: #b91c1c;
        border-left: 5px solid #ef4444;
    }
</style>

<div class="galeri-container">
    <?php if (isset($_GET['status'])) : ?>
        <div class="alert-msg alert-<?= $_GET['status'] == 'success' ? 'success' : 'error' ?>">
            <i class="fas <?= $_GET['status'] == 'success' ? 'fa-check-circle' : 'fa-exclamation-circle' ?>"></i>
            <?= htmlspecialchars($_GET['message']) ?>
        </div>
    <?php endif; ?>

    <div class="header-panel">
        <div>
            <h1>Manajemen Galeri</h1>
            <p style="color: var(--gray);">Kelola foto dokumentasi kegiatan dan prestasi sekolah.</p>
        </div>
        <button class="btn-add" onclick="openAddForm()">
            <i class="fas fa-plus"></i> Tambah Foto
        </button>
    </div>

    <div class="table-card">
        <div class="table-header">
            <h3>Daftar Foto</h3>
            <div class="search-wrap">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Cari foto...">
            </div>
        </div>
        <div style="overflow-x: auto;">
            <table id="galeriTable">
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th width="120">Foto</th>
                        <th>Judul/Keterangan</th>
                        <th>Kategori</th>
                        <th width="100">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $res = mysqli_query($conn, "SELECT * FROM galeri ORDER BY id DESC");
                    $no = 1;
                    while ($row = mysqli_fetch_assoc($res)) :
                        $cat_class = '';
                        $kat_db = strtolower($row['kategori']);
                        if ($kat_db == 'kegiatan sekolah') {
                            $cat_class = 'category-school';
                            $display_kat = 'Kegiatan Sekolah';
                        } elseif ($kat_db == 'prestasi siswa') {
                            $cat_class = 'category-achievement';
                            $display_kat = 'Prestasi Siswa';
                        } else {
                            $cat_class = 'category-extracurricular';
                            $display_kat = 'Kegiatan Eskul';
                        }
                    ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td>
                                <?php if (!empty($row['foto']) && file_exists('../' . $row['foto'])) : ?>
                                    <img src="../<?= $row['foto'] ?>" class="galeri-thumb" alt="Foto">
                                <?php else : ?>
                                    <div class="no-thumb"><i class="fas fa-image"></i></div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div style="font-weight: 600; color: var(--primary);"><?= htmlspecialchars($row['judul']) ?></div>
                                <?php if (!empty($row['deskripsi'])) : ?>
                                    <div style="font-size: 0.8rem; color: #64748b; margin-top: 4px; display: -webkit-box; -webkit-line-clamp: 2; line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                        <?= htmlspecialchars($row['deskripsi']) ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td><span class="badge <?= $cat_class ?>"><?= $display_kat ?></span></td>
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
                    <?php endwhile; ?>
                    <?php if (mysqli_num_rows($res) == 0) : ?>
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 40px; color: #94a3b8;">
                                <i class="fas fa-images" style="font-size: 3rem; display: block; margin-bottom: 10px; opacity: 0.3;"></i>
                                Belum ada foto galeri.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Form -->
<div class="form-overlay" id="formOverlay">
    <div class="form-card">
        <div class="form-header">
            <h2 id="formTitle"><i class="fas fa-plus-circle"></i> Tambah Foto</h2>
            <div class="close-form" onclick="closeForm()">
                <i class="fas fa-times"></i>
            </div>
        </div>
        <form id="galeriForm" action="" method="POST" enctype="multipart/form-data" style="display: contents;">
            <div class="form-body">
                <input type="hidden" name="aksi" id="formAksi" value="tambah">
                <input type="hidden" name="id" id="galeriId" value="">

                <div class="form-group">
                    <label>Judul Galeri <span style="color: red;">*</span></label>
                    <input type="text" name="judul" id="judul" required placeholder="Contoh: Upacara Bendera">
                </div>

                <div class="form-group">
                    <label>Kategori & Lokasi <span style="color: red;">*</span></label>
                    <select name="kategori" id="kategori" required>
                        <option value="Sekolah">Lingkungan Sekolah</option>
                        <option value="Prestasi">Prestasi Siswa</option>
                        <option value="Ekstrakurikuler">Kegiatan Ekskul</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Deskripsi Singkat</label>
                    <textarea name="keterangan" id="keterangan" rows="3" placeholder="Ceritakan tentang foto ini..."></textarea>
                </div>

                <div class="form-group">
                    <label>Pilih File Foto</label>
                    <div class="file-upload-wrapper">
                        <input type="file" name="foto" id="foto" class="file-upload-input" onchange="previewImage(this)">
                        <div class="file-upload-label" id="uploadLabelArea">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <span>Klik atau seret foto ke sini</span>
                            <p style="font-size: 0.8rem; margin-top: 5px;">Format: JPG, PNG, GIF (Maks. 10MB)</p>
                        </div>
                    </div>
                </div>

                <div id="imagePreview" class="image-preview" style="display: none; margin-top: 15px; border-radius: 12px; overflow: hidden; border: 1px solid #e2e8f0;">
                    <img src="" id="previewImg" style="width: 100%; height: auto; display: block;">
                </div>
            </div>

            <div class="form-footer">
                <button type="button" class="btn-cancel-delete" onclick="closeForm()">
                    <i class="fas fa-times"></i> Batal
                </button>
                <button type="submit" class="btn-submit" id="submitBtn">
                    <i class="fas fa-save"></i> <span id="btnText">Simpan Foto</span>
                </button>
            </div>
        </form>
    </div>
</div>



<script>
    // Standardized Form Functions
    function openAddForm() {
        const overlay = document.getElementById('formOverlay');
        const form = document.getElementById('galeriForm');
        document.getElementById('formTitle').innerHTML = '<i class="fas fa-plus-circle"></i> Tambah Foto Baru';
        document.getElementById('btnText').innerText = 'Simpan Foto';
        document.getElementById('formAksi').value = 'tambah';
        form.reset();
        document.getElementById('galeriId').value = '';
        document.getElementById('imagePreview').style.display = 'none';
        document.getElementById('previewImg').src = ''; // Clear previous image
        document.getElementById('uploadLabelArea').querySelector('span').innerText = 'Klik atau seret foto ke sini'; // Reset upload text
        overlay.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function openEditForm(data) {
        const overlay = document.getElementById('formOverlay');
        const form = document.getElementById('galeriForm');
        document.getElementById('formTitle').innerHTML = '<i class="fas fa-edit"></i> Edit Foto';
        document.getElementById('btnText').innerText = 'Update Foto';
        document.getElementById('formAksi').value = 'edit';

        document.getElementById('galeriId').value = data.id || '';
        document.getElementById('judul').value = data.judul || '';
        document.getElementById('kategori').value = data.kategori || '';
        document.getElementById('keterangan').value = data.deskripsi || data.keterangan || '';

        // Preview image if exists
        const preview = document.getElementById('imagePreview');
        const previewImg = document.getElementById('previewImg');
        if (data.foto) {
            previewImg.src = '../' + data.foto;
            preview.style.display = 'block';
        } else {
            preview.style.display = 'none';
            previewImg.src = '';
        }
        document.getElementById('uploadLabelArea').querySelector('span').innerText = 'Klik atau seret foto ke sini'; // Reset upload text

        overlay.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeForm() {
        document.getElementById('formOverlay').style.display = 'none';
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

    function showDeleteModal(id, judul) {
        if (confirm('Apakah Anda yakin ingin menghapus foto "' + judul + '"?')) {
            window.location.href = 'galeri.php?aksi=hapus&id=' + id;
        }
    }

    // Search logic
    document.getElementById('searchInput').addEventListener('keyup', function() {
        const value = this.value.toLowerCase();
        const rows = document.querySelectorAll('#galeriTable tbody tr');
        rows.forEach(row => {
            if (row.innerText.toLowerCase().includes(value)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Close on overlay click
    window.onclick = function(e) {
        if (e.target == document.getElementById('formOverlay')) closeForm();
    }
</script>

<?php include 'layout/footer.php'; ?>