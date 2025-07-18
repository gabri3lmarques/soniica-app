<?php
/**
 * Search Component Class
 * 
 * A class that generates a search form for a WordPress site
 */
class SearchComponent {
    /**
     * Renders the search component HTML
     * 
     * @param string $placeholder The placeholder text for the search input
     * @return string The HTML for the search component
     */
    public function render($placeholder = "Estilo, gênero, vibe...") {
        ob_start();
        ?>
        <div class="serach-component">
            <form class="search" method="get" action="<?php echo home_url('/'); ?>">
                <input type="text" name="s" placeholder="<?php echo esc_attr($placeholder); ?>" autocomplete="off">
            </form>    
        </div>
        <?php
        return ob_get_clean();
    }
}