<?php
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
include '../database/conn.php';

$sql = "SELECT * FROM berita WHERE id = $id";
$result = mysqli_query($conn, $sql);
$berita = mysqli_fetch_assoc($result);

if (!$berita) {
    header("Location: berita.php");
    exit();
}

$sql_recommend = "SELECT * FROM berita WHERE id != $id ORDER BY tanggal DESC LIMIT 4";
$result_recommend = mysqli_query($conn, $sql_recommend);

$word_count = str_word_count(strip_tags($berita['deskripsi']));
$reading_time = max(1, ceil($word_count / 200));

$foto_path = (!empty($berita['foto']) && file_exists('../' . $berita['foto']))
    ? '../' . $berita['foto']
    : null;

$kategori_colors = [
    'kegiatan'    => ['#10b981', '#059669'],
    'prestasi'    => ['#f59e0b', '#d97706'],
    'pengumuman'  => ['#6366f1', '#4f46e5'],
];
$kat = strtolower($berita['kategori'] ?? 'kegiatan');
$kat_color = $kategori_colors[$kat] ?? ['#0992c2', '#0b2d72'];
?>
<?php include 'header.php'; ?>

<!-- Reading Progress Bar -->
<div id="readingProgress"></div>

<style>
    /* =============================================
   READING PROGRESS BAR
============================================= */
    #readingProgress {
        position: fixed;
        top: 0;
        left: 0;
        height: 3px;
        width: 0%;
        background: linear-gradient(90deg, #0b2d72, #0992c2, #0ac4e0);
        z-index: 99999;
        transition: width 0.1s linear;
        box-shadow: 0 0 10px rgba(9, 146, 194, 0.6);
    }

    /* =============================================
   HERO - PARALLAX CINEMATIC
============================================= */
    .db-hero {
        position: relative;
        min-height: 80vh;
        display: flex;
        align-items: flex-end;
        overflow: hidden;
        padding-bottom: 8rem;
    }

    .db-hero-bg {
        position: absolute;
        inset: 0;
        background: var(--gradient-primary);
        z-index: 0;
    }

    .db-hero-bg img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        opacity: 0.22;
        filter: blur(2px) saturate(1.3);
        transform: scale(1.05);
        transition: transform 12s ease-out;
    }

    .db-hero-bg::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(to bottom,
                rgba(11, 45, 114, 0.3) 0%,
                rgba(11, 45, 114, 0.6) 50%,
                rgba(7, 29, 74, 0.95) 100%);
    }

    /* Particles */
    .db-hero-particles {
        position: absolute;
        inset: 0;
        overflow: hidden;
        z-index: 1;
        pointer-events: none;
    }

    .db-particle {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.08);
        animation: particleFloat linear infinite;
    }

    @keyframes particleFloat {
        0% {
            transform: translateY(100vh) rotate(0deg);
            opacity: 0;
        }

        10% {
            opacity: 1;
        }

        90% {
            opacity: 1;
        }

        100% {
            transform: translateY(-100px) rotate(720deg);
            opacity: 0;
        }
    }

    .db-hero-content {
        position: relative;
        z-index: 5;
        max-width: 900px;
        margin: 150px auto;
        padding: 0 2rem;
        width: 100%;
        animation: heroSlideUp 1s cubic-bezier(0.23, 1, 0.32, 1) both 0.2s;
    }

    @keyframes heroSlideUp {
        from {
            opacity: 0;
            transform: translateY(50px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .db-hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 20px;
        border-radius: 50px;
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-bottom: 1.5rem;
        border: 1px solid rgba(255, 255, 255, 0.25);
        backdrop-filter: blur(12px);
        background: rgba(255, 255, 255, 0.12);
        color: #fff;
    }

    .db-hero-badge-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        animation: badgePulse 1.5s ease-in-out infinite;
    }

    @keyframes badgePulse {

        0%,
        100% {
            transform: scale(1);
            opacity: 1;
        }

        50% {
            transform: scale(1.5);
            opacity: 0.6;
        }
    }

    .db-hero-title {
        font-family: "Poppins", sans-serif;
        font-size: clamp(1.8rem, 4.5vw, 3.6rem);
        font-weight: 900;
        color: #fff;
        line-height: 1.15;
        margin-bottom: 2rem;
        text-shadow: 0 4px 30px rgba(0, 0, 0, 0.3);
    }

    .db-hero-meta {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 1.5rem;
    }

    .db-hero-meta-item {
        display: flex;
        align-items: center;
        gap: 8px;
        color: rgba(255, 255, 255, 0.8);
        font-size: 0.88rem;
        font-weight: 500;
    }

    .db-hero-meta-item i {
        color: #0ac4e0;
        font-size: 0.9rem;
    }

    .db-author-chip {
        display: flex;
        align-items: center;
        gap: 10px;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 50px;
        padding: 6px 16px 6px 6px;
    }

    .db-author-ava {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: linear-gradient(135deg, #0992c2, #0ac4e0);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        font-weight: 800;
        color: #fff;
        flex-shrink: 0;
    }

    .db-hero-scroll-hint {
        position: absolute;
        bottom: 9rem;
        left: 50%;
        transform: translateX(-50%);
        z-index: 5;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
        color: rgba(255, 255, 255, 0.5);
        font-size: 0.72rem;
        letter-spacing: 2px;
        text-transform: uppercase;
        animation: hintBounce 2s ease-in-out infinite;
    }

    @keyframes hintBounce {

        0%,
        100% {
            transform: translateX(-50%) translateY(0);
        }

        50% {
            transform: translateX(-50%) translateY(8px);
        }
    }

    .db-hero-scroll-hint i {
        font-size: 1rem;
    }

    /* Wave separator */
    .db-wave-wrap {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        overflow: hidden;
        line-height: 0;
        z-index: 4;
    }

    .db-wave-wrap svg {
        display: block;
        width: 100%;
    }

    /* =============================================
   LAYOUT
============================================= */
    .db-layout {
        max-width: 1240px;
        margin: 2rem auto 5rem;
        padding: 0 2rem;
        display: grid;
        grid-template-columns: 1fr 360px;
        gap: 2.5rem;
        position: relative;
        z-index: 10;
    }

    @media (max-width: 1024px) {
        .db-layout {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .db-layout {
            padding: 0 1rem;
            margin-bottom: 3rem;
        }
    }

    /* =============================================
   ARTICLE CARD
============================================= */
    .db-article {
        background: #fff;
        border-radius: 28px;
        box-shadow: 0 25px 80px rgba(11, 45, 114, 0.1);
        overflow: hidden;
        animation: cardRise 0.8s cubic-bezier(0.34, 1.56, 0.64, 1) both 0.3s;
    }

    @keyframes cardRise {
        from {
            opacity: 0;
            transform: translateY(40px) scale(0.97);
        }

        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    /* Article Nav */
    .db-article-nav {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.5rem 2.5rem;
        border-bottom: 1px solid rgba(11, 45, 114, 0.06);
    }

    .db-back-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--gray);
        font-size: 0.9rem;
        font-weight: 600;
        padding: 8px 18px;
        border-radius: 50px;
        background: var(--light);
        transition: var(--transition);
    }

    .db-back-btn:hover {
        background: var(--primary);
        color: #fff;
        transform: translateX(-3px);
    }

    .db-back-btn i {
        font-size: 0.8rem;
    }

    .db-kat-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 18px;
        border-radius: 50px;
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #fff;
    }

    /* Featured Image */
    .db-feat-img-wrap {
        position: relative;
        overflow: hidden;
        max-height: 520px;
    }

    .db-feat-img-wrap img {
        width: 100%;
        height: 100%;
        max-height: 520px;
        object-fit: cover;
        display: block;
        transition: transform 6s ease-out;
    }

    .db-feat-img-wrap:hover img {
        transform: scale(1.04);
    }

    .db-feat-img-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(11, 45, 114, 0.15) 0%, transparent 60%);
    }

    /* No image placeholder */
    .db-no-img {
        background: var(--gradient-primary);
        height: 220px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 5rem;
        opacity: 0.3;
    }

    /* Article Body */
    .db-article-body {
        padding: 2.5rem 3rem;
    }

    @media (max-width: 768px) {
        .db-article-body {
            padding: 1.5rem;
        }

        .db-article-nav {
            padding: 1rem 1.5rem;
        }
    }

    /* Typography */
    .db-article-body .db-article-text {
        font-size: 1.12rem;
        line-height: 2;
        color: #374151;
        font-weight: 400;
    }

    .db-article-body .db-article-text p {
        margin-bottom: 1.6rem;
    }

    /* Pull quote style for first paragraph */
    .db-first-para {
        font-size: 1.22rem !important;
        font-weight: 500 !important;
        color: var(--dark) !important;
        border-left: 4px solid #0992c2;
        padding-left: 1.5rem;
        margin-bottom: 2rem !important;
        line-height: 1.8 !important;
    }

    /* Divider */
    .db-divider {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin: 2.5rem 0;
    }

    .db-divider span {
        flex: 1;
        height: 1px;
        background: linear-gradient(to right, transparent, var(--gray-light), transparent);
    }

    .db-divider i {
        color: var(--primary-light);
        font-size: 1rem;
    }

    /* =============================================
   SHARE SECTION
============================================= */
    .db-share-section {
        margin-top: 3rem;
        padding-top: 2rem;
        border-top: 1px solid var(--gray-light);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1.5rem;
    }

    .db-share-label {
        font-weight: 700;
        color: var(--dark);
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .db-share-label i {
        color: var(--primary);
    }

    .db-share-btns {
        display: flex;
        gap: 10px;
    }

    .db-share-btn {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 1rem;
        transition: var(--transition);
        position: relative;
        overflow: hidden;
    }

    .db-share-btn::before {
        content: '';
        position: absolute;
        inset: 0;
        background: rgba(255, 255, 255, 0.15);
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }

    .db-share-btn:hover::before {
        transform: scaleX(1);
    }

    .db-share-btn:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        color: #fff;
    }

    .db-share-btn.fb {
        background: #1877f2;
    }

    .db-share-btn.tw {
        background: #1da1f2;
    }

    .db-share-btn.wa {
        background: #25d366;
    }

    .db-share-btn.lnk {
        background: var(--primary);
    }

    .db-copy-toast {
        position: fixed;
        bottom: 2rem;
        left: 50%;
        transform: translateX(-50%) translateY(20px);
        background: var(--dark);
        color: #fff;
        padding: 12px 24px;
        border-radius: 50px;
        font-size: 0.88rem;
        font-weight: 600;
        opacity: 0;
        pointer-events: none;
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        z-index: 99999;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }

    .db-copy-toast.show {
        opacity: 1;
        transform: translateX(-50%) translateY(0);
    }

    /* =============================================
   AUTHOR CARD
============================================= */
    .db-author-card {
        margin-top: 2.5rem;
        border-radius: 20px;
        background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
        padding: 1.8rem;
        display: flex;
        align-items: center;
        gap: 1.5rem;
        border: 1px solid rgba(9, 146, 194, 0.15);
    }

    .db-author-card-ava {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: var(--gradient-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        font-weight: 900;
        color: #fff;
        flex-shrink: 0;
        box-shadow: 0 8px 20px rgba(9, 146, 194, 0.3);
    }

    .db-author-name {
        font-size: 1.05rem;
        font-weight: 800;
        color: var(--dark);
        margin-bottom: 3px;
    }

    .db-author-role {
        font-size: 0.8rem;
        color: var(--primary-light);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    /* =============================================
   SIDEBAR
============================================= */
    .db-sidebar {
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }

    .db-sidebar-widget {
        background: #fff;
        border-radius: 24px;
        padding: 1.8rem;
        box-shadow: 0 10px 40px rgba(11, 45, 114, 0.07);
        animation: cardRise 0.8s cubic-bezier(0.34, 1.56, 0.64, 1) both 0.5s;
    }

    .db-widget-title {
        font-size: 1.15rem;
        font-weight: 800;
        color: var(--dark);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .db-widget-title-bar {
        width: 4px;
        height: 22px;
        border-radius: 4px;
        background: var(--gradient-primary);
        flex-shrink: 0;
    }

    /* Recommended list */
    .db-rec-list {
        display: flex;
        flex-direction: column;
        gap: 0;
    }

    .db-rec-item {
        display: flex;
        gap: 14px;
        padding: 1rem 0;
        border-bottom: 1px solid var(--gray-light);
        transition: var(--transition);
        text-decoration: none;
        color: inherit;
        position: relative;
    }

    .db-rec-item::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(90deg, rgba(9, 146, 194, 0.05) 0%, transparent 100%);
        border-radius: 12px;
        opacity: 0;
        transition: opacity 0.3s;
    }

    .db-rec-item:hover::before {
        opacity: 1;
    }

    .db-rec-item:hover {
        transform: translateX(6px);
    }

    .db-rec-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .db-rec-img-wrap {
        width: 80px;
        height: 80px;
        border-radius: 12px;
        overflow: hidden;
        flex-shrink: 0;
        background: var(--gray-light);
    }

    .db-rec-img-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }

    .db-rec-item:hover .db-rec-img-wrap img {
        transform: scale(1.1);
    }

    .db-rec-no-img {
        width: 100%;
        height: 100%;
        background: var(--gradient-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        color: rgba(255, 255, 255, 0.5);
        font-size: 1.5rem;
    }

    .db-rec-info {
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .db-rec-cat {
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--primary-light);
        margin-bottom: 4px;
    }

    .db-rec-title {
        font-size: 0.88rem;
        font-weight: 700;
        color: var(--dark);
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        transition: color 0.3s;
        margin-bottom: 6px;
    }

    .db-rec-item:hover .db-rec-title {
        color: var(--primary);
    }

    .db-rec-date {
        font-size: 0.75rem;
        color: var(--gray);
        display: flex;
        align-items: center;
        gap: 5px;
    }

    /* Stats Widget */
    .db-stats-widget {
        background: var(--gradient-primary);
        color: #fff;
    }

    .db-stats-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .db-stat-item {
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 16px;
        padding: 1.2rem;
        text-align: center;
        backdrop-filter: blur(10px);
        transition: var(--transition);
    }

    .db-stat-item:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: translateY(-3px);
    }

    .db-stat-num {
        font-size: 1.8rem;
        font-weight: 900;
        color: #fff;
        line-height: 1;
    }

    .db-stat-label {
        font-size: 0.72rem;
        color: rgba(255, 255, 255, 0.7);
        margin-top: 4px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    /* Newsletter */
    .db-newsletter {
        background: linear-gradient(135deg, #1e293b, #0f172a);
        color: #fff;
        overflow: hidden;
        position: relative;
    }

    .db-newsletter::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -30%;
        width: 200px;
        height: 200px;
        border-radius: 50%;
        background: rgba(9, 146, 194, 0.15);
        filter: blur(40px);
    }

    .db-newsletter .db-widget-title {
        color: #fff;
    }

    .db-newsletter .db-widget-title-bar {
        background: #0ac4e0;
    }

    .db-newsletter p {
        font-size: 0.88rem;
        color: rgba(255, 255, 255, 0.65);
        margin-bottom: 1.2rem;
        line-height: 1.7;
    }

    .db-nl-input {
        width: 100%;
        padding: 11px 16px;
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.15);
        background: rgba(255, 255, 255, 0.08);
        color: #fff;
        font-family: inherit;
        font-size: 0.88rem;
        margin-bottom: 10px;
        outline: none;
        transition: border-color 0.3s;
    }

    .db-nl-input::placeholder {
        color: rgba(255, 255, 255, 0.35);
    }

    .db-nl-input:focus {
        border-color: #0ac4e0;
    }

    .db-nl-btn {
        width: 100%;
        padding: 11px;
        background: linear-gradient(135deg, #0992c2, #0ac4e0);
        color: #fff;
        border: none;
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.9rem;
        cursor: pointer;
        transition: var(--transition);
    }

    .db-nl-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(9, 146, 194, 0.4);
    }

    /* Related Tags */
    .db-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .db-tag {
        padding: 6px 14px;
        border-radius: 50px;
        background: var(--light);
        color: var(--gray);
        font-size: 0.78rem;
        font-weight: 600;
        border: 1px solid var(--gray-light);
        transition: var(--transition);
        cursor: default;
    }

    .db-tag:hover {
        background: var(--primary);
        color: #fff;
        border-color: var(--primary);
        transform: translateY(-2px);
    }

    /* Back to list CTA */
    .db-cta-bar {
        max-width: 1240px;
        margin: 0 auto 5rem;
        padding: 0 2rem;
    }

    .db-cta-inner {
        background: var(--gradient-primary);
        border-radius: 24px;
        padding: 3rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 2rem;
        flex-wrap: wrap;
        box-shadow: 0 20px 60px rgba(11, 45, 114, 0.2);
        position: relative;
        overflow: hidden;
    }

    .db-cta-inner::before {
        content: '';
        position: absolute;
        right: -50px;
        top: -50px;
        width: 200px;
        height: 200px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.07);
    }

    .db-cta-text h3 {
        color: #fff;
        font-size: 1.5rem;
        font-weight: 800;
        margin-bottom: 6px;
    }

    .db-cta-text p {
        color: rgba(255, 255, 255, 0.7);
        font-size: 0.9rem;
    }

    .db-cta-btn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: #fff;
        color: var(--primary);
        padding: 14px 28px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 0.95rem;
        transition: var(--transition);
        white-space: nowrap;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    .db-cta-btn:hover {
        background: var(--accent);
        transform: translateY(-3px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
        color: var(--primary);
    }

    /* Sticky sidebar on desktop */
    @media (min-width: 1025px) {
        .db-sidebar {
            position: sticky;
            top: 8rem;
            align-self: start;
        }
    }

    /* Responsive */
    @media (max-width: 768px) {
        .db-hero {
            min-height: 65vh;
            padding-bottom: 6rem;
        }

        .db-hero-title {
            font-size: 1.7rem;
        }

        .db-share-section {
            flex-direction: column;
            align-items: flex-start;
        }

        .db-cta-inner {
            flex-direction: column;
            text-align: center;
        }

        .db-cta-inner::before {
            display: none;
        }

        .db-author-card {
            flex-direction: column;
            text-align: center;
        }

        .db-stats-grid {
            grid-template-columns: 1fr 1fr;
        }
    }
</style>

<!-- TOAST -->
<div class="db-copy-toast" id="copyToast"><i class="fas fa-check-circle"></i> Tautan disalin!</div>

<!-- ===== HERO ===== -->
<section class="db-hero">
    <div class="db-hero-bg">
        <?php if ($foto_path): ?>
            <img src="<?php echo htmlspecialchars($foto_path); ?>" alt="" id="heroParallaxImg">
        <?php endif; ?>
    </div>

    <!-- Floating particles -->
    <div class="db-hero-particles" id="heroParticles"></div>

    <div class="db-hero-content">
        <div class="db-hero-badge">
            <span class="db-hero-badge-dot" style="background: <?php echo $kat_color[0]; ?>;"></span>
            <?php echo htmlspecialchars(ucfirst($berita['kategori'] ?? 'Berita')); ?>
        </div>
        <h1 class="db-hero-title"><?php echo htmlspecialchars($berita['judul']); ?></h1>
        <div class="db-hero-meta">
            <div class="db-author-chip">
                <div class="db-author-ava"><?php echo strtoupper(substr($berita['penulis'], 0, 1)); ?></div>
                <span style="color:rgba(255,255,255,0.9);font-size:0.88rem;font-weight:600;"><?php echo htmlspecialchars($berita['penulis']); ?></span>
            </div>
            <div class="db-hero-meta-item">
                <i class="far fa-calendar-alt"></i>
                <?php echo date('d F Y', strtotime($berita['tanggal'])); ?>
            </div>
            <div class="db-hero-meta-item">
                <i class="far fa-clock"></i>
                <?php echo $reading_time; ?> menit baca
            </div>
        </div>
    </div>

    <div class="db-hero-scroll-hint">
        <span>Scroll</span>
        <i class="fas fa-chevron-down"></i>
    </div>

    <!-- Wave -->
    <div class="db-wave-wrap">
        <svg viewBox="0 0 1440 80" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
            <path d="M0,40 C360,80 1080,0 1440,40 L1440,80 L0,80 Z" fill="#f8fafc" />
        </svg>
    </div>
</section>

<!-- ===== LAYOUT ===== -->
<div class="db-layout">

    <!-- ========== ARTICLE ========== -->
    <article class="db-article">

        <!-- Nav Bar -->
        <div class="db-article-nav">
            <a href="berita.php" class="db-back-btn">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <span class="db-kat-badge" style="background: linear-gradient(135deg, <?php echo $kat_color[0]; ?>, <?php echo $kat_color[1]; ?>);">
                <i class="fas fa-tag" style="font-size:0.7rem;"></i>
                <?php echo htmlspecialchars(ucfirst($berita['kategori'] ?? 'Berita')); ?>
            </span>
        </div>

        <!-- Featured Image -->
        <?php if ($foto_path): ?>
            <div class="db-feat-img-wrap">
                <img src="<?php echo htmlspecialchars($foto_path); ?>" alt="<?php echo htmlspecialchars($berita['judul']); ?>">
                <div class="db-feat-img-overlay"></div>
            </div>
        <?php else: ?>
            <div class="db-no-img"><i class="fas fa-newspaper"></i></div>
        <?php endif; ?>

        <!-- Body -->
        <div class="db-article-body">
            <div class="db-article-text">
                <?php
                $paragraphs = array_filter(array_map('trim', explode("\n", $berita['deskripsi'])));
                $first = true;
                foreach ($paragraphs as $para) {
                    if (empty($para)) continue;
                    if ($first) {
                        echo '<p class="db-first-para">' . htmlspecialchars($para) . '</p>';
                        $first = false;
                    } else {
                        echo '<p>' . htmlspecialchars($para) . '</p>';
                    }
                }
                // Fallback jika single string
                if (empty($paragraphs)) {
                    echo '<p class="db-first-para">' . nl2br(htmlspecialchars($berita['deskripsi'])) . '</p>';
                }
                ?>
            </div>

            <div class="db-divider">
                <span></span><i class="fas fa-star"></i><span></span>
            </div>

            <!-- Author Card -->
            <div class="db-author-card">
                <div class="db-author-card-ava">
                    <?php echo strtoupper(substr($berita['penulis'], 0, 1)); ?>
                </div>
                <div>
                    <div class="db-author-name"><?php echo htmlspecialchars($berita['penulis']); ?></div>
                    <div class="db-author-role">Penulis &middot; Redaksi SMP PGRI 3 BOGOR</div>
                    <div style="font-size:0.8rem;color:var(--gray);margin-top:6px;">
                        <i class="far fa-calendar-alt" style="margin-right:5px;color:var(--primary-light);"></i>
                        Dipublikasikan <?php echo date('d F Y', strtotime($berita['tanggal'])); ?>
                    </div>
                </div>
            </div>

            <!-- Share -->
            <div class="db-share-section">
                <div class="db-share-label"><i class="fas fa-share-alt"></i> Bagikan artikel ini</div>
                <div class="db-share-btns">
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>"
                        target="_blank" class="db-share-btn fb" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode($berita['judul']); ?>&url=<?php echo urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>"
                        target="_blank" class="db-share-btn tw" title="Twitter/X"><i class="fab fa-twitter"></i></a>
                    <a href="https://wa.me/?text=<?php echo urlencode($berita['judul'] . ' - http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>"
                        target="_blank" class="db-share-btn wa" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                    <button class="db-share-btn lnk" title="Salin tautan" onclick="copyLink()"><i class="fas fa-link"></i></button>
                </div>
            </div>

        </div><!-- /body -->
    </article>

    <!-- ========== SIDEBAR ========== -->
    <aside class="db-sidebar">

        <!-- Article Stats -->
        <div class="db-sidebar-widget db-stats-widget">
            <div class="db-widget-title" style="color:#fff;">
                <div class="db-widget-title-bar" style="background:#0ac4e0;"></div>
                Info Artikel
            </div>
            <div class="db-stats-grid">
                <div class="db-stat-item">
                    <div class="db-stat-num"><?php echo $reading_time; ?></div>
                    <div class="db-stat-label">Menit baca</div>
                </div>
                <div class="db-stat-item">
                    <div class="db-stat-num"><?php echo number_format($word_count); ?></div>
                    <div class="db-stat-label">Kata</div>
                </div>
                <div class="db-stat-item">
                    <div class="db-stat-num"><?php echo date('d M', strtotime($berita['tanggal'])); ?></div>
                    <div class="db-stat-label">Tanggal</div>
                </div>
                <div class="db-stat-item">
                    <div class="db-stat-num"><?php echo date('Y', strtotime($berita['tanggal'])); ?></div>
                    <div class="db-stat-label">Tahun</div>
                </div>
            </div>
        </div>

        <!-- Recommended -->
        <?php
        $rec_count = mysqli_num_rows($result_recommend);
        if ($rec_count > 0): ?>
            <div class="db-sidebar-widget">
                <div class="db-widget-title">
                    <div class="db-widget-title-bar"></div>
                    Baca Juga
                </div>
                <div class="db-rec-list">
                    <?php mysqli_data_seek($result_recommend, 0);
                    while ($rec = mysqli_fetch_assoc($result_recommend)): ?>
                        <a href="detail-berita.php?id=<?php echo $rec['id']; ?>" class="db-rec-item">
                            <div class="db-rec-img-wrap">
                                <?php if (!empty($rec['foto']) && file_exists('../' . $rec['foto'])): ?>
                                    <img src="../<?php echo htmlspecialchars($rec['foto']); ?>" alt="">
                                <?php else: ?>
                                    <div class="db-rec-no-img"><i class="fas fa-newspaper"></i></div>
                                <?php endif; ?>
                            </div>
                            <div class="db-rec-info">
                                <span class="db-rec-cat"><?php echo htmlspecialchars($rec['kategori'] ?? ''); ?></span>
                                <div class="db-rec-title"><?php echo htmlspecialchars($rec['judul']); ?></div>
                                <div class="db-rec-date">
                                    <i class="far fa-calendar-alt"></i>
                                    <?php echo date('d M Y', strtotime($rec['tanggal'])); ?>
                                </div>
                            </div>
                        </a>
                    <?php endwhile; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Tags -->
        <div class="db-sidebar-widget">
            <div class="db-widget-title">
                <div class="db-widget-title-bar"></div>
                Topik
            </div>
            <div class="db-tags">
                <span class="db-tag">SMP PGRI 3</span>
                <span class="db-tag">Bogor</span>
                <span class="db-tag"><?php echo htmlspecialchars(ucfirst($berita['kategori'] ?? 'Berita')); ?></span>
                <span class="db-tag">Pendidikan</span>
                <span class="db-tag">Sekolah</span>
                <span class="db-tag">2026</span>
            </div>
        </div>

        <!-- Newsletter -->
        <div class="db-sidebar-widget db-newsletter">
            <div class="db-widget-title">
                <div class="db-widget-title-bar"></div>
                Berlangganan
            </div>
            <p>Dapatkan berita & info terbaru langsung ke email Anda.</p>
            <form onsubmit="event.preventDefault(); this.innerHTML='<p style=\'color:#0ac4e0;font-weight:700;\'>✓ Terima kasih!</p>';">
                <input class="db-nl-input" type="email" placeholder="Email Anda" required>
                <button class="db-nl-btn" type="submit">Langganan Sekarang</button>
            </form>
        </div>

    </aside>
</div>

<!-- CTA Bar -->
<div class="db-cta-bar">
    <div class="db-cta-inner">
        <div class="db-cta-text">
            <h3>Jelajahi Berita Lainnya</h3>
            <p>Tetap update dengan perkembangan terkini di SMP PGRI 3 BOGOR</p>
        </div>
        <a href="berita.php" class="db-cta-btn">
            <i class="fas fa-newspaper"></i> Lihat Semua Berita
        </a>
    </div>
</div>

<script>
    // === Reading Progress ===
    const progressBar = document.getElementById('readingProgress');
    window.addEventListener('scroll', () => {
        const docH = document.documentElement.scrollHeight - window.innerHeight;
        const pct = (window.scrollY / docH) * 100;
        progressBar.style.width = Math.min(pct, 100) + '%';
    });

    // === Parallax Hero Image ===
    const heroImg = document.getElementById('heroParallaxImg');
    if (heroImg) {
        window.addEventListener('scroll', () => {
            heroImg.style.transform = `scale(1.05) translateY(${window.scrollY * 0.12}px)`;
        });
    }

    // === Particles ===
    (function() {
        const container = document.getElementById('heroParticles');
        if (!container) return;
        for (let i = 0; i < 18; i++) {
            const p = document.createElement('div');
            p.className = 'db-particle';
            const size = Math.random() * 60 + 10;
            p.style.cssText = `
            width:${size}px; height:${size}px;
            left:${Math.random()*100}%;
            animation-duration:${Math.random()*15+10}s;
            animation-delay:${Math.random()*-20}s;
            opacity:${Math.random()*0.15 + 0.02};
        `;
            container.appendChild(p);
        }
    })();

    // === Copy link ===
    function copyLink() {
        navigator.clipboard.writeText(window.location.href).then(() => {
            const toast = document.getElementById('copyToast');
            toast.classList.add('show');
            setTimeout(() => toast.classList.remove('show'), 2800);
        });
    }

    // === Scroll reveal ===
    const revealEls = document.querySelectorAll('.db-article, .db-sidebar-widget, .db-cta-inner');
    const revealObs = new IntersectionObserver((entries) => {
        entries.forEach(e => {
            if (e.isIntersecting) {
                e.target.style.opacity = '1';
                e.target.style.transform = 'translateY(0)';
                revealObs.unobserve(e.target);
            }
        });
    }, {
        threshold: 0.08
    });
    revealEls.forEach((el, i) => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = `opacity 0.7s ease ${i * 0.08}s, transform 0.7s ease ${i * 0.08}s`;
        revealObs.observe(el);
    });
</script>

<?php include 'footer.php'; ?>