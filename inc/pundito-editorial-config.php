<?php

// Configura o post filho da editorial ao criar um novo post com a ação "Add Chapter"
function pundito_configure_child_post_editorial($data, $postarr) {
    if ($data['post_type'] === 'editorial' && isset($_GET['post_parent'])) {
        $data['post_parent'] = intval($_GET['post_parent']);
    }
    return $data;
}
add_filter('wp_insert_post_data', 'pundito_configure_child_post_editorial', 10, 2);
