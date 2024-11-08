jQuery(window).on('load', function() {
    var selectElement = jQuery('#pundito_order_select');

    if (selectElement.length) {
        if (!selectElement.val()) {
            selectElement.val('Intro');
        }
    }
});
