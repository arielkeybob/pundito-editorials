<?php
if (!isset($post_data) || !is_array($post_data)) {
    return; // Garante que $post_data é válido antes de prosseguir
}

// Obtém a URL da imagem de destaque ou define o placeholder se não houver uma
$thumbnail_url = get_the_post_thumbnail_url($post_data['id'], 'medium');
if (!$thumbnail_url) {
    $thumbnail_url = plugin_dir_url(__DIR__) . 'img/placeholder.png';
}

// URL para ver capítulos, usado apenas para posts pais
$view_children_url = add_query_arg(['view_children_of' => $post_data['id']], admin_url('edit.php?post_type=editorial'));

?>
<div class="editorial-item <?php echo esc_attr($post_data['class']); ?>" 
     style="background-image: url('<?php echo esc_url($thumbnail_url); ?>');" 
     <?php if (!$post_data['view_children']) : ?>onclick="window.location='<?php echo esc_url(get_edit_post_link($post_data['id'])); ?>';"<?php endif; ?>>

    <!-- Link para ver capítulos, apenas para posts pais -->
    <?php if ($post_data['view_children']) : ?>
        <a href="<?php echo esc_url($view_children_url); ?>" class="full-card-link" title="<?php esc_attr_e('See Chapters', 'text_domain'); ?>"></a>
        <div class="icon-overlay"><span class="dashicons dashicons-portfolio"></span></div> <!-- Ícone para posts pais -->
    <?php else : ?>
        <div class="icon-overlay"><span class="dashicons dashicons-media-document"></span></div> <!-- Ícone para posts filhos -->
    <?php endif; ?>

    <div class="editorial-content">
        <div class="editorial-title">
            <a href="<?php echo esc_url(get_edit_post_link($post_data['id'])); ?>"><?php echo esc_html(get_the_title($post_data['id'])); ?></a>
        </div>
        <div class="editorial-actions">
            <a href="<?php echo esc_url(get_edit_post_link($post_data['id'])); ?>"><?php _e('Edit', 'text_domain'); ?></a> |
            <a id="delete-editorial-btn" href="<?php echo esc_url(get_delete_post_link($post_data['id'])); ?>" 
               onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this?', 'text_domain'); ?>')"><?php _e('Delete', 'text_domain'); ?></a>
            <?php if ($post_data['view_children']) : ?>
                | <a href="<?php echo esc_url(admin_url('post-new.php?post_type=editorial&post_parent=' . $post_data['id'])); ?>"><?php _e('Add Chapter', 'text_domain'); ?></a>
                | <a href="<?php echo esc_url($view_children_url); ?>"><?php _e('See Chapters', 'text_domain'); ?></a>
            <?php endif; ?>
        </div>

        <?php
        // Exibe o termo da taxonomia 'episode_order' para posts filhos
        if (!$post_data['view_children']) {
            $terms = wp_get_post_terms($post_data['id'], 'episode_order');
            if (!is_wp_error($terms) && !empty($terms)) {
                echo '<div class="editorial-order-select">' . esc_html($terms[0]->name) . '</div>';
            }
        }
        ?>

        <div class="editorial-date">
            <?php echo esc_html(get_the_date('', $post_data['id'])); ?>
        </div>
    </div>
</div>