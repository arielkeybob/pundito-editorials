document.addEventListener('DOMContentLoaded', function() {
    var postData = PostData || { isChild: 'no' };  // Assume 'no' if PostData is undefined
    var isChild = postData.isChild === 'yes';

    var intro = introJs();
    var steps = isChild ? [
        {
            element: '#titlewrap',
            intro: 'Digite o título deste chapter aqui.'
        },
        {
            element: '#pundito_order_select',
            intro: 'Defina dia da semana ou (Ordem)'
        },
        {
            element: '#postimagediv',
            intro: 'Defina a imagem destacada do chapter aqui.'
        },
        {
            element: '#pundito_industries',
            intro: 'Defina se o post também aparecerá em algum "Industry" específico'
        },
        {
            element: '#minor-publishing',
            intro: 'Salve como rascunho antes de editar com o Elementor'
        }
    ] : [
        {
            element: '#titlewrap',
            intro: 'Digite o título deste week aqui.'
        },
        {
            element: '#postimagediv',
            intro: 'Defina a imagem destacada do week aqui.'
        },
        {
            element: '#minor-publishing',
            intro: 'Salve como rascunho antes de editar com o Elementor'
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
});
