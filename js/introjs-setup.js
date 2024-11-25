document.addEventListener('DOMContentLoaded', function() {
    var postData = PostData || { isChild: 'no' };  // Assume 'no' if PostData is undefined
    var isChild = postData.isChild === 'yes';

    // Determine pagenow based on URL
    var pagenow = '';
    var url = window.location.href;

    if (url.indexOf('post-new.php') !== -1) {
        pagenow = 'post-new';
    } else if (url.indexOf('post.php') !== -1) {
        pagenow = 'post';
    }

    // Function to get query parameters from URL
    function getQueryVariable(variable) {
        var query = window.location.search.substring(1);
        var vars = query.split('&');
        for (var i = 0; i < vars.length; i++) {
            var pair = vars[i].split('=');
            if (decodeURIComponent(pair[0]) == variable) {
                return decodeURIComponent(pair[1]);
            }
        }
        return null;
    }

    // Function to remove a query parameter from URL
    function removeQueryParam(param) {
        var url = window.location.href;
        var regex = new RegExp('[&?]' + param + '(=1)?(&)?');
        url = url.replace(regex, function(match, p1, p2) {
            return p2 ? '?' : '';
        });
        window.history.replaceState({}, document.title, url);
    }

    // Function to start the tour based on isChild
    function startIntroTour() {
        var intro = introJs();
        var steps = isChild ? [
            {
                element: '#titlewrap',
                intro: 'Enter the title for this chapter here.'
            },
            {
                element: '#pundito_order_select',
                intro: 'Set the day of the week or order.'
            },
            {
                element: '#postimagediv',
                intro: 'Set the featured image for the chapter here.'
            },
            {
                element: '#pundito_industries',
                intro: 'Determine if the post will also appear in a specific "Industry".'
            },
            {
                element: '#minor-publishing',
                intro: 'Save as draft before editing with Elementor.'
            }
        ] : [
            {
                element: '#titlewrap',
                intro: 'Enter the title for this week here.'
            },
            {
                element: '#postimagediv',
                intro: 'Set the featured image for the week here.'
            },
            {
                element: '#minor-publishing',
                intro: 'Save as draft before editing with Elementor.'
            }
        ];

        intro.setOptions({
            steps: steps,
            showButtons: true,
            showStepNumbers: true,
            exitOnEsc: false,
            exitOnOverlayClick: false
        });

        intro.start();
    }

    // Check if we're on the creation screen
    if (pagenow === 'post-new') {
        startIntroTour();

        // Add hidden input when clicking "Publish" or "Save Draft"
        var publishButton = document.getElementById('publish');
        if (publishButton) {
            publishButton.addEventListener('click', function() {
                var form = document.getElementById('post');
                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'introjs_continue';
                input.value = '1';
                form.appendChild(input);
            });
        }

        var saveDraftButton = document.getElementById('save-post');
        if (saveDraftButton) {
            saveDraftButton.addEventListener('click', function() {
                var form = document.getElementById('post');
                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'introjs_continue';
                input.value = '1';
                form.appendChild(input);
            });
        }
    }

    // Check if we're on the edit screen with 'introjs_continue' parameter
    if (pagenow === 'post' && getQueryVariable('introjs_continue') === '1') {
        // Start the tour to highlight the Elementor button
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

        // Remove the parameter from the URL
        removeQueryParam('introjs_continue');
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