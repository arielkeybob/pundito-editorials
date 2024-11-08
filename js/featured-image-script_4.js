jQuery(document).ready(function($) {
    $('.featured-image-id').each(function() {
        var postID = $(this).text();
        var row = $(this).closest('tr');

        wp.media.attachment(postID).fetch().done(function(attachment) {
            var thumbnailURL = attachment.url;
            if (thumbnailURL) {
                row.addClass('featured-image-row');
                row.css({
                    'background-image': 'url(' + thumbnailURL + ')',
                    'background-position': 'center',
                    'background-size': 'cover'
                });
            }
        });
    });
});


window.addEventListener('DOMContentLoaded', function() {
            var trElements = document.querySelectorAll('tr.level-1');
            trElements.forEach(function(element) {
                element.classList.add('overlay-level-1');
            });
        });
window.addEventListener('DOMContentLoaded', function() {
            var trElements = document.querySelectorAll('tr.level-0');
            trElements.forEach(function(element) {
                element.classList.add('overlay-level-0');
            });
        });        