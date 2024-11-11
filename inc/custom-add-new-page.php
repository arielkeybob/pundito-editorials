<?php
add_action('admin_menu', 'register_custom_editorial_page');

function register_custom_editorial_page() {
    add_menu_page(
        'Create Editorial', 
        'Custom Editorial', 
        'manage_options', 
        'custom_editorial_creation', 
        'custom_editorial_creation_callback', 
        'dashicons-welcome-write-blog'
    );
}

function custom_editorial_creation_callback() {
    // Aqui você pode incluir o HTML do formulário ou incluir outro arquivo PHP com a UI
    include(plugin_dir_path(__FILE__) . 'custom-editorial-form.php');
}
