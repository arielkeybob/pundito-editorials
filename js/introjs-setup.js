document.addEventListener('DOMContentLoaded', function() {
    // Obter o post_type do campo oculto
    var postTypeInput = document.getElementById('post_type');
    var post_type = '';
    if (postTypeInput) {
        post_type = postTypeInput.value;
    }

    // Determinar pagenow com base na URL e classes do body
    var pagenow = '';
    var url = window.location.href;
    var bodyClasses = document.body.className;

    if (url.indexOf('post-new.php') !== -1 || bodyClasses.indexOf('post-new-php') !== -1) {
        pagenow = 'post-new';
    } else if (url.indexOf('post.php') !== -1 || bodyClasses.indexOf('post-php') !== -1) {
        pagenow = 'post';
    }

    // Função para obter parâmetros da URL
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

    // Função para remover um parâmetro da URL
    function removeQueryParam(param) {
        var url = window.location.href;
        var regex = new RegExp('[&?]' + param + '(=1)?(&)?');
        url = url.replace(regex, function(match, p1, p2) {
            return p2 ? '?' : '';
        });
        window.history.replaceState({}, document.title, url);
    }

    // Verificar se estamos na tela de criação do post
    if (pagenow === 'post-new' && post_type === 'editorial') {
        // Iniciar o tour na tela de criação
        var intro = introJs();
        intro.setOptions({
            steps: [
                {
                    element: '#titlewrap',
                    intro: 'Digite o título deste editorial aqui.'
                },
                {
                    element: '#pundito_order_select',
                    intro: 'Defina dia da semana ou (Ordem)'
                },
                {
                    element: '#postimagediv',
                    intro: 'Defina a imagem destacada do editorial aqui.'
                },
                {
                    element: '#pundito_industries',
                    intro: 'Defina se o post também aparecerá em algum "Industry" específico'
                },
                {
                    element: '#minor-publishing',
                    intro: 'Salve como rascunho antes de editar com o Elementor'
                }
            ],
            showButtons: true,
            showStepNumbers: true,
            exitOnEsc: false,
            exitOnOverlayClick: false
        });

        intro.onafterchange(function(targetElement) {
            var currentStep = this._currentStep;

            // Se estivermos na etapa do dropdown
            if (currentStep === 1) { // Índice da etapa do dropdown é 1
                var selectElement = document.getElementById('pundito_order_select_dropdown');
                var nextButton = document.querySelector('.introjs-nextbutton');

                // Função para verificar o valor selecionado
                function checkSelection() {
                    if (selectElement.value === '') {
                        // Desabilitar o botão "Próximo"
                        nextButton.classList.add('introjs-disabled');
                        nextButton.style.pointerEvents = 'none';
                    } else {
                        // Habilitar o botão "Próximo"
                        nextButton.classList.remove('introjs-disabled');
                        nextButton.style.pointerEvents = '';
                    }
                }

                // Verificar inicialmente
                checkSelection();

                // Adicionar listener ao dropdown
                selectElement.addEventListener('change', checkSelection);
            } else {
                // Garantir que o botão "Próximo" esteja habilitado nas outras etapas
                var nextButton = document.querySelector('.introjs-nextbutton');
                if (nextButton) {
                    nextButton.classList.remove('introjs-disabled');
                    nextButton.style.pointerEvents = '';
                }
            }
        });

        intro.start();

        // Adicionar um input oculto ao formulário ao clicar em "Publicar" ou "Salvar rascunho"
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

    // Verificar se estamos na tela de edição com o parâmetro 'introjs_continue'
    if (pagenow === 'post' && post_type === 'editorial' && getQueryVariable('introjs_continue') === '1') {
        // Verificar se o elemento existe
        if (document.getElementById('elementor-switch-mode')) {
            // Iniciar o tour na tela de edição
            introJs().setOptions({
                steps: [
                    {
                        element: '#elementor-switch-mode',
                        intro: 'Agora você pode editar com o Elementor.'
                    }
                ]
            }).start();

            // Remover o parâmetro da URL
            removeQueryParam('introjs_continue');
        }
    }
});
