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
        const adAudioSrc = "https://od.lk/s/Ml8yMzUyMTU1MDZf/soniica.mp3";
        window.songsPlayedCount = 0;
        const originalHandleSongEnd = globalPlayer.handleSongEnd.bind(globalPlayer);
        const nextSong = globalPlayer.next.bind(globalPlayer);
        globalPlayer.handleSongEnd = function() {
            window.songsPlayedCount++;
            if (window.songsPlayedCount === 1) {
                // Pausa e muta o player principal
                globalPlayer.audio.pause();
                globalPlayer.audio.muted = true;
                // Cria e toca o anúncio
                const adAudio = new Audio(adAudioSrc);
                adAudio.play();
                globalPlayer.titleElement.textContent = "Sponsored Ad";
                globalPlayer.artistElement.textContent = "Advertisement";
                globalPlayer.thumbElement.src = "<?php echo get_template_directory_uri() . '/assets/img/logo/soniica.png'; ?>";
                globalPlayer.timeElement.textContent = "00:00";
                adAudio.addEventListener('ended', () => {
                    // Desmuta e volta a tocar o player principal
                    globalPlayer.audio.muted = false;
                    nextSong();
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