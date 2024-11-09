jQuery(document).ready(function($) {
    var $parentSelector = $('#parent_id'); // Selector para o dropdown de seleção de parent
    var $orderSelect = $('#pundito_order_select_dropdown'); // Selector para o dropdown de 'Order Select'

    function updateOrderSelection() {
        var parentValue = $parentSelector.val(); // Pega o valor atual do dropdown de parent
        console.log("Parent ID selecionado: ", parentValue);

        if (parentValue === '') { // Verifica se o post é parent
            console.log("Sem parent selecionado, definindo 'Order Select' para 'Intro'");
            $orderSelect.val('Intro').prop('disabled', true); // Define 'Intro' e bloqueia para edição
            $orderSelect.find('option[value="Intro"]').show(); // Mostra a opção 'Intro' caso esteja escondida
        } else {
            console.log("Parent selecionado, mantendo valor existente do 'Order Select'. Valor atual:", $orderSelect.val());
            $orderSelect.prop('disabled', false); // Permite edição se tiver um parent
            $orderSelect.find('option[value="Intro"]').hide(); // Esconde a opção 'Intro'
        }
    }

    // Atualiza ao carregar e quando o valor de parent é alterado
    updateOrderSelection();
    $parentSelector.change(updateOrderSelection);
    
    //console.log("Valor inicial de Order Select no carregamento:", $orderSelect.val());
});
