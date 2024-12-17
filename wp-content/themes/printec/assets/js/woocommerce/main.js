(function ($) {
    'use strict';
    var $body = $('body');
    var xhr = false;

    function tooltip() {

        $body.on('mouseenter', '.product-transition .shop-action .woosw-btn:not(.tooltipstered), .product-transition .shop-action .woosq-btn:not(.tooltipstered), .product-transition .shop-action .woosc-btn:not(.tooltipstered)', function () {
            var $element = $(this);
            if (typeof $.fn.tooltipster !== 'undefined') {
                $element.tooltipster({
                    position: 'left',
                    functionBefore: function (instance, helper) {
                        instance.content(instance._$origin.text());
                    },
                    theme: 'opal-product-tooltipster',
                    delay: 0,
                    animation: 'grow'
                }).tooltipster('show');
            }
        });

        $body.on('mouseenter', '.printec-wrap-swatches .printec-tooltip:not(.tooltipstered), .product-caption .shop-action .woosw-btn:not(.tooltipstered), .product-caption .shop-action .woosq-btn:not(.tooltipstered), .product-caption .shop-action .woosc-btn:not(.tooltipstered)', function () {
            var $element = $(this);
            if (typeof $.fn.tooltipster !== 'undefined') {
                $element.tooltipster({
                    position: 'top',
                    functionBefore: function (instance, helper) {
                        instance.content(instance._$origin.text());
                    },
                    theme: 'opal-product-tooltipster',
                    delay: 0,
                    animation: 'grow'
                }).tooltipster('show');
            }
        });
    }

    function ajax_wishlist_count() {

        $('body').on('woosw_change_count', function (event, count) {
            var counter = $('.header-wishlist .count');
            if(count == 0) {
                counter.addClass('hide');
            }else  {
                counter.removeClass('hide');
            }
            counter.html(count);
        });
    }

    function wooMenuFilter() {
        let $widget_filter = $('.printec-menu-filter-wrap .widget'),
            count = $widget_filter.length,
            $parrent_filter = $('.printec-sorting'),
            parrent_width = $parrent_filter.width(),
            child_width = 0;

        if ($widget_filter.length > 0) {
            $widget_filter.each((index, element) => {
                child_width += $(element).outerWidth();
                if (!--count) addClassActive(parrent_width,child_width,$parrent_filter);
            });

        }
        function addClassActive(parrent_width,child_width,$parrent_filter) {
            if (child_width > ( parrent_width - 390)) {
                $parrent_filter.addClass('active-filter-toggle');
            } else {
                $parrent_filter.removeClass('active-filter-toggle');
            }
        }
    }

    function woo_widget_categories() {
        var widget = $('.widget_product_categories'),
            main_ul = widget.find('ul');
        if (main_ul.length) {
            var dropdown_widget_nav = function () {

                main_ul.find('li').each(function () {

                    var main = $(this),
                        link = main.find('> a'),
                        ul = main.find('> ul.children');
                    if (ul.length) {

                        //init widget
                        // main.removeClass('opened').addClass('closed');

                        if (main.hasClass('closed')) {
                            ul.hide();

                            link.before('<i class="icon-plus"></i>');
                        } else if (main.hasClass('opened')) {
                            link.before('<i class="icon-minus"></i>');
                        } else {
                            main.addClass('opened');
                            link.before('<i class="icon-minus"></i>');
                        }

                        // on click
                        main.find('i').on('click', function (e) {

                            ul.slideToggle('slow');

                            if (main.hasClass('closed')) {
                                main.removeClass('closed').addClass('opened');
                                main.find('>i').removeClass('icon-plus').addClass('icon-minus');
                            } else {
                                main.removeClass('opened').addClass('closed');
                                main.find('>i').removeClass('icon-minus').addClass('icon-plus');
                            }

                            e.stopImmediatePropagation();
                        });

                        main.on('click', function (e) {

                            if ($(e.target).filter('a').length)
                                return;

                            ul.slideToggle('slow');

                            if (main.hasClass('closed')) {
                                main.removeClass('closed').addClass('opened');
                                main.find('i').removeClass('icon-plus').addClass('icon-minus');
                            } else {
                                main.removeClass('opened').addClass('closed');
                                main.find('i').removeClass('icon-minus').addClass('icon-plus');
                            }

                            e.stopImmediatePropagation();
                        });
                    }
                });
            };
            dropdown_widget_nav();
        }
    }

    function cross_sells_carousel() {
        var csell_wrap = $('body.woocommerce-cart .cross-sells ul.products');
        var item = csell_wrap.find('li.product');

        if (item.length > 3) {
            csell_wrap.slick(
                {
                    dots: true,
                    arrows: false,
                    infinite: false,
                    speed: 300,
                    slidesToShow: parseInt(3),
                    autoplay: false,
                    slidesToScroll: 1,
                    lazyLoad: 'ondemand',
                    responsive: [
                        {
                            breakpoint: 1024,
                            settings: {
                                slidesToShow: parseInt(3),
                            }
                        },
                        {
                            breakpoint: 768,
                            settings: {
                                slidesToShow: parseInt(1),
                            }
                        }
                    ]
                }
            );
        }
    }

    function sendRequest(url, append = false) {

        if (xhr) {
            xhr.abort();
        }

        xhr = $.ajax({
            type: "GET",
            url: url,
            beforeSend: function () {
                var $products = $('ul.printec-products');
                if(!append) {
                    $products.addClass('preloader');
                }
            },
            success: function (data) {
                let $html = $(data);
                if(append) {
                    $('#main ul.printec-products').append($html.find('#main ul.printec-products > li'));
                }else {
                    $('#main ul.printec-products').replaceWith($html.find('#main ul.printec-products'));
                }
                $('#main .woocommerce-pagination-wrap').replaceWith($html.find('#main .woocommerce-pagination-wrap'));
                window.history.pushState(null, null, url);
                xhr = false;
                $(document).trigger('printec-products-loaded');
            }
        });
    }

    $body.on('change', '.printec-products-per-page #per_page', function (e) {
        e.preventDefault();
        var url = this.value;
        sendRequest(url);
    });

    $body.on('click', '.products-load-more-btn', function (e) {
        e.preventDefault();
        $(this).addClass('loading');
        var url = $(this).attr('href');
        sendRequest(url,true);
    });
    function productsPaginationScroll() {
        if (typeof $.fn.waypoint == 'function') {
            var waypoint = $('.products-load-more-btn.load-on-scroll').waypoint(function() {
                $('.products-load-more-btn.load-on-scroll').trigger('click');
            }, {offset: '100%'});
        }
    }

    function productHoverRecalc() {
        $('body').on('mouseenter', '.product-block', function (e) {
            let heightHideInfo = $('.product-caption-bottom', this).outerHeight();
            $('.content-product-imagin', this).css({
                marginBottom: -heightHideInfo
            });
        });
    }


    $(document).ready(function () {
        cross_sells_carousel();
        wooMenuFilter();
    }).on('printec-products-loaded',function () {
        $('.products-load-more-btn').removeClass('loading');
        productsPaginationScroll();
    });

    $(window).resize(function () {
        wooMenuFilter();
    });
    productHoverRecalc();
    productsPaginationScroll();
    woo_widget_categories();
    tooltip();
    ajax_wishlist_count();
})(jQuery);
