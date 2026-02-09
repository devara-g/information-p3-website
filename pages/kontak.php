<?php include 'header.php'; ?>

<section class="about-hero">
    <h1>Kontak Kami</h1>
    <p>Hubungi SMP PGRI 3 BOGOR</p>
</section>

<section class="gallery-content">
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem;">
        <div>
            <div class="about-card" style="text-align: left; margin-bottom: 1.5rem;">
                <h3 style="margin-bottom: 1rem;"><i class="fas fa-map-marker-alt"></i> Alamat</h3>
                <p>Jl. Raya Tajur No. 12B<br>Bogor Timur<br>Jawa Barat</p>
            </div>
            <div class="about-card" style="text-align: left; margin-bottom: 1.5rem;">
                <h3 style="margin-bottom: 1rem;"><i class="fas fa-phone"></i> Telepon</h3>
                <p>(0251) 1234-5678<br>(0251) 1234-5679</p>
            </div>
            <div class="about-card" style="text-align: left; margin-bottom: 1.5rem;">
                <h3 style="margin-bottom: 1rem;"><i class="fas fa-envelope"></i> Email</h3>
                <p>info@smppgri3bogor.sch.id<br>humas@smppgri3bogor.sch.id</p>
            </div>
            <div class="about-card" style="text-align: left;">
                <h3 style="margin-bottom: 1rem;"><i class="fas fa-globe"></i> Sosial Media</h3>
                <p><i class="fab fa-facebook"></i> Facebook: @smppgri3bogor<br>
                    <i class="fab fa-instagram"></i> Instagram: @smppgri3bogor<br>
                    <i class="fab fa-twitter"></i> Twitter: @smppgri3bogor<br>
                    <i class="fab fa-youtube"></i> YouTube: SMP PGRI 3 BOGOR
                </p>
            </div>
        </div>
        <div>
            <div class="about-card">
                <h3 style="margin-bottom: 1.5rem;">📨 Kirim Pesan</h3>
                <form style="text-align: left;">
                    <div style="margin-bottom: 1rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Nama</label>
                        <input type="text" placeholder="Nama lengkap Anda" style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem;">
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Email</label>
                        <input type="email" placeholder="email@anda.com" style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem;">
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Subjek</label>
                        <input type="text" placeholder="Subjek pesan" style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem;">
                    </div>
                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Pesan</label>
                        <textarea placeholder="Tulis pesan Anda..." rows="5" style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem; resize: vertical;"></textarea>
                    </div>
                    <button type="submit" style="background: var(--primary); color: white; border: none; padding: 1rem 2rem; border-radius: 8px; cursor: pointer; font-size: 1rem; width: 100%;">Kirim Pesan</button>
                </form>
            </div>
        </div>
    </div>
</section>

<section style="padding: 0 2rem 4rem; max-width: 1200px; margin: 0 auto;">
    <div style="width: 100%; height: 400px; background: #ddd; border-radius: 15px; display: flex; align-items: center; justify-content: center;">
        <iframe style="color: #666; font-size: 1.2rem;" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2539.0293709443768!2d106.76799617233665!3d-6.6017412933921005!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69c5a76272bded%3A0x6062ff519c374276!2sSMP%20PGRI%203%20BOGOR!5e1!3m2!1sid!2sid!4v1770558972453!5m2!1sid!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
</section>

<?php include 'footer.php'; ?>