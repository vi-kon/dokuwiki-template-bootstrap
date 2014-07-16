(function ($) {
    "use strict";

    $(document).ready(function () {
        var options, navbarTOC;

        options = {
            container: 'body',
            trigger:   'hover'
        };
        $('[data-toggle="popover"]').popover(options);

        options = {
            target: '.navbar-toc'
        };
        $('body').scrollspy(options);

        options = {
            offset: {
                top:    function () {
                    this.top = $('.page-header').outerHeight(true) - 10;
                    return this.top;
                },
                bottom: function () {
                    this.bottom = $('.page-footer').outerHeight(true);
                    return this.bottom;
                }
            }
        };
        navbarTOC = $('.navbar-toc').eq(0);
        navbarTOC.closest('.panel-navbar-toc')
            .css('width', navbarTOC.closest('.col-sm-3').width() + "px")
            .affix(options);
    });
}(jQuery));