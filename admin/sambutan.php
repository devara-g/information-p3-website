<?php
$title = "Sambutan Kepala Sekolah";
include '../database/conn.php';

// Proses CRUD
$message = '';
$messageType = '';

// Tangkap pesan dari redirect
if (isset($_GET['msg'])) {
    if ($_GET['msg'] == 'updated') {
        $message = "Data sambutan berhasil diupdate!";
        $messageType = "success";
    }
}

// Pastikan tabel sambutan ada
$tableCheck = mysqli_query($conn, "SHOW TABLES LIKE 'sambutan'");
if (mysqli_num_rows($tableCheck) == 0) {
    $createTable = "CREATE TABLE `sambutan` (
        `id` int NOT NULL AUTO_INCREMENT,
        `nama_kepsek` varchar(150) NOT NULL,
        `foto_kepsek` varchar(255) DEFAULT NULL,
        `pesan_sambutan` text NOT NULL,
        `jumlah_siswa` int NOT NULL DEFAULT 0,
        `jumlah_pengajar` int NOT NULL DEFAULT 0,
        `tanggal_sambutan` date NOT NULL,
        `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci";
    mysqli_query($conn, $createTable);

    // Insert default data
    $defaultPesan = "Segala puji bagi Allah SWT yang telah memberikan rahmat dan hidayah-Nya. Dengan penuh kebanggaan, saya menyambut Anda di website resmi SMP PGRI 3 BOGOR.\n\nKami berkomitmen untuk memberikan pendidikan terbaik bagi putra-putri Anda — mencetak generasi yang tidak hanya cerdas secara intelektual, tetapi juga memiliki karakter yang kuat dan berakhlak mulia.\n\nDidukung oleh tenaga pengajar profesional dan fasilitas modern, kami yakin mampu menghasilkan lulusan yang kompeten, kreatif, dan siap menghadapi tantangan global.\n\nMari bersama kita wujudkan generasi yang cerdas, inovatif, dan berdaya saing tinggi untuk Indonesia yang lebih maju.";
    $defaultPesan = $conn->real_escape_string($defaultPesan);
    mysqli_query($conn, "INSERT INTO sambutan (nama_kepsek, foto_kepsek, pesan_sambutan, jumlah_siswa, jumlah_pengajar, tanggal_sambutan) VALUES ('Drs. Indra Robriandri, M.Si', NULL, '$defaultPesan', 800, 45, '2026-02-01')");
}

// HANDLE SIMPAN/UPDATE DATA
if (isset($_POST['action']) && $_POST['action'] == 'save') {
    $id              = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $nama_kepsek     = $conn->real_escape_string(strip_tags(trim($_POST['nama_kepsek'])));
    $pesan_sambutan  = $conn->real_escape_string(trim($_POST['pesan_sambutan']));
    $jumlah_siswa    = (int)$_POST['jumlah_siswa'];
    $jumlah_pengajar = (int)$_POST['jumlah_pengajar'];
    $tanggal_sambutan = $conn->real_escape_string($_POST['tanggal_sambutan']);

    // Validasi input
    if (empty($nama_kepsek) || empty($pesan_sambutan) || empty($tanggal_sambutan)) {
        $message = "Semua field wajib diisi!";
        $messageType = "error";
    } elseif ($jumlah_siswa < 0 || $jumlah_pengajar < 0) {
        $message = "Jumlah siswa dan pengajar tidak boleh negatif!";
        $messageType = "error";
    } else {
        // Handle upload foto
        $foto_sql = "";
        $upload_ok = true;

        if (isset($_FILES['foto_kepsek']) && $_FILES['foto_kepsek']['error'] == 0) {
            $allowed_types = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime_type = finfo_file($finfo, $_FILES['foto_kepsek']['tmp_name']);
            finfo_close($finfo);

            if (!in_array($mime_type, $allowed_types)) {
                $message = "File foto tidak valid (format: JPG, JPEG, PNG, GIF, WEBP)";
                $messageType = "error";
                $upload_ok = false;
            } elseif ($_FILES['foto_kepsek']['size'] > 3 * 1024 * 1024) {
                $message = "Ukuran file maksimal 3MB. <br><br><a href='https://www.iloveimg.com/compress-image' target='_blank' style='color: #2563eb; text-decoration: underline; font-weight: bold;'>Klik di sini untuk kompress foto online</a>";
                $messageType = "error";
                $upload_ok = false;
            } else {
                // Hapus foto lama jika update
                if ($id > 0) {
                    $oldResult = $conn->query("SELECT foto_kepsek FROM sambutan WHERE id = $id");
                    $oldData = $oldResult->fetch_assoc();
                    if ($oldData && $oldData['foto_kepsek'] && file_exists('../upload/img/' . $oldData['foto_kepsek'])) {
                        unlink('../upload/img/' . $oldData['foto_kepsek']);
                    }
                }

                // Upload foto baru
                $ext = strtolower(pathinfo($_FILES['foto_kepsek']['name'], PATHINFO_EXTENSION));
                $foto_filename = 'sambutan_' . uniqid() . '.' . $ext;
                $upload_path = '../upload/img/' . $foto_filename;

                if (!is_dir('../upload/img/')) {
                    mkdir('../upload/img/', 0755, true);
                }

                if (move_uploaded_file($_FILES['foto_kepsek']['tmp_name'], $upload_path)) {
                    $foto_sql = ", foto_kepsek = '$foto_filename'";
                } else {
                    $message = "Gagal mengupload foto. Pastikan folder upload tersedia.";
                    $messageType = "error";
                    $upload_ok = false;
                }
            }
        }

        if ($upload_ok && empty($message)) {
            if ($id > 0) {
                // UPDATE
                $sql = "UPDATE sambutan SET
                        nama_kepsek      = '$nama_kepsek',
                        pesan_sambutan   = '$pesan_sambutan',
                        jumlah_siswa     = $jumlah_siswa,
                        jumlah_pengajar  = $jumlah_pengajar,
                        tanggal_sambutan = '$tanggal_sambutan'
                        $foto_sql
                        WHERE id = $id";
            } else {
                // INSERT
                $foto_val = str_replace(", foto_kepsek = '", "", $foto_sql);
                $foto_val = str_replace("'", "", $foto_val);
                $sql = "INSERT INTO sambutan (nama_kepsek, foto_kepsek, pesan_sambutan, jumlah_siswa, jumlah_pengajar, tanggal_sambutan)
                        VALUES ('$nama_kepsek', '$foto_val', '$pesan_sambutan', $jumlah_siswa, $jumlah_pengajar, '$tanggal_sambutan')";
            }

            if ($conn->query($sql)) {
                header("Location: sambutan.php?msg=updated");
                exit;
            } else {
                $message = "Error: " . $conn->error;
                $messageType = "error";
            }
        }
    }
}

// AMBIL DATA SAMBUTAN (hanya 1 row)
$sambutan = null;
$result = $conn->query("SELECT * FROM sambutan ORDER BY id DESC LIMIT 1");
if ($result && $result->num_rows > 0) {
    $sambutan = $result->fetch_assoc();
}

include 'layout/header.php';
?>

<div class="admin-page-container">
    <!-- Alert Message -->
    <?php if ($message): ?>
        <div class="alert-container" id="alertContainer">
            <div class="alert alert-<?php echo $messageType; ?>">
                <i class="fas <?php
                    if ($messageType == 'success') echo 'fa-check-circle';
                    elseif ($messageType == 'warning') echo 'fa-exclamation-triangle';
                    else echo 'fa-exclamation-circle';
                ?>"></i>
                <div class="alert-content">
                    <div class="alert-title">
                        <?php
                        if ($messageType == 'success') echo 'Berhasil!';
                        elseif ($messageType == 'warning') echo 'Peringatan!';
                        else echo 'Gagal!';
                        ?>
                    </div>
                    <div class="alert-message">
                        <?php echo $message; ?>
                    </div>
                </div>
                <div class="alert-close" onclick="this.closest('.alert-container').remove()">
                    <i class="fas fa-times"></i>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="header-panel stagger-item stagger-1">
        <div>
            <h1>Sambutan Kepala Sekolah</h1>
            <p style="color: var(--gray);">Kelola konten sambutan kepala sekolah yang tampil di halaman beranda.</p>
        </div>
        <button class="btn-add" onclick="openEditForm()">
            <i class="fas fa-edit"></i> Edit Sambutan
        </button>
    </div>

    <!-- Preview Card -->
    <div class="card-panel stagger-item stagger-2" style="margin-bottom: 2rem; border-radius: 20px;">
        <div class="table-header" style="border-bottom: none; padding-bottom: 0; margin-bottom: 0; box-shadow: none; border: none; padding: 0;">
            <h3><i class="fas fa-eye" style="margin-right: 10px; color: var(--accent);"></i> Preview Sambutan</h3>
            <a href="../index.php" target="_blank" class="btn-sm" style="text-decoration: none;">
                <i class="fas fa-external-link-alt"></i> Lihat di Website
            </a>
        </div>
    </div>

    <?php if ($sambutan): ?>
    <div class="card-panel stagger-item stagger-3" style="border-radius: 20px; padding: 2rem;">
        <div style="display: grid; grid-template-columns: 250px 1fr; gap: 2rem; align-items: start;">
            <!-- Left: Photo & Info -->
            <div style="text-align: center;">
                <div style="width: 180px; height: 180px; border-radius: 50%; overflow: hidden; margin: 0 auto 1rem; border: 4px solid #e2e8f0; box-shadow: 0 8px 25px rgba(0,0,0,0.1);">
                    <?php if ($sambutan['foto_kepsek'] && file_exists('../upload/img/' . $sambutan['foto_kepsek'])): ?>
                        <img src="../upload/img/<?= htmlspecialchars($sambutan['foto_kepsek']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                    <?php elseif (file_exists('../img/kepsek.jpg')): ?>
                        <img src="../img/kepsek.jpg" style="width: 100%; height: 100%; object-fit: cover;">
                    <?php else: ?>
                        <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #0b2d72, #2563eb); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 3rem; font-weight: 700;">
                            <?= strtoupper(substr($sambutan['nama_kepsek'], 0, 1)) ?>
                        </div>
                    <?php endif; ?>
                </div>
                <h3 style="margin: 0.5rem 0 0.25rem; color: var(--dark);"><?= htmlspecialchars($sambutan['nama_kepsek']) ?></h3>
                <p style="color: var(--gray); font-size: 0.9rem;"><i class="fas fa-graduation-cap"></i> Kepala Sekolah</p>

                <div style="display: flex; justify-content: center; gap: 1.5rem; margin-top: 1.2rem;">
                    <div style="text-align: center;">
                        <div style="font-size: 1.5rem; font-weight: 700; color: var(--accent);"><?= number_format($sambutan['jumlah_siswa']) ?></div>
                        <div style="font-size: 0.75rem; color: var(--gray);">Siswa Aktif</div>
                    </div>
                    <div style="width: 1px; background: #e2e8f0;"></div>
                    <div style="text-align: center;">
                        <div style="font-size: 1.5rem; font-weight: 700; color: var(--accent);"><?= number_format($sambutan['jumlah_pengajar']) ?></div>
                        <div style="font-size: 0.75rem; color: var(--gray);">Tenaga Pengajar</div>
                    </div>
                </div>
            </div>

            <!-- Right: Message -->
            <div>
                <div style="background: #f8fafc; border-radius: 16px; padding: 1.5rem; position: relative; border-left: 4px solid var(--accent);">
                    <div style="font-size: 2.5rem; color: var(--accent); opacity: 0.3; font-family: serif; line-height: 1; margin-bottom: 0.5rem;">&ldquo;</div>
                    <div style="color: #475569; line-height: 1.8; font-size: 0.95rem; white-space: pre-line;"><?= htmlspecialchars($sambutan['pesan_sambutan']) ?></div>
                </div>
                <div style="margin-top: 1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--gray); font-size: 0.85rem;">
                    <i class="fas fa-calendar-check"></i>
                    <span><?php
                        $bulan = [
                            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
                            '04' => 'April', '05' => 'Mei', '06' => 'Juni',
                            '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
                            '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                        ];
                        $tgl = $sambutan['tanggal_sambutan'];
                        $m = $bulan[date('m', strtotime($tgl))] ?? '';
                        echo 'Bogor, ' . $m . ' ' . date('Y', strtotime($tgl));
                    ?></span>
                    <span style="margin-left: auto; font-size: 0.8rem;">
                        <i class="fas fa-clock"></i> Terakhir diupdate: <?= date('d/m/Y H:i', strtotime($sambutan['updated_at'])) ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <?php else: ?>
    <div class="card-panel stagger-item stagger-3" style="border-radius: 20px; padding: 3rem; text-align: center;">
        <div class="empty-grid-state">
            <i class="fas fa-chalkboard-teacher empty-icon"></i>
            <h3>Belum Ada Data Sambutan</h3>
            <p style="color: var(--gray);">Klik tombol "Edit Sambutan" untuk menambahkan konten sambutan kepala sekolah.</p>
            <div style="margin-top: 1.5rem;">
                <button class="btn-add" onclick="openEditForm()">
                    <i class="fas fa-plus"></i> Tambah Sambutan
                </button>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Modal Form -->
<div class="modal-overlay" id="formOverlay" style="display: none;">
    <div class="modal-card" style="max-width: 700px;">
        <div class="modal-header">
            <h2 id="formTitle">
                <i class="fas fa-edit"></i>
                Edit Sambutan Kepala Sekolah
            </h2>
            <div class="btn-close-modal" onclick="closeForm()"><i class="fas fa-times"></i></div>
        </div>
        <form action="" method="POST" enctype="multipart/form-data" id="sambutanForm" style="display: contents;">
            <input type="hidden" name="action" value="save">
            <input type="hidden" name="id" value="<?= $sambutan ? $sambutan['id'] : '' ?>">

            <div class="modal-body" style="max-height: 65vh; overflow-y: auto;">
                <!-- Nama Kepala Sekolah -->
                <div class="form-group">
                    <label><i class="fas fa-user" style="margin-right: 5px; color: var(--accent);"></i> Nama Kepala Sekolah</label>
                    <input type="text" name="nama_kepsek" id="nama_kepsek" required
                        placeholder="Contoh: Drs. Indra Robriandri, M.Si"
                        value="<?= $sambutan ? htmlspecialchars($sambutan['nama_kepsek']) : '' ?>">
                </div>

                <!-- Stats Row -->
                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px;">
                    <div class="form-group">
                        <label><i class="fas fa-users" style="margin-right: 5px; color: var(--accent);"></i> Jumlah Siswa Aktif</label>
                        <input type="number" name="jumlah_siswa" id="jumlah_siswa" required min="0"
                            placeholder="800"
                            value="<?= $sambutan ? (int)$sambutan['jumlah_siswa'] : '' ?>">
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-chalkboard-teacher" style="margin-right: 5px; color: var(--accent);"></i> Tenaga Pengajar</label>
                        <input type="number" name="jumlah_pengajar" id="jumlah_pengajar" required min="0"
                            placeholder="45"
                            value="<?= $sambutan ? (int)$sambutan['jumlah_pengajar'] : '' ?>">
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-calendar" style="margin-right: 5px; color: var(--accent);"></i> Tanggal Sambutan</label>
                        <input type="date" name="tanggal_sambutan" id="tanggal_sambutan" required
                            value="<?= $sambutan ? htmlspecialchars($sambutan['tanggal_sambutan']) : date('Y-m-d') ?>">
                    </div>
                </div>

                <!-- Pesan Sambutan -->
                <div class="form-group">
                    <label><i class="fas fa-comment-alt" style="margin-right: 5px; color: var(--accent);"></i> Pesan Sambutan</label>
                    <textarea name="pesan_sambutan" id="pesan_sambutan" required
                        placeholder="Tulis pesan sambutan kepala sekolah..."
                        style="min-height: 200px; resize: vertical; font-family: inherit; font-size: 0.95rem; line-height: 1.7; padding: 15px; border: 1px solid #e2e8f0; border-radius: 12px; width: 100%; box-sizing: border-box; transition: border-color 0.3s;"
                        onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='#e2e8f0'"
                    ><?= $sambutan ? htmlspecialchars($sambutan['pesan_sambutan']) : '' ?></textarea>
                    <small style="color: var(--gray); margin-top: 4px; display: block;">
                        <i class="fas fa-info-circle"></i> Gunakan Enter untuk paragraf baru. Salam pembuka (Assalamu'alaikum) dan penutup (Wassalamu'alaikum) akan ditampilkan otomatis.
                    </small>
                </div>

                <!-- Foto Kepala Sekolah -->
                <div class="form-group">
                    <label><i class="fas fa-camera" style="margin-right: 5px; color: var(--accent);"></i> Foto Kepala Sekolah</label>
                    <?php if ($sambutan && $sambutan['foto_kepsek'] && file_exists('../upload/img/' . $sambutan['foto_kepsek'])): ?>
                        <div id="existingPhotoWrapper" style="margin-bottom: 10px; text-align: center;">
                            <img src="../upload/img/<?= htmlspecialchars($sambutan['foto_kepsek']) ?>"
                                style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 3px solid var(--primary-light);">
                            <p style="font-size: 0.75rem; color: var(--gray); margin-top: 5px;">Foto saat ini</p>
                        </div>
                    <?php elseif (file_exists('../img/kepsek.jpg')): ?>
                        <div id="existingPhotoWrapper" style="margin-bottom: 10px; text-align: center;">
                            <img src="../img/kepsek.jpg"
                                style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 3px solid var(--primary-light);">
                            <p style="font-size: 0.75rem; color: var(--gray); margin-top: 5px;">Foto default (akan diganti setelah upload)</p>
                        </div>
                    <?php else: ?>
                        <div id="existingPhotoWrapper" style="display:none;"></div>
                    <?php endif; ?>
                    <div class="file-upload-wrapper">
                        <input type="file" name="foto_kepsek" class="file-upload-input" id="fotoInput" onchange="previewFile(this)" accept="image/*">
                        <div class="file-upload-label">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <span>Klik atau seret foto ke sini (Maks. 3MB)</span>
                            <span class="file-name" id="fileName"></span>
                        </div>
                    </div>
                </div>

                <!-- Image Preview -->
                <div id="imagePreview" style="display: none; margin-top: 15px; border-radius: 50%; width: 100px; height: 100px; overflow: hidden; border: 3px solid #e2e8f0; margin-left: auto; margin-right: auto;">
                    <img src="" id="previewImg" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeForm()">
                    <i class="fas fa-times"></i> Batal
                </button>
                <button type="submit" class="btn-submit" id="submitBtn">
                    <i class="fas fa-save"></i>
                    <span id="btnText">Simpan Perubahan</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditForm() {
        const overlay = document.getElementById('formOverlay');
        const modalCard = overlay.querySelector('.modal-card');
        modalCard.style.animation = 'none';
        modalCard.offsetHeight;
        modalCard.style.animation = 'editModalIn 0.4s cubic-bezier(0.34, 1.56, 0.64, 1)';

        overlay.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeForm() {
        document.getElementById('formOverlay').style.display = 'none';
        document.body.style.overflow = '';
    }

    function previewFile(input) {
        const file = input.files[0];
        if (file) {
            // Validasi ukuran di client-side
            if (file.size > 3 * 1024 * 1024) {
                alert('Ukuran file maksimal 3MB!');
                input.value = '';
                return;
            }

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

    // Close on overlay click
    document.getElementById('formOverlay').addEventListener('click', function(e) {
        if (e.target === this) closeForm();
    });

    // Close on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeForm();
    });

    // Auto-hide alert
    setTimeout(function() {
        const alertContainer = document.getElementById('alertContainer');
        if (alertContainer) {
            alertContainer.style.transition = 'opacity 0.5s ease';
            alertContainer.style.opacity = '0';
            setTimeout(() => alertContainer.remove(), 500);
        }
    }, 5000);

    // Move modal to body for z-index
    document.addEventListener('DOMContentLoaded', function() {
        const formOverlay = document.getElementById('formOverlay');
        if (formOverlay) document.body.appendChild(formOverlay);
    });

    // Re-open form on error
    <?php if (isset($message) && isset($messageType) && $messageType == 'error'): ?>
        document.addEventListener('DOMContentLoaded', function() {
            openEditForm();
        });
    <?php endif; ?>
</script>

<?php include 'layout/footer.php'; ?>
