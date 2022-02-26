(function ($) {
    "use strict";

    $(document).on('click', '.theme-overlay .theme-backdrop,.theme-overlay .theme-header .close', function (e) {
        $('.theme-overlay').html('');
        $('body').removeClass('modal-open');
    });

    $(document).on('click', '.themes .theme .more-details, .themes .theme .theme-screenshot, .themes .theme .preview.install-theme-preview', function (e) {
        let themeSlug = $(this).parents('.theme').attr('data-slug');
        if (themeSlug === '') {
            return false;
        }
        let themesList = [];
        Object.keys(mobiliThemesOBJ).forEach(function (item) {
            themesList.push(mobiliThemesOBJ[item]);
        });

        let theme = themesList.filter((item) => {
            return item.id === themeSlug;
        });

        if (theme.length > 0) {
            let themeItem  = wp.template('theme-single');
            let themeModal = $('.theme-overlay');
            themeModal.html('<div class="theme-overlay active">' + themeItem(theme[0]) + '</div>');

            $('body').addClass('modal-open');
        }

    });
})(jQuery);