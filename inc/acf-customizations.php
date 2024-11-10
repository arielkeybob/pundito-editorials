<?php
function pundito_add_custom_metabox() {
    add_meta_box(
        'pundito_order_select',
        __('Order Select', 'text_domain'),
        'pundito_order_select_metabox_html',
        'editorial',
        'side',
        'high'  // Altera a prioridade para 'high'
    );
}
add_action('add_meta_boxes', 'pundito_add_custom_metabox');

function pundito_order_select_metabox_html($post) {
    $selected = get_post_meta($post->ID, '_pundito_order_select', true);
    $parent_id = wp_get_post_parent_id($post->ID);
    $used_options = [];

    // Buscar todos os irmãos deste post para obter as opções já utilizadas
    if ($parent_id) {
        $sibling_args = array(
            'post_parent' => $parent_id,
            'post_type' => 'editorial',
            'posts_per_page' => -1,
            'exclude' => array($post->ID)  // Exclui o post atual da busca
        );
        $siblings = get_posts($sibling_args);
        foreach ($siblings as $sibling) {
            $order = get_post_meta($sibling->ID, '_pundito_order_select', true);
            if ($order) {
                $used_options[] = $order;
            }
        }
    }

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
        if (!in_array($option, $used_options)) {
            $is_selected = ($selected == $option) ? ' selected' : '';
            echo sprintf('<option value="%s"%s>%s</option>', esc_attr($option), $is_selected, esc_html($option));
        }
    }

    echo '</select>';
}

function pundito_save_order_select($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $is_parent = (wp_get_post_parent_id($post_id) == 0);
    $order_value = isset($_POST['pundito_order_select']) ? sanitize_text_field($_POST['pundito_order_select']) : '';

    // Para posts pai, força o valor 'Intro' caso nenhum valor seja submetido
    if ($is_parent && empty($order_value)) {
        $order_value = 'Intro';
    }

    // Atualiza o meta valor selecionado
    update_post_meta($post_id, '_pundito_order_select', $order_value);

    // Associa ou atualiza o termo da taxonomia ao post
    if (!empty($order_value)) {
        wp_set_post_terms($post_id, [$order_value], 'episode_order', false);
    }
}
add_action('save_post_editorial', 'pundito_save_order_select');
