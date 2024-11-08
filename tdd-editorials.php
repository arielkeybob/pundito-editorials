<?php
/**
 * Plugin Name: TDD Editorials
 * Plugin URI: https://thedigitaldance.com
 * Description: Plugin personalizado para a funcionalidade editorial.
 * Version: 1.3.4
 * Author: The Digital Dance & Ariel Souza
 * Author URI: https://arielsouza.com.br
 * License: GPL2
 */

if (!defined('ABSPATH')) {
    die('We\'re sorry, but you can not directly access this file.');
}

// Requer os arquivos necessários
require_once 'inc/custom-post-types.php';
require_once 'inc/admin-customizations.php';
require_once 'inc/custom-taxonomies.php';
require_once 'inc/acf-customizations.php';

/**
 * Funções relacionadas ao post type 'editorial'
 */

// Carregar template customizado para o post type 'editorial'
function load_custom_editorial_list_template() {
    global $pagenow;

    // Adiciona log para verificar se a função está sendo chamada
    error_log('Função load_custom_editorial_list_template chamada.');

    // Verifica se estamos na página de edição do tipo de post 'editorial'
    if ($pagenow === 'edit.php') {
        $screen = get_current_screen();
        if (isset($screen->post_type)) {
            error_log('Post type detectado: ' . $screen->post_type);
        } else {
            error_log('Nenhum post type detectado.');
        }

        if ($screen->post_type === 'editorial') {
            // Determina qual template carregar com base na presença do parâmetro 'view_children_of'
            if (isset($_GET['view_children_of']) && !empty($_GET['view_children_of'])) {
                $template_path = plugin_dir_path(__FILE__) . 'templates/custom-editorial-chapters.php';
                error_log('Verificando a existência do template de capítulos: ' . $template_path);
            } else {
                $template_path = plugin_dir_path(__FILE__) . 'templates/custom-editorial-list.php';
                error_log('Verificando a existência do template de listagem: ' . $template_path);
            }

            if (file_exists($template_path)) {
                error_log('Template encontrado. Carregando o template.');
                include $template_path;
                exit;  // Interrompe o carregamento da lista padrão do WP
            } else {
                error_log('Template não encontrado: ' . $template_path);
            }
        } else {
            error_log('Post type não é editorial.');
        }
    } else {
        error_log('$pagenow não é edit.php.');
    }
}
add_action('load-edit.php', 'load_custom_editorial_list_template');

function my_admin_enqueue_scripts() {
    wp_enqueue_style('my-custom-admin-styles', plugin_dir_url(__FILE__) . 'css/admin-styles.css');
}
add_action('admin_enqueue_scripts', 'my_admin_enqueue_scripts');




// Configurar o post filho da editorial ao criar um novo post a partir da ação "Add Chapter"
function configure_child_post_editorial($data, $postarr) {
    if ($data['post_type'] === 'editorial' && isset($_GET['post_parent'])) {
        $data['post_parent'] = intval($_GET['post_parent']);
    }
    return $data;
}
add_filter('wp_insert_post_data', 'configure_child_post_editorial', 10, 2);



