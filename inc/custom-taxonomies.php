<?php
/**
 * Register custom taxonomies for Pundito Theme
 */

/**
 * Registra a taxonomia 'episode_order' para o tipo de post 'editorial'.
 */
function pundito_register_taxonomy_episode_order() {
    $labels = array(
        'name'              => _x('Episode Orders', 'taxonomy general name'),
        'singular_name'     => _x('Episode Order', 'taxonomy singular name'),
        'search_items'      => __('Search Episode Orders'),
        'all_items'         => __('All Episode Orders'),
        'edit_item'         => __('Edit Episode Order'),
        'update_item'       => __('Update Episode Order'),
        'add_new_item'      => __('Add New Episode Order'),
        'new_item_name'     => __('New Episode Order Name'),
        'menu_name'         => __('Episode Orders'),
    );

    $args = array(
        'hierarchical'      => false,
        'labels'            => $labels,
        'show_ui'           => false,
        'show_in_menu'      => true,
        'show_in_nav_menus' => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'episode_order', 'with_front' => true),
        'show_in_rest'      => true,
    );

    register_taxonomy('episode_order', array('editorial'), $args);
}


/**
 * Registra a taxonomia 'industry' para os tipos de post 'editorial' e 'industry_post'.
 */
function pundito_register_industry_taxonomy() {
    $labels = array(
        'name'                       => _x('Industries', 'taxonomy general name', 'pundito'),
        'singular_name'              => _x('Industry', 'taxonomy singular name', 'pundito'),
        'search_items'               => __('Search Industries', 'pundito'),
        'popular_items'              => __('Popular Industries', 'pundito'),
        'all_items'                  => __('All Industries', 'pundito'),
        'edit_item'                  => __('Edit Industry', 'pundito'),
        'update_item'                => __('Update Industry', 'pundito'),
        'add_new_item'               => __('Add New Industry', 'pundito'),
        'new_item_name'              => __('New Industry Name', 'pundito'),
        'menu_name'                  => __('Industries', 'pundito'),
    );

    $args = array(
        'public' => true,
        'hierarchical'          => false,
        'labels'                => $labels,
        'show_ui'               => true,
        'show_in_nav_menus'     => true,
        'show_admin_column'     => true,
        'query_var'             => true,
        'rewrite'               => array('slug' => 'industry', 'with_front' => true, 'has_archive' => true),
        'show_in_rest'          => true,
    );

    register_taxonomy('industry', ['editorial', 'industry_post'], $args);
    add_action('industry_add_form_fields', 'pundito_add_industry_image_field', 10, 2);
    add_action('created_industry', 'pundito_save_industry_image', 10, 2);
    add_action('industry_edit_form_fields', 'pundito_edit_industry_image_field', 10, 2);
    add_action('edited_industry', 'pundito_update_industry_image', 10, 2);
}

// Inicializa todas as taxonomias
function pundito_register_all_taxonomies() {
    pundito_register_taxonomy_episode_order();
    pundito_register_industry_taxonomy();
}
add_action('init', 'pundito_register_all_taxonomies');


/**
 * Adiciona campo de imagem no formulário de nova taxonomia 'Industry'.
 */
function pundito_add_industry_image_field($taxonomy) {
    ?>
    <div class="form-field term-group">
        <label for="pundito-industry-image-id"><?php _e('Featured Image', 'pundito'); ?></label>
        <input type="hidden" id="pundito-industry-image-id" name="pundito-industry-image-id" class="custom_media_url" value="">
        <div id="pundito-industry-image-wrapper"></div>
        <p>
            <input type="button" class="button button-secondary pundito_media_button" id="pundito-industry-upload-image" name="pundito-industry-upload-image" value="<?php _e('Add Image', 'pundito'); ?>" />
            <input type="button" class="button button-secondary pundito_media_button" id="pundito-industry-remove-image" name="pundito-industry-remove-image" value="<?php _e('Remove Image', 'pundito'); ?>" />
        </p>
    </div>
    <?php
}

/**
 * Salva a imagem quando a taxonomia é criada.
 */
function pundito_save_industry_image($term_id, $tt_id) {
    if (isset($_POST['pundito-industry-image-id']) && '' !== $_POST['pundito-industry-image-id']) {
        add_term_meta($term_id, 'pundito-industry-image-id', $_POST['pundito-industry-image-id'], true);
    }
}

/**
 * Adiciona campo de imagem ao editar a taxonomia 'Industry'.
 */
function pundito_edit_industry_image_field($term, $taxonomy) {
    $image_id = get_term_meta($term->term_id, 'pundito-industry-image-id', true);
    ?>
    <tr class="form-field term-group-wrap">
        <th scope="row">
            <label for="pundito-industry-image-id"><?php _e('Featured Image', 'pundito'); ?></label>
        </th>
        <td>
            <input type="hidden" id="pundito-industry-image-id" name="pundito-industry-image-id" value="<?php echo $image_id; ?>">
            <div id="pundito-industry-image-wrapper">
                <?php if ($image_id) : ?>
                    <?php echo wp_get_attachment_image($image_id, 'thumbnail'); ?>
                <?php endif; ?>
            </div>
            <p>
                <input type="button" class="button button-secondary pundito_media_button" id="pundito-industry-upload-image" name="pundito-industry-upload-image" value="<?php _e('Add Image', 'pundito'); ?>" />
                <input type of="button" class="button button-secondary pundito_media_button" id="pundito-industry-remove-image" name="pundito-industry-remove-image" value="<?php _e('Remove Image', 'pundito'); ?>" />
            </p>
        </td>
    </tr>
    <?php
}

/**
 * Atualiza a imagem quando a taxonomia é editada.
 */
function pundito_update_industry_image($term_id, $tt_id) {
    if (isset($_POST['pundito-industry-image-id']) && '' !== $_POST['pundito-industry-image-id']) {
        update_term_meta($term_id, 'pundito-industry-image-id', $_POST['pundito-industry-image-id']);
    } else {
        delete_term_meta($term_id, 'pundito-industry-image-id');
    }
}


function pundito_enqueue_media_scripts($hook) {
    if ($hook !== 'term.php' && $hook !== 'edit-tags.php') return;
    wp_enqueue_media();
    wp_enqueue_script('pundito-admin-media', plugin_dir_url(__FILE__) . '../js/admin-media.js', array('jquery'), null, true);
}
add_action('admin_enqueue_scripts', 'pundito_enqueue_media_scripts');
