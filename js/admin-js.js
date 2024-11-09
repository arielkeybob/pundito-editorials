jQuery(document).ready(function($) {
    var $parentSelector = $('#parent_id'); // Selector para o dropdown de seleção de parent
    var $orderSelect = $('#pundito_order_select_dropdown'); // Selector para o dropdown de 'Order Select'

    // Função para atualizar o dropdown de seleção de ordem com base na disponibilidade
    function updateOrderSelection() {
        var parentValue = $parentSelector.val(); // Pega o valor atual do dropdown de parent
        console.log("Parent ID selecionado: ", parentValue);

        if (parentValue === '') {
            // Se é um post pai, permite apenas a opção "Intro"
            console.log("Sem parent selecionado, definindo 'Order Select' para 'Intro'");
            $orderSelect.val('Intro').prop('disabled', true);
            $orderSelect.find('option').each(function() {
                if ($(this).val() !== 'Intro') {
                    $(this).hide(); // Esconde todas as opções exceto 'Intro'
                } else {
                    $(this).show(); // Mostra a opção 'Intro'
                }
            });
        } else {
            // Se é um post filho, habilita a edição e ajusta as opções disponíveis
            $orderSelect.prop('disabled', false);
            $orderSelect.find('option[value="Intro"]').hide(); // Esconde a opção 'Intro'
            // Solicita as opções disponíveis via Ajax
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'get_available_order_options',
                    parent_id: parentValue
                },
                success: function(response) {
                    if (response.success) {
                        // Atualiza as opções do dropdown
                        $orderSelect.find('option').each(function() {
                            if (response.data.includes($(this).val())) {
                                $(this).show();
                            } else {
                                $(this).hide();
                            }
                        });
                    }
                }
            });
        }
    }

    // Atualiza ao carregar e quando o valor de parent é alterado
    updateOrderSelection();
    $parentSelector.change(updateOrderSelection);
});
