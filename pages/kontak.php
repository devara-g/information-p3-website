<?php include 'header.php'; ?>

<section class="about-hero">
    <div class="floating-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
    </div>
    <h1>Kontak Kami</h1>
    <p>Hubungi SMP PGRI 3 BOGOR</p>
</section>

<section class="contact-section">
    <div class="contact-wrapper">
        <!-- Contact Information -->
        <div class="contact-info-card" data-delay="0s">
            <div class="contact-header">
                <h2>Informasi Kontak</h2>
                <p>Jangan ragu untuk menghubungi kami melalui saluran resmi berikut.</p>
            </div>

            <div class="contact-details">
                <div class="contact-item" data-delay="0.2s">
                    <div class="icon-box">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="item-content">
                        <h3>Alamat</h3>
                        <p>Jl. Raya Tajur No. 12B<br>Bogor Timur, Jawa Barat</p>
                    </div>
                </div>

                <div class="contact-item" data-delay="0.4s">
                    <div class="icon-box">
                        <i class="fas fa-phone-alt"></i>
                    </div>
                    <div class="item-content">
                        <h3>Telepon</h3>
                        <p>(0251) 1234-5678<br>(0251) 1234-5679</p>
                    </div>
                </div>

                <div class="contact-item" data-delay="0.6s">
                    <div class="icon-box">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="item-content">
                        <h3>Email</h3>
                        <p>info@smppgri3bogor.sch.id<br>humas@smppgri3bogor.sch.id</p>
                    </div>
                </div>
            </div>

            <div class="social-links-contact" data-delay="0.8s">
                <h3>Ikuti Kami</h3>
                <div class="social-icons">
                    <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
        </div>

        <!-- Contact Form -->
        <div class="contact-form-card" data-delay="0.3s">
            <div class="form-header">
                <h2>Kirim Pesan</h2>
                <p>Kami akan segera membalas pesan Anda.</p>
            </div>
            <form>
                <div class="form-grid">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Nama Lengkap" required>
                    </div>
                    <div class="form-group">
                        <input type="email" class="form-control" placeholder="Email Anda" required>
                    </div>
                    <div class="form-group full">
                        <input type="text" class="form-control" placeholder="Subjek Pesan" required>
                    </div>
                    <div class="form-group full">
                        <textarea class="form-control" rows="5" placeholder="Tulis pesan Anda disini..." required></textarea>
                    </div>
                </div>
                <button type="submit" class="btn-send">
                    Kirim Pesan <i class="fas fa-paper-plane"></i>
                </button>
            </form>
        </div>
    </div>
</section>

<section class="map-section">
    <div class="map-wrapper" data-delay="0.5s">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2539.0293709443768!2d106.76799617233665!3d-6.6017412933921005!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69c5a76272bded%3A0x6062ff519c374276!2sSMP%20PGRI%203%20BOGOR!5e1!3m2!1sid!2sid!4v1770558972453!5m2!1sid!2sid" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
</section>

<?php include 'footer.php'; ?>