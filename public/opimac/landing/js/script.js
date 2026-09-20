"use strict";
(function () {
    // Global variables
    var
        userAgent = navigator.userAgent.toLowerCase(),
        initialDate = new Date(),

        $document = $(document),
        $window = $(window),
        $html = $("html"),
        $body = $("body"),

        isDesktop = $html.hasClass("desktop"),
        isIE = userAgent.indexOf("msie") !== -1 ? parseInt(userAgent.split("msie")[1], 10) : userAgent.indexOf("trident") !== -1 ? 11 : userAgent.indexOf("edge") !== -1 ? 12 : false,
        isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent),
        windowReady = false,
        isNoviBuilder = false,
        livedemo = true,

        plugins = {
            owl: $('.owl-carousel'),
            preloader: $('.preloader'),
            copyrightYear: $(".copyright-year"),
            wow: $('.wow'),
            parallaxScroll: $('[data-parallax-scroll]')
        };

   
 function lazyInit(element, func) {
        var scrollHandler = function () {
            if ((!element.hasClass('lazy-loaded') && (isScrolledIntoView(element)))) {
                func.call(element);
                element.addClass('lazy-loaded');
            }
        };

        scrollHandler();
        $window.on('scroll', scrollHandler);
    }

    // Initialize scripts that require a loaded window
    $window.on('load', function () {
        // Page loader & Page transition
        if (plugins.preloader.length && !isNoviBuilder) {
            pageTransition({
                target: document.querySelector('.page'),
                delay: 0,
                duration: 500,
                classIn: 'fadeIn',
                classOut: 'fadeOut',
                classActive: 'animated',
                conditions: function (event, link) {
                    return link && !/(\#|javascript:void\(0\)|callto:|tel:|mailto:|:\/\/)/.test(link) && !event.currentTarget.hasAttribute('data-lightgallery');
                },
                onTransitionStart: function (options) {
                    setTimeout(function () {
                        plugins.preloader.removeClass('loaded');
                    }, options.duration * .75);
                },
                onReady: function () {
                    plugins.preloader.addClass('loaded');
                    windowReady = true;
                }
            });
        }
    });

	// Initialize scripts that require a finished document
    $(function () {
        function initOwlCarousel(carousel) {
            var
                aliaces = ['-', '-sm-', '-md-', '-lg-', '-xl-', '-xxl-'],
                values = [0, 576, 768, 992, 1200, 1600],
                responsive = {};

            for (var j = 0; j < values.length; j++) {
                responsive[values[j]] = {};
                for (var k = j; k >= -1; k--) {
                    if (!responsive[values[j]]['items'] && carousel.attr('data' + aliaces[k] + 'items')) {
                        responsive[values[j]]['items'] = k < 0 ? 1 : parseInt(carousel.attr('data' + aliaces[k] + 'items'), 10);
                    }
                    if (!responsive[values[j]]['stagePadding'] && responsive[values[j]]['stagePadding'] !== 0 && carousel.attr('data' + aliaces[k] + 'stage-padding')) {
                        responsive[values[j]]['stagePadding'] = k < 0 ? 0 : parseInt(carousel.attr('data' + aliaces[k] + 'stage-padding'), 10);
                    }
                    if (!responsive[values[j]]['margin'] && responsive[values[j]]['margin'] !== 0 && carousel.attr('data' + aliaces[k] + 'margin')) {
                        responsive[values[j]]['margin'] = k < 0 ? 30 : parseInt(carousel.attr('data' + aliaces[k] + 'margin'), 30);
                    }
                }
            }
            carousel.owlCarousel({
                autoplay: isNoviBuilder ? false : carousel.attr('data-autoplay') !== 'false',
                autoplayTimeout: carousel.attr("data-autoplay-time-out") ? Number(carousel.attr("data-autoplay-time-out")) : 3000,
                autoplayHoverPause: true,
                URLhashListener: carousel.attr('data-hash-navigation') === 'true' || false,
                startPosition: 'URLHash',
                loop: isNoviBuilder ? false : carousel.attr('data-loop') !== 'false',
                items: 1,
                autoHeight: carousel.attr('data-auto-height') === 'true',
                center: carousel.attr('data-center') === 'true',
                dotsContainer: carousel.attr('data-pagination-class') || false,
                navContainer: carousel.attr('data-navigation-class') || false,
                mouseDrag: isNoviBuilder ? false : carousel.attr('data-mouse-drag') !== 'false',
                nav: carousel.attr('data-nav') === 'true',
                dots: carousel.attr('data-dots') === 'true',
                dotsEach: carousel.attr('data-dots-each') ? parseInt(carousel.attr('data-dots-each'), 10) : false,
                animateIn: carousel.attr('data-animation-in') ? carousel.attr('data-animation-in') : false,
                animateOut: carousel.attr('data-animation-out') ? carousel.attr('data-animation-out') : false,
                responsive: responsive,
                navText: carousel.attr('data-nav-text') ? $.parseJSON(carousel.attr('data-nav-text')) : ['<svg width="57" height="63" viewBox="0 0 57 63" fill="none">\n' +
                '<path d="M22.5 2.4641C26.2128 0.320508 30.7872 0.320508 34.5 2.4641L50.6458 11.7859C54.3586 13.9295 56.6458 17.891 56.6458 22.1782V40.8218C56.6458 45.109 54.3586 49.0705 50.6458 51.2141L34.5 60.5359C30.7872 62.6795 26.2128 62.6795 22.5 60.5359L6.35417 51.2141C2.64136 49.0705 0.354174 45.109 0.354174 40.8218L0.354174 22.1782C0.354174 17.891 2.64136 13.9295 6.35417 11.7859L22.5 2.4641Z" fill="#FDB818"/>\n' +
                '</svg>', '<svg width="57" height="63" viewBox="0 0 57 63" fill="none">\n' +
                '<path d="M22.5 2.4641C26.2128 0.320508 30.7872 0.320508 34.5 2.4641L50.6458 11.7859C54.3586 13.9295 56.6458 17.891 56.6458 22.1782V40.8218C56.6458 45.109 54.3586 49.0705 50.6458 51.2141L34.5 60.5359C30.7872 62.6795 26.2128 62.6795 22.5 60.5359L6.35417 51.2141C2.64136 49.0705 0.354174 45.109 0.354174 40.8218L0.354174 22.1782C0.354174 17.891 2.64136 13.9295 6.35417 11.7859L22.5 2.4641Z" fill="#FDB818"/>\n' +
                '</svg>'],
                navClass: carousel.attr('data-nav-class') ? $.parseJSON(carousel.attr('data-nav-class')) : ['owl-prev', 'owl-next']
            });
        }

        // Copyright Year (Evaluates correct copyright year)
        if (plugins.copyrightYear.length) {
            plugins.copyrightYear.text(initialDate.getFullYear());
        }

        // UI To Top
        if (isDesktop && !isNoviBuilder) {
            $().UItoTop({
                easingType: 'easeOutQuad',
                containerClass: 'ui-to-top fa fa-angle-up'
            });
        }

        // Owl carousel
        if (plugins.owl.length) {
            for (var i = 0; i < plugins.owl.length; i++) {
                var carousel = $(plugins.owl[i]);
                plugins.owl[i].owl = carousel;
                initOwlCarousel(carousel);
            }
        }

        // WOW
        if ($html.hasClass("wow-animation") && plugins.wow.length && !isNoviBuilder && isDesktop) {
            new WOW().init();
        }

        if (plugins.parallaxScroll.length && !isNoviBuilder && !isMobile) {
            ParallaxScroll.init();
        }


    });
}());
