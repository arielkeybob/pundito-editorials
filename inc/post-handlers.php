<?php

// Move os posts filhos para a lixeira quando o post pai é lixeirado.
add_action('wp_trash_post', 'pundito_trash_children');

function pundito_trash_children($post_id) {
    $post = get_post($post_id);

    if ('editorial' === $post->post_type) {
        $children = get_posts(array(
            'post_type'   => 'editorial',
            'post_parent' => $post_id,
            'numberposts' => -1,
            'post_status' => 'any'
        ));

        if (!empty($children)) {
            foreach ($children as $child) {
                wp_trash_post($child->ID);
            }
        }
    }
}

// Exclui permanentemente os posts filhos quando o post pai é excluído permanentemente.
add_action('before_delete_post', 'delete_post_children');

function delete_post_children($post_id) {
    $post = get_post($post_id);

    if ('editorial' === $post->post_type) {
        $children = get_posts(array(
            'post_type'   => 'editorial',
            'post_parent' => $post_id,
            'numberposts' => -1,
            'post_status' => 'any'
        ));

        if (!empty($children)) {
            foreach ($children as $child) {
                wp_delete_post($child->ID, true);
            }
        }
    }
}
