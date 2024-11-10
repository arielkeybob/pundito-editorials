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
