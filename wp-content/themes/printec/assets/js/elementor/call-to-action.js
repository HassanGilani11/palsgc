(function ($) {
    "use strict";
    $(window).on('elementor/frontend/init', () => {
        elementorFrontend.hooks.addAction('frontend/element_ready/printec-banner.default', ($scope) => {
            let $title_element = $scope.find('.letter_text')
            if($title_element.length) {
                let lettertext = $title_element.text().split(' ');
                let html_replace = '';
                for (var i = 0; i < lettertext.length; i++) {
                    html_replace += '<span class="letter">' + lettertext[i] + '</span>';
                }
                $title_element.html(html_replace);
            }
        });
    });
})(jQuery);

