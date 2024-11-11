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
function pundito_editorials_enqueue_admin_assets() {
    wp_enqueue_style('pundito-admin-styles', plugin_dir_url(__FILE__) . '../css/admin-styles.css');
    //wp_enqueue_script('pundito-admin-js', plugin_dir_url(__FILE__) . '../js/admin-js.js', ['jquery'], '1.0', true);
}
add_action('admin_enqueue_scripts', 'pundito_editorials_enqueue_admin_assets');

function load_custom_admin_js() {
    $screen = get_current_screen(); // Obtenha informações sobre a tela atual
    
    // Verifica se estamos na página de edição do post type 'editorial'
    if ( $screen->id === 'editorial' ) { 
        wp_enqueue_script('custom-admin-js', plugin_dir_url(__DIR__) . 'js/admin-js.js', array('jquery'), '1.0', true);
        
        // Localize o script para incluir valores dinâmicos de PHP
        wp_localize_script('custom-admin-js', 'meusDados', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('meu_nonce_especifico') // Cria um nonce para segurança
        ));
    }
}

add_action('admin_enqueue_scripts', 'load_custom_admin_js');


/*add_action('admin_init', 'redirect_custom_editorial_creation');


function redirect_custom_editorial_creation() {
    global $pagenow;
    // Verifica se está na página de adição de novo 'editorial'
    if ($pagenow == 'post-new.php' && isset($_GET['post_type']) && $_GET['post_type'] == 'editorial') {
        wp_redirect(admin_url('admin.php?page=custom_editorial_creation'));
        exit;
    }
}
*/



function pundito_enqueue_introjs($hook) {
    global $post_type;
    if ($hook !== 'post-new.php' || $post_type !== 'editorial') {
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

    // Enfileirar o seu script personalizado
    wp_enqueue_script(
        'introjs-setup',
        plugin_dir_url(__FILE__) . '../js/introjs-setup.js',
        array('introjs-js'),
        null,
        true
    );
}
add_action('admin_enqueue_scripts', 'pundito_enqueue_introjs');
