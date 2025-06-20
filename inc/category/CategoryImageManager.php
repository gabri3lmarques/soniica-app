<?php

/*
Essa classe é responsável por adicionar imagens às categorias
*/

namespace category;

// Evita acesso direto
if (!defined('ABSPATH')) {
    exit;
}

class CategoryImageManager {
    public function __construct() {
        add_action('category_add_form_fields', [$this, 'add_category_image_field']);
        add_action('category_edit_form_fields', [$this, 'edit_category_image_field']);
        add_action('edited_category', [$this, 'save_category_image']);
        add_action('create_category', [$this, 'save_category_image']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_category_script']);
    }

    // Adiciona o campo de imagem na criação de categorias
    public function add_category_image_field($taxonomy) {
        ?>
        <div class="form-field">
            <label for="category-image">Imagem da Categoria</label>
            <input type="hidden" id="category-image" name="category-image" value="" />
            <div id="category-image-preview" style="margin: 10px 0; max-width: 150px; max-height: 150px;">
                <img src="" style="width: 100%; height: auto; display: none;" />
            </div>
            <button type="button" class="button upload-category-image">Escolher Imagem</button>
            <button type="button" class="button remove-category-image" style="display: none;">Remover Imagem</button>
            <p>Selecione uma imagem para a categoria.</p>
        </div>
        <?php
    }

    // Adiciona o campo de imagem na edição de categorias
    public function edit_category_image_field($term) {
        $image_id = get_term_meta($term->term_id, 'category-image', true);
        $image_url = $image_id ? wp_get_attachment_url($image_id) : '';
        ?>
        <tr class="form-field">
            <th scope="row"><label for="category-image">Imagem da Categoria</label></th>
            <td>
                <input type="hidden" id="category-image" name="category-image" value="<?php echo esc_attr($image_id); ?>" />
                <div id="category-image-preview" style="margin: 10px 0; max-width: 150px; max-height: 150px;">
                    <img src="<?php echo esc_url($image_url); ?>" style="width: 100%; height: auto; <?php echo $image_url ? '' : 'display: none;'; ?>" />
                </div>
                <button type="button" class="button upload-category-image">Escolher Imagem</button>
                <button type="button" class="button remove-category-image" style="<?php echo $image_url ? '' : 'display: none;'; ?>">Remover Imagem</button>
                <p>Selecione uma imagem para a categoria.</p>
            </td>
        </tr>
        <?php
    }

    // Salva o ID da imagem da categoria
    public function save_category_image($term_id) {
        if (isset($_POST['category-image']) && !empty($_POST['category-image'])) {
            update_term_meta($term_id, 'category-image', sanitize_text_field($_POST['category-image']));
        } else {
            delete_term_meta($term_id, 'category-image');
        }
    }

    //chama o js
    function enqueue_category_script() {
        wp_enqueue_media(); // Enfileira os scripts necessários para o wp.media
        wp_enqueue_script(
            'soniica-admin-script',
            get_template_directory_uri() . '/inc/category/CategoryImageManager.js',
            array('jquery'),
            null,
            true
        );
    }
}


