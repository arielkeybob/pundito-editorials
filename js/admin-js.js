jQuery(document).ready(function($) {
    var $parentSelector = $('#parent_id');
    var $orderSelect = $('#pundito_order_select_dropdown');

    function updateOrderSelection() {
        var parentValue = $parentSelector.val();
        console.log("Parent ID selecionado: ", parentValue);

        if (parentValue === '') {
            console.log("Sem parent selecionado, definindo 'Order Select' para 'Intro'");
            $orderSelect.val('Intro').prop('disabled', true).find('option').hide().filter('[value="Intro"]').show();
        } else {
            console.log("Enviando dados AJAX com parent ID:", parentValue);
            $orderSelect.prop('disabled', false).find('option[value="Intro"]').hide();
            $.ajax({
                url: meusDados.ajaxurl,
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'get_available_order_options',
                    parent_id: parentValue,
                    nonce: meusDados.nonce
                },
                success: function(response) {
                    console.log("Resposta AJAX recebida:", response);
                    if (response.success) {
                        $orderSelect.find('option').hide().filter(function() {
                            return response.data.includes(this.value);
                        }).show();
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error("Erro AJAX:", textStatus, errorThrown);
                }
            });
        }
    }

    updateOrderSelection();
    $parentSelector.change(updateOrderSelection);
});
