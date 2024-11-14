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




/**
 * Customiza a interface administrativa para o tipo de post 'editorial'.
 */
function pundito_modify_editorial_post_screen() {
    global $post, $pagenow;

    if ($pagenow == 'post.php' && isset($_GET['post'])) {
        $post_id = $_GET['post'];
        $post = get_post($post_id);

        if ($post->post_type == 'editorial') {
            $parent_id = wp_get_post_parent_id($post_id);

            if ($parent_id != 0) {  // Confere se o post é filho
                add_action('admin_head', function() use ($parent_id, $post_id) {
                    echo "<script type='text/javascript'>
                        document.addEventListener('DOMContentLoaded', function() {
                            var addButton = document.querySelector('.page-title-action');
                            if (addButton) {
                                addButton.remove();
                            }

                            var pageTitle = document.querySelector('.wp-heading-inline');
                            if (pageTitle) {
                                var addChapterButton = document.createElement('a');
                                addChapterButton.href = '" . admin_url("post-new.php?post_type=editorial&post_parent={$parent_id}") . "';
                                addChapterButton.className = 'page-title-action';
                                addChapterButton.textContent = 'Add Chapter';
                                pageTitle.parentNode.insertBefore(addChapterButton, pageTitle.nextSibling);
                            }

                            var backButton = document.createElement('a');
                            backButton.href = '" . admin_url("edit.php?post_type=editorial&view_children_of={$parent_id}") . "';
                            backButton.className = 'page-title-action';
                            backButton.textContent = 'Back to Chapters';
                            pageTitle.parentNode.insertBefore(backButton, pageTitle.nextSibling);
                        });
                    </script>";
                });
            }
        }
    }
}
add_action('load-post.php', 'pundito_modify_editorial_post_screen');



function pundito_remove_add_new_sub_menu() {
    remove_submenu_page('edit.php?post_type=editorial', 'post-new.php?post_type=editorial');
}
add_action('admin_menu', 'pundito_remove_add_new_sub_menu');
