<?php

// Customização do Elementor para carregar posts filhos na visualização do post pai
function pundito_elementor_filter_children_query($query) {
    if (is_singular('editorial')) {
        $post_id = get_the_ID();
        $query->set('post_parent', $post_id);
        $query->set('post_type', 'editorial');
    }
}
add_action('elementor/query/filhos_do_post_pai', 'pundito_elementor_filter_children_query');


function pundito_elementor_filter_sibling_query($query) {
    if ( $query->get( 'pundito_sibling_query_modified' ) ) {
        return;
    }
    $query->set( 'pundito_sibling_query_modified', true );

    global $post;

    if ( isset( $post ) && $post instanceof WP_Post && $post->post_type === 'editorial' ) {
        $current_post_id = $post->ID;
        error_log('ID do post atual: ' . $current_post_id);

        $parent_id = wp_get_post_parent_id( $current_post_id );
        error_log('ID do post pai: ' . ( $parent_id ? $parent_id : 'Nenhum' ));

        remove_action( 'elementor/query/irmaos_do_post_atual', 'pundito_elementor_filter_sibling_query' );

        if ( $parent_id ) {
            $siblings = get_posts( array(
                'post_type'        => 'editorial',
                'post_parent'      => $parent_id,
                'posts_per_page'   => -1,
                'fields'           => 'ids',
                'suppress_filters' => true,
            ) );
        } else {
            $siblings = get_posts( array(
                'post_type'        => 'editorial',
                'post_parent'      => 0,
                'posts_per_page'   => -1,
                'fields'           => 'ids',
                'suppress_filters' => true,
            ) );
        }

        add_action( 'elementor/query/irmaos_do_post_atual', 'pundito_elementor_filter_sibling_query' );

        if ( ! is_array( $siblings ) ) {
            $siblings = array();
        }

        if ( ! in_array( $current_post_id, $siblings ) ) {
            $siblings[] = $current_post_id;
        }

        $post_ids = array_unique( $siblings );

        if ( is_array( $post_ids ) ) {
            error_log('Posts encontrados: ' . implode(', ', $post_ids));
        } else {
            error_log('Nenhum post encontrado.');
        }

        $query->set( 'post_type', 'editorial' );
        $query->set( 'post__in', $post_ids );
        $query->set( 'orderby', 'menu_order' );
        $query->set( 'order', 'ASC' );
        $query->set( 'posts_per_page', -1 );
    } else {
        error_log('Não foi possível obter o ID do post atual ou o post não é do tipo "editorial".');
        return;
    }
}
add_action( 'elementor/query/irmaos_do_post_atual', 'pundito_elementor_filter_sibling_query' );
