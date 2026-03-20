<?php
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
include '../database/conn.php';

// Fetch current news
$sql = "SELECT * FROM berita WHERE id = $id";
$result = mysqli_query($conn, $sql);
$berita = mysqli_fetch_assoc($result);

if (!$berita) {
    header("Location: berita.php");
    exit();
}

// Fetch recommended news (berita terbaru / lainnya) excluding the current one
$sql_recommend = "SELECT * FROM berita WHERE id != $id ORDER BY tanggal DESC LIMIT 4";
$result_recommend = mysqli_query($conn, $sql_recommend);

?>
<?php include 'header.php'; ?>

<!-- Custom Styles for Modern News Detail -->
<style>
    /* Modern News Detail Page Styles */
    .news-detail-wrapper {
        max-width: 1200px;
        margin: -80px auto 4rem;
        /* Reduced overlap to show more wave */
        padding: 0 2rem;
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 3rem;
        position: relative;
        z-index: 10;
    }

    @media (max-width: 992px) {
        .news-detail-wrapper {
            grid-template-columns: 1fr;
            margin-top: -50px;
        }
    }

    /* Article Container */
    .article-container {
        background: var(--white);
        border-radius: 24px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        padding: 3rem;
        margin-top:100px;
    }

    @media (max-width: 768px) {
        .article-container {
            padding: 1.5rem;
            border-radius: 16px;
        }
    }

    /* Breadcrumb & Navigation */
    .article-nav {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        font-size: 0.95rem;
    }

    .back-btn-pro {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--gray);
        font-weight: 500;
        transition: var(--transition);
    }

    .back-btn-pro:hover {
        color: var(--primary);
        transform: translateX(-5px);
    }

    .article-category-badge {
        background: var(--gradient-primary);
        color: var(--white);
        padding: 6px 16px;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    /* Article Header */
    .article-header {
        margin-bottom: 2.5rem;
    }

    .article-title {
        font-family: "Playfair Display", serif;
        font-size: 2.8rem;
        color: var(--dark);
        line-height: 1.25;
        margin-bottom: 1.5rem;
        font-weight: 800;
    }

    @media (max-width: 768px) {
        .article-title {
            font-size: 2rem;
        }
    }

    .article-meta {
        display: flex;
        align-items: center;
        gap: 20px;
        color: var(--gray);
        font-size: 0.95rem;
        border-top: 1px solid var(--gray-light);
        border-bottom: 1px solid var(--gray-light);
        padding: 1rem 0;
        flex-wrap: wrap;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .meta-item i {
        color: var(--primary);
    }

    .author-avatar {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        background: var(--primary);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--white);
        font-weight: bold;
        font-size: 14px;
    }

    /* Article Featured Image */
    .article-featured-image {
        width: 100%;
        height: auto;
        border-radius: 16px;
        margin-bottom: 3rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        max-height: 500px;
        object-fit: cover;
    }

    /* Article Content */
    .article-content {
        font-size: 1.15rem;
        color: #475569;
        line-height: 1.9;
    }

    .article-content p {
        margin-bottom: 1.5rem;
    }

    .article-content blockquote {
        border-left: 5px solid var(--primary);
        background: var(--light);
        padding: 1.5rem 2rem;
        margin: 2rem 0;
        border-radius: 0 16px 16px 0;
        font-style: italic;
        font-size: 1.25rem;
        color: var(--dark);
    }

    /* Article Footer (Share etc) */
    .article-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 4rem;
        padding-top: 2rem;
        border-top: 1px solid var(--gray-light);
    }

    @media (max-width: 768px) {
        .article-footer {
            flex-direction: column;
            align-items: flex-start;
            gap: 1.5rem;
        }
    }

    .share-buttons {
        display: flex;
        gap: 15px;
        align-items: center;
    }

    .share-buttons span {
        font-weight: 600;
        color: var(--dark);
        margin-right: 10px;
    }

    .share-btn {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--white);
        transition: var(--transition);
    }

    .share-btn.fb {
        background: #1877f2;
    }

    .share-btn.tw {
        background: #1da1f2;
    }

    .share-btn.wa {
        background: #25d366;
    }

    .share-btn.link {
        background: var(--gray);
    }

    .share-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        color: white;
    }

    /* Sidebar Styles */
    .sidebar {
        display: flex;
        flex-direction: column;
        gap: 2.5rem;
        margin-top: 100px;
    }

    .sidebar-widget {
        background: var(--white);
        border-radius: 24px;
        padding: 2rem;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.05);
    }

    .widget-title {
        font-size: 1.4rem;
        font-weight: 800;
        color: var(--dark);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .widget-title::before {
        content: '';
        width: 5px;
        height: 25px;
        background: var(--gradient-primary);
        border-radius: 10px;
    }

    .recommended-news-list {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .recommended-item {
        display: flex;
        gap: 15px;
        transition: var(--transition);
        border-bottom: 1px solid var(--gray-light);
        padding-bottom: 1.5rem;
    }

    .recommended-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .recommended-item:hover {
        transform: translateX(5px);
    }

    .recommended-img {
        width: 90px;
        height: 90px;
        border-radius: 12px;
        object-fit: cover;
        flex-shrink: 0;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .recommended-info {
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .recommended-title {
        font-size: 1rem;
        font-weight: 700;
        color: var(--dark);
        line-height: 1.4;
        margin-bottom: 8px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        transition: var(--transition);
    }

    .recommended-item:hover .recommended-title {
        color: var(--primary);
    }

    .recommended-date {
        font-size: 0.8rem;
        color: var(--gray);
        display: flex;
        align-items: center;
        gap: 5px;
    }

    /* Newsletter Widget */
    .newsletter-widget {
        background: var(--gradient-primary);
        color: var(--white);
    }

    .newsletter-widget .widget-title {
        color: var(--white);
    }

    .newsletter-widget .widget-title::before {
        background: var(--white);
    }

    .newsletter-widget p {
        font-size: 0.95rem;
        margin-bottom: 1.5rem;
        opacity: 0.9;
    }

    .newsletter-form input {
        width: 100%;
        padding: 12px 15px;
        border-radius: 10px;
        border: none;
        margin-bottom: 10px;
        outline: none;
        font-family: inherit;
    }

    .newsletter-form button {
        width: 100%;
        padding: 12px;
        background: var(--dark);
        color: var(--white);
        border: none;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
    }

    .newsletter-form button:hover {
        background: var(--white);
        color: var(--primary);
    }

    /* Adjust Hero Section for News Detail */
    .news-detail-hero {
        padding-top: 10rem;
        padding-bottom: 12rem;
        background: var(--gradient-primary);
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .hero-title-container {
        position: relative;
        z-index: 5;
        max-width: 1000px;
        margin: 0 auto;
        padding: 0 2rem;
    }

    /* Decorative badge above title */
    .hero-badge {
        display: inline-block;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        color: var(--accent);
        padding: 8px 20px;
        border-radius: 50px;
        font-size: 0.9rem;
        font-weight: 700;
        letter-spacing: 2px;
        text-transform: uppercase;
        margin-bottom: 1.5rem;
        border: 1px solid rgba(255, 255, 255, 0.2);
        animation: fadeInDown 0.8s ease-out both;
    }

    .hero-title-container h1 {
        color: var(--white);
        font-family: "Poppins", sans-serif;
        font-size: 4rem;
        font-weight: 900;
        line-height: 1.1;
        margin-bottom: 2rem;
        text-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        background: linear-gradient(to bottom, #ffffff, #e2e8f0);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        animation: heroTextReveal 1s cubic-bezier(0.23, 1, 0.32, 1) both;
    }

    @keyframes heroTextReveal {
        0% { transform: translateY(30px); opacity: 0; filter: blur(10px); }
        100% { transform: translateY(0); opacity: 1; filter: blur(0); }
    }

    @keyframes fadeInDown {
        0% { transform: translateY(-20px); opacity: 0; }
        100% { transform: translateY(0); opacity: 1; }
    }

    @media (max-width: 768px) {
        .hero-title-container h1 {
            font-size: 2.5rem;
        }
    }
</style>

<section class="news-detail-hero">
    <div class="floating-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
    </div>

    <div class="hero-title-container">
        <div class="hero-badge">detail berita</div>
        <h1><?php echo htmlspecialchars($berita['judul']); ?></h1>
    </div>

    <?php include 'wave.php'; ?>
</section>

<div class="news-detail-wrapper">
    <!-- Main Article Column -->
    <main class="article-container animate-fade-in-up">     
        <div class="article-nav">
            <a href="berita.php" class="back-btn-pro"><i class="fas fa-arrow-left"></i> Kembali ke Berita</a>
            <span class="article-category-badge"><?php echo htmlspecialchars($berita['kategori'] ?? 'Berita'); ?></span>
        </div>

        <div class="article-header">
            <div class="article-meta">
                <div class="meta-item">
                    <div class="author-avatar"><?php echo strtoupper(substr($berita['penulis'], 0, 1)); ?></div>
                    <span style="font-weight: 600; color: var(--dark);"><?php echo htmlspecialchars($berita['penulis']); ?></span>
                </div>
                <div class="meta-item">
                    <i class="far fa-calendar-alt"></i>
                    <span><?php echo date('d M Y', strtotime($berita['tanggal'])); ?></span>
                </div>
                <div class="meta-item">
                    <i class="far fa-clock"></i>
                    <span><?php 
                        $word_count = str_word_count(strip_tags($berita['deskripsi']));
                        $reading_time = ceil($word_count / 200);
                        echo $reading_time . " Menit baca";
                    ?></span>
                </div>
            </div>
        </div>

        <?php
        // Cek apakah foto ada atau tidak
        if (!empty($berita['foto']) && file_exists('../' . $berita['foto'])) {
            $foto_path = '../' . $berita['foto'];
            $alt_text = $berita['judul'];
        } else {
            $foto_path = '../assets/img/no-image.jpg';
            $alt_text = 'Tidak ada gambar';
        }
        ?>

        <img src="<?php echo htmlspecialchars($foto_path); ?>" alt="<?php echo htmlspecialchars($alt_text); ?>" class="article-featured-image">

        <div class="article-content">
            <?php
            echo nl2br(htmlspecialchars($berita['deskripsi']));
            ?>
        </div>

        <div class="article-footer">
            <div class="share-buttons">
                <span>Bagikan:</span>
                <a href="#" class="share-btn fb"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="share-btn tw"><i class="fab fa-twitter"></i></a>
                <a href="#" class="share-btn wa"><i class="fab fa-whatsapp"></i></a>
                <a href="#" class="share-btn link" onclick="navigator.clipboard.writeText(window.location.href); alert('Tautan disalin!'); return false;"><i class="fas fa-link"></i></a>
            </div>
        </div>
    </main>

    <!-- Sidebar Column -->
    <aside class="sidebar animate-fade-in-up" style="animation-delay: 0.2s;">
        <!-- Recommended News Widget -->
        <div class="sidebar-widget">
            <h3 class="widget-title">Baca Juga</h3>
            <div class="recommended-news-list">
                <?php while ($rec = mysqli_fetch_assoc($result_recommend)) :
                    $rec_img = (!empty($rec['foto']) && file_exists('../' . $rec['foto'])) ? '../' . $rec['foto'] : '../assets/img/no-image.jpg';
                ?>
                    <a href="detail-berita.php?id=<?php echo $rec['id']; ?>" class="recommended-item">
                        <img src="<?php echo htmlspecialchars($rec_img); ?>" alt="Thumbnail" class="recommended-img">
                        <div class="recommended-info">
                            <h4 class="recommended-title"><?php echo htmlspecialchars($rec['judul']); ?></h4>
                            <div class="recommended-date">
                                <i class="far fa-calendar-alt"></i> <?php echo date('d M Y', strtotime($rec['tanggal'])); ?>
                            </div>
                        </div>
                    </a>
                <?php endwhile; ?>

                <?php if (mysqli_num_rows($result_recommend) == 0): ?>
                    <p style="color: var(--gray); font-size: 0.9rem;">Belum ada berita lain.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Newsletter Widget -->
            <div class="sidebar-widget newsletter-widget">
                <h3 class="widget-title">Berlangganan</h3>
                <p>Dapatkan informasi dan berita terbaru langsung ke kotak masuk Anda.</p>
                <form class="newsletter-form" onsubmit="event.preventDefault(); alert('Terima kasih telah berlangganan!');">
                    <input type="email" placeholder="Alamat Email Anda" required>
                    <button type="submit">Langganan</button>
                </form>
            </div>
    </aside>
</div>

<?php include 'footer.php'; ?>