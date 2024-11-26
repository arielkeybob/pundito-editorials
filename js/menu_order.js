jQuery(window).on('load', function() {
    var orderSelect = jQuery('#pundito_order_select_dropdown');
    var pageOrder = jQuery('#menu_order');
    
    var orderMap = {
        'Intro': 0,
        'Monday': 1,
        'Tuesday': 2,
        'Wednesday': 3,
        'Thursday': 4,
        'Friday': 5,
        'Bonus': 6,
        'Bonus 2': 7
    };

    orderSelect.change(function() {
        var selectedOrder = jQuery(this).val();
        var mappedOrder = orderMap[selectedOrder];
        
        if (mappedOrder !== undefined && pageOrder.length > 0) {
            pageOrder.val(mappedOrder);
            console.log("Order selecionado:", selectedOrder, "Mapped:", mappedOrder);
            console.log("Menu order atualizado para:", mappedOrder);
        }
    });
});
