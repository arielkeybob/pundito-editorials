<?php
/**
 * Register custom taxonomies for Pundito Theme
 */

// Registra a taxonomia 'report'
//Acho que podemos excluir isso
/*
function pundito_register_taxonomy_report() {
    $labels = array(
        'name'              => _x('Reports', 'taxonomy general name'),
        'singular_name'     => _x('Report', 'taxonomy singular name'),
        'search_items'      => __('Search Reports'),
        'all_items'         => __('All Reports'),
        'parent_item'       => __('Parent Report'),
        'parent_item_colon' => __('Parent Report:'),
        'edit_item'         => __('Edit Report'),
        'update_item'       => __('Update Report'),
        'add_new_item'      => __('Add New Report'),
        'new_item_name'     => __('New Report Name'),
        'menu_name'         => __('Reports'),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_in_menu'      => true,
        'show_in_nav_menus' => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'report', 'with_front' => true),
        'show_in_rest'      => true,
    );

    register_taxonomy('report', array('editorial'), $args);
}
*/

// Registra a taxonomia 'episode_order'
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
        'hierarchical'      => false,  // Não hierárquica
        'labels'            => $labels,
        'show_ui'           => false,
        'show_in_menu'      => true,
        'show_in_nav_menus' => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'episode_order', 'with_front' => true),
        'show_in_rest'      => true,
    );

    register_taxonomy('episode_order', array('editorial'), $args);
}

// Registra a taxonomia 'child_of'
function pundito_register_taxonomy_child_of() {
    $labels = array(
        'name'              => _x('Children of', 'taxonomy general name'),
        'singular_name'     => _x('Child of', 'taxonomy singular name'),
        'search_items'      => __('Search Children of'),
        'all_items'         => __('All Children of'),
        'edit_item'         => __('Edit Child of'),
        'update_item'       => __('Update Child of'),
        'add_new_item'      => __('Add New Child of'),
        'new_item_name'     => __('New Child of Name'),
        'menu_name'         => __('Children of'),
    );

    $args = array(
        'hierarchical'      => false,  // Não hierárquica
        'labels'            => $labels,
        'show_ui'           => false,  // Não mostra na UI
        'show_in_menu'      => false,
        'show_in_nav_menus' => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'child_of', 'with_front' => true),
        'show_in_rest'      => true,
    );

    register_taxonomy('child_of', array('editorial'), $args);
}

//add_action('init', 'pundito_register_taxonomy_report');
add_action('init', 'pundito_register_taxonomy_episode_order');
add_action('init', 'pundito_register_taxonomy_child_of');




function pundito_register_industry_taxonomy() {
    $labels = array(
        'name'                       => _x('Industries', 'taxonomy general name', 'text_domain'),
        'singular_name'              => _x('Industry', 'taxonomy singular name', 'text_domain'),
        'search_items'               => __('Search Industries', 'text_domain'),
        'popular_items'              => __('Popular Industries', 'text_domain'),
        'all_items'                  => __('All Industries', 'text_domain'),
        'parent_item'                => null,  // null para taxonomias não hierárquicas
        'parent_item_colon'          => null,  // null para taxonomias não hierárquicas
        'edit_item'                  => __('Edit Industry', 'text_domain'),
        'update_item'                => __('Update Industry', 'text_domain'),
        'add_new_item'               => __('Add New Industry', 'text_domain'),
        'new_item_name'              => __('New Industry Name', 'text_domain'),
        'separate_items_with_commas' => __('Separate industries with commas', 'text_domain'),
        'add_or_remove_items'        => __('Add or remove industries', 'text_domain'),
        'choose_from_most_used'      => __('Choose from the most used industries', 'text_domain'),
        'not_found'                  => __('No industries found.', 'text_domain'),
        'menu_name'                  => __('Industries', 'text_domain'),
    );

    $args = array(
        'hierarchical'          => false,
        'labels'                => $labels,
        'show_ui'               => true,
        'show_in_nav_menus'     => true,
        'show_admin_column'     => true,
        'update_count_callback' => '_update_post_term_count',
        'query_var'             => true,
        'rewrite'               => array('slug' => 'industry'),
        'show_in_rest'          => true,  // Habilita suporte ao Gutenberg
    );

    register_taxonomy('industry', ['editorial', 'industry_post'], $args);
}

add_action('init', 'pundito_register_industry_taxonomy');
