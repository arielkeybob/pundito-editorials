<?php
if (isset($_GET['view_children_of']) && !empty($_GET['view_children_of'])) {
    $parent_id = intval($_GET['view_children_of']);
    require_once(ABSPATH . 'wp-admin/admin-header.php');
    $args = [
        'post_type' => 'editorial',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'post_parent' => $parent_id,
    ];
    $child_posts = new WP_Query($args);
    ?>
    
    <div class="wrap">
        <h1><?php _e('Chapters for ' . get_the_title($parent_id), 'text_domain'); ?></h1>
        <a href="<?php echo admin_url('edit.php?post_type=editorial'); ?>">&laquo; Back to Editorials</a>
        <div class="editorial-chapters-list-container">
            <?php
            if ($child_posts->have_posts()) {
                while ($child_posts->have_posts()) {
                    $child_posts->the_post();
                    $post_data = [
                        'id' => get_the_ID(),
                        'class' => 'editorial-child',
                        'view_children' => false,
                    ];
                    include('editorial-item.php');  // Inclui o template parcial
                }
            } else {
                echo '<div class="editorial-item"><p>' . __('No chapters found.', 'text_domain') . '</p></div>';
            }
            wp_reset_postdata();
            ?>
        </div>
    </div>
    <?php
    require_once(ABSPATH . 'wp-admin/admin-footer.php');
}
?>
