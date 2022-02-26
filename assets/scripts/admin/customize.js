(function ($) {
    "use strict";
    $(document).ready(function () {
        $('#customize-save-button-wrapper').prepend('<a href="'+mobiliCustomizeArgs.mobileVersionUrl+'" style="float: right;" class="mobili-mobile-preview-button button-primary">' + mobiliCustomizeArgs.mobileVersionLabel + '</a>');
    });
})(jQuery);