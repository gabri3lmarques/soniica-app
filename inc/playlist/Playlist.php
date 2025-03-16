<?php

namespace playlist;

class Playlist {

    public function __construct() {
        add_action('init', [$this, 'register_playlist_post_type']);
        add_action('rest_api_init', [$this, 'register_rest_routes']);
    }

    // 1. Registro do Custom Post Type 'playlist'
    public function register_playlist_post_type() {
        $labels = [
            'name' => 'Playlists',
            'singular_name' => 'Playlist'
        ];
    
        $args = [
            'labels' => $labels,
            'public' => true, // Permite acesso direto via URL
            'show_ui' => true,
            'supports' => ['title', 'author'],
            'capability_type' => 'post',
            'has_archive' => true, // Permite o arquivo de todas as playlists
            'rewrite' => [
                'slug' => 'playlist',  // Define o slug da URL
                'with_front' => false, // Remove o /blog/ se o site usar
            ],
        ];
    
        register_post_type('playlist', $args);
    }
    

    // 2. Registro das rotas da REST API para manipulação das playlists

    public function register_rest_routes() {

        // Rota para criar uma nova playlist
        register_rest_route('soniica/v1', '/playlist/create', [
            'methods' => 'POST',
            'callback' => [$this, 'create_playlist'],
            'permission_callback' => function () {
                return is_user_logged_in();
            }
        ]);

        // Rota para excluir uma playlist
        register_rest_route('soniica/v1', '/playlist/(?P<id>\d+)', [
            'methods' => 'DELETE',
            'callback' => [$this, 'delete_playlist'],
            'permission_callback' => [$this, 'verify_ownership']
        ]);

        // Rota para adicionar uma nova musica a uma playlist
        register_rest_route('soniica/v1', '/playlist/(?P<id>\d+)/add-song', [
            'methods' => 'POST',
            'callback' => [$this, 'add_song_to_playlist'],
            'permission_callback' => [$this, 'verify_ownership']
        ]);

        // Rota para remover uma musica de uma playlist
        register_rest_route('soniica/v1', '/playlist/(?P<id>\d+)/remove-song', [
            'methods' => 'POST',
            'callback' => [$this, 'remove_song_from_playlist'],
            'permission_callback' => [$this, 'verify_ownership']
        ]);
    }

    // 3. Criar uma nova playlist
    public function create_playlist($request) {
        $title = sanitize_text_field($request['title']);

        $playlist_id = wp_insert_post([
            'post_title' => $title,
            'post_type' => 'playlist',
            'post_status' => 'publish',
            'post_author' => get_current_user_id(),
        ]);

        if (is_wp_error($playlist_id)) {
            return new \WP_Error('playlist_creation_failed', 'Não foi possível criar a playlist.', ['status' => 500]);
        }

        return ['success' => true, 'playlist_id' => $playlist_id];
    }

    // 4. Deletar uma playlist
    public function delete_playlist($playlist_id) {
        $playlist_id = (int) $playlist_id;
    
        if (get_post_type($playlist_id) !== 'playlist') {
            return ['success' => false, 'message' => 'Playlist inválida.'];
        }
    
        // Deleta a playlist
        $deleted = wp_delete_post($playlist_id, true);
    
        if ($deleted) {
            return ['success' => true, 'message' => 'Playlist excluída com sucesso.'];
        } else {
            return ['success' => false, 'message' => 'Erro ao excluir a playlist.'];
        }
    }

    // Adicionar uma música à playlist
    public function add_song_to_playlist($request) {
        $playlist_id = (int) $request['id'];
        $song_id = (int) $request['song_id'];

        // Obtém a lista de músicas da playlist (ou um array vazio se não houver)
        $songs = get_post_meta($playlist_id, 'songs', true) ?: [];

        // Verifica se a musica já está na playlist
        if (in_array($song_id, $songs)) {
            return ['success' => false, 'message' => 'A musica ja esta na playlist'];
        }

        // Adiciona a musica e atualiza o post meta
        $songs[] = $song_id;
        update_post_meta($playlist_id, 'songs', $songs);

        return [
            'success' => true,
            'message' => 'Música adicionada com sucesso!',
            'songs' => $songs,
        ];
    }

    // 6. Remover uma musica da playlist
    public function remove_song_from_playlist($request) {
        
        $playlist_id = (int) $request['id'];
        $song_id = (int) $request['song_id'];

        $songs = get_post_meta($playlist_id, 'songs', true) ?: [];
        $songs = array_diff($songs, [$song_id]);
        update_post_meta($playlist_id, 'songs', $songs);

        return ['success' => true, 'songs' => $songs];
    }

    // 7. Verificar se o usuário é o dono da playlist
    private function verify_ownership($request) {
        $playlist_id = (int) $request['id'];
        $playlist = get_post($playlist_id);

        return (get_current_user_id() === (int) $playlist->post_author);
    }
}

