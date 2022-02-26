(function ($) {
    "use strict";
    $(document).ready(function () {
        $('.smart-content[data-text]').each(function () {
            let targetEl = $($(this).attr('data-text'));
            let thisItem = $(this);
            let changeTimer = null;
            targetEl.on('keyup focus mouseover', function () {
                thisItem.removeClass('content-changed');
                clearTimeout(changeTimer);
                changeTimer = setTimeout(function () {
                    thisItem.addClass('content-changed');
                }, 1000);
                thisItem.html($(this).val());
            });
            targetEl.trigger('keyup');
        });
        $('.smart-content[data-src]').each(function () {
            let targetEl = $($(this).attr('data-src'));
            let thisItem = $(this);
            targetEl.on('keyup focus mouseover', function () {
                let changeTimer = null;
                thisItem.removeClass('content-changed');
                clearTimeout(changeTimer);
                changeTimer = setTimeout(function () {
                    thisItem.addClass('content-changed');
                }, 1000);
                thisItem.attr('src', $(this).val());
            });
            targetEl.trigger('keyup');
        });

        $('.smart-content[data-style]').each(function () {
            let targetEl = $($(this).attr('data-input'));
            let thisItem = $(this);
            targetEl.on('keyup focus mouseover change', function () {
                let itemStyle = thisItem.attr('data-style');
                thisItem.attr('style', itemStyle.replaceAll('[value]', $(this).val()));
            });
            targetEl.trigger('keyup');
        });

        $('.mi-color-input').each(function () {
            let timeOutFix = null;
            $(this).wpColorPicker({
                change: function (event, ui) {
                    let element = event.target;
                    let color   = ui.color.toString();
                    clearTimeout(timeOutFix);
                    timeOutFix = setTimeout(function () {
                        $(element).trigger('keyup');
                    },500);
                },
            });
        });

        $('.mi-show-more-options+table.form-table').hide();
    });


    $(document).on('click', '.has-confirm[data-message]', function (e) {
        let message = $(this).attr('data-message');
        if (!confirm(message)){
            e.preventDefault();
        }
    });

    $(document).on('click', '.mi-show-more-options', function (e) {
        e.preventDefault();
        $(this).slideUp('fast');
        $(this).next('table.form-table').slideDown('fast');
    });
    $(document).on('click', '.mi-media-input .select-media', function (e) {
        e.preventDefault();
        let uploadHandler = wp.media({
            multiple: false
        }).on('select', () => {
            var attachment = uploadHandler.state().get('selection').first().toJSON();
            let el         = $(this).parents('.mi-media-input').find('.media-input');
            el.val(attachment.url);
            el.trigger('keyup');
        }).open();
    });
})(jQuery);