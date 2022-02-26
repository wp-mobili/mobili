(function ($) {
    "use strict";
    $(document).on('click', '#upload-form-toggle', function () {
        $(".upload-theme").slideToggle('fast');
    });

    let storeRequest = null;
    let totalPages   = 1;
    let showSpinner  = true;
    let themesCache  = [];
    $(document).on('loadThemes', 'body', function () {
        $('.mobili-store-wrap').removeClass('no-results');
        if (showSpinner) {
            $('.mobili-store-wrap').addClass('loading-content');
        }else{
            $('body').addClass('loading-more');
        }

        let sort   = $('input[name="sort"]').val();
        let page   = $('input[name="pageNum"]').val();
        let search = $('input[name="search"]').val();

        if (storeRequest !== null) {
            storeRequest.abort();
        }

        storeRequest = $.ajax({
            method: "POST",
            url   : mobiliInstallArgs.adminAjax,
            data  : {
                action: "mi_ajax_store_list",
                sort  : sort,
                page  : page,
                search: search,
            }
        })
            .done(function (response) {
                if (typeof response.data.info !== 'undefined' && typeof response.data.info.pages !== 'undefined') {
                    totalPages = response.data.info.pages;
                }
                if (typeof response.data.info !== 'undefined' && typeof response.data.info.results !== 'undefined') {
                    $('.filter-count .theme-count').text(response.data.info.results);
                } else {
                    $('.filter-count .theme-count').text('0');
                }
                if (typeof response.data.themes !== 'undefined' && response.data.themes.length > 0) {
                    themesCache = themesCache.concat(response.data.themes);
                    response.data.themes.forEach(function (item) {
                        let themeItem = wp.template('theme');
                        let themeItemWrapper = $("<div class='theme' data-slug='"+item.slug+"'></div>");
                        if (item.active){
                            themeItemWrapper.addClass('active');
                        }
                        themeItemWrapper.html(themeItem(item));
                        $('.theme-browser .themes').append(themeItemWrapper);
                    });
                } else {
                    $('.mobili-store-wrap').addClass('no-results');
                }
                $('body').removeClass('loading-page').removeClass('loading-more');
                $('.mobili-store-wrap').removeClass('loading-content');
                showSpinner = true;
            });
    });

    $(document).ready(function () {
        $('body').trigger('loadThemes');
        $(window).scroll(function () {
            if (($(window).scrollTop() + $(window).height() + 200) <= $('.page-form-data').offset().top) {
                return false;
            }
            if ($('body').hasClass('loading-page')) {
                return false;
            }

            let pageEl     = $('input[name="pageNum"]');
            let pageNumber = parseInt(pageEl.val());
            if (pageNumber >= totalPages) {
                return false;
            }

            showSpinner = false;
            pageEl.val(pageNumber + 1);
            $('body').addClass('loading-page').trigger('loadThemes');

        });
    });

    function addParam(key, val) {
        let url = new URL(window.location.href);
        url.searchParams.set(key, val);
        return url.href;
    }

    $(document).on('click', '.theme-install-overlay .close-full-overlay', function (e) {
        $('.theme-install-overlay').removeClass('iframe-ready').fadeOut('fast');
        $('body').removeClass('full-overlay-active');
    });
    $(document).on('click', '.theme-install-overlay .collapse-sidebar', function (e) {
        $('.theme-install-overlay').toggleClass('expanded').toggleClass('collapsed');
    });
    $(document).on('click', '.themes .theme .more-details, .themes .theme .theme-screenshot, .themes .theme .preview.install-theme-preview', function (e) {
        let themeSlug = $(this).parents('.theme').attr('data-slug');
        if (themeSlug === '') {
            return false;
        }
        let theme = themesCache.filter((item) => {
            return item.slug === themeSlug;
        });
        if (theme.length > 0) {
            let themeItem  = wp.template('theme-preview');
            let themeModal = $('.theme-install-overlay');
            themeModal.html(themeItem(theme[0]));
            themeModal.find('.wp-full-overlay-main iframe').load(function(){
                themeModal.addClass('iframe-ready');
            });
            themeModal.fadeIn('fast');
            $('body').addClass('full-overlay-active');
        }

    });

    $(document).on('click', '.filter-links a', function (e) {
        e.preventDefault();
        if ($(this).hasClass('current')) {
            return false;
        }
        $('input[name="pageNum"]').val('1');
        let queryParams = new URLSearchParams(window.location.search);
        queryParams.set('sort', $(this).attr('data-slug'));
        history.replaceState(null, null, "?" + queryParams.toString());

        $('input[name="sort"]').val($(this).attr('data-slug'));
        $('.filter-links a.current').removeClass('current');
        $(this).addClass('current');
        $('.theme-browser .themes').html('');
        $('body').trigger('loadThemes');
    });
    $(document).on('change keyup', '#wp-filter-search-input', function () {
        $('input[name="pageNum"]').val('1');
        let queryParams = new URLSearchParams(window.location.search);
        queryParams.set('search', $(this).val());
        if ($(this).val() === '') {
            queryParams.delete('search');
        }
        history.replaceState(null, null, "?" + queryParams.toString());
        $('.theme-browser .themes').html('');
        $('body').trigger('loadThemes');

    });
    $(document).on('submit', 'form.search-form', function (e) {
        e.preventDefault();
        $('#wp-filter-search-input').trigger('change');
    });
})(jQuery);