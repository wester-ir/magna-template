// Dropdown
!(function () {
    $(document).on('click', '[data-role="dropdown-trigger"]', function () {
        const content = $(this).next('[data-role="dropdown-content"]');

        if (content.hasClass('hidden')) {
            $(this).attr('data-is-active', true);
            content.removeClass('hidden');
        } else {
            $(this).attr('data-is-active', false);
            content.addClass('hidden');
        }
    });

    $(document).on('click', function (e) {
        if ($(e.target).closest('[data-role="dropdown"]').length === 0) {
            $('[data-role="dropdown-content"]').addClass('hidden');
            $('[data-role="dropdown-trigger"]').attr('data-is-active', 'false');
        }
    });
})();

// Navbar indicator
!(function () {
    const list = $(".navbar-items");
    const indicator = $("#navbar-indicator");

    list.on("mouseleave", function() {
        indicator.animate({
            width: 0,
        }, 300);
    });

    const items = list.find(".navbar-indicator-trigger");

    items.on("mouseenter", function() {
        const leftPos = $(this).position().left;
        const width = $(this).outerWidth();

        indicator.animate({
            "left": leftPos + "px",
            "width": width + "px",
        }, 300);
    });
})();

// Hide the navbar in the header when the user scrolls down
(function () {
    var navbar = $('#navbar');

    var scrollThreshold = 100;
    var prevScrollPos = $(window).scrollTop();
    var isScrollingDown = false;
    var timer;

    $(window).scroll(function() {
        var productScrollPos = $(window).scrollTop();

        if (productScrollPos > prevScrollPos) {
            // Scrolling down
            if (productScrollPos > scrollThreshold && ! isScrollingDown && $('.navbar-category-dropdown').hasClass('hidden')) {
                navbar.attr('data-hidden', 'true');
                isScrollingDown = true;
            }
        } else {
            clearTimeout(timer);

            // Scrolling up
            navbar.attr('data-hidden', 'false');
            isScrollingDown = false;
        }

        prevScrollPos = productScrollPos;
    });
})();

// Category
$('.category-parents .item').hover(function () {
    var id = $(this).data('id');

    $('.category-parents .item').attr('data-is-active', 'false');
    $(this).attr('data-is-active', 'true');

    $('.category-children[data-children-of]').attr('data-is-visible', 'false');
    $('.category-children[data-children-of="'+ id +'"]').attr('data-is-visible', 'true');
});

$('#categories').hover(function () {
    setTimeout(function () {
        $('#categories').attr('data-is-hovering', 'true');
    }, 100);
    $('.navbar-category-dropdown').removeClass('hidden');
}, function () {
    setTimeout(function () {
        $('#categories').attr('data-is-hovering', 'false');
    }, 100);
    $('.navbar-category-dropdown').addClass('hidden');
});

$('#categories button').on('click', function () {
    if ($('#categories').attr('data-is-hovering') === 'true') {
        var dropDown = $('.navbar-category-dropdown');

        if (dropDown.hasClass('hidden')) {
            dropDown.removeClass('hidden');
        } else {
            dropDown.addClass('hidden');
        }
    }
});
