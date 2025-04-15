// $(document).ready(function() {
//     $('.layout-menu-toggle').on('click', function(e) {
//         e.preventDefault();
//         // Toggle the collapse state of the sidebar
//         $('body').toggleClass('layout-menu-collapsed');

//         // Toggle the icon based on the cllapse state
//         if ($('body').hasClass('layout-menu-collapsed')) {
//             // Change the icon to the right arrow when collapsed
//             $(this).find('i').removeClass('bx-chevron-left').addClass('bx-chevron-right');
//         } else {
//             // Change the icon to the left arrow when expanded
//             $(this).find('i').removeClass('bx-chevron-right').addClass('bx-chevron-left');
//         }
//     });

//     // Hover event for expanding/collapsing when the sidebar is collapsed
//     $('#layout-menu').hover(function() {
//         if ($('body').hasClass('layout-menu-collapsed')) {
//             $('body').addClass('layout-menu-hover-expanded');
//             $('body').removeClass('layout-menu-collapsed');
//         }
//     }, function() {
//         if ($('body').hasClass('layout-menu-hover-expanded')) {
//             $('body').removeClass('layout-menu-hover-expanded');
//             $('body').addClass('layout-menu-collapsed');
//         }
//     });
// });


// resources/vendor/js/menu-toggle.js
// resources/vendor/js/menu-toggle.js
$(document).ready(function() {
    let hoverTimeout;
    let isTransitioning = false;
    const TRANSITION_DURATION = 300; // Match this with CSS transition duration

    // Helper function to expand menu
    function expandMenu(immediate = false) {
        if (isTransitioning && !immediate) return;

        isTransitioning = true;
        $('body').removeClass('layout-menu-collapsed').addClass('layout-menu-expanded');
        $('.layout-menu-toggle i').removeClass('bx-chevron-right').addClass('bx-chevron-left');
        $('#layout-menu').css('width', '260px');

        setTimeout(() => {
            isTransitioning = false;
        }, TRANSITION_DURATION);
    }

    // Helper function to collapse menu
    function collapseMenu(immediate = false) {
        if (isTransitioning && !immediate) return;

        isTransitioning = true;
        $('body').addClass('layout-menu-collapsed').removeClass('layout-menu-expanded');
        $('.layout-menu-toggle i').removeClass('bx-chevron-left').addClass('bx-chevron-right');
        $('#layout-menu').css('width', '80px');

        setTimeout(() => {
            isTransitioning = false;
        }, TRANSITION_DURATION);
    }

    // Menu Toggle Click Handler
    $('.layout-menu-toggle').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();

        if ($('body').hasClass('layout-menu-collapsed')) {
            expandMenu(true);
        } else {
            collapseMenu(true);
        }

        $(window).trigger('resize');
    });

    // Hover handlers with debouncing
    $('#layout-menu').on('mouseenter', function(e) {
        if (!$('body').hasClass('layout-menu-collapsed')) return;

        clearTimeout(hoverTimeout);
        expandMenu();
        $('body').addClass('layout-menu-hover-expanded');
    }).on('mouseleave', function(e) {
        if (!$('body').hasClass('layout-menu-hover-expanded')) return;

        clearTimeout(hoverTimeout);
        hoverTimeout = setTimeout(() => {
            collapseMenu();
            $('body').removeClass('layout-menu-hover-expanded');
        }, 200); // Small delay before collapsing
    });

    // Submenu Toggle Handler
    $('.menu-toggle').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();

        const $menuItem = $(this).closest('.menu-item');
        const $submenu = $menuItem.children('.menu-sub');

        // Don't process if already transitioning
        if ($submenu.is(':animated')) return;

        // Close other submenus at the same level
        const $siblings = $menuItem.siblings('.menu-item.open');
        $siblings.each(function() {
            const $sibling = $(this);
            $sibling.removeClass('open');
            $sibling.children('.menu-sub').slideUp(TRANSITION_DURATION);
        });

        // Toggle clicked submenu
        if ($menuItem.hasClass('open')) {
            $menuItem.removeClass('open');
            $submenu.slideUp(TRANSITION_DURATION);
        } else {
            $menuItem.addClass('open');
            $submenu.slideDown(TRANSITION_DURATION);
        }
    });

    // Initialize active menu items
    function initActiveMenuItems() {
        $('.menu-item.active').each(function() {
            const $activeItem = $(this);
            $activeItem.parents('.menu-item').addClass('open');
            $activeItem.parents('.menu-sub').show();
        });
    }

    // Prevent submenu clicks from triggering parent events
    $('.menu-sub').on('click', function(e) {
        e.stopPropagation();
    });

    // Initialize
    initActiveMenuItems();
});
