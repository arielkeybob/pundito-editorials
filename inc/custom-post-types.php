<?php
/**
 * Register custom post types for Pundito Theme
 */

/**
 * Registra o post type 'Library'
 */
function pundito_register_library_post_type() {
    $args = array(
        'public' => true,
        'label' => 'Library',
        'supports' => array('title', 'editor', 'thumbnail'),
        'has_archive' => true,
        'rewrite' => array('slug' => 'library'),
    );
    register_post_type('dlm_download', $args); // Mudando o identificador para 'library'
}

/**
 * Registra o post type 'Editorial'
 */
function pundito_register_editorial_post_type() {
    $labels = array(
        'name'               => __('Editorials'),
        'singular_name'      => __('Editorial'),
        'menu_name'          => __('Editorials'),
        'add_new'            => __('Add New'),
        'add_new_item'       => __('Add New Editorial'),
        'edit_item'          => __('Edit Editorial'),
        'view_item'          => __('View Editorial'),
        'all_items'          => __('All Editorials'),
        'search_items'       => __('Search Editorials'),
        'not_found'          => __('No editorials found.'),
        'not_found_in_trash' => __('No editorials found in Trash.'),
        'featured_image'     => __('Featured Image'),
        'set_featured_image' => __('Set featured image'),
        'archives'           => __('Editorial Archives'),
        'items_list'         => __('Editorials list'),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'delete_with_user'   => false,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'editorial', 'with_front' => true),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => true,
        'menu_icon'          => 'dashicons-table-col-after',
        'supports'           => array('title', 'thumbnail', 'page-attributes'),
        'show_in_rest'       => true,
        'taxonomies'         => array('child_of'),
    );

    register_post_type('editorial', $args);
}

/**
 * Registra o post type 'Industry Posts'
 */
function pundito_register_industry_posts_type() {
    $labels = array(
        'name'                  => _x('Industry Posts', 'Post type general name', 'pundito'),
        'singular_name'         => _x('Industry Post', 'Post type singular name', 'pundito'),
        'menu_name'             => _x('Industry Posts', 'Admin Menu text', 'pundito'),
        'add_new'               => __('Add New', 'pundito'),
        'add_new_item'          => __('Add New Industry Post', 'pundito'),
        'edit_item'             => __('Edit Industry Post', 'pundito'),
        'view_item'             => __('View Industry Post', 'pundito'),
        'all_items'             => __('All Industry Posts', 'pundito'),
        'search_items'          => __('Search Industry Posts', 'pundito'),
        'not_found'             => __('No industry posts found.', 'pundito'),
        'featured_image'        => _x('Industry Post Cover Image', 'Overrides the “Featured Image” phrase', 'pundito'),
        'set_featured_image'    => _x('Set cover image', 'Overrides the “Set featured image” phrase', 'pundito'),
        'use_featured_image'    => _x('Use as cover image', 'Overrides the “Use as featured image” phrase', 'pundito'),
        'archives'              => _x('Industry Post archives', 'post type archive label', 'pundito'),
        'items_list'            => _x('Industry posts list', 'Screen reader text', 'pundito'),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'industry-post'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_icon'          => 'dashicons-businessman',
        'supports'           => array('title', 'thumbnail', 'page-attributes'),
        'show_in_rest'       => true,
    );

    register_post_type('industry_post', $args);
}

/**
 * Inicializa todos os tipos de post
 */
function pundito_register_all_post_types() {
    pundito_register_library_post_type();
    pundito_register_editorial_post_type();
    pundito_register_industry_posts_type();
}
add_action('init', 'pundito_register_all_post_types');
