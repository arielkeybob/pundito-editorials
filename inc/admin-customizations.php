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
        echo '
        <style type="text/css">
            .wpra-reactions-wrap.wpra-plugin-container.wpra-rendered {
                display: none;
            }
        </style>';
    }
}
add_action('wp_head', 'pundito_custom_css_for_editorial');


function enqueue_custom_admin_script() {
    wp_enqueue_script('my-custom-admin-script', plugin_dir_url(__DIR__) . 'js/admin-js.js', array('jquery'), '1.0', true);
}
add_action('admin_enqueue_scripts', 'enqueue_custom_admin_script');


function filter_parent_dropdown_pages_args($dropdown_args, $post) {
    if (isset($dropdown_args['post_type']) && $dropdown_args['post_type'] === 'editorial') {
        $dropdown_args['post_parent'] = 0;
        $dropdown_args['depth'] = 1; // Limita a busca para apenas o primeiro nível de posts
    }
    return $dropdown_args;
}
add_filter('page_attributes_dropdown_pages_args', 'filter_parent_dropdown_pages_args', 10, 2);
add_filter('quick_edit_dropdown_pages_args', 'filter_parent_dropdown_pages_args', 10, 2);




function set_editorial_menu_order( $post_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        error_log('Skipping autosave for ' . $post_id);
        return;
    }

    // Verifica se é um post parent e se o valor esperado está presente
    if ( 'editorial' !== get_post_type( $post_id ) || !isset( $_POST['pundito_order_select'] ) ) {
        error_log('Not the right post type or pundito_order_select not set for post ' . $post_id);
        return;
    }

    // Evitar recursão ao salvar o post
    static $updating_post = false;
    if ( $updating_post ) {
        error_log('Preventing recursion on post ' . $post_id);
        return;
    }

    $updating_post = true;

    // Mapeamento dos valores do dropdown para o menu_order
    $order_map = array(
        'Intro'     => 0,
        'Monday'    => 1,
        'Tuesday'   => 2,
        'Wednesday' => 3,
        'Thursday'  => 4,
        'Friday'    => 5,
        'Bonus'     => 6,
        'Bonus 2'   => 7
    );

    $selected_order = $_POST['pundito_order_select'];

    if ( array_key_exists( $selected_order, $order_map ) ) {
        // Atualiza o menu_order com o valor mapeado
        wp_update_post( array(
            'ID'         => $post_id,
            'menu_order' => $order_map[$selected_order]
        ));
        error_log('Updated post ' . $post_id . ' to menu_order ' . $order_map[$selected_order]);
    } else {
        error_log('Selected order ' . $selected_order . ' is not a valid key for post ' . $post_id);
    }

    $updating_post = false;
}
add_action( 'save_post', 'set_editorial_menu_order' );
