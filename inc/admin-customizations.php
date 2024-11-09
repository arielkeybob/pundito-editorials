<?php
/**
 * Admin customizations for Pundito Theme
 */

// Esquema de cores personalizado no admin
function pundito_admin_color_scheme() {
    $theme_dir = get_stylesheet_directory_uri();
    wp_admin_css_color('pundito', __('Pundito'),
        $theme_dir . '/tddacademy.css',
        array('#031799', '#f2fcff', '#f59e0b', '#1b38f4')
    );
}
add_action('admin_init', 'pundito_admin_color_scheme');

// Força o esquema de cores personalizado para todos os usuários
function pundito_force_admin_color_scheme($color_scheme) {
    return 'pundito';
}
add_filter('get_user_option_admin_color', 'pundito_force_admin_color_scheme');

// Custom CSS para ocultar elementos no tipo de post 'editorial'
function pundito_custom_css_for_editorial() {
    if (get_post_type() == 'editorial' && wp_get_post_parent_id(get_the_ID()) == 0) {
        echo '<style type="text/css">.wpra-reactions-wrap.wpra-plugin-container.wpra-rendered { display: none; }</style>';
    }
}
add_action('wp_head', 'pundito_custom_css_for_editorial');

// Carrega scripts de administração personalizados





function enqueue_custom_admin_script() {
    wp_enqueue_script('my-custom-admin-script', plugin_dir_url(__DIR__) . 'js/admin-js.js', array('jquery'), '1.0', true);
}
add_action('admin_enqueue_scripts', 'enqueue_custom_admin_script');

// Filtra os argumentos do dropdown de páginas pai para limitar aos posts de nível superior
function filter_parent_dropdown_pages_args($dropdown_args, $post) {
    if (isset($dropdown_args['post_type']) && $dropdown_args['post_type'] === 'editorial') {
        $dropdown_args['post_parent'] = 0;
        $dropdown_args['depth'] = 1; // Limita a busca para apenas o primeiro nível de posts
    }
    return $dropdown_args;
}
add_filter('page_attributes_dropdown_pages_args', 'filter_parent_dropdown_pages_args', 10, 2);
add_filter('quick_edit_dropdown_pages_args', 'filter_parent_dropdown_pages_args', 10, 2);




// Adiciona e configura os metaboxes para selecionar indústrias
function pundito_add_industry_metabox() {
    add_meta_box(
        'pundito_industries',
        __('Select Industries', 'text_domain'),
        'pundito_industries_metabox_html',
        ['editorial', 'industry_post'], // Adiciona aos post types necessários
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'pundito_add_industry_metabox');

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

function pundito_save_industries($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;
    if (!isset($_POST['pundito_industries'])) return;

    // Limpa e garante que estamos trabalhando com IDs de inteiros
    $industries = array_map('intval', $_POST['pundito_industries']); // Convertendo os IDs de string para integer
    wp_set_object_terms($post_id, $industries, 'industry', false); // false para não anexar, mas substituir os termos existentes
}
add_action('save_post', 'pundito_save_industries');
