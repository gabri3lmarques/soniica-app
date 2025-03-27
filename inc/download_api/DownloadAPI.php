<?php

namespace download_api;

use WP_REST_Response;
use download\DownloadController;
use user\Users;

class DownloadAPI {
    public static function init() {
        add_action('rest_api_init', [__CLASS__, 'register_routes']);
    }

    public static function register_routes() {
        register_rest_route('soniica/v1', '/register-download', [
            'methods' => 'POST',
            'callback' => [__CLASS__, 'handle_request'],
            'permission_callback' => '__return_true',
        ]);
    }

    public static function handle_request($request) {
        if (!is_user_logged_in()) {
            return new WP_REST_Response([
                'success' => false,
                'message' => 'Você precisa estar logado.'
            ], 401);
        }

        $download_check = DownloadController::canDownload();

        if (!$download_check['allowed']) {
            return new WP_REST_Response([
                'success' => false,
                'message' => $download_check['message']
            ], 403);
        }

        $is_premium = Users::check_user_premium_status();

        if (!$is_premium) {
            DownloadController::registerDownload();
        }

        return new WP_REST_Response([
            'success' => true,
            'message' => 'Download permitido',
            'is_premium' => $is_premium
        ], 200);
    }
}

DownloadAPI::init();

?>