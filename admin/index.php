<?php
$title = "Dashboard Admin";
include 'layout/header.php';
?>

<!-- Welcome Banner -->
<div class="welcome-banner animate-fade-in-up">
    <i class="fas fa-chart-line welcome-bg"></i>
    <h1>Selamat Datang, <?= $_SESSION['username']; ?>!</h1>
    <p>Ini adalah pusat kontrol website SMP PGRI 3 BOGOR. Anda dapat mengelola berita, galeri, data guru, dan informasi lainnya dari sini.</p>
</div>

<!-- Stats Widgets -->
<div class="stats-grid">
    <div class="stat-card animate-fade-in-up" style="animation-delay: 0.1s;">
        <div class="stat-icon bg-blue">
            <i class="fas fa-newspaper"></i>
        </div>
        <div class="stat-info">
            <h3>12</h3>
            <p>Berita Dipublish</p>
        </div>
    </div>

    <div class="stat-card animate-fade-in-up" style="animation-delay: 0.2s;">
        <div class="stat-icon bg-green">
            <i class="fas fa-images"></i>
        </div>
        <div class="stat-info">
            <h3>48</h3>
            <p>Foto Galeri</p>
        </div>
    </div>

    <div class="stat-card animate-fade-in-up" style="animation-delay: 0.3s;">
        <div class="stat-icon bg-purple">
            <i class="fas fa-chalkboard-teacher"></i>
        </div>
        <div class="stat-info">
            <h3>24</h3>
            <p>Total Guru</p>
        </div>
    </div>

    <div class="stat-card animate-fade-in-up" style="animation-delay: 0.4s;">
        <div class="stat-icon bg-orange">
            <i class="fas fa-calendar-check"></i>
        </div>
        <div class="stat-info">
            <h3>5</h3>
            <p>Agenda Mendatang</p>
        </div>
    </div>
</div>

<!-- Dashboard Content -->
<div class="dashboard-grid">
    <!-- Chart Section -->
    <div class="card-panel animate-fade-in-up" style="animation-delay: 0.5s;">
        <div class="card-header">
            <h3>Statistik Pengunjung</h3>
            <a href="#" class="btn-sm">Lihat Detail</a>
        </div>
        <canvas id="visitorChart" height="120"></canvas>
    </div>

    <!-- Recent Activity -->
    <div class="card-panel animate-fade-in-up" style="animation-delay: 0.6s;">
        <div class="card-header">
            <h3>Aktivitas Terbaru</h3>
        </div>

        <div class="activity-list">
            <div class="activity-item">
                <div class="activity-icon">
                    <i class="fas fa-edit"></i>
                </div>
                <div class="activity-content">
                    <h4>Update Berita</h4>
                    <p>Admin mengedit "Juara 1 Lomba..."</p>
                </div>
                <div class="activity-time">5m lalu</div>
            </div>

            <div class="activity-item">
                <div class="activity-icon">
                    <i class="fas fa-plus"></i>
                </div>
                <div class="activity-content">
                    <h4>Upload Galeri</h4>
                    <p>Menambahkan 5 foto baru</p>
                </div>
                <div class="activity-time">1j lalu</div>
            </div>

            <div class="activity-item">
                <div class="activity-icon">
                    <i class="fas fa-trash"></i>
                </div>
                <div class="activity-content">
                    <h4>Hapus Agenda</h4>
                    <p>Menghapus agenda "Rapat Guru"</p>
                </div>
                <div class="activity-time">3j lalu</div>
            </div>
            <div class="activity-item">
                <div class="activity-icon">
                    <i class="fas fa-user-plus"></i>
                </div>
                <div class="activity-content">
                    <h4>Data Guru</h4>
                    <p>Menambahkan data guru baru</p>
                </div>
                <div class="activity-time">1h lalu</div>
            </div>
        </div>
    </div>
</div>

<!-- Simple Chart Script -->
<script>
    const ctx = document.getElementById('visitorChart').getContext('2d');
    const visitorChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Sn', 'Sl', 'Rb', 'Km', 'Jm', 'Sb', 'Mg'],
            datasets: [{
                label: 'Pengunjung Minggu Ini',
                data: [65, 59, 80, 81, 56, 120, 90],
                fill: true,
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderColor: '#0b2d72',
                tension: 0.4,
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        display: false
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
</script>

<?php include 'layout/footer.php'; ?>