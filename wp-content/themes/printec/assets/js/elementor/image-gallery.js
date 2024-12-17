(function ($) {
    "use strict";
    $(window).on('elementor/frontend/init', () => {
        elementorFrontend.hooks.addAction('frontend/element_ready/printec-image-gallery.default', ($scope) => {
            let $iso = $scope.find('.isotope-grid');
            if ($iso) {
                let currentIsotope = $iso.isotope({filter: '*'});
                $scope.find('.elementor-galerry__filters li').on('click', function () {
                    $(this).parents('ul.elementor-galerry__filters').find('li.elementor-galerry__filter').removeClass('elementor-active');
                    $(this).addClass('elementor-active');
                    let selector = $(this).attr('data-filter');
                    currentIsotope.isotope({filter: selector});
                });
            }

            let $carousel = $('.printec-carousel', $scope);
            if ($carousel.length > 0) {
                let data = $carousel.data('settings');
                $carousel.slick(
                    {
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
                        rtl: data.rtl,
                        centerMode: data.centerMode ? data.centerMode : false,
                        variableWidth: data.variableWidth ? data.variableWidth : false,
                        centerPadding: data.centerPadding ? data.centerPadding : '50px',
                        responsive: [
                            {
                                breakpoint: parseInt(data.breakpoint_laptop),
                                settings: {
                                    slidesToShow: parseInt(data.items_laptop),
                                    centerPadding: data.centerPadding_laptop ? data.centerPadding_laptop : '0px',
                                }
                            },
                            {
                                breakpoint: parseInt(data.breakpoint_tablet_extra),
                                settings: {
                                    slidesToShow: parseInt(data.items_tablet_extra),
                                    centerPadding: data.centerPadding_extra ? data.centerPadding_extra : '0px',
                                }
                            },
                            {
                                breakpoint: parseInt(data.breakpoint_tablet),
                                settings: {
                                    slidesToShow: parseInt(data.items_tablet),
                                    centerPadding: data.centerPadding_tablet ? data.centerPadding_tablet : '0px',
                                }
                            },
                            {
                                breakpoint: parseInt(data.breakpoint_mobile_extra),
                                settings: {
                                    slidesToShow: parseInt(data.items_mobile_extra),
                                    centerPadding: data.centerPadding_mobile_extra ? data.centerPadding_mobile_extra : '0px',
                                }
                            },
                            {
                                breakpoint: parseInt(data.breakpoint_mobile),
                                settings: {
                                    slidesToShow: parseInt(data.items_mobile),
                                    centerPadding: data.centerPadding_mobile ? data.centerPadding_mobile : '0px',
                                }
                            }
                        ]
                    }
                );

            }

        });
    });

})(jQuery);
