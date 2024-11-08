<?php
require_once(ABSPATH . 'wp-admin/admin-header.php');

$post_type = 'editorial';
$paged = isset($_GET['paged']) ? (int) $_GET['paged'] : 1;
$posts_per_group = 3;  // Quantidade de grupos por página
$selected_year = isset($_GET['year']) ? (int) $_GET['year'] : null;

// Consulta para buscar todos os anos com posts parents
$years_args = [
    'post_type'      => $post_type,
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'post_parent'    => 0,
    'fields'         => 'ids',  // Buscar apenas os IDs para otimizar
    'orderby'        => 'date',
    'order'          => 'DESC'
];

$years_query = new WP_Query($years_args);
$years = [];

if ($years_query->have_posts()) {
    while ($years_query->have_posts()) {
        $years_query->the_post();
        $years[get_the_date('Y')] = get_the_date('Y');
    }
}
wp_reset_postdata(); // Limpar após a consulta dos anos

// Consulta dos posts com filtro de ano aplicado, se houver
$args = [
    'post_type'      => $post_type,
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'post_parent'    => 0,
    'orderby'        => 'date',
    'order'          => 'DESC'
];

if ($selected_year) {
    $args['date_query'] = [
        ['year' => $selected_year]
    ];
}

$parent_posts = new WP_Query($args);
$groups = [];

if ($parent_posts->have_posts()) {
    while ($parent_posts->have_posts()) {
        $parent_posts->the_post();
        $month_year = get_the_date('F Y');
        $groups[$month_year][] = [
            'id'    => get_the_ID(),
            'class' => 'editorial-parent',
            'view_children' => true,
        ];
    }
}

// Determinar a paginação
$total_groups = count($groups);
$pages = ceil($total_groups / $posts_per_group);
$current_page = max(1, min($paged, $pages));

// Obter os grupos para a página atual
$current_groups = array_slice($groups, ($current_page - 1) * $posts_per_group, $posts_per_group);

// Formulário de filtro
echo '<form method="get" action="">';
echo '<select name="year" onchange="this.form.submit()">';
echo '<option value="">' . __('Select Year', 'text_domain') . '</option>';
foreach ($years as $year) {
    echo '<option value="' . esc_attr($year) . '"' . selected($selected_year, $year, false) . '>' . esc_html($year) . '</option>';
}
echo '</select>';
echo '<input type="hidden" name="post_type" value="' . esc_attr($post_type) . '"/>'; // Mantém o tipo de post na query
echo '</form>';

?>

<div class="wrap">
    <h1><?php _e('Editorials', 'text_domain'); ?></h1>
    <div class="editorial-list-container">
        <?php foreach ($current_groups as $month_year => $posts): ?>
            <div class="monthly-group">
                <h2><?php echo $month_year; ?></h2>
                <?php foreach ($posts as $post_data): ?>
                    <?php
                    // Incluir o template parcial
                    include('editorial-item.php');
                    ?>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>
    <?php echo paginate_links(array(
        'base'      => add_query_arg('paged', '%#%'),
        'format'    => '',
        'current'   => $current_page,
        'total'     => $pages,
        'prev_text' => __('&laquo; Prev', 'text_domain'),
        'next_text' => __('Next &raquo;', 'text_domain'),
    )); ?>
</div>
<?php
wp_reset_postdata();
require_once(ABSPATH . 'wp-admin/admin-footer.php');
?>
