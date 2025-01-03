<?php
if (isset($_GET['view_children_of']) && !empty($_GET['view_children_of'])) {
    $parent_id = intval($_GET['view_children_of']);
    
    // Verifica se o ID é válido e corresponde a um post do tipo 'editorial'
    if (get_post_type($parent_id) !== 'editorial') {
        wp_die(__('Invalid editorial post ID.', 'text_domain'));
    }

    require_once(ABSPATH . 'wp-admin/admin-header.php');

    // Query para os posts filhos do editorial, ordenados por 'menu_order' ascendente
    $args = [
        'post_type'      => 'editorial',
        'post_status'    => ['publish', 'draft'],
        'posts_per_page' => -1,
        'post_parent'    => $parent_id,
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
    ];
    $child_posts = new WP_Query($args);
    ?>
    
    <div class="wrap">
        <h1><?php echo sprintf(__('Chapters for %s', 'text_domain'), get_the_title($parent_id)); ?></h1>
        <a href="<?php echo esc_url(admin_url('edit.php?post_type=editorial')); ?>" class="page-title-action">&laquo; <?php _e('Back to Weeks', 'text_domain'); ?></a>
        <a href="<?php echo esc_url(admin_url('post-new.php?post_type=editorial&post_parent=' . $parent_id)); ?>" class="page-title-action"><?php _e('Add Chapter to this Week', 'text_domain'); ?></a>
        
        <div class="editorial-chapters-list-container">
            <?php
            if ($child_posts->have_posts()) {
                while ($child_posts->have_posts()) {
                    $child_posts->the_post();

                    // Dados do post filho para serem usados no template parcial
                    $post_data = [
                        'id'            => get_the_ID(),
                        'class'         => 'editorial-child',
                        'view_children' => false,
                    ];

                    // Inclui o template parcial para renderizar cada item
                    include('editorial-item-card.php');
                }
            } else {
                echo '<div class="notice notice-warning"><p>' . __('No chapters found.', 'text_domain') . '</p></div>';
            }
            wp_reset_postdata();
            ?>
        </div>
    </div>
    <?php
    require_once(ABSPATH . 'wp-admin/admin-footer.php');
}
?>
