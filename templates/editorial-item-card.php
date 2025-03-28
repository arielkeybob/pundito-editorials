<?php
if (!isset($post_data) || !is_array($post_data)) {
    return; // Garante que $post_data é válido antes de prosseguir
}

// Obtém a URL da imagem definida pelo plugin Featured Image with URL
$featured_image_url = get_post_meta($post_data['id'], 'fifu_image_url', true);

// Obtém a imagem destacada padrão do WordPress (versão FULL para manter a animação de GIFs)
$thumbnail_url = get_the_post_thumbnail_url($post_data['id'], 'full'); 

// Verifica se a imagem destacada do WordPress é um GIF
$is_gif = (!empty($thumbnail_url) && pathinfo($thumbnail_url, PATHINFO_EXTENSION) === 'gif');

// Define a imagem a ser usada, priorizando a ordem correta
if (!empty($featured_image_url)) {
    $image_url = esc_url($featured_image_url); // Usa a URL do plugin se disponível
    $is_gif = (pathinfo($image_url, PATHINFO_EXTENSION) === 'gif'); // Verifica se a URL externa é um GIF
} elseif (!empty($thumbnail_url)) {
    $image_url = esc_url($thumbnail_url); // Usa a imagem destacada se não houver URL externa
} else {
    $image_url = plugin_dir_url(__DIR__) . 'img/placeholder.png'; // Usa o placeholder se nenhuma estiver definida
}

$view_children_url = add_query_arg(['view_children_of' => $post_data['id']], admin_url('edit.php?post_type=editorial'));
$post_status = get_post_status($post_data['id']);
$status_class = $post_status === 'publish' ? 'status-success' : 'status-draft';
$status_text = $post_status === 'publish' ? __('Published', 'text_domain') : __('Draft', 'text_domain');
?>

<div class="editorial-item <?php echo esc_attr($post_data['class']); ?>" 
     <?php if (!$is_gif) : ?>style="background-image: url('<?php echo esc_url($image_url); ?>');"<?php endif; ?> 
     <?php if (!$post_data['view_children']) : ?>onclick="window.location='<?php echo esc_url(get_edit_post_link($post_data['id'])); ?>';"<?php endif; ?>>

    <?php if ($is_gif) : ?>
        <img src="<?php echo esc_url($image_url); ?>" alt="<?php esc_attr_e('Editorial Image', 'text_domain'); ?>" class="editorial-gif">
    <?php endif; ?>

    <?php if ($post_data['view_children']) : ?>
        <a href="<?php echo esc_url($view_children_url); ?>" class="full-card-link" title="<?php esc_attr_e('See Chapters', 'text_domain'); ?>"></a>
        <div class="icon-overlay"><span class="dashicons dashicons-portfolio"></span></div>
    <?php else : ?>
        <div class="icon-overlay"><span class="dashicons dashicons-media-document"></span></div>
    <?php endif; ?>

    <div class="editorial-content">
        <div class="editorial-title">
            <a href="<?php echo esc_url(get_edit_post_link($post_data['id'])); ?>"><?php echo esc_html(get_the_title($post_data['id'])); ?></a>
        </div>
        <div class="editorial-order-select">
            <?php
            $terms = wp_get_post_terms($post_data['id'], 'episode_order');
            if (!is_wp_error($terms) && !empty($terms)) {
                echo esc_html($terms[0]->name);
            }
            ?>
        </div>
        <div class="editorial-status">
            <span class="<?php echo esc_attr($status_class); ?>"></span>
            <span style="font-size: 12px;"><?php echo esc_html($status_text); ?></span>
        </div>
        <div class="editorial-actions">
            <a href="<?php echo esc_url(get_edit_post_link($post_data['id'])); ?>"><?php _e('Edit', 'text_domain'); ?></a> |
            <a id="delete-editorial-btn" href="<?php echo esc_url(get_delete_post_link($post_data['id'])); ?>" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this?', 'text_domain'); ?>')"><?php _e('Delete', 'text_domain'); ?></a>
            <?php if ($post_data['view_children']) : ?>
                | <a href="<?php echo esc_url(admin_url('post-new.php?post_type=editorial&post_parent=' . $post_data['id'])); ?>"><?php _e('Add Chapter', 'text_domain'); ?></a>
                | <a href="<?php echo esc_url($view_children_url); ?>"><?php _e('See Chapters', 'text_domain'); ?></a>
            <?php endif; ?>
        </div>
        <div class="editorial-date">
            <?php echo esc_html(get_the_date('', $post_data['id'])); ?>
        </div>
    </div>
</div>

<style>
.editorial-item {
    position: relative;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    border-radius: 8px;
    overflow: hidden;
    width: 100%;
    height: 200px; /* Garante altura uniforme dos cards */
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Para imagens carregadas como GIF */
.editorial-item img.editorial-gif {
    width: 100%;
    height: 100%;
    object-fit: cover; /* Faz o GIF ocupar o mesmo espaço do background */
    position: absolute;
    top: 0;
    left: 0;
    border-radius: 8px;
    z-index: -1; /* Mantém o GIF atrás do conteúdo */
}

/* Ajuste para garantir legibilidade do conteúdo sobre a imagem */
.editorial-content {
    position: relative;
    z-index: 1;
    background: rgba(0, 0, 0, 0.5); /* Pequeno overlay para melhorar contraste */
    padding: 10px;
    border-radius: 8px;
}

/* Ícone de status */
.editorial-status span {
    display: inline-block;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    margin-right: 5px;
}

/* Cores do status */
.status-success {
    background-color: #28a745;
}
.status-draft {
    background-color: #dc3545;
}
</style>
