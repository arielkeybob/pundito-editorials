<?php

/**
 * Adiciona os metaboxes ao tipo de post 'editorial'
 */
function pundito_add_editorial_metaboxes() {
    add_meta_box(
        'pundito_order_select',
        __('Order Select', 'pundito'),
        'pundito_render_order_select_metabox',
        'editorial',
        'side',
        'high'
    );

    add_meta_box(
        'pundito_industries',
        __('Select Industries', 'pundito'),
        'pundito_industries_metabox_html',
        ['editorial', 'industry_post'],
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'pundito_add_editorial_metaboxes');

/**
 * Renderiza o metabox de seleção de ordem.
 */
function pundito_render_order_select_metabox($post) {
    $selected_order = get_post_meta($post->ID, '_pundito_order_select', true);
    $parent_id = wp_get_post_parent_id($post->ID);
    $used_options = pundito_get_used_order_options($parent_id, $post->ID);

    $options = ['Intro', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Bonus', 'Bonus 2'];
    echo '<label for="pundito_order_select_dropdown">' . __('Select Order:', 'pundito') . '</label>';
    echo '<select name="pundito_order_select" id="pundito_order_select_dropdown" required>'; // Adicionado atributo required
    echo '<option value="">' . __('Select an Option', 'pundito') . '</option>';

    foreach ($options as $option) {
        if (!in_array($option, $used_options)) {
            $is_selected = ($selected_order === $option) ? ' selected' : '';
            echo sprintf('<option value="%s"%s>%s</option>', esc_attr($option), $is_selected, esc_html($option));
        }
    }
    echo '</select>';
}

/**
 * Função para obter opções de ordem já usadas.
 */
function pundito_get_used_order_options($parent_id, $exclude_id) {
    $used_options = [];
    if ($parent_id) {
        $siblings = get_posts([
            'post_parent' => $parent_id,
            'post_type'   => 'editorial',
            'posts_per_page' => -1,
            'exclude'     => [$exclude_id]
        ]);

        foreach ($siblings as $sibling) {
            $used_options[] = get_post_meta($sibling->ID, '_pundito_order_select', true);
        }
    }
    return $used_options;
}

/**
 * Salvamento do metabox de seleção de ordem.
 */
function pundito_save_order_select($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $order_value = isset($_POST['pundito_order_select']) ? sanitize_text_field($_POST['pundito_order_select']) : '';
    $is_parent = (wp_get_post_parent_id($post_id) === 0);

    // Garante que 'Intro' seja definido para posts pais sem seleção de ordem
    if ($is_parent && empty($order_value)) {
        $order_value = 'Intro';
    }

    update_post_meta($post_id, '_pundito_order_select', $order_value);
    if (!empty($order_value)) {
        wp_set_post_terms($post_id, [$order_value], 'episode_order', false);
    }
}
add_action('save_post_editorial', 'pundito_save_order_select');

/**
 * Renderiza o metabox de seleção de indústrias.
 */
function pundito_industries_metabox_html($post) {
    $terms = get_terms(['taxonomy' => 'industry', 'hide_empty' => false]);
    $selected_terms = wp_get_object_terms($post->ID, 'industry', array('fields' => 'ids'));

    echo '<ul>';
    foreach ($terms as $term) {
        $is_checked = in_array($term->term_id, $selected_terms) ? ' checked' : '';
        echo '<li><label>';
        echo '<input type="checkbox" name="pundito_industries[]" value="' . esc_attr($term->term_id) . '"' . $is_checked . '> ';
        echo esc_html($term->name);
        echo '</label></li>';
    }
    echo '</ul>';
}

/**
 * Salvamento do metabox de seleção de indústrias.
 */
function pundito_save_industries($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;
    if (!isset($_POST['pundito_industries'])) return;

    $industries = array_map('intval', $_POST['pundito_industries']);
    wp_set_object_terms($post_id, $industries, 'industry', false);
}
add_action('save_post', 'pundito_save_industries');
