<?php
$title = "Manajemen Fasilitas";
include '../database/conn.php';

// Ensure tables exist
$chk = mysqli_query($conn, "SHOW TABLES LIKE 'fasilitas'");
if (mysqli_num_rows($chk) == 0) {
    $conn->query("CREATE TABLE `fasilitas` (
        `id` int NOT NULL AUTO_INCREMENT,
        `nama` varchar(200) NOT NULL,
        `deskripsi` text NOT NULL,
        `icon` varchar(50) NOT NULL DEFAULT 'fa-building',
        `color` varchar(20) NOT NULL DEFAULT '#3b82f6',
        `tag` varchar(100) NOT NULL DEFAULT '',
        `sort_order` int NOT NULL DEFAULT 0,
        `is_active` tinyint(1) NOT NULL DEFAULT 1,
        `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
        `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");
}
$chk2 = mysqli_query($conn, "SHOW TABLES LIKE 'fasilitas_images'");
if (mysqli_num_rows($chk2) == 0) {
    $conn->query("CREATE TABLE `fasilitas_images` (
        `id` int NOT NULL AUTO_INCREMENT,
        `fasilitas_id` int NOT NULL,
        `filename` varchar(255) NOT NULL,
        `sort_order` int NOT NULL DEFAULT 0,
        `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        KEY `idx_fasilitas_id` (`fasilitas_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");
}

// Fetch data
$fasilitas_list = $conn->query("SELECT * FROM fasilitas ORDER BY sort_order ASC, id ASC");

$msg = $_GET['msg'] ?? '';
$text = $_GET['text'] ?? '';

include 'layout/header.php';
?>

<style>
.fas-status-badge { display:inline-flex;align-items:center;gap:5px;padding:4px 12px;border-radius:50px;font-size:0.75rem;font-weight:700; }
.fas-status-active { background:#f0fdf4;color:#16a34a;border:1px solid #bbf7d0; }
.fas-status-inactive { background:#fef2f2;color:#dc2626;border:1px solid #fecaca; }
.fas-color-dot { display:inline-block;width:18px;height:18px;border-radius:50%;vertical-align:middle;border:2px solid rgba(0,0,0,0.1); }
.fas-thumb-grid { display:flex;gap:6px;flex-wrap:wrap; }
.fas-thumb-item { width:50px;height:50px;border-radius:10px;overflow:hidden;border:2px solid #e2e8f0; }
.fas-thumb-item img { width:100%;height:100%;object-fit:cover; }
.fas-no-img { width:50px;height:50px;border-radius:10px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;color:#94a3b8;font-size:1rem; }
.fas-img-manage { display:flex;flex-wrap:wrap;gap:10px;margin-top:10px; }
.fas-img-card { position:relative;width:100px;height:80px;border-radius:12px;overflow:hidden;border:2px solid #e2e8f0; }
.fas-img-card img { width:100%;height:100%;object-fit:cover; }
.fas-img-del { position:absolute;top:4px;right:4px;width:22px;height:22px;border-radius:50%;background:rgba(239,68,68,0.9);color:#fff;border:none;cursor:pointer;font-size:0.65rem;display:flex;align-items:center;justify-content:center; }
.fas-img-del:hover { background:#dc2626;transform:scale(1.1); }
.fas-preview-grid { display:flex;flex-wrap:wrap;gap:8px;margin-top:8px; }
.fas-preview-item { width:80px;height:60px;border-radius:8px;overflow:hidden;border:2px solid #e2e8f0; }
.fas-preview-item img { width:100%;height:100%;object-fit:cover; }
.vm-btn-action { width:38px;height:38px;border-radius:10px;border:none;cursor:pointer;transition:all 0.25s ease;display:inline-flex;align-items:center;justify-content:center;font-size:0.95rem;text-decoration:none; }
.vm-btn-edit { background:rgba(245,158,11,0.1);color:#f59e0b; }
.vm-btn-edit:hover { background:#f59e0b;color:white;transform:translateY(-2px);box-shadow:0 4px 12px rgba(245,158,11,0.35); }
.vm-btn-delete { background:rgba(244,63,94,0.1);color:#f43f5e; }
.vm-btn-delete:hover { background:#f43f5e;color:white;transform:translateY(-2px);box-shadow:0 4px 12px rgba(244,63,94,0.35); }
</style>

<div class="admin-page-container">
<?php if ($msg): ?>
<div class="alert-container" id="alertContainer">
<div class="alert alert-<?= $msg ?>">
<i class="fas <?= $msg=='success'?'fa-check-circle':'fa-exclamation-circle' ?>"></i>
<div class="alert-content"><div class="alert-title"><?= $msg=='success'?'Berhasil!':'Gagal!' ?></div>
<div class="alert-message"><?= htmlspecialchars($text) ?></div></div>
<div class="alert-close" onclick="this.closest('.alert-container').remove()"><i class="fas fa-times"></i></div>
</div></div>
<?php endif; ?>

<div class="header-panel stagger-item stagger-1">
<div><h1><i class="fas fa-school" style="margin-right:10px"></i>Manajemen Fasilitas</h1>
<p style="color:var(--gray)">Kelola data fasilitas sekolah yang tampil di halaman Fasilitas.</p></div>
<div style="display:flex;gap:8px">
<a href="../pages/fasilitas.php" target="_blank" class="btn-add" style="text-decoration:none;background:#f1f5f9;color:#64748b;border:1px solid #e2e8f0"><i class="fas fa-external-link-alt"></i> Lihat Halaman</a>
<button class="btn-add" onclick="openAddForm()"><i class="fas fa-plus"></i> Tambah Fasilitas</button>
</div>
</div>

<div class="card-panel stagger-item stagger-2" style="border-radius:20px">
<div class="table-header"><h3><i class="fas fa-building" style="margin-right:10px;color:var(--accent)"></i>Data Fasilitas Sekolah</h3>
<div class="header-search"><i class="fas fa-search"></i><input type="text" id="searchInput" placeholder="Cari fasilitas..."></div>
</div>
<div class="table-responsive"><table class="data-table" id="fasTable">
<thead><tr><th width="50">No</th><th>Gambar</th><th>Nama</th><th>Kategori</th><th>Warna</th><th>Urutan</th><th>Status</th><th width="120">Aksi</th></tr></thead>
<tbody>
<?php $no=1; if($fasilitas_list && $fasilitas_list->num_rows > 0): while($f = $fasilitas_list->fetch_assoc()):
    $imgs = $conn->query("SELECT * FROM fasilitas_images WHERE fasilitas_id = {$f['id']} ORDER BY sort_order ASC");
?>
<tr>
<td><?= $no++ ?></td>
<td>
<div class="fas-thumb-grid">
<?php if($imgs && $imgs->num_rows > 0): $img1 = $imgs->fetch_assoc(); ?>
<div class="fas-thumb-item"><img src="../upload/img/fasilitas/<?= htmlspecialchars($img1['filename']) ?>" alt=""></div>
<?php if($imgs->num_rows > 0): ?><div class="fas-no-img" title="<?= $imgs->num_rows ?> gambar lagi">+<?= $imgs->num_rows ?></div><?php endif; ?>
<?php else: ?>
<div class="fas-no-img"><i class="fas fa-image"></i></div>
<?php endif; ?>
</div>
</td>
<td><div style="font-weight:700;color:var(--dark)"><?= htmlspecialchars($f['nama']) ?></div>
<div style="font-size:0.78rem;color:#94a3b8;margin-top:2px;max-width:200px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis"><?= htmlspecialchars(mb_substr($f['deskripsi'],0,50)) ?>...</div></td>
<td><span style="background:<?= htmlspecialchars($f['color']) ?>15;color:<?= htmlspecialchars($f['color']) ?>;padding:4px 12px;border-radius:8px;font-weight:600;font-size:0.8rem"><i class="fas <?= htmlspecialchars($f['icon']) ?>" style="margin-right:4px"></i><?= htmlspecialchars($f['tag']) ?></span></td>
<td><span class="fas-color-dot" style="background:<?= htmlspecialchars($f['color']) ?>"></span></td>
<td><?= $f['sort_order'] ?></td>
<td><span class="fas-status-badge <?= $f['is_active'] ? 'fas-status-active' : 'fas-status-inactive' ?>"><i class="fas fa-circle" style="font-size:0.4rem"></i><?= $f['is_active'] ? 'Aktif' : 'Nonaktif' ?></span></td>
<td><div style="display:flex;gap:6px">
<button class="vm-btn-action vm-btn-edit" onclick='openEditForm(<?= json_encode($f) ?>)' title="Edit"><i class="fas fa-edit"></i></button>
<a href="fasilitas-process.php?action=toggle_status&id=<?= $f['id'] ?>" class="vm-btn-action" style="background:rgba(14,165,233,0.1);color:#0ea5e9" title="Toggle Status"><i class="fas fa-power-off"></i></a>
<a href="javascript:void(0)" onclick="confirmDelete(<?= $f['id'] ?>,'<?= htmlspecialchars(addslashes($f['nama'])) ?>')" class="vm-btn-action vm-btn-delete" title="Hapus"><i class="fas fa-trash"></i></a>
</div></td>
</tr>
<?php endwhile; else: ?>
<tr><td colspan="8" style="text-align:center;padding:3rem;color:#94a3b8"><i class="fas fa-building" style="font-size:2.5rem;display:block;margin-bottom:10px;opacity:0.3"></i>Belum ada data fasilitas</td></tr>
<?php endif; ?>
</tbody></table></div>
</div>
</div>

<!-- MODAL FORM -->
<div class="modal-overlay" id="modalFasilitas" style="display:none">
<div class="modal-card" style="max-width:700px">
<div class="modal-header"><h2 id="fasFormTitle"><i class="fas fa-plus"></i> Tambah Fasilitas</h2>
<div class="btn-close-modal" onclick="closeModal()"><i class="fas fa-times"></i></div></div>
<form action="fasilitas-process.php" method="POST" enctype="multipart/form-data" style="display:contents" id="fasForm">
<input type="hidden" name="action" value="save">
<input type="hidden" name="id" id="fas_id" value="">
<div class="modal-body" style="max-height:65vh;overflow-y:auto">
<div class="form-group"><label><i class="fas fa-building" style="margin-right:5px;color:var(--accent)"></i>Nama Fasilitas <span style="color:red">*</span></label>
<input type="text" name="nama" id="fas_nama" required placeholder="Contoh: Ruang Kelas"></div>

<div class="form-group"><label><i class="fas fa-align-left" style="margin-right:5px;color:var(--accent)"></i>Deskripsi <span style="color:red">*</span></label>
<textarea name="deskripsi" id="fas_deskripsi" required placeholder="Deskripsi lengkap fasilitas..." style="min-height:100px;resize:vertical;font-family:inherit;padding:12px;border:1px solid #e2e8f0;border-radius:12px;width:100%;box-sizing:border-box"></textarea></div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:15px">
<div class="form-group"><label><i class="fas fa-icons" style="margin-right:5px;color:var(--accent)"></i>Icon (FA class)</label>
<input type="text" name="icon" id="fas_icon" value="fa-building" placeholder="fa-building">
<small style="color:var(--gray)">Tanpa prefix "fas". Contoh: fa-flask, fa-book-open</small></div>
<div class="form-group"><label><i class="fas fa-tag" style="margin-right:5px;color:var(--accent)"></i>Kategori / Tag</label>
<input type="text" name="tag" id="fas_tag" placeholder="Contoh: Akademik"></div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:15px">
<div class="form-group"><label><i class="fas fa-palette" style="margin-right:5px;color:var(--accent)"></i>Warna Aksen</label>
<input type="color" name="color" id="fas_color" value="#3b82f6" style="height:42px;border-radius:10px;width:100%"></div>
<div class="form-group"><label><i class="fas fa-sort-numeric-up" style="margin-right:5px;color:var(--accent)"></i>Urutan</label>
<input type="number" name="sort_order" id="fas_sort" min="0" value="0"></div>
<div class="form-group"><label><i class="fas fa-toggle-on" style="margin-right:5px;color:var(--accent)"></i>Status</label>
<div style="display:flex;align-items:center;gap:10px;height:42px">
<label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-weight:500">
<input type="checkbox" name="is_active" id="fas_active" checked style="width:18px;height:18px;accent-color:#0ea5e9"> Aktif
</label></div></div>
</div>

<!-- Existing Images (edit mode) -->
<div class="form-group" id="existingImagesGroup" style="display:none">
<label><i class="fas fa-images" style="margin-right:5px;color:var(--accent)"></i>Gambar Saat Ini</label>
<div class="fas-img-manage" id="existingImages"></div>
</div>

<!-- Upload New Images -->
<div class="form-group">
<label><i class="fas fa-cloud-upload-alt" style="margin-right:5px;color:var(--accent)"></i>Upload Gambar (Multiple)</label>
<div class="file-upload-wrapper">
<input type="file" name="images[]" multiple accept="image/*" class="file-upload-input" id="fasFileInput" onchange="previewFiles(this)">
<div class="file-upload-label"><i class="fas fa-cloud-upload-alt"></i>
<span>Klik atau seret gambar ke sini (Multi-select)</span>
<p style="font-size:0.8rem;margin-top:5px">Format: JPG, PNG, GIF, WEBP (Maks. 3MB per file)</p></div>
</div>
<div class="fas-preview-grid" id="previewGrid"></div>
</div>
</div>

<div class="modal-footer">
<button type="button" class="btn-cancel" onclick="closeModal()"><i class="fas fa-times"></i> Batal</button>
<button type="submit" class="btn-submit"><i class="fas fa-save"></i> <span id="fasBtnText">Simpan</span></button>
</div>
</form></div></div>

<!-- DELETE CONFIRM -->
<div class="modal-overlay" id="deleteModal" style="display:none">
<div class="modal-card" style="max-width:400px">
<div class="modal-header"><h2><i class="fas fa-exclamation-triangle" style="color:#ef4444;margin-right:10px"></i>Konfirmasi Hapus</h2>
<div class="btn-close-modal" onclick="document.getElementById('deleteModal').style.display='none';document.body.style.overflow=''"><i class="fas fa-times"></i></div></div>
<div class="modal-body" style="text-align:center;padding:2rem 1.5rem">
<p id="deleteMsg" style="font-size:1.05rem;color:#64748b;margin-bottom:2rem">Yakin ingin menghapus fasilitas ini?</p>
<div style="display:flex;gap:1rem;justify-content:center">
<button type="button" class="btn-cancel" onclick="document.getElementById('deleteModal').style.display='none';document.body.style.overflow=''"><i class="fas fa-times"></i> Batal</button>
<a href="#" id="deleteConfirmBtn" class="btn-delete" style="display:inline-flex;align-items:center;gap:6px;padding:10px 20px;background:#ef4444;color:#fff;border-radius:12px;text-decoration:none;font-weight:600;font-size:0.9rem"><i class="fas fa-trash"></i> Hapus</a>
</div></div></div></div>

<script>
function openAddForm(){
    document.getElementById('fas_id').value='';
    document.getElementById('fas_nama').value='';
    document.getElementById('fas_deskripsi').value='';
    document.getElementById('fas_icon').value='fa-building';
    document.getElementById('fas_color').value='#3b82f6';
    document.getElementById('fas_tag').value='';
    document.getElementById('fas_sort').value='0';
    document.getElementById('fas_active').checked=true;
    document.getElementById('existingImagesGroup').style.display='none';
    document.getElementById('existingImages').innerHTML='';
    document.getElementById('previewGrid').innerHTML='';
    document.getElementById('fasFormTitle').innerHTML='<i class="fas fa-plus"></i> Tambah Fasilitas';
    document.getElementById('fasBtnText').textContent='Simpan';
    showModal();
}

function openEditForm(d){
    document.getElementById('fas_id').value=d.id;
    document.getElementById('fas_nama').value=d.nama||'';
    document.getElementById('fas_deskripsi').value=d.deskripsi||'';
    document.getElementById('fas_icon').value=d.icon||'fa-building';
    document.getElementById('fas_color').value=d.color||'#3b82f6';
    document.getElementById('fas_tag').value=d.tag||'';
    document.getElementById('fas_sort').value=d.sort_order||0;
    document.getElementById('fas_active').checked=(d.is_active==1);
    document.getElementById('fasFormTitle').innerHTML='<i class="fas fa-edit"></i> Edit Fasilitas';
    document.getElementById('fasBtnText').textContent='Update';
    document.getElementById('previewGrid').innerHTML='';

    // Load existing images via inline data
    loadExistingImages(d.id);
    showModal();
}

function loadExistingImages(fasId){
    var container=document.getElementById('existingImages');
    var group=document.getElementById('existingImagesGroup');
    // Fetch images using a simple AJAX call
    fetch('fasilitas-get-images.php?id='+fasId)
    .then(r=>r.json())
    .then(data=>{
        if(data.length>0){
            group.style.display='block';
            container.innerHTML='';
            data.forEach(img=>{
                container.innerHTML+=`<div class="fas-img-card"><img src="../upload/img/fasilitas/${img.filename}" alt="">
                <a href="fasilitas-process.php?action=delete_image&img_id=${img.id}&fas_id=${fasId}" class="fas-img-del" onclick="return confirm('Hapus gambar ini?')"><i class="fas fa-times"></i></a></div>`;
            });
        } else { group.style.display='none'; container.innerHTML=''; }
    }).catch(()=>{ group.style.display='none'; });
}

function showModal(){
    var m=document.getElementById('modalFasilitas');
    var mc=m.querySelector('.modal-card');
    mc.style.animation='none';mc.offsetHeight;
    mc.style.animation='editModalIn 0.4s cubic-bezier(0.34,1.56,0.64,1)';
    m.style.display='flex';document.body.style.overflow='hidden';
}
function closeModal(){document.getElementById('modalFasilitas').style.display='none';document.body.style.overflow='';}

function confirmDelete(id,nama){
    document.getElementById('deleteMsg').innerHTML='Yakin ingin menghapus fasilitas <strong>"'+nama+'"</strong> beserta semua gambarnya?<br>Tindakan ini tidak dapat dibatalkan.';
    document.getElementById('deleteConfirmBtn').href='fasilitas-process.php?action=delete&id='+id;
    document.getElementById('deleteModal').style.display='flex';document.body.style.overflow='hidden';
}

function previewFiles(input){
    var grid=document.getElementById('previewGrid');
    grid.innerHTML='';
    if(input.files){
        Array.from(input.files).forEach(file=>{
            if(file.size>3*1024*1024){alert('File "'+file.name+'" melebihi 3MB!');return;}
            var reader=new FileReader();
            reader.onload=function(e){
                grid.innerHTML+='<div class="fas-preview-item"><img src="'+e.target.result+'"></div>';
            };
            reader.readAsDataURL(file);
        });
    }
}

// Search
document.getElementById('searchInput').addEventListener('keyup',function(){
    var v=this.value.toLowerCase();
    document.querySelectorAll('#fasTable tbody tr').forEach(r=>{r.style.display=r.innerText.toLowerCase().includes(v)?'':'none';});
});

// Close modals
document.querySelectorAll('.modal-overlay').forEach(m=>{m.addEventListener('click',function(e){if(e.target===this){this.style.display='none';document.body.style.overflow='';}});});
document.addEventListener('keydown',function(e){if(e.key==='Escape'){document.querySelectorAll('.modal-overlay').forEach(m=>{m.style.display='none'});document.body.style.overflow='';}});
setTimeout(function(){var a=document.getElementById('alertContainer');if(a){a.style.transition='opacity 0.5s';a.style.opacity='0';setTimeout(()=>a.remove(),500);}},5000);
document.addEventListener('DOMContentLoaded',function(){document.querySelectorAll('.modal-overlay').forEach(m=>document.body.appendChild(m));});
</script>

<?php include 'layout/footer.php'; ?>
