<?php
$title = "Visi & Misi";
include '../database/conn.php';

// Ensure tables exist
foreach (['visi','misi','program_7k'] as $tbl) {
    $chk = mysqli_query($conn, "SHOW TABLES LIKE '$tbl'");
    if (mysqli_num_rows($chk) == 0) {
        if ($tbl == 'visi') $conn->query("CREATE TABLE visi (id int NOT NULL AUTO_INCREMENT, badge_text varchar(100) DEFAULT 'Visi Sekolah', badge_icon varchar(50) DEFAULT 'fas fa-eye', judul_section varchar(150) DEFAULT 'Visi Kami', deskripsi_section varchar(255) DEFAULT '', isi_visi text NOT NULL, updated_at timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, PRIMARY KEY(id)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        if ($tbl == 'misi') $conn->query("CREATE TABLE misi (id int NOT NULL AUTO_INCREMENT, badge_text varchar(100) DEFAULT 'Misi Sekolah', badge_icon varchar(50) DEFAULT 'fas fa-bullseye', judul_section varchar(150) DEFAULT '7 Misi Utama', deskripsi_section varchar(255) DEFAULT '', nomor int DEFAULT 0, judul varchar(200) NOT NULL, deskripsi text NOT NULL, icon varchar(50) DEFAULT 'fas fa-star', icon_bg varchar(20) DEFAULT '#eff6ff', icon_color varchar(20) DEFAULT '#3b82f6', sort_order int DEFAULT 0, updated_at timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, PRIMARY KEY(id)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        if ($tbl == 'program_7k') $conn->query("CREATE TABLE program_7k (id int NOT NULL AUTO_INCREMENT, badge_text varchar(100) DEFAULT 'Program 7K', badge_icon varchar(50) DEFAULT 'fas fa-star', judul_section varchar(150) DEFAULT '7K Sekolah', deskripsi_section varchar(255) DEFAULT '', nomor int DEFAULT 0, nama varchar(200) NOT NULL, icon varchar(10) DEFAULT '⭐', warna varchar(20) DEFAULT '#3b82f6', sort_order int DEFAULT 0, updated_at timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, PRIMARY KEY(id)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    }
}

$visi = $conn->query("SELECT * FROM visi ORDER BY id LIMIT 1")->fetch_assoc();
$misi_list = $conn->query("SELECT * FROM misi ORDER BY sort_order ASC");
$k7_list = $conn->query("SELECT * FROM program_7k ORDER BY sort_order ASC");
$misi_first = $conn->query("SELECT * FROM misi ORDER BY id LIMIT 1")->fetch_assoc();
$k7_first = $conn->query("SELECT * FROM program_7k ORDER BY id LIMIT 1")->fetch_assoc();

$msg = $_GET['msg'] ?? '';
$text = $_GET['text'] ?? '';

include 'layout/header.php';
?>

<style>
.vm-btn-action {
    width: 38px; height: 38px; border-radius: 10px; border: none; cursor: pointer;
    transition: all 0.25s ease; display: inline-flex; align-items: center;
    justify-content: center; font-size: 0.95rem; text-decoration: none;
}
.vm-btn-edit {
    background: rgba(245, 158, 11, 0.1); color: #f59e0b;
}
.vm-btn-edit:hover {
    background: #f59e0b; color: white;
    transform: translateY(-2px); box-shadow: 0 4px 12px rgba(245, 158, 11, 0.35);
}
.vm-btn-delete {
    background: rgba(244, 63, 94, 0.1); color: #f43f5e;
}
.vm-btn-delete:hover {
    background: #f43f5e; color: white;
    transform: translateY(-2px); box-shadow: 0 4px 12px rgba(244, 63, 94, 0.35);
}
.vm-btn-header {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 9px 18px; border-radius: 10px; border: 2px solid #e2e8f0;
    background: #fff; color: #64748b; font-size: 0.85rem; font-weight: 600;
    cursor: pointer; transition: all 0.25s ease; font-family: inherit;
}
.vm-btn-header:hover {
    border-color: #0ea5e9; color: #0ea5e9; background: #f0f9ff;
    transform: translateY(-2px); box-shadow: 0 4px 12px rgba(14, 165, 233, 0.15);
}
.vm-btn-header i { font-size: 0.8rem; }
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
<div><h1><i class="fas fa-eye" style="margin-right:10px"></i>Kelola Visi & Misi</h1>
<p style="color:var(--gray)">Kelola konten halaman Visi, Misi, dan Program 7K sekolah.</p></div>
<a href="../pages/visi-misi.php" target="_blank" class="btn-add" style="text-decoration:none"><i class="fas fa-external-link-alt"></i> Lihat Halaman</a>
</div>

<!-- VISI SECTION -->
<div class="card-panel stagger-item stagger-2" style="margin-bottom:2rem;border-radius:20px">
<div class="table-header"><h3><i class="fas fa-eye" style="margin-right:10px;color:var(--accent)"></i>Visi Sekolah</h3>
<button class="btn-add" onclick="document.getElementById('modalVisi').style.display='flex';document.body.style.overflow='hidden'"><i class="fas fa-edit"></i> Edit Visi</button></div>
<?php if ($visi): ?>
<div style="padding:1.5rem;background:#f8fafc;border-radius:16px;margin:1rem;border-left:4px solid var(--accent)">
<p style="margin-bottom:8px"><strong>Badge:</strong> <i class="<?= htmlspecialchars($visi['badge_icon']) ?>"></i> <?= htmlspecialchars($visi['badge_text']) ?></p>
<p style="margin-bottom:8px"><strong>Judul Section:</strong> <?= htmlspecialchars($visi['judul_section']) ?></p>
<p style="margin-bottom:8px"><strong>Deskripsi:</strong> <?= htmlspecialchars($visi['deskripsi_section']) ?></p>
<div style="margin-top:12px;padding:1rem;background:#fff;border-radius:12px;font-size:1.1rem;font-style:italic;color:#1e293b">"<?= htmlspecialchars($visi['isi_visi']) ?>"</div>
</div>
<?php else: ?>
<div style="text-align:center;padding:2rem;color:#94a3b8"><i class="fas fa-plus-circle" style="font-size:2rem;margin-bottom:10px;display:block"></i>Belum ada data visi</div>
<?php endif; ?>
</div>

<!-- MISI SECTION -->
<div class="card-panel stagger-item stagger-3" style="margin-bottom:2rem;border-radius:20px">
<div class="table-header"><h3><i class="fas fa-bullseye" style="margin-right:10px;color:var(--accent)"></i>Data Misi Sekolah</h3>
<div style="display:flex;gap:8px">
<button class="vm-btn-header" onclick="document.getElementById('modalMisiSection').style.display='flex';document.body.style.overflow='hidden'"><i class="fas fa-cog"></i> Edit Header</button>
<button class="btn-add" onclick="openMisiForm()"><i class="fas fa-plus"></i> Tambah Misi</button>
</div></div>
<div class="table-responsive"><table class="data-table">
<thead><tr><th>No</th><th>Icon</th><th>Judul</th><th>Deskripsi</th><th>Urutan</th><th>Aksi</th></tr></thead>
<tbody>
<?php if ($misi_list->num_rows > 0): while($m = $misi_list->fetch_assoc()): ?>
<tr>
<td><span style="background:<?= htmlspecialchars($m['icon_bg']) ?>;color:<?= htmlspecialchars($m['icon_color']) ?>;padding:4px 10px;border-radius:8px;font-weight:700"><?= $m['nomor'] ?></span></td>
<td><div style="width:36px;height:36px;border-radius:10px;display:flex;align-items:center;justify-content:center;background:<?= htmlspecialchars($m['icon_bg']) ?>;color:<?= htmlspecialchars($m['icon_color']) ?>"><i class="<?= htmlspecialchars($m['icon']) ?>"></i></div></td>
<td><strong><?= htmlspecialchars($m['judul']) ?></strong></td>
<td style="max-width:300px"><?= htmlspecialchars(mb_substr($m['deskripsi'],0,80)) ?>...</td>
<td><?= $m['sort_order'] ?></td>
<td><div style="display:flex;gap:6px">
<button class="vm-btn-action vm-btn-edit" onclick='editMisi(<?= json_encode($m) ?>)'><i class="fas fa-edit"></i></button>
<a href="visi-misi-process.php?action=delete&type=misi&id=<?= $m['id'] ?>" class="vm-btn-action vm-btn-delete" onclick="return confirm('Yakin hapus misi ini?')"><i class="fas fa-trash"></i></a>
</div></td>
</tr>
<?php endwhile; else: ?>
<tr><td colspan="6" style="text-align:center;padding:2rem;color:#94a3b8">Belum ada data misi</td></tr>
<?php endif; ?>
</tbody></table></div>
</div>

<!-- 7K SECTION -->
<div class="card-panel stagger-item stagger-4" style="margin-bottom:2rem;border-radius:20px">
<div class="table-header"><h3><i class="fas fa-star" style="margin-right:10px;color:var(--accent)"></i>Program 7K Sekolah</h3>
<div style="display:flex;gap:8px">
<button class="vm-btn-header" onclick="document.getElementById('modal7kSection').style.display='flex';document.body.style.overflow='hidden'"><i class="fas fa-cog"></i> Edit Header</button>
<button class="btn-add" onclick="open7kForm()"><i class="fas fa-plus"></i> Tambah 7K</button>
</div></div>
<div class="table-responsive"><table class="data-table">
<thead><tr><th>No</th><th>Icon</th><th>Nama Program</th><th>Warna</th><th>Urutan</th><th>Aksi</th></tr></thead>
<tbody>
<?php if ($k7_list->num_rows > 0): while($k = $k7_list->fetch_assoc()): ?>
<tr>
<td><?= $k['nomor'] ?></td>
<td style="font-size:1.5rem"><?= $k['icon'] ?></td>
<td><?= $k['nama'] ?></td>
<td><span style="display:inline-block;width:20px;height:20px;border-radius:50%;background:<?= htmlspecialchars($k['warna']) ?>;vertical-align:middle"></span> <?= htmlspecialchars($k['warna']) ?></td>
<td><?= $k['sort_order'] ?></td>
<td><div style="display:flex;gap:6px">
<button class="vm-btn-action vm-btn-edit" onclick='edit7k(<?= json_encode($k) ?>)'><i class="fas fa-edit"></i></button>
<a href="visi-misi-process.php?action=delete&type=7k&id=<?= $k['id'] ?>" class="vm-btn-action vm-btn-delete" onclick="return confirm('Yakin hapus program ini?')"><i class="fas fa-trash"></i></a>
</div></td>
</tr>
<?php endwhile; else: ?>
<tr><td colspan="6" style="text-align:center;padding:2rem;color:#94a3b8">Belum ada data program 7K</td></tr>
<?php endif; ?>
</tbody></table></div>
</div>
</div>

<!-- MODAL VISI -->
<div class="modal-overlay" id="modalVisi" style="display:none">
<div class="modal-card" style="max-width:650px">
<div class="modal-header"><h2><i class="fas fa-eye"></i> Edit Visi</h2>
<div class="btn-close-modal" onclick="closeModal('modalVisi')"><i class="fas fa-times"></i></div></div>
<form action="visi-misi-process.php" method="POST" style="display:contents">
<input type="hidden" name="action" value="save"><input type="hidden" name="type" value="visi">
<input type="hidden" name="id" value="<?= $visi['id'] ?? '' ?>">
<div class="modal-body" style="max-height:65vh;overflow-y:auto">
<div style="display:grid;grid-template-columns:1fr 1fr;gap:15px">
<div class="form-group"><label><i class="fas fa-tag" style="margin-right:5px;color:var(--accent)"></i>Badge Text</label>
<input type="text" name="badge_text" value="<?= htmlspecialchars($visi['badge_text'] ?? 'Visi Sekolah') ?>" required></div>
<div class="form-group"><label><i class="fas fa-icons" style="margin-right:5px;color:var(--accent)"></i>Badge Icon (FA class)</label>
<input type="text" name="badge_icon" value="<?= htmlspecialchars($visi['badge_icon'] ?? 'fas fa-eye') ?>" required></div>
</div>
<div style="display:grid;grid-template-columns:1fr 1fr;gap:15px">
<div class="form-group"><label>Judul Section</label>
<input type="text" name="judul_section" value="<?= htmlspecialchars($visi['judul_section'] ?? 'Visi Kami') ?>" required></div>
<div class="form-group"><label>Deskripsi Section</label>
<input type="text" name="deskripsi_section" value="<?= htmlspecialchars($visi['deskripsi_section'] ?? '') ?>" required></div>
</div>
<div class="form-group"><label><i class="fas fa-quote-left" style="margin-right:5px;color:var(--accent)"></i>Isi Visi</label>
<textarea name="isi_visi" required style="min-height:120px;resize:vertical;font-family:inherit;padding:12px;border:1px solid #e2e8f0;border-radius:12px;width:100%;box-sizing:border-box"><?= htmlspecialchars($visi['isi_visi'] ?? '') ?></textarea></div>
</div>
<div class="modal-footer"><button type="button" class="btn-cancel" onclick="closeModal('modalVisi')"><i class="fas fa-times"></i> Batal</button>
<button type="submit" class="btn-submit"><i class="fas fa-save"></i> Simpan</button></div>
</form></div></div>

<!-- MODAL MISI -->
<div class="modal-overlay" id="modalMisi" style="display:none">
<div class="modal-card" style="max-width:650px">
<div class="modal-header"><h2 id="misiTitle"><i class="fas fa-plus"></i> Tambah Misi</h2>
<div class="btn-close-modal" onclick="closeModal('modalMisi')"><i class="fas fa-times"></i></div></div>
<form action="visi-misi-process.php" method="POST" style="display:contents">
<input type="hidden" name="action" value="save"><input type="hidden" name="type" value="misi">
<input type="hidden" name="id" id="misi_id" value="">
<div class="modal-body" style="max-height:65vh;overflow-y:auto">
<div style="display:grid;grid-template-columns:1fr 1fr;gap:15px">
<div class="form-group"><label>Nomor Misi</label>
<input type="number" name="nomor" id="misi_nomor" min="1" required></div>
<div class="form-group"><label>Urutan Tampil</label>
<input type="number" name="sort_order" id="misi_sort" min="0" required></div>
</div>
<div class="form-group"><label>Judul Misi</label>
<input type="text" name="judul" id="misi_judul" required placeholder="Contoh: Akhlak & Ibadah"></div>
<div class="form-group"><label>Deskripsi Misi</label>
<textarea name="deskripsi" id="misi_deskripsi" required style="min-height:100px;resize:vertical;font-family:inherit;padding:12px;border:1px solid #e2e8f0;border-radius:12px;width:100%;box-sizing:border-box"></textarea></div>
<div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:15px">
<div class="form-group"><label>Icon (FA class)</label>
<input type="text" name="icon" id="misi_icon" value="fas fa-star" required></div>
<div class="form-group"><label>Warna Background</label>
<input type="color" name="icon_bg" id="misi_icon_bg" value="#eff6ff" style="height:42px;border-radius:10px"></div>
<div class="form-group"><label>Warna Icon</label>
<input type="color" name="icon_color" id="misi_icon_color" value="#3b82f6" style="height:42px;border-radius:10px"></div>
</div>
</div>
<div class="modal-footer"><button type="button" class="btn-cancel" onclick="closeModal('modalMisi')"><i class="fas fa-times"></i> Batal</button>
<button type="submit" class="btn-submit"><i class="fas fa-save"></i> Simpan</button></div>
</form></div></div>

<!-- MODAL MISI SECTION HEADER -->
<div class="modal-overlay" id="modalMisiSection" style="display:none">
<div class="modal-card" style="max-width:550px">
<div class="modal-header"><h2><i class="fas fa-cog"></i> Edit Header Section Misi</h2>
<div class="btn-close-modal" onclick="closeModal('modalMisiSection')"><i class="fas fa-times"></i></div></div>
<form action="visi-misi-process.php" method="POST" style="display:contents">
<input type="hidden" name="action" value="save"><input type="hidden" name="type" value="misi_section">
<div class="modal-body">
<div style="display:grid;grid-template-columns:1fr 1fr;gap:15px">
<div class="form-group"><label>Badge Text</label><input type="text" name="badge_text" value="<?= htmlspecialchars($misi_first['badge_text'] ?? 'Misi Sekolah') ?>" required></div>
<div class="form-group"><label>Badge Icon</label><input type="text" name="badge_icon" value="<?= htmlspecialchars($misi_first['badge_icon'] ?? 'fas fa-bullseye') ?>" required></div>
</div>
<div class="form-group"><label>Judul Section</label><input type="text" name="judul_section" value="<?= htmlspecialchars($misi_first['judul_section'] ?? '7 Misi Utama') ?>" required></div>
<div class="form-group"><label>Deskripsi Section</label><input type="text" name="deskripsi_section" value="<?= htmlspecialchars($misi_first['deskripsi_section'] ?? '') ?>" required></div>
</div>
<div class="modal-footer"><button type="button" class="btn-cancel" onclick="closeModal('modalMisiSection')"><i class="fas fa-times"></i> Batal</button>
<button type="submit" class="btn-submit"><i class="fas fa-save"></i> Simpan</button></div>
</form></div></div>

<!-- MODAL 7K -->
<div class="modal-overlay" id="modal7k" style="display:none">
<div class="modal-card" style="max-width:550px">
<div class="modal-header"><h2 id="k7Title"><i class="fas fa-plus"></i> Tambah Program 7K</h2>
<div class="btn-close-modal" onclick="closeModal('modal7k')"><i class="fas fa-times"></i></div></div>
<form action="visi-misi-process.php" method="POST" style="display:contents">
<input type="hidden" name="action" value="save"><input type="hidden" name="type" value="7k">
<input type="hidden" name="id" id="k7_id" value="">
<div class="modal-body">
<div style="display:grid;grid-template-columns:1fr 1fr;gap:15px">
<div class="form-group"><label>Nomor</label><input type="number" name="nomor" id="k7_nomor" min="1" required></div>
<div class="form-group"><label>Urutan Tampil</label><input type="number" name="sort_order" id="k7_sort" min="0" required></div>
</div>
<div class="form-group"><label>Nama Program</label>
<input type="text" name="nama" id="k7_nama" required placeholder="Contoh: Ke<strong>tertiban</strong>">
<small style="color:var(--gray)">Gunakan &lt;strong&gt; untuk menebalkan sebagian teks</small></div>
<div style="display:grid;grid-template-columns:1fr 1fr;gap:15px">
<div class="form-group"><label>Icon (Emoji)</label><input type="text" name="icon" id="k7_icon" value="⭐" required style="font-size:1.5rem"></div>
<div class="form-group"><label>Warna Aksen</label><input type="color" name="warna" id="k7_warna" value="#3b82f6" style="height:42px;border-radius:10px"></div>
</div>
</div>
<div class="modal-footer"><button type="button" class="btn-cancel" onclick="closeModal('modal7k')"><i class="fas fa-times"></i> Batal</button>
<button type="submit" class="btn-submit"><i class="fas fa-save"></i> Simpan</button></div>
</form></div></div>

<!-- MODAL 7K SECTION HEADER -->
<div class="modal-overlay" id="modal7kSection" style="display:none">
<div class="modal-card" style="max-width:550px">
<div class="modal-header"><h2><i class="fas fa-cog"></i> Edit Header Section 7K</h2>
<div class="btn-close-modal" onclick="closeModal('modal7kSection')"><i class="fas fa-times"></i></div></div>
<form action="visi-misi-process.php" method="POST" style="display:contents">
<input type="hidden" name="action" value="save"><input type="hidden" name="type" value="7k_section">
<div class="modal-body">
<div style="display:grid;grid-template-columns:1fr 1fr;gap:15px">
<div class="form-group"><label>Badge Text</label><input type="text" name="badge_text" value="<?= htmlspecialchars($k7_first['badge_text'] ?? 'Program 7K') ?>" required></div>
<div class="form-group"><label>Badge Icon</label><input type="text" name="badge_icon" value="<?= htmlspecialchars($k7_first['badge_icon'] ?? 'fas fa-star') ?>" required></div>
</div>
<div class="form-group"><label>Judul Section</label><input type="text" name="judul_section" value="<?= htmlspecialchars($k7_first['judul_section'] ?? '7K Sekolah') ?>" required></div>
<div class="form-group"><label>Deskripsi Section</label><input type="text" name="deskripsi_section" value="<?= htmlspecialchars($k7_first['deskripsi_section'] ?? '') ?>" required></div>
</div>
<div class="modal-footer"><button type="button" class="btn-cancel" onclick="closeModal('modal7kSection')"><i class="fas fa-times"></i> Batal</button>
<button type="submit" class="btn-submit"><i class="fas fa-save"></i> Simpan</button></div>
</form></div></div>

<script>
function closeModal(id){document.getElementById(id).style.display='none';document.body.style.overflow='';}
function openMisiForm(){document.getElementById('misi_id').value='';document.getElementById('misi_nomor').value='';document.getElementById('misi_sort').value='';document.getElementById('misi_judul').value='';document.getElementById('misi_deskripsi').value='';document.getElementById('misi_icon').value='fas fa-star';document.getElementById('misi_icon_bg').value='#eff6ff';document.getElementById('misi_icon_color').value='#3b82f6';document.getElementById('misiTitle').innerHTML='<i class="fas fa-plus"></i> Tambah Misi';document.getElementById('modalMisi').style.display='flex';document.body.style.overflow='hidden';}
function editMisi(d){document.getElementById('misi_id').value=d.id;document.getElementById('misi_nomor').value=d.nomor;document.getElementById('misi_sort').value=d.sort_order;document.getElementById('misi_judul').value=d.judul;document.getElementById('misi_deskripsi').value=d.deskripsi;document.getElementById('misi_icon').value=d.icon;document.getElementById('misi_icon_bg').value=d.icon_bg;document.getElementById('misi_icon_color').value=d.icon_color;document.getElementById('misiTitle').innerHTML='<i class="fas fa-edit"></i> Edit Misi';document.getElementById('modalMisi').style.display='flex';document.body.style.overflow='hidden';}
function open7kForm(){document.getElementById('k7_id').value='';document.getElementById('k7_nomor').value='';document.getElementById('k7_sort').value='';document.getElementById('k7_nama').value='';document.getElementById('k7_icon').value='⭐';document.getElementById('k7_warna').value='#3b82f6';document.getElementById('k7Title').innerHTML='<i class="fas fa-plus"></i> Tambah Program 7K';document.getElementById('modal7k').style.display='flex';document.body.style.overflow='hidden';}
function edit7k(d){document.getElementById('k7_id').value=d.id;document.getElementById('k7_nomor').value=d.nomor;document.getElementById('k7_sort').value=d.sort_order;document.getElementById('k7_nama').value=d.nama;document.getElementById('k7_icon').value=d.icon;document.getElementById('k7_warna').value=d.warna;document.getElementById('k7Title').innerHTML='<i class="fas fa-edit"></i> Edit Program 7K';document.getElementById('modal7k').style.display='flex';document.body.style.overflow='hidden';}
document.querySelectorAll('.modal-overlay').forEach(m=>{m.addEventListener('click',function(e){if(e.target===this)closeModal(this.id)});});
document.addEventListener('keydown',function(e){if(e.key==='Escape')document.querySelectorAll('.modal-overlay').forEach(m=>{m.style.display='none'});document.body.style.overflow='';});
setTimeout(function(){var a=document.getElementById('alertContainer');if(a){a.style.transition='opacity 0.5s';a.style.opacity='0';setTimeout(()=>a.remove(),500);}},5000);
document.addEventListener('DOMContentLoaded',function(){document.querySelectorAll('.modal-overlay').forEach(m=>document.body.appendChild(m));});
</script>

<?php include 'layout/footer.php'; ?>
