document.addEventListener('DOMContentLoaded', function() {
    introJs().setOptions({
        steps: [
            {
                element: '#titlewrap',
                intro: 'Digite o título do seu editorial aqui.'
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
                intro: 'Defina se o post também aparecerá em algum "Industry" especifico'
            },
            {
                element: '#submitdiv',
                intro: 'Aqui você pode publicar ou salvar seu editorial.'
            }
        ]
    }).start();
});
