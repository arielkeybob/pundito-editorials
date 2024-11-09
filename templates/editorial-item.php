<?php
// Assume que a variável $post_data é um array contendo os dados necessários para o post.
$thumbnail_url = get_the_post_thumbnail_url($post_data['id'], 'medium'); // Usando 'medium' que é padrão do WordPress
if (!$thumbnail_url) { // Se não houver uma imagem de destaque, use a imagem de placeholder
    $thumbnail_url = plugin_dir_url(__DIR__) . 'img/placeholder.png'; // Caminho para o placeholder na pasta do plugin
}
error_log("Post ID: " . $post_data['id'] . " - Thumbnail URL: " . $thumbnail_url); // Log da URL da imagem de destaque

?>
<div class="editorial-item <?php echo $post_data['class']; ?>" style="background-image: url('<?php echo esc_url($thumbnail_url); ?>');" 
    <?php if (!$post_data['view_children']) : ?>onclick="window.location='<?php echo get_edit_post_link($post_data['id']); ?>';" style="cursor: pointer;"<?php endif; ?>>
    <!-- Overlay Link for See Chapters, se for um post pai -->
    <?php if ($post_data['view_children']) : ?>
        <a href="<?php echo add_query_arg(['view_children_of' => $post_data['id']], admin_url('edit.php?post_type=' . $post_type)); ?>" class="full-card-link" title="<?php _e('See Chapters', 'text_domain'); ?>"></a>
        <div class="icon-overlay"><span class="dashicons dashicons-portfolio"></span></div> <!-- Ícone para posts pais -->
    <?php else : ?>
        <div class="icon-overlay"><span class="dashicons dashicons-media-document"></span></div> <!-- Ícone para posts filhos -->
    <?php endif; ?>

    <div class="editorial-content">
        <div class="editorial-title">
            <a href="<?php echo get_edit_post_link($post_data['id']); ?>"><?php echo get_the_title($post_data['id']); ?></a>
        </div>
        <div class="editorial-actions">
            <a href="<?php echo get_edit_post_link($post_data['id']); ?>"><?php _e('Edit', 'text_domain'); ?></a> |
            <a id="delete-editorial-btn" href="<?php echo get_delete_post_link($post_data['id']); ?>" onclick="return confirm('<?php _e('Are you sure you want to delete this?', 'text_domain'); ?>')"><?php _e('Delete', 'text_domain'); ?></a>
            <?php if ($post_data['view_children']) : ?>
                | <a href="<?php echo admin_url('post-new.php?post_type=editorial&post_parent=' . $post_data['id']); ?>"><?php _e('Add Chapter', 'text_domain'); ?></a>
                | <a href="<?php echo add_query_arg(['view_children_of' => $post_data['id']], admin_url('edit.php?post_type=' . $post_type)); ?>"><?php _e('See Chapters', 'text_domain'); ?></a>
            <?php endif; ?>
        </div>
        <?php
        // Mostrar o termo da taxonomia 'episode_order' apenas para posts filhos
        if (!$post_data['view_children']) {
            $terms = wp_get_post_terms($post_data['id'], 'episode_order');
            if (!is_wp_error($terms) && !empty($terms)) {
                echo '<div class="editorial-order-select">' . esc_html($terms[0]->name) . '</div>';
            }
        }
        ?>
        <div class="editorial-date">
            <?php echo get_the_date('', $post_data['id']); ?>
        </div>
    </div>
</div>
