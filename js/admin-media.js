jQuery(document).ready(function($) {
    var frame;

    // Função para atualizar a visibilidade do botão remover
    function updateRemoveButtonVisibility() {
        if ($('#pundito-industry-image-id').val()) {
            $('#pundito-industry-remove-image').show();
        } else {
            $('#pundito-industry-remove-image').hide();
        }
    }

    $('#pundito-industry-upload-image').on('click', function(e) {
        e.preventDefault();

        // Se o frame já existir, reabri-lo
        if (frame) {
            frame.open();
            return;
        }

        // Criar um novo frame de mídia
        frame = wp.media({
            title: 'Select or Upload Media',
            button: {
                text: 'Use this media'
            },
            multiple: false
        });

        // Quando uma imagem for selecionada, atualizar o input e a visualização
        frame.on('select', function() {
            var attachment = frame.state().get('selection').first().toJSON();
            $('#pundito-industry-image-id').val(attachment.id);
            $('#pundito-industry-image-wrapper').html('<img src="' + attachment.url + '" width="150" height="auto">');
            updateRemoveButtonVisibility();
        });

        // Abrir o frame
        frame.open();
    });

    // Remover a imagem
    $('#pundito-industry-remove-image').on('click', function(e) {
        e.preventDefault();
        $('#pundito-industry-image-id').val('');
        $('#pundito-industry-image-wrapper').html('');
        updateRemoveButtonVisibility();
    });

    // Atualizar a visibilidade do botão remover ao carregar a página
    updateRemoveButtonVisibility();
});
