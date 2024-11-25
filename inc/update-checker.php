<?php
add_action('admin_init', 'tdd_editorials_check_for_update');

function tdd_editorials_check_for_update() {
    $current_version = '1.3.4'; // Substitua pela versão atual do plugin
    $username = 'arielkeybob'; // Seu nome de usuário no GitHub
    $repository = 'pundito-editorials'; // Nome do repositório
    $token = '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'; // Substitua pelo seu token pessoal
    $url = "https://api.github.com/repos/$username/$repository/releases/latest";

    $args = [
        'headers' => [
            'Authorization' => "token $token",
            'User-Agent'    => 'WordPress Plugin Updates'
        ]
    ];

    $response = wp_remote_get($url, $args);

    if (is_wp_error($response)) {
        return; // Erro na requisição
    }

    $data = json_decode(wp_remote_retrieve_body($response), true);

    if (!isset($data['tag_name']) || !isset($data['zipball_url'])) {
        return; // Dados incompletos
    }

    $latest_version = $data['tag_name'];
    $download_link = $data['zipball_url'];

    // Verifica se há uma nova versão
    if (version_compare($current_version, $latest_version, '<')) {
        add_action('admin_notices', function() use ($latest_version, $download_link) {
            echo '<div class="notice notice-info is-dismissible">';
            echo '<p>';
            echo 'Uma nova versão (' . esc_html($latest_version) . ') está disponível para o plugin TDD Editorials. ';
            echo '<a href="' . esc_url($download_link) . '" target="_blank">Baixe aqui</a>';
            echo '</p>';
            echo '</div>';
        });

        // Baixa e atualiza automaticamente (opcional)
        tdd_editorials_update_plugin($download_link, $token);
    }
}

function tdd_editorials_update_plugin($download_link, $token) {
    $args = [
        'headers' => [
            'Authorization' => "token $token",
            'User-Agent'    => 'WordPress Plugin Updates'
        ]
    ];

    $tmpFile = download_url($download_link, $args);

    if (is_wp_error($tmpFile)) {
        @unlink($tmpFile);
        return false;
    }

    // Descompactar e substituir os arquivos do plugin
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/plugin.php';

    WP_Filesystem();

    $pluginFolder = WP_PLUGIN_DIR . '/tdd-editorials';
    $unzip_result = unzip_file($tmpFile, $pluginFolder);
    @unlink($tmpFile);

    if (is_wp_error($unzip_result)) {
        return false;
    }

    // Reativar o plugin
    deactivate_plugins('tdd-editorials/tdd-editorials.php');
    activate_plugins('tdd-editorials/tdd-editorials.php');

    return true;
}
