<?php include 'components/header.php'; ?>

<section class="hero">

        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
        <div class="shape shape-4"></div>
        <div class="shape shape-5"></div>
        <div class="shape shape-6"></div>
    </div>
    <div class="hero-content">
        <div class="hero-text">
            <h1 class="stagger-reveal">
                <span>Selamat Datang di</span>
                <span class="highlight">SMP PGRI 3 BOGOR</span>
            </h1>
            <p class="fade-up-delay">Membangun Generasi Cerdas, Kreatif, dan Berkarakter</p>

            <div class="principal-bubble">
                <i class="fas fa-quote-left quote-icon"></i>
                <h3>"Pendidikan adalah passport masa depan"</h3>
                <p>- Indra Robriandri, S.Pd -</p>
            </div>
        </div>

        <div class="hero-image">
            <div class="principal-photo">
                <img src="img/p3hd.jpg" alt="Logo Sekolah">
            </div>
        </div>
    </div>
    <?php include 'pages/wave.php'; ?>
</section>

<!-- ══ SAMBUTAN KEPALA SEKOLAH — PREMIUM REDESIGN ══ -->
<section class="sm-section">
    <!-- Decorative background blobs -->
    <div class="sm-blob sm-blob-1"></div>
    <div class="sm-blob sm-blob-2"></div>

    <div class="sm-wrap">
        <!-- Section Header -->
        <div class="sm-header" id="smHeader">
            <span class="sm-eyebrow"><i class="fas fa-chalkboard-teacher"></i> Dari Pimpinan</span>
            <h2 class="sm-title">Sambutan <span>Kepala Sekolah</span></h2>
        </div>

        <div class="sm-inner">
            <!-- ── LEFT: Profile Card ── -->
            <div class="sm-profile" id="smProfile">
                <!-- Floating accent circles -->
                <div class="sm-profile-deco sm-deco-1"></div>
                <div class="sm-profile-deco sm-deco-2"></div>

                <div class="sm-photo-wrap">
                    <div class="sm-photo-ring"></div>
                    <img src="img/kepsek.jpg" alt="Kepala Sekolah" class="sm-photo">
                    <div class="sm-photo-verified">
                        <i class="fas fa-check"></i>
                    </div>
                </div>

                <div class="sm-profile-name">
                    <h3>Drs. Indra Robriandri, M.Si</h3>
                    <p><i class="fas fa-graduation-cap"></i> Kepala Sekolah</p>
                </div>

                <!-- School Stats -->
                <div class="sm-stats">
                    <div class="sm-stat">
                        <span class="sm-stat-num" data-target="42">0</span>
                        <span class="sm-stat-plus">+</span>
                        <span class="sm-stat-label">Tahun<br>Berdiri</span>
                    </div>
                    <div class="sm-stat-divider"></div>
                    <div class="sm-stat">
                        <span class="sm-stat-num" data-target="800">0</span>
                        <span class="sm-stat-plus">+</span>
                        <span class="sm-stat-label">Siswa<br>Aktif</span>
                    </div>
                    <div class="sm-stat-divider"></div>
                    <div class="sm-stat">
                        <span class="sm-stat-num" data-target="45">0</span>
                        <span class="sm-stat-plus">+</span>
                        <span class="sm-stat-label">Tenaga<br>Pengajar</span>
                    </div>
                </div>

                <!-- Social badges -->
                <div class="sm-badges">
                    <span class="sm-badge-item"><i class="fas fa-award"></i> Akreditasi A</span>
                    <span class="sm-badge-item"><i class="fas fa-medal"></i> PGRI Terbaik</span>
                </div>
            </div>

            <!-- ── RIGHT: Message Panel ── -->
            <div class="sm-message" id="smMessage">
                <div class="sm-big-quote">&ldquo;</div>

                <p class="sm-greeting">Assalamu'alaikum Wr. Wb.</p>

                <div class="sm-body">
                    <p>Segala puji bagi Allah SWT yang telah memberikan rahmat dan hidayah-Nya. Dengan penuh kebanggaan, saya menyambut Anda di website resmi <strong>SMP PGRI 3 BOGOR</strong>.</p>
                    <p>Kami berkomitmen untuk memberikan pendidikan terbaik bagi putra-putri Anda — mencetak generasi yang tidak hanya cerdas secara intelektual, tetapi juga memiliki <strong>karakter yang kuat dan berakhlak mulia</strong>.</p>
                    <p>Didukung oleh tenaga pengajar profesional dan fasilitas modern, kami yakin mampu menghasilkan lulusan yang kompeten, kreatif, dan siap menghadapi tantangan global.</p>
                    <p>Mari bersama kita wujudkan generasi yang cerdas, inovatif, dan berdaya saing tinggi untuk Indonesia yang lebih maju.</p>
                </div>

                <p class="sm-closing">Wassalamu'alaikum Wr. Wb.</p>

                <div class="sm-signature">
                    <div class="sm-sig-wave">
                        <svg viewBox="0 0 200 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 30 C40 5, 60 35, 90 20 S140 5, 170 25 S190 35, 200 20" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" fill="none"/>
                        </svg>
                    </div>
                    <div class="sm-sig-info">
                        <span class="sm-sig-date"><i class="fas fa-calendar-check"></i> Bogor, Februari 2026</span>
                        <strong class="sm-sig-name">Drs. Indra Robriandri, M.Si</strong>
                        <span class="sm-sig-pos">Kepala Sekolah SMP PGRI 3 BOGOR</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
// ── Sambutan: scroll reveal + counter animation ──
(function() {
    const header  = document.getElementById('smHeader');
    const profile = document.getElementById('smProfile');
    const message = document.getElementById('smMessage');

    function countUp(el) {
        const target = parseInt(el.dataset.target);
        const duration = 1800;
        const step = target / (duration / 16);
        let current = 0;
        const timer = setInterval(() => {
            current += step;
            if (current >= target) { el.textContent = target; clearInterval(timer); }
            else el.textContent = Math.floor(current);
        }, 16);
    }

    const io = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (!entry.isIntersecting) return;
            const t = entry.target;
            t.classList.add('sm-in');
            // Trigger counters when profile card enters
            if (t === profile) {
                setTimeout(() => {
                    document.querySelectorAll('.sm-stat-num').forEach(el => countUp(el));
                }, 400);
            }
            io.unobserve(t);
        });
    }, { threshold: 0.15 });

    [header, profile, message].forEach(el => { if(el) io.observe(el); });
})();
</script>

<?php include 'components/footer.php'; ?>

