(function ($) {
    "use strict";
    $(window).on('elementor/frontend/init', () => {
        elementorFrontend.hooks.addAction('frontend/element_ready/printec-price-table.default', ($scope) => {
            let $carousel = $('.printec-carousel', $scope);
            if ($carousel.length > 0) {
                let data = $carousel.data('settings'),
                    rtl = $('body').hasClass('rtl');
                $carousel.slick(
                    {
                        rtl: rtl,
                        dots: data.navigation == 'both' || data.navigation == 'dots' ? true : false,
                        arrows: data.navigation == 'both' || data.navigation == 'arrows' ? true : false,
                        infinite: data.loop,
                        speed: data.speed,
                        slidesToShow: parseInt(data.items),
                        autoplay: data.autoplay,
                        autoplaySpeed: data.autoplaySpeed,
                        pauseOnHover: data.pauseOnHover,
                        slidesToScroll: 1,
                        lazyLoad: 'ondemand',
                        responsive: [
                            {
                                breakpoint: parseInt(data.breakpoint_laptop),
                                settings: {
                                    slidesToShow: parseInt(data.items_laptop),
                                }
                            },
                            {
                                breakpoint: parseInt(data.breakpoint_tablet_extra),
                                settings: {
                                    slidesToShow: parseInt(data.items_tablet_extra),
                                }
                            },
                            {
                                breakpoint: parseInt(data.breakpoint_tablet),
                                settings: {
                                    slidesToShow: parseInt(data.items_tablet),
                                }
                            },
                            {
                                breakpoint: parseInt(data.breakpoint_mobile_extra),
                                settings: {
                                    slidesToShow: parseInt(data.items_mobile_extra),
                                }
                            },
                            {
                                breakpoint: parseInt(data.breakpoint_mobile),
                                settings: {
                                    slidesToShow: parseInt(data.items_mobile),
                                }
                            }
                        ]
                    }
                );

            }
        });
    });

})(jQuery);
