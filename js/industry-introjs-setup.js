document.addEventListener('DOMContentLoaded', function() {
    // Determine pagenow based on URL
    var pagenow = '';
    var url = window.location.href;

    if (url.indexOf('post-new.php') !== -1) {
        pagenow = 'post-new';
    } else if (url.indexOf('post.php') !== -1) {
        pagenow = 'post';
    }

    // Function to start the intro tour for the industry_post type
    function startIntroTour() {
        var intro = introJs();
        intro.setOptions({
            steps: [
                {
                    element: 'input#title',
                    intro: 'Enter the title for this industry post here.'
                },
                {
                    element: '#pundito_industries',
                    intro: 'Determine if the post will also appear in a specific "Industry".'
                },
                {
                    element: '#postimagediv',
                    intro: 'Set the featured image for this post here.'
                },
                {
                    element: '#minor-publishing',
                    intro: 'Save as draft before editing with Elementor.'
                }
            ],
            showButtons: true,
            exitOnEsc: true,
            exitOnOverlayClick: true
        });
        intro.start();
    }

    // Check if we're on the creation screen
    if (pagenow === 'post-new') {
        startIntroTour();
    }

    // Function to handle post-publication tutorial
    if (pagenow === 'post') {
        var postType = document.querySelector('input[name="post_type"]').value;
        if (postType === 'industry_post') {
            introJs().setOptions({
                steps: [
                    {
                        element: '#elementor-switch-mode',
                        intro: 'Now you can edit with Elementor.'
                    }
                ],
                showButtons: true,
                exitOnEsc: true,
                exitOnOverlayClick: true
            }).start();
        }
    }
});




document.addEventListener('DOMContentLoaded', function() {
    // Seleciona o link pelo ID específico
    var linkToSetThumbnail = document.querySelector('#set-post-thumbnail');

    if (linkToSetThumbnail) {
        linkToSetThumbnail.addEventListener('click', function(event) {
            console.log('Link para definir a imagem de destaque clicado.');

            // Aguarda 1 segundo após o clique
            setTimeout(function() {
                var modalElement = document.getElementById('__wp-uploader-id-2');
                if (modalElement) {
                    modalElement.classList.add('introjs-showElement');
                    console.log('Classe introjs-showElement adicionada à div __wp-uploader-id-2.');
                } else {
                    console.log('Div com ID __wp-uploader-id-2 não encontrada.');
                }
            }, 1000);

            // Comente a linha abaixo se desejar permitir que o thickbox abra normalmente
            // event.preventDefault();
        });
    } else {
        console.log('Link para definir a imagem de destaque não encontrado.');
    }
});