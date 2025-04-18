</body>
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