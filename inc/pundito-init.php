<?php

// Carrega arquivos necessários para o plugin
require_once plugin_dir_path(__FILE__) . 'custom-post-types.php';
require_once plugin_dir_path(__FILE__) . 'admin-customizations.php';
require_once plugin_dir_path(__FILE__) . 'custom-taxonomies.php';
require_once plugin_dir_path(__FILE__) . 'pundito-metaboxes.php';
require_once plugin_dir_path(__FILE__) . 'pundito-template-functions.php';
require_once plugin_dir_path(__FILE__) . 'pundito-editorial-config.php';
require_once plugin_dir_path(__FILE__) . 'pundito-elementor-hooks.php';


// Funções de inicialização
function pundito_editorials_enqueue_admin_assets() {
    wp_enqueue_style('pundito-admin-styles', plugin_dir_url(__FILE__) . '../css/admin-styles.css');
    wp_enqueue_script('pundito-admin-js', plugin_dir_url(__FILE__) . '../js/admin-js.js', ['jquery'], '1.0', true);
}
add_action('admin_enqueue_scripts', 'pundito_editorials_enqueue_admin_assets');
