<?php

namespace search;

class Soniica_Search {
    public function __construct() {
        add_filter('posts_search', [$this, 'optimize_search'], 10, 2);
        add_action('after_switch_theme', [$this, 'add_search_index']);
        add_filter('the_posts', [$this, 'get_cached_results'], 10, 2);
        add_action('pre_get_posts', [$this, 'limit_results']);
    }

    public function optimize_search($search, $wp_query) {
        global $wpdb;

        if (!is_admin() && $wp_query->is_search() && $wp_query->is_main_query()) {
            $search_terms = array_filter(explode(' ', get_search_query()));
            if (empty($search_terms)) return $search;

            $like_terms = array_map(fn($term) => '%' . $wpdb->esc_like($term) . '%', $search_terms);
            $search_sql_parts = [];
            $search_values = [];

            foreach ($like_terms as $like_term) {
                $search_sql_parts[] = "({$wpdb->posts}.post_title LIKE %s OR {$wpdb->posts}.post_content LIKE %s)";
                $search_values[] = $like_term;
                $search_values[] = $like_term;
            }

            $tax_queries = array_map(fn($like_term) => $wpdb->prepare(
                "EXISTS (
                    SELECT 1 FROM {$wpdb->term_relationships} tr
                    INNER JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
                    INNER JOIN {$wpdb->terms} t ON tt.term_id = t.term_id
                    WHERE tr.object_id = {$wpdb->posts}.ID
                    AND tt.taxonomy IN ('post_tag', 'category')
                    AND t.name LIKE %s
                    LIMIT 1
                )", $like_term
            ), $like_terms);

            $search = " AND (" . implode(' OR ', $search_sql_parts) . " OR " . implode(' OR ', $tax_queries) . ")";
            if (!empty($search_values)) $search = $wpdb->prepare($search, ...$search_values);
        }

        return $search;
    }

    public function add_search_index() {
        global $wpdb;
        $check_index = $wpdb->get_results("SHOW INDEX FROM {$wpdb->terms} WHERE Key_name = 'term_search_idx'");
        if (empty($check_index)) {
            $wpdb->query("CREATE INDEX term_search_idx ON {$wpdb->terms} (name)");
        }
    }

    public function get_cached_results($posts, $wp_query) {
        if (!is_admin() && $wp_query->is_search() && $wp_query->is_main_query()) {
            $cache_key = 'search_' . md5(get_search_query());
            $cached_posts = wp_cache_get($cache_key, 'search_cache');
            if ($cached_posts !== false) return $cached_posts;
            wp_cache_set($cache_key, $posts, 'search_cache', 3600);
        }
        return $posts;
    }

    public function limit_results($query) {
        if ($query->is_search() && $query->is_main_query() && !is_admin()) {
            // Definindo o post_type como 'song' para retornar apenas este tipo
            $query->set('post_type', 'song');
    
            // Se quiser manter o limite de resultados por página
            $query->set('posts_per_page', 20);
        }
    }
}

// Inicializar a classe
new Soniica_Search();