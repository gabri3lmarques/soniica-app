</body>
<?php 
use user\Users;
$is_premium = Users::check_user_premium_status();
?>
<div id="site-loader">
    <div class="spinner"></div>
</div>
<style>
#site-loader {
    position: fixed;
    width: 100%;
    height: 100%;
    background: #708303;
    z-index: 99;
    top: 0;
    left: 0;
    display: flex;
    justify-content: center;
    align-items: center;
}
.spinner {
    width: 50px;
    height: 50px;
    border: 6px solid #ffffff;
    border-top: 6px solid #708303;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}
@keyframes spin {
    to {
    transform: rotate(360deg);
    }
}
</style>
<script>
    window.addEventListener('load', function () {
    const loader = document.getElementById('site-loader');
    // Tempo desde o início do carregamento da página até agora
    const loadTime = performance.now();
    console.log(`A página carregou em ${loadTime.toFixed(2)}ms`);
    if (loader) {
        loader.style.opacity = '0';
        loader.style.transition = 'opacity 0.5s ease';
        setTimeout(() => loader.remove(), 500);
    }
    });
</script>
<?php wp_footer(); ?>
<?php 
    if(!$is_premium):
?>
<script>
const mainPlayer = document.querySelector('#player-main');
if(mainPlayer){
    const songs = document.querySelectorAll('.playlist .song');
    const adAudioSrc = "https://od.lk/s/Ml8yMzUyMTU1MDZf/soniica.mp3";
    window.songsPlayedCount = 0;
    const originalHandleSongEnd = globalPlayer.handleSongEnd.bind(globalPlayer);
    const stopPlay = globalPlayer.pause.bind(globalPlayer);
    const nextSong = globalPlayer.next.bind(globalPlayer);
    const initializeFirstSong = globalPlayer.initializeFirstSong.bind(globalPlayer);
    globalPlayer.handleSongEnd = function() {
        window.songsPlayedCount++;
        if (window.songsPlayedCount === 1) {
            // Pausa e muta o player principal
            stopPlay();
            songs.forEach(song => {
                song.classList.remove('active');
            });
            globalPlayer.audio.muted = true;
            mainPlayer.classList.add('disabled');
            // Cria e toca o anúncio
            const adAudio = new Audio(adAudioSrc);
            adAudio.play();
            globalPlayer.titleElement.textContent = "Sponsored Ad";
            globalPlayer.artistElement.textContent = "Advertisement";
            globalPlayer.thumbElement.src = "<?php echo get_template_directory_uri() . '/assets/img/logo/soniica.png'; ?>";
            globalPlayer.timeElement.textContent = "00:00";
            const currentSong = globalPlayer.currentSong;
            const playlistSongs = [...songs];
            const isLastSong = playlistSongs.indexOf(currentSong) === playlistSongs.length - 1;
            console.log(currentSong);
            adAudio.addEventListener('ended', () => {
                // Desmuta o player principal
                globalPlayer.audio.muted = false;
                mainPlayer.classList.remove('disabled');
                // Checa se é a última música
                if (!isLastSong) {
                    nextSong();
                } else {
                    initializeFirstSong();
                }
                // Reseta o contador de músicas
                window.songsPlayedCount = 0;
                
            });
            return; // Impede de avançar na playlist durante o anúncio
        }
        originalHandleSongEnd();
    };
}
</script>
<?php endif; ?> 