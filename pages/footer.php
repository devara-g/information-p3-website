    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h3>SMP PGRI 3 BOGOR</h3>
                <p>Sekolah Menengah Pertama yang berkomitmen menghasilkan lulusan kompeten, kreatif, dan berakhlak mulia.</p>
            </div>
            <div class="footer-section">
                <h3>Menu Cepat</h3>
                <ul>
                    <li><a href="../index.php">Beranda</a></li>
                    <li><a href="about.php">Profil Sekolah</a></li>
                    <li><a href="berita.php">Berita</a></li>
                    <li><a href="galeri.php">Galeri</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Kontak Kami</h3>
                <ul>
                    <li><i class="fas fa-map-marker-alt"></i> Jalan Raya Ciomas No.308 Ciomas Rahayu, Jl. Raya Ciomas, Pasirmulya, Kec. Ciomas, Kabupaten Bogor, Jawa Barat 16610</li>
                    <li><i class="fas fa-phone"></i> (0251) 1234-5678</li>
                    <li><i class="fas fa-envelope"></i> info@smppgri3bogor.sch.id</li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2026 SMP PGRI 3 BOGOR. All Rights Reserved.</p>
        </div>

        <!-- Discreet Admin Portal Access -->
        <a href="../admin/login.php" class="admin-fixed" title="Admin Portal">
            <i class="fas fa-user-cog"></i>
        </a>
    </footer>

    <!-- ========== FLOATING MUSIC PLAYER ========== -->

    <!-- Toggle Button -->
    <button id="musicToggleBtn" title="Putar Musik">
        <i class="fas fa-music"></i>
    </button>

    <!-- Player Widget -->
    <div id="floatingMusicPlayer">

        <!-- Disc + Info -->
        <div class="mp-disc-wrap">
            <div class="mp-disc" id="mpDisc"></div>
            <div class="mp-info">
                <div class="mp-song-title-wrap">
                    <span class="mp-song-title" id="mpSongTitle">Hymne Guru</span>
                </div>
                <div class="mp-artist" id="mpArtist">Lagu Nasional Indonesia</div>
                <!-- Visualizer -->
                <div class="mp-visualizer" id="mpVisualizer">
                    <span></span><span></span><span></span><span></span><span></span>
                </div>
            </div>
        </div>

        <!-- Progress -->
        <div class="mp-progress-wrap">
            <div class="mp-time-row">
                <span id="mpCurrentTime">0:00</span>
                <span id="mpDuration">0:00</span>
            </div>
            <input type="range" class="mp-progress" id="mpProgress" value="0" min="0" max="100" step="0.1">
        </div>

        <!-- Controls -->
        <div class="mp-controls">
            <button class="mp-btn" id="mpShuffleBtn" title="Acak">
                <i class="fas fa-random"></i>
            </button>
            <button class="mp-btn" id="mpPrevBtn" title="Sebelumnya">
                <i class="fas fa-step-backward"></i>
            </button>
            <button class="mp-btn mp-btn-play" id="mpPlayBtn" title="Putar / Jeda">
                <i class="fas fa-play"></i>
            </button>
            <button class="mp-btn" id="mpNextBtn" title="Berikutnya">
                <i class="fas fa-step-forward"></i>
            </button>
            <button class="mp-btn" id="mpRepeatBtn" title="Ulangi">
                <i class="fas fa-redo-alt"></i>
            </button>
        </div>

        <!-- Volume -->
        <div class="mp-volume-row">
            <button class="mp-mute-btn" id="mpMuteBtn" title="Bisukan">
                <i class="fas fa-volume-up" id="mpVolumeIcon"></i>
            </button>
            <input type="range" class="mp-volume" id="mpVolume" min="0" max="1" step="0.01" value="0.7">
            <i class="fas fa-volume-up" style="color:rgba(255,255,255,0.3);font-size:0.85rem;"></i>
        </div>

        <!-- Playlist toggle -->
        <button class="mp-playlist-toggle" id="mpPlaylistToggle">
            <i class="fas fa-list-music"></i> Daftar Putar
            <i class="fas fa-chevron-down" id="mpPlaylistChevron" style="margin-left:4px;font-size:0.65rem;"></i>
        </button>

        <!-- Playlist -->
        <div class="mp-playlist" id="mpPlaylist">
            <!-- Items injected by JS -->
        </div>
    </div>

    <!-- Hidden HTML5 Audio -->
    <audio id="mpAudio" preload="none"></audio>

    <!-- Music Player Script -->
    <script>
    (function () {
        'use strict';

        const playlist = [
            { title: 'Hymne Guru', artist: 'Lagu Nasional Indonesia', src: '../music/Hymne Guru - Lirik Lagu Nasional Indonesia.mp3' },
            { title: 'Indonesia Raya', artist: 'Lagu Kebangsaan Indonesia', src: '../music/Indonesian National Anthem - Indonesia Raya (IDEN).mp3' },
            { title: 'Satu Nusa Satu Bangsa', artist: 'Lagu Nasional Indonesia', src: '../music/Lirik Lagu Satu Nusa Satu Bangsa  vocal by Ceo Jati Atmodjo.mp3' },
            { title: 'Tanah Airku', artist: 'Lagu Nasional Indonesia', src: '../music/TANAH AIRKU  TEKS ( 2023 ).mp3' },
            { title: 'Garuda Pancasila', artist: 'Lagu Nasional Indonesia', src: '../music/Garuda Pancasila - Lagu Nasional Indonesia (dengan Lirik).mp3' },
            { title: 'Mars PGRI', artist: 'Mars PGRI', src: '../music/MARS PGRI (lirik).mp3' }
        ];

        let currentIndex = 0, isPlaying = false, isShuffle = false, isRepeat = false, isMuted = false, playerOpen = false;

        const audio           = document.getElementById('mpAudio');
        const toggleBtn       = document.getElementById('musicToggleBtn');
        const playerEl        = document.getElementById('floatingMusicPlayer');
        const disc            = document.getElementById('mpDisc');
        const songTitle       = document.getElementById('mpSongTitle');
        const artistEl        = document.getElementById('mpArtist');
        const visualizer      = document.getElementById('mpVisualizer');
        const progressBar     = document.getElementById('mpProgress');
        const currentTimeEl   = document.getElementById('mpCurrentTime');
        const durationEl      = document.getElementById('mpDuration');
        const playBtn         = document.getElementById('mpPlayBtn');
        const prevBtn         = document.getElementById('mpPrevBtn');
        const nextBtn         = document.getElementById('mpNextBtn');
        const shuffleBtn      = document.getElementById('mpShuffleBtn');
        const repeatBtn       = document.getElementById('mpRepeatBtn');
        const volumeSlider    = document.getElementById('mpVolume');
        const muteBtn         = document.getElementById('mpMuteBtn');
        const volumeIcon      = document.getElementById('mpVolumeIcon');
        const playlistEl      = document.getElementById('mpPlaylist');
        const playlistToggle  = document.getElementById('mpPlaylistToggle');
        const playlistChevron = document.getElementById('mpPlaylistChevron');
        const toggleIcon      = toggleBtn.querySelector('i');

        function formatTime(s) {
            if (isNaN(s)) return '0:00';
            const m = Math.floor(s / 60), sec = Math.floor(s % 60);
            return m + ':' + (sec < 10 ? '0' : '') + sec;
        }
        function updateProgressStyle() {
            const pct = progressBar.value + '%';
            progressBar.style.background = `linear-gradient(to right, rgba(255,255,255,0.85) 0%, rgba(255,255,255,0.85) ${pct}, rgba(255,255,255,0.15) ${pct}, rgba(255,255,255,0.15) 100%)`;
        }
        function updateVolumeStyle() {
            const pct = (volumeSlider.value * 100) + '%';
            volumeSlider.style.background = `linear-gradient(to right, rgba(255,255,255,0.75) 0%, rgba(255,255,255,0.75) ${pct}, rgba(255,255,255,0.15) ${pct}, rgba(255,255,255,0.15) 100%)`;
        }
        function loadSong(index, andPlay) {
            const song = playlist[index];
            audio.src = song.src;
            songTitle.classList.remove('marquee');
            songTitle.textContent = song.title;
            if (song.title.length > 22) {
                songTitle.textContent = song.title + '   •   ' + song.title + '   •   ';
                void songTitle.offsetWidth;
                songTitle.classList.add('marquee');
            }
            artistEl.textContent = song.artist;
            document.querySelectorAll('.mp-playlist-item').forEach((item, i) => {
                item.classList.toggle('active', i === index);
                item.querySelector('i').className = (i === index) ? 'fas fa-music' : 'fas fa-circle';
            });
            progressBar.value = 0;
            updateProgressStyle();
            currentTimeEl.textContent = '0:00';
            durationEl.textContent = '0:00';
            if (andPlay) audio.play().catch(() => {});
        }
        function setPlaying(val) {
            isPlaying = val;
            if (val) {
                disc.classList.add('spinning'); disc.classList.remove('paused');
                visualizer.classList.add('playing');
                playBtn.innerHTML = '<i class="fas fa-pause"></i>';
                toggleIcon.className = 'fas fa-pause';
            } else {
                disc.classList.add('paused');
                visualizer.classList.remove('playing');
                playBtn.innerHTML = '<i class="fas fa-play"></i>';
                toggleIcon.className = 'fas fa-music';
            }
        }
        function playNext() {
            currentIndex = isShuffle ? Math.floor(Math.random() * playlist.length) : (currentIndex + 1) % playlist.length;
            loadSong(currentIndex, true);
        }
        function playPrev() {
            if (audio.currentTime > 3) { audio.currentTime = 0; return; }
            currentIndex = (currentIndex - 1 + playlist.length) % playlist.length;
            loadSong(currentIndex, isPlaying);
        }

        playBtn.addEventListener('click', () => { isPlaying ? audio.pause() : (audio.src || loadSong(currentIndex, false), audio.play().catch(() => {})); });
        audio.addEventListener('play', () => setPlaying(true));
        audio.addEventListener('pause', () => setPlaying(false));
        audio.addEventListener('ended', () => { isRepeat ? (audio.currentTime = 0, audio.play().catch(() => {})) : playNext(); });
        prevBtn.addEventListener('click', playPrev);
        nextBtn.addEventListener('click', playNext);
        shuffleBtn.addEventListener('click', () => {
            isShuffle = !isShuffle;
            shuffleBtn.style.color = isShuffle ? '#fff' : 'rgba(255,255,255,0.45)';
            shuffleBtn.style.background = isShuffle ? 'rgba(255,255,255,0.22)' : 'rgba(255,255,255,0.12)';
        });
        repeatBtn.addEventListener('click', () => {
            isRepeat = !isRepeat;
            repeatBtn.style.color = isRepeat ? '#fff' : 'rgba(255,255,255,0.45)';
            repeatBtn.style.background = isRepeat ? 'rgba(255,255,255,0.22)' : 'rgba(255,255,255,0.12)';
        });
        audio.addEventListener('timeupdate', () => {
            if (!audio.duration) return;
            progressBar.value = (audio.currentTime / audio.duration) * 100;
            updateProgressStyle();
            currentTimeEl.textContent = formatTime(audio.currentTime);
        });
        audio.addEventListener('loadedmetadata', () => { durationEl.textContent = formatTime(audio.duration); });
        progressBar.addEventListener('input', () => {
            if (audio.duration) audio.currentTime = (progressBar.value / 100) * audio.duration;
            updateProgressStyle();
        });
        audio.volume = parseFloat(volumeSlider.value);
        volumeSlider.addEventListener('input', () => {
            audio.volume = parseFloat(volumeSlider.value);
            isMuted = audio.volume === 0;
            updateVolumeIcon(); updateVolumeStyle();
        });
        function updateVolumeIcon() {
            volumeIcon.className = (isMuted || audio.volume === 0) ? 'fas fa-volume-mute' : audio.volume < 0.4 ? 'fas fa-volume-down' : 'fas fa-volume-up';
        }
        muteBtn.addEventListener('click', () => {
            isMuted = !isMuted; audio.muted = isMuted;
            volumeIcon.className = isMuted ? 'fas fa-volume-mute' : (audio.volume < 0.4 ? 'fas fa-volume-down' : 'fas fa-volume-up');
        });
        toggleBtn.addEventListener('click', () => {
            playerOpen = !playerOpen;
            if (playerOpen) {
                playerEl.classList.add('active');
                playerEl.style.animation = 'none'; void playerEl.offsetWidth; playerEl.style.animation = '';
                if (!audio.src) loadSong(currentIndex, false);
            } else {
                playerEl.classList.remove('active');
            }
        });
        playlist.forEach((song, i) => {
            const item = document.createElement('div');
            item.className = 'mp-playlist-item' + (i === 0 ? ' active' : '');
            item.innerHTML = `<span class="mp-playlist-num">${i + 1}</span><i class="${i === 0 ? 'fas fa-music' : 'fas fa-circle'}"></i><span style="flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${song.title}</span>`;
            item.addEventListener('click', () => { currentIndex = i; loadSong(i, true); });
            playlistEl.appendChild(item);
        });
        playlistToggle.addEventListener('click', () => {
            playlistEl.classList.toggle('open');
            playlistChevron.style.transform = playlistEl.classList.contains('open') ? 'rotate(180deg)' : 'rotate(0deg)';
        });
        updateProgressStyle(); updateVolumeStyle();
    })();
    </script>

    </body>
    </html>

