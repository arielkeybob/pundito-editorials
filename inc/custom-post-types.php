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




// Registra o post type 'Industry Posts'
function pundito_register_industry_posts_type() {
    $labels = array(
        'name'                  => _x('Industry Posts', 'Post type general name', 'text_domain'),
        'singular_name'         => _x('Industry Post', 'Post type singular name', 'text_domain'),
        'menu_name'             => _x('Industry Posts', 'Admin Menu text', 'text_domain'),
        'name_admin_bar'        => _x('Industry Post', 'Add New on Toolbar', 'text_domain'),
        'add_new'               => __('Add New', 'text_domain'),
        'add_new_item'          => __('Add New Industry Post', 'text_domain'),
        'new_item'              => __('New Industry Post', 'text_domain'),
        'edit_item'             => __('Edit Industry Post', 'text_domain'),
        'view_item'             => __('View Industry Post', 'text_domain'),
        'all_items'             => __('All Industry Posts', 'text_domain'),
        'search_items'          => __('Search Industry Posts', 'text_domain'),
        'parent_item_colon'     => __('Parent Industry Post:', 'text_domain'),
        'not_found'             => __('No industry posts found.', 'text_domain'),
        'not_found_in_trash'    => __('No industry posts found in Trash.', 'text_domain'),
        'featured_image'        => _x('Industry Post Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'text_domain'),
        'set_featured_image'    => _x('Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'text_domain'),
        'remove_featured_image' => _x('Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'text_domain'),
        'use_featured_image'    => _x('Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'text_domain'),
        'archives'              => _x('Industry Post archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'text_domain'),
        'insert_into_item'      => _x('Insert into industry post', 'Overrides the “Insert into post”/“Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'text_domain'),
        'uploaded_to_this_item' => _x('Uploaded to this industry post', 'Overrides the “Uploaded to this post”/“Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'text_domain'),
        'filter_items_list'     => _x('Filter industry posts list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/“Filter pages list”. Added in 4.4', 'text_domain'),
        'items_list_navigation' => _x('Industry posts list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/“Pages list navigation”. Added in 4.4', 'text_domain'),
        'items_list'            => _x('Industry posts list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/“Pages list”. Added in 4.4', 'text_domain'),
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
        'menu_position'      => null,
        'menu_icon'          => 'dashicons-businessman',
        'supports'           => array('title', 'thumbnail', 'page-attributes'),
        'show_in_rest'       => true,
    );

    register_post_type('industry_post', $args);
}
add_action('init', 'pundito_register_industry_posts_type');
