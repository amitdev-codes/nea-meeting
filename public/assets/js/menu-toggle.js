$(document).ready(function() {
    $('.layout-menu-toggle').on('click', function(e) {
        e.preventDefault();
        // Toggle the collapse state of the sidebar
        $('body').toggleClass('layout-menu-collapsed');

        // Toggle the icon based on the cllapse state
        if ($('body').hasClass('layout-menu-collapsed')) {
            // Change the icon to the right arrow when collapsed
            $(this).find('i').removeClass('bx-chevron-left').addClass('bx-chevron-right');
        } else {
            // Change the icon to the left arrow when expanded
            $(this).find('i').removeClass('bx-chevron-right').addClass('bx-chevron-left');
        }
    });

    // Hover event for expanding/collapsing when the sidebar is collapsed
    $('#layout-menu').hover(function() {
        if ($('body').hasClass('layout-menu-collapsed')) {
            $('body').addClass('layout-menu-hover-expanded');
            $('body').removeClass('layout-menu-collapsed');
        }
    }, function() {
        if ($('body').hasClass('layout-menu-hover-expanded')) {
            $('body').removeClass('layout-menu-hover-expanded');
            $('body').addClass('layout-menu-collapsed');
        }
    });
});
