document.addEventListener('DOMContentLoaded', function() {
    var postData = PostData || { isChild: 'no' };  // Assume 'no' if PostData is undefined
    var isChild = postData.isChild === 'yes';

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
});
