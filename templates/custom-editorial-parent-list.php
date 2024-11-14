<?php
require_once(ABSPATH . 'wp-admin/admin-header.php');

$post_type = 'editorial';
$paged = isset($_GET['paged']) ? max(1, (int) $_GET['paged']) : 1;
$posts_per_group = 3;
$selected_year = isset($_GET['year']) ? (int) $_GET['year'] : null;

// Obtém os anos disponíveis para o filtro
$years_args = [
    'post_type'      => $post_type,
    'post_status'    => ['publish', 'draft'],
    'posts_per_page' => -1,
    'post_parent'    => 0,
    'fields'         => 'ids',
    'orderby'        => 'date',
    'order'          => 'DESC',
    'date_query'     => $selected_year ? [['year' => $selected_year]] : [],
];

$years_query = new WP_Query($years_args);
$years = [];

if ($years_query->have_posts()) {
    while ($years_query->have_posts()) {
        $years_query->the_post();
        $year = get_the_date('Y');
        $years[$year] = $year;
    }
}
wp_reset_postdata();

// Consulta os posts principais, aplicando o filtro de ano, se houver
$args = [
    'post_type'      => $post_type,
    'post_status'    => ['publish', 'draft'],
    'posts_per_page' => -1,
    'post_parent'    => 0,
    'orderby'        => 'date',
    'order'          => 'DESC',
    'date_query'     => $selected_year ? [['year' => $selected_year]] : [],
];

$parent_posts = new WP_Query($args);
$groups = [];

if ($parent_posts->have_posts()) {
    while ($parent_posts->have_posts()) {
        $parent_posts->the_post();
        $month_year = get_the_date('F Y');
        $groups[$month_year][] = [
            'id'            => get_the_ID(),
            'class'         => 'editorial-parent',
            'view_children' => true,
        ];
    }
}

// Paginação dos grupos de meses
$total_groups = count($groups);
$pages = ceil($total_groups / $posts_per_group);
$current_page = max(1, min($paged, $pages));
$current_groups = array_slice($groups, ($current_page - 1) * $posts_per_group, $posts_per_group);

?>
<div class="wrap">
    <h1><?php _e('Editorials', 'text_domain'); ?></h1>
    <a href="<?php echo admin_url('post-new.php?post_type=editorial'); ?>" class="page-title-action"><?php _e('New Week', 'text_domain'); ?></a> <!-- Botão Adicionar Nova Semana -->

    <form method="get" action="" style="display: inline-block; margin-left: 20px;">
        <select name="year" onchange="this.form.submit()">
            <option value=""><?php _e('Select Year', 'text_domain'); ?></option>
            <?php foreach ($years as $year): ?>
                <option value="<?php echo esc_attr($year); ?>" <?php selected($selected_year, $year); ?>>
                    <?php echo esc_html($year); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <input type="hidden" name="post_type" value="<?php echo esc_attr($post_type); ?>" />
    </form>

    <div class="editorial-list-container">
        <?php foreach ($current_groups as $month_year => $posts): ?>
            <div class="monthly-group">
                <h2><?php echo esc_html($month_year); ?></h2>
                <?php foreach ($posts as $post_data): ?>
                    <?php include 'editorial-item-card.php'; ?>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <?php
    // Paginação
    echo paginate_links([
        'base'      => add_query_arg('paged', '%#%'),
        'format'    => '',
        'current'   => $current_page,
        'total'     => $pages,
        'prev_text' => __('&laquo; Prev', 'text_domain'),
        'next_text' => __('Next &raquo;', 'text_domain'),
    ]);
    ?>
</div>

<?php
wp_reset_postdata();
require_once(ABSPATH . 'wp-admin/admin-footer.php');
?>
