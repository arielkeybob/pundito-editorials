<?php
/**
 * Register custom taxonomies for Pundito Theme
 */

/**
 * Registra a taxonomia 'episode_order' para o tipo de post 'editorial'.
 */
function pundito_register_taxonomy_episode_order() {
    $labels = array(
        'name'              => _x('Episode Orders', 'taxonomy general name'),
        'singular_name'     => _x('Episode Order', 'taxonomy singular name'),
        'search_items'      => __('Search Episode Orders'),
        'all_items'         => __('All Episode Orders'),
        'edit_item'         => __('Edit Episode Order'),
        'update_item'       => __('Update Episode Order'),
        'add_new_item'      => __('Add New Episode Order'),
        'new_item_name'     => __('New Episode Order Name'),
        'menu_name'         => __('Episode Orders'),
    );

    $args = array(
        'hierarchical'      => false,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_in_menu'      => true,
        'show_in_nav_menus' => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'episode_order', 'with_front' => true),
        'show_in_rest'      => true,
    );

    register_taxonomy('episode_order', array('editorial'), $args);
}


/**
 * Registra a taxonomia 'industry' para os tipos de post 'editorial' e 'industry_post'.
 */
function pundito_register_industry_taxonomy() {
    $labels = array(
        'name'                       => _x('Industries', 'taxonomy general name', 'pundito'),
        'singular_name'              => _x('Industry', 'taxonomy singular name', 'pundito'),
        'search_items'               => __('Search Industries', 'pundito'),
        'popular_items'              => __('Popular Industries', 'pundito'),
        'all_items'                  => __('All Industries', 'pundito'),
        'edit_item'                  => __('Edit Industry', 'pundito'),
        'update_item'                => __('Update Industry', 'pundito'),
        'add_new_item'               => __('Add New Industry', 'pundito'),
        'new_item_name'              => __('New Industry Name', 'pundito'),
        'menu_name'                  => __('Industries', 'pundito'),
    );

    $args = array(
        'hierarchical'          => false,
        'labels'                => $labels,
        'show_ui'               => false,
        'show_in_nav_menus'     => true,
        'show_admin_column'     => true,
        'query_var'             => true,
        'rewrite'               => array('slug' => 'industry'),
        'show_in_rest'          => true,
    );

    register_taxonomy('industry', ['editorial', 'industry_post'], $args);
}

// Inicializa todas as taxonomias
function pundito_register_all_taxonomies() {
    pundito_register_taxonomy_episode_order();
    pundito_register_industry_taxonomy();
}
add_action('init', 'pundito_register_all_taxonomies');
