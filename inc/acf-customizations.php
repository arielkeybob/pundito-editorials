<?php
function pundito_add_custom_metabox() {
    add_meta_box(
        'pundito_order_select',
        __('Order Select', 'text_domain'),
        'pundito_order_select_metabox_html',
        'editorial',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'pundito_add_custom_metabox');

function pundito_order_select_metabox_html($post) {
    $selected = get_post_meta($post->ID, '_pundito_order_select', true);

    echo '<label for="pundito_order_select_dropdown">' . __('Select Order:', 'text_domain') . '</label>';
    echo '<select name="pundito_order_select" id="pundito_order_select_dropdown" class="postbox">';
    echo '<option value="">' . __('Select an Option', 'text_domain') . '</option>';

    $options = [
        'Intro',
        'Monday',
        'Tuesday',
        'Wednesday',
        'Thursday',
        'Friday',
        'Bonus',
        'Bonus 2',
    ];

    foreach ($options as $option) {
        $is_selected = ($selected == $option) ? ' selected' : '';
        echo sprintf('<option value="%s"%s>%s</option>', esc_attr($option), $is_selected, esc_html($option));
    }

    echo '</select>';
}

function pundito_save_order_select($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;
    if (!isset($_POST['pundito_order_select']) || empty($_POST['pundito_order_select'])) return;

    $order_value = sanitize_text_field($_POST['pundito_order_select']);
    update_post_meta($post_id, '_pundito_order_select', $order_value);

    // Primeiro tenta encontrar o termo existente pela sua nome
    $term = get_term_by('name', $order_value, 'episode_order');

    // Se não existe, cria um novo
    if (!$term) {
        $term = wp_insert_term($order_value, 'episode_order');
        if (is_wp_error($term)) {
            error_log('Error creating term: ' . $term->get_error_message());
            return;
        }
    }

    // Associa o termo ao post
    wp_set_post_terms($post_id, array($term instanceof WP_Term ? $term->term_id : $term['term_id']), 'episode_order', false);
}
add_action('save_post_editorial', 'pundito_save_order_select');
