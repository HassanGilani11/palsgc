(function ($) {
    "use strict";

    $(window).on('elementor/frontend/init', () => {
        elementorFrontend.hooks.addAction( 'frontend/element_ready/printec-button-popup.default', ( $scope ) => {
            if ($scope.find('.printec-button-popup a.button-popup').length > 0) {
                $scope.find('.printec-button-popup a.button-popup').magnificPopup({
                    type: 'inline',
                    removalDelay: 500,
                    closeBtnInside: true,
                    callbacks: {
                        beforeOpen: function() {
                            this.st.mainClass = this.st.el.attr('data-effect');
                        }
                    },
                    midClick: true
                });
            }
        } );
    });

})(jQuery);