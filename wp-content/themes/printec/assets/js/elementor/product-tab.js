(function ($) {
    "use strict";
    $(window).on('elementor/frontend/init', () => {
        elementorFrontend.hooks.addAction('frontend/element_ready/printec-products-tabs.default', ($scope) => {

            $scope.find('.scrollbar-macosx').scrollbar();
            $('.scrollbar-macosx').on('wheel',function(ev){
                let y = parseInt(ev.originalEvent.deltaY);
                if (y)
                    this.scrollLeft += y;
                ev.preventDefault();

            });

            let $tabs = $scope.find('.elementor-tabs-wrapper');
            let $contents = $scope.find('.elementor-tabs-content-wrapper');
            $contents.find('.elementor-tab-content').hide();
            // Active tab
            $contents.find('.elementor-active').show();
            let $carousel = $('.woocommerce-carousel ul', $scope);
            let $carousel_setting = $('.elementor-tabs-content-wrapper', $scope);
            let data = $carousel_setting.data('settings'),
                rtl = $('body').hasClass('rtl');

            $tabs.find('.elementor-tab-title').on('click', function () {
                $tabs.find('.elementor-tab-title').removeClass('elementor-active');
                $contents.find('.elementor-tab-content').removeClass('elementor-active').hide();
                $(this).addClass('elementor-active');
                let id = $(this).attr('aria-controls');
                $contents.find('#' + id).addClass('elementor-active').show();
                $carousel.slick('refresh');
            });


            if (typeof data === 'undefined') {
                return;
            }
            if(data['layout_carousel'] === true){
                $carousel.slick(
                    {
                        rtl: rtl,
                        dots: data.navigation === 'both' || data.navigation === 'dots',
                        arrows: data.navigation === 'both' || data.navigation === 'arrows',
                        infinite: data.loop,
                        speed: data.speed,
                        slidesToShow: parseInt(data.items),
                        autoplay: data.autoplay,
                        autoplaySpeed: parseInt(data.autoplayTimeout),
                        pauseOnHover: data.pauseOnHover,
                        slidesToScroll: 1,
                        lazyLoad: 'ondemand',
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
                            },
                            {
                                breakpoint: 767,
                                settings: {
                                    slidesToShow: 2,
                                }
                            },
                            {
                                breakpoint: 500,
                                settings: {
                                    slidesToShow: 1,
                                }
                            }
                        ]
                    }
                );
            }
            else if(data['layout_carousel'] === false){
                $carousel.slick(
                    {
                        dots: data.navigation === 'both' || data.navigation === 'dots' ? true : false,
                        arrows: data.navigation === 'both' || data.navigation === 'arrows' ? true : false,
                        infinite: data.loop,
                        speed: data.speed,
                        slidesToShow: parseInt(data.items),
                        autoplay: data.autoplay,
                        autoplaySpeed: parseInt(data.autoplayTimeout),
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
