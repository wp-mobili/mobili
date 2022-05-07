(function ($) {
    "use strict";

    $(document).on('click', '.theme:not(.mobile-theme) .more-details', function () {
        let themeSlug = $(this).parents('.theme').attr('data-slug');
        let switchLink = MobiliThemeDesktopData.switchLink.replaceAll('%theme%', themeSlug);
        let themeModalButtons = $('.theme-wrap .inactive-theme');
        if (themeModalButtons.find('.button.convert').length === 0) {
            themeModalButtons.append('<a href="' + switchLink + '" class="button convert has-confirm" data-message="' + MobiliThemeDesktopData.switchConform + '">' + MobiliThemeDesktopData.switchText + '</a>');
        }
    });

})(jQuery);