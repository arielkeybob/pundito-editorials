<?php

// Carrega arquivos necessários para o plugin
require_once plugin_dir_path(__FILE__) . 'custom-post-types.php';
require_once plugin_dir_path(__FILE__) . 'admin-customizations.php';
require_once plugin_dir_path(__FILE__) . 'custom-taxonomies.php';
require_once plugin_dir_path(__FILE__) . 'pundito-metaboxes.php';
require_once plugin_dir_path(__FILE__) . 'pundito-template-functions.php';
require_once plugin_dir_path(__FILE__) . 'pundito-editorial-config.php';
require_once plugin_dir_path(__FILE__) . 'pundito-elementor-hooks.php';
require_once plugin_dir_path(__FILE__) . 'post-handlers.php';

// Enfileirar estilos administrativos
function pundito_editorials_enqueue_admin_assets() {
    wp_enqueue_style(
        'pundito-admin-styles',
        plugin_dir_url(__FILE__) . '../css/admin-styles.css'
    );
}
add_action('admin_enqueue_scripts', 'pundito_editorials_enqueue_admin_assets');

function pundito_enqueue_custom_admin_js($hook) {
    global $post;
    if (( $hook !== 'post-new.php' && $hook !== 'post.php' ) || get_post_type($post) !== 'editorial' ) {
        return;
    }

    wp_enqueue_script(
        'custom-admin-js',
        plugin_dir_url(__FILE__) . '../js/admin-js.js',
        array('jquery'),
        '1.0',
        true
    );

    $is_child = $post && $post->post_parent ? 'yes' : 'no';
    wp_localize_script('custom-admin-js', 'PostData', array(
        'isChild' => $is_child
    ));
}
add_action('admin_enqueue_scripts', 'pundito_enqueue_custom_admin_js');


// Enfileirar o intro.js e o script de configuração na tela de edição/criação do 'editorial'
function pundito_enqueue_introjs($hook) {
    global $post_type;
    if ( ( $hook !== 'post-new.php' && $hook !== 'post.php' ) || $post_type !== 'editorial' ) {
        return;
    }

    wp_enqueue_style('introjs-css', 'https://cdn.jsdelivr.net/npm/intro.js/minified/introjs.min.css');
    wp_enqueue_script('introjs-js', 'https://cdn.jsdelivr.net/npm/intro.js/minified/intro.min.js', array('jquery'), null, true);
    wp_enqueue_script('introjs-setup', plugin_dir_url(__FILE__) . '../js/introjs-setup.js', array('introjs-js'), null, true);
}
add_action('admin_enqueue_scripts', 'pundito_enqueue_introjs');

// Modify the redirect URL after saving/publishing to include 'introjs_continue' if necessary
function pundito_modify_redirect_location($location, $post_id) {
    if (get_post_type($post_id) === 'editorial' && isset($_POST['introjs_continue']) && $_POST['introjs_continue'] == '1') {
        // Add the parameter to the redirect URL
        $location = add_query_arg('introjs_continue', '1', $location);
    }
    return $location;
}
add_filter('redirect_post_location', 'pundito_modify_redirect_location', 10, 2);


function add_editorial_body_class($classes) {
    global $post;
    if ($post->post_type === 'editorial') {
        if (wp_get_post_parent_id($post->ID) > 0) {
            if (!is_array($classes)) {
                $classes = explode(' ', $classes);
            }
            $classes[] = 'editorial-child';
        } else {
            if (!is_array($classes)) {
                $classes = explode(' ', $classes);
            }
            $classes[] = 'editorial-parent';
        }
    }
    return $classes;
}
add_filter('admin_body_class', 'add_editorial_body_class');
