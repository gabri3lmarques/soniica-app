<?php

namespace loader;

class Loader {
    public function __construct() {
        add_action('wp_footer', [$this, 'render_loader_html']);
        add_action('wp_head', [$this, 'render_loader_styles']);
        add_action('wp_footer', [$this, 'render_loader_script'], 100);
    }

    public function render_loader_html() {
        ?>
        <div id="site-loader">
            <div class="spinner"></div>
        </div>
        <?php
    }

    public function render_loader_styles() {
        ?>
        <style>
            #site-loader {
                position: fixed;
                width: 100%;
                height: 100%;
                background: #fff;
                z-index: 9999;
                top: 0;
                left: 0;
                display: flex;
                justify-content: center;
                align-items: center;
            }

            .spinner {
                width: 50px;
                height: 50px;
                border: 6px solid #ccc;
                border-top: 6px solid #000;
                border-radius: 50%;
                animation: spin 1s linear infinite;
            }

            @keyframes spin {
                to {
                    transform: rotate(360deg);
                }
            }
        </style>
        <?php
    }

    public function render_loader_script() {
        ?>
        <script>
            function hideSiteLoader() {
                const loader = document.getElementById('site-loader');
                if (!loader) return;
    
                loader.style.transition = 'opacity 0.5s ease';
                loader.style.opacity = '0';
                setTimeout(() => loader.remove(), 500);
            }
    
            // Primeiro tenta pelo evento window.load
            window.addEventListener('load', hideSiteLoader);
    
            // Se por algum motivo 'load' não disparar, garante remoção depois de 15s
            setTimeout(hideSiteLoader, 15000);
        </script>
        <?php
    }
}
