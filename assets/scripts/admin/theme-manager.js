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
            let themeItem = wp.template('theme-single');
            let themeModal = $('.theme-overlay');

            if (theme[0].active){
                themeModal.addClass('active');
            }else{
                themeModal.removeClass('active');
            }
            themeModal.html(themeItem(theme[0]));

            $('body').addClass('modal-open');
        }
    });

    $(document).on('click', '.theme .more-details', function () {
        let themeSlug = $(this).parents('.theme').attr('data-slug');
        let switchLink = MobiliThemeMobileData.switchLink.replaceAll('%theme%', themeSlug);
        let themeModalButtons = $('.theme-wrap .inactive-theme');
        if (themeModalButtons.find('.button.convert').length === 0) {
            themeModalButtons.append('<a href="' + switchLink + '" class="button convert has-confirm" data-message="' + MobiliThemeMobileData.switchConform + '">' + MobiliThemeMobileData.switchText + '</a>');
        }
    });
})(jQuery);