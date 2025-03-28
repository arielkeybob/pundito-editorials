<?php

require_once(ABSPATH . 'wp-admin/admin-header.php');

$post_type = 'editorial';
$paged = isset($_GET['paged']) ? max(1, (int) $_GET['paged']) : 1;
$selected_year = isset($_GET['year']) ? (int) $_GET['year'] : null;
$selected_month = isset($_GET['month']) ? (int) $_GET['month'] : null;
$posts_per_group = 3;

// Obtém os anos disponíveis para o filtro
$years_args = [
    'post_type'      => $post_type,
    'post_status'    => ['publish', 'draft'],
    'posts_per_page' => -1,
    'post_parent'    => 0,
    'fields'         => 'ids',
    'orderby'        => 'date',
    'order'          => 'DESC'
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

$months = [];
if ($selected_year) {
    // Query para pegar meses após selecionar um ano
    $months_query = new WP_Query([
        'post_type'      => $post_type,
        'post_status'    => ['publish', 'draft'],
        'posts_per_page' => -1,
        'post_parent'    => 0,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'date_query'     => [['year' => $selected_year]],
        'fields' => 'ids'
    ]);

    if ($months_query->have_posts()) {
        while ($months_query->have_posts()) {
            $months_query->the_post();
            $month = get_the_date('n');
            $months[$month] = get_the_date('F');
        }
        $months = array_unique($months);
    }
    wp_reset_postdata();
}

$args = [
    'post_type'      => $post_type,
    'post_status'    => ['publish', 'draft'],
    'posts_per_page' => -1,
    'post_parent'    => 0,
    'orderby'        => 'date',
    'order'          => 'DESC',
    'date_query'     => [['year' => $selected_year, 'month' => $selected_month]]
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

$total_groups = count($groups);
$pages = ceil($total_groups / $posts_per_group);
$current_page = max(1, min($paged, $pages));
$current_groups = array_slice($groups, ($current_page - 1) * $posts_per_group, $posts_per_group);
?>
<div class="wrap">
    <h1><?php _e('Editorials', 'text_domain'); ?></h1>
    <a href="<?php echo admin_url('post-new.php?post_type=editorial'); ?>" class="page-title-action"><?php _e('New Week', 'text_domain'); ?></a>
    <a href="<?php echo admin_url('edit.php?post_type=editorial&post_status=trash'); ?>" class="page-title-action"><span class="dashicons dashicons-trash"></span></a>
    <form method="get" action="" class="admin-editorial-filters year-month-filter">
        <select name="year" onchange="this.form.submit()" class="year-select">
            <option value=""><?php _e('Select Year', 'text_domain'); ?></option>
            <?php foreach ($years as $year): ?>
                <option value="<?php echo esc_attr($year); ?>" <?php selected($selected_year, $year); ?>>
                    <?php echo esc_html($year); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <?php if (!empty($months)): ?>
            <select name="month" onchange="this.form.submit()" class="month-select">
                <option value=""><?php _e('Select Month', 'text_domain'); ?></option>
                <?php foreach ($months as $monthNum => $monthName): ?>
                    <option value="<?php echo esc_attr($monthNum); ?>" <?php selected($selected_month, $monthNum); ?>>
                        <?php echo esc_html($monthName); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        <?php endif; ?>
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
