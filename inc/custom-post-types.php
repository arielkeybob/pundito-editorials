<?php
/**
 * Register custom post types for Pundito Theme
 */

// Registra o post type 'Library'
function pundito_custom_post_type() {
    $args = array(
        'public' => true,
        'label' => 'Library',
        'supports' => array('title', 'editor', 'thumbnail'),
        'has_archive' => true,
        'rewrite' => array('slug' => 'library'),
    );
    register_post_type('dlm_download', $args);
}
add_action('init', 'pundito_custom_post_type');



// Registra o post type 'Editorial'

function pundito_register_editorial_post_type() {
    $labels = array(
        'name'               => __('Editorials'),
        'singular_name'      => __('Editorial'),
        'menu_name'          => __('Editorials'),
        'name_admin_bar'     => __('Editorial'),
        'add_new'            => __('Add New'),
        'add_new_item'       => __('Add New Editorial'),
        'new_item'           => __('New Editorial'),
        'edit_item'          => __('Edit Editorial'),
        'view_item'          => __('View Editorial'),
        'all_items'          => __('All Editorials'),
        'search_items'       => __('Search Editorials'),
        'not_found'          => __('No editorials found.'),
        'not_found_in_trash' => __('No editorials found in Trash.'),
        'parent_item_colon'  => __('Parent Editorial:'),
        'featured_image'     => __('Featured Image'),
        'set_featured_image' => __('Set featured image'),
        'remove_featured_image' => __('Remove featured image'),
        'use_featured_image' => __('Use as featured image'),
        'archives'           => __('Editorial Archives'),
        'attributes'         => __('Editorial Attributes'),
        'insert_into_item'   => __('Insert into editorial'),
        'uploaded_to_this_item' => __('Uploaded to this editorial'),
        'filter_items_list'  => __('Filter editorials list'),
        'items_list_navigation' => __('Editorials list navigation'),
        'items_list'         => __('Editorials list'),
    );

    $args = array(
        'labels'             => $labels,
        'description'        => '',  // Adicione aqui, se desejar.
        'public'             => true,
        'publicly_queryable'  => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'show_in_nav_menus'  => true,
        'delete_with_user'   => false,
        'query_var'          => true,
        'exclude_from_search' => false,
        'rewrite'            => array('slug' => 'editorial', 'with_front' => true),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => true, // Isso habilita a hierarquia
        'menu_position'      => null,
        'menu_icon'          => 'dashicons-table-col-after',
        'supports'           => array('title', 'thumbnail', 'page-attributes'), // Adicione 'page-attributes' aqui
        'show_in_rest'       => true,
        'taxonomies'         => array('child_of')
    );

    register_post_type('editorial', $args);
}
add_action('init', 'pundito_register_editorial_post_type');
