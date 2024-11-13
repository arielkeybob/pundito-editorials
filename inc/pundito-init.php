<?php

// Carrega arquivos necessários para o plugin
require_once plugin_dir_path(__FILE__) . 'custom-post-types.php';
require_once plugin_dir_path(__FILE__) . 'admin-customizations.php';
require_once plugin_dir_path(__FILE__) . 'custom-taxonomies.php';
require_once plugin_dir_path(__FILE__) . 'pundito-metaboxes.php';
require_once plugin_dir_path(__FILE__) . 'pundito-template-functions.php';
require_once plugin_dir_path(__FILE__) . 'pundito-editorial-config.php';
require_once plugin_dir_path(__FILE__) . 'pundito-elementor-hooks.php';
//require_once plugin_dir_path(__FILE__) . 'custom-add-new-page.php';

// Funções de inicialização

// Enfileirar estilos administrativos
function pundito_editorials_enqueue_admin_assets() {
    wp_enqueue_style(
        'pundito-admin-styles',
        plugin_dir_url(__FILE__) . '../css/admin-styles.css'
    );
}
add_action('admin_enqueue_scripts', 'pundito_editorials_enqueue_admin_assets');

// Enfileirar scripts personalizados na tela de edição/criação do post type 'editorial'
function pundito_enqueue_custom_admin_js($hook) {
    global $post_type;
    if ( ( $hook !== 'post-new.php' && $hook !== 'post.php' ) || $post_type !== 'editorial' ) {
        return;
    }

    // Enfileirar o script personalizado
    wp_enqueue_script(
        'custom-admin-js',
        plugin_dir_url(__FILE__) . '../js/admin-js.js',
        array('jquery'),
        '1.0',
        true
    );

    // Localizar o script para incluir valores dinâmicos de PHP
    wp_localize_script('custom-admin-js', 'meusDados', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce'   => wp_create_nonce('meu_nonce_especifico')
    ));
}
add_action('admin_enqueue_scripts', 'pundito_enqueue_custom_admin_js');

// Enfileirar o intro.js e o script de configuração na tela de edição/criação do 'editorial'
function pundito_enqueue_introjs($hook) {
    global $post_type;
    if ( ( $hook !== 'post-new.php' && $hook !== 'post.php' ) || $post_type !== 'editorial' ) {
        return;
    }

    // Enfileirar o estilo do intro.js via CDN
    wp_enqueue_style(
        'introjs-css',
        'https://cdn.jsdelivr.net/npm/intro.js/minified/introjs.min.css'
    );

    // Enfileirar o script do intro.js via CDN
    wp_enqueue_script(
        'introjs-js',
        'https://cdn.jsdelivr.net/npm/intro.js/minified/intro.min.js',
        array('jquery'),
        null,
        true
    );

    // Enfileirar o script de configuração
    wp_enqueue_script(
        'introjs-setup',
        plugin_dir_url(__FILE__) . '../js/introjs-setup.js',
        array('introjs-js'),
        null,
        true
    );
}
add_action('admin_enqueue_scripts', 'pundito_enqueue_introjs');



// Modificar a URL de redirecionamento após salvar/publicar para adicionar 'introjs_continue' se necessário
function pundito_modify_redirect_location($location, $post_id) {
    if ( get_post_type($post_id) === 'editorial' && isset($_POST['introjs_continue']) && $_POST['introjs_continue'] == '1' ) {
        // Adicionar o parâmetro à URL de redirecionamento
        $location = add_query_arg('introjs_continue', '1', $location);
    }
    return $location;
}
add_filter('redirect_post_location', 'pundito_modify_redirect_location', 10, 2);

?>
