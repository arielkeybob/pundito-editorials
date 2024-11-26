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


function pundito_elementor_filter_sibling_or_children_query($query) {
    // Usa uma variável estática para evitar recursão
    static $running = false;
    if ( $running ) {
        return;
    }
    $running = true;

    // Verifica se já modificamos esta query para evitar recursão
    if ( $query->get( 'pundito_sibling_or_children_query_modified' ) ) {
        $running = false;
        return;
    }
    $query->set( 'pundito_sibling_or_children_query_modified', true );

    global $post;

    if ( isset( $post ) && $post instanceof WP_Post && $post->post_type === 'editorial' ) {
        $current_post_id = $post->ID;
        // error_log('ID do post atual: ' . $current_post_id);

        $parent_id = wp_get_post_parent_id( $current_post_id );
        // error_log('ID do post pai: ' . ( $parent_id ? $parent_id : 'Nenhum' ));

        if ( $parent_id ) {
            // O post tem um pai
            // error_log('Exibindo post pai, irmãos e o post atual.');

            // Remove filtros para evitar loops
            $args = array(
                'post_type'        => 'editorial',
                'posts_per_page'   => -1,
                'fields'           => 'ids',
                'orderby'          => 'menu_order',
                'order'            => 'ASC',
                'suppress_filters' => true,
            );

            // Obter o post pai
            $posts = array( $parent_id );

            // Obter os irmãos (incluindo o post atual)
            $siblings = get_posts( array_merge( $args, array(
                'post_parent' => $parent_id,
            ) ) );

            // Mescla o post pai com os irmãos
            $posts = array_merge( $posts, $siblings );
        } else {
            // O post não tem pai, exibir ele mesmo e seus filhos
            // error_log('Exibindo o post atual e seus filhos.');

            // Começa com o post atual
            $posts = array( $current_post_id );

            // Obter os filhos do post atual
            $children = get_posts( array(
                'post_type'        => 'editorial',
                'post_parent'      => $current_post_id,
                'posts_per_page'   => -1,
                'fields'           => 'ids',
                'orderby'          => 'menu_order',
                'order'            => 'ASC',
                'suppress_filters' => true,
            ) );

            // Mescla o post atual com seus filhos
            $posts = array_merge( $posts, $children );
        }

        // Garante que $posts seja sempre um array
        if ( ! is_array( $posts ) ) {
            $posts = array();
        }

        // Remove duplicatas
        $post_ids = array_unique( $posts );

        // Log dos posts encontrados
        if ( is_array( $post_ids ) ) {
            // error_log('Posts encontrados: ' . implode(', ', $post_ids));
        } else {
            // error_log('Nenhum post encontrado.');
        }

        // Configura a query do Elementor
        $query->set( 'post_type', 'editorial' );
        $query->set( 'post__in', $post_ids );
        $query->set( 'orderby', 'post__in' ); // Mantém a ordem dos IDs
        $query->set( 'posts_per_page', -1 );
    } else {
        // error_log('Não foi possível obter o ID do post atual ou o post não é do tipo "editorial".');
        $running = false;
        return;
    }

    $running = false;
}
add_action( 'elementor/query/irmaos_ou_filhos_do_post_atual', 'pundito_elementor_filter_sibling_or_children_query', 10 );

