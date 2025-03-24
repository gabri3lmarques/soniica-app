<?php

namespace assets;

class Assets {
    public function __construct() {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
    }

    public function enqueue_assets() {
        wp_enqueue_style('soniica-styles', get_template_directory_uri() . '/assets/css/main.css', array(), '1.0.0', 'all');
        wp_enqueue_script('soniica-script', get_template_directory_uri() . '/assets/js/main.min.js', ['jquery'], null, true);
        wp_localize_script('soniica-script', 'wpApiSettings', [
            'nonce' => wp_create_nonce('wp_rest'),
            'root' => esc_url_raw(rest_url())
        ]);
    }
}