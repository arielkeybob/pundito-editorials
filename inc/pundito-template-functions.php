<?php

// Carrega template customizado para o post type 'editorial'
function pundito_load_custom_editorial_template() {
    global $pagenow;
    if ($pagenow !== 'edit.php') return;

    $screen = get_current_screen();
    if (isset($screen->post_type) && $screen->post_type === 'editorial') {
        $template_path = plugin_dir_path(__FILE__) . '../templates/';
        $template_path .= isset($_GET['view_children_of']) ? 'custom-editorial-chapters.php' : 'custom-editorial-list.php';

        if (file_exists($template_path)) {
            include $template_path;
            exit;  // Interrompe o carregamento da lista padrão do WP
        }
    }
}
add_action('load-edit.php', 'pundito_load_custom_editorial_template');
