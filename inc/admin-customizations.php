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
        $dropdown_args['depth'] = 1;
    }
    return $dropdown_args;
}
add_filter('page_attributes_dropdown_pages_args', 'filter_parent_dropdown_pages_args', 10, 2);
add_filter('quick_edit_dropdown_pages_args', 'filter_parent_dropdown_pages_args', 10, 2);
