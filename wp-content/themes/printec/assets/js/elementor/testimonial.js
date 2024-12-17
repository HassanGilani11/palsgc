(function ($) {
    "use strict";
    $(window).on('elementor/frontend/init', () => {
        elementorFrontend.hooks.addAction('frontend/element_ready/printec-testimonials.default', ($scope) => {
            let $carousel = $('.printec-carousel', $scope);
            let $currentItem = $('.current-item', $scope);
            let rtl = $('body').hasClass('rtl');
            if ($carousel.length > 0) {
                let data = $carousel.data('settings');
                $carousel.on('init reInit afterChange', function (event, slick, currentSlide, nextSlide) {
                    var i = (currentSlide ? currentSlide : 0) + 1;
                    $currentItem.text(i + '/' + slick.slideCount);
                });
                if ($carousel.hasClass('layout-nav')) {
                    let $nav = $('.testimonial-image-style', $scope);
                    $carousel.slick({
                        rtl: rtl,
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        arrows: false,
                        fade: true,
                        asNavFor: $nav
                    });
                    let centerMode = $carousel.hasClass('alignment-center');
                        $nav.slick({
                            rtl: rtl,
                            slidesToShow: parseInt(data.items),
                            slidesToScroll: 1,
                            asNavFor: $carousel,
                            adaptiveHeight: false,
                            dots: false,
                            centerMode: centerMode,
                            focusOnSelect: true,
                            arrows: false,
                            centerPadding: '0px'
                        });
                } else {
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

            }

        });
    });

})(jQuery);
