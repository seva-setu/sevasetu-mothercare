/**
* IE Check
* @returns {Number}
*/
function isIE () {
    var myNav = navigator.userAgent.toLowerCase();
    return (myNav.indexOf('msie') != -1) ? parseInt(myNav.split('msie')[1]) : false;
} 
    
jQuery(function() {
    /**
         * initialize scroll settings
         * - sticky header
         * - scroll up btn
         */
    var initTylerScroll = function() {
        var header = jQuery('header'),
        scrollTop = jQuery(document).scrollTop(),
        documentHeight = jQuery(document).height(),
        windowHeight = jQuery(window).height(),
        windowWidth = jQuery(window).width(),
        limit = documentHeight - header.height() < windowHeight
        ? header.height()
        : 1;

        // only for "medium" devices
        if(windowWidth<992)  {
            jQuery('#scroll-up').fadeOut();
            header.removeClass('sticky');
            jQuery(document.body).removeClass('header-sticky');
            return;
        }

        // show "scroll up" btn
        if(windowWidth > 1024 && scrollTop >= windowHeight/2) {
            jQuery('#scroll-up').fadeIn();
        }
        else {
            jQuery('#scroll-up').fadeOut();
        }

        // sticky header
        if (scrollTop >= limit) {
            header.addClass('sticky');
            jQuery(document.body).addClass('header-sticky');
        } else if (scrollTop <= limit) {
            header.removeClass('sticky');
            jQuery(document.body).removeClass('header-sticky');
        }
    }
    jQuery(window).ready(initTylerScroll);
    jQuery(window).scroll(initTylerScroll);
    jQuery(window).resize(initTylerScroll);

    // mobile menu expanded
    jQuery('#tyler-navigation')
    .on('show.bs.collapse', function () {
        jQuery(document.body)
        .addClass('header-menu-open')
        .attr('data-scroll', jQuery(document).scrollTop());
        window.scrollTo(0,0);
    })
    .on('hide.bs.collapse', function() {
        jQuery(document.body).removeClass('header-menu-open');
        window.scrollTo(0, jQuery(document.body).attr('data-scroll'));
    });


    /**
         * change element text size to fit it's dimension
         * @param el
         * @param rel
         */
    var textFit = function(el, rel) {
        rel = Math.min(1, rel || 1);
        el = jQuery(el);

        var elInner = el.find('.text-fit-inner')[0] ||
        el.wrapInner("<span class='text-fit-inner' style='display:block'></span>").find('.text-fit-inner')[0];
        elInner = jQuery(elInner);

        var maxW = Math.min(el.innerWidth(), parseInt(el.css('max-width'))),
        maxH = Math.min(el.innerHeight(), parseInt(el.css('max-height')));

        if(elInner.outerWidth() > maxW || elInner.outerHeight() > maxH) {
            rel *= 0.95;
            elInner.css('font-size', rel+'em');
            textFit(el, rel);
        }
    }
    if(!isIE() || isIE()>7) {
        var initTextFit = function() {
            jQuery('.text-fit').each(function(i, el) {
                textFit(el);
            });
        }
        initTextFit();
        jQuery(window).on('resize', initTextFit);
    }



    //Add Hover effect to menus
    if(jQuery(window).width()>=922) {
        jQuery('header #menu-primary .menu-item-has-children')
        .hover(function() {
            var ul = jQuery(this).find('>ul');
            ul.stop(true, true).delay(200).fadeIn({
                start: function() {
                    if(jQuery(this).parents('ul.sub-menu').length!=0 && ul.offset().left > jQuery(window).width() - ul.width() - 100) {
                        ul.css({
                            'left':'auto',
                            'right':'100%'
                        });
                    }
                }
            });


        }, function() {
            jQuery(this).find('>ul').stop(true, true).delay(200).fadeOut();
        });
    }

    // expand menu on click
    jQuery('header #menu-primary .menu-item-has-children')
    .click(function(event) {
        if(jQuery('.navbar-toggle').is(':visible')){
            if ( jQuery(this).hasClass('menu-item-has-children') ) {
            	event.preventDefault();
            	
            	// make responsive submenu elemts clickable
            	jQuery( '#menu-primary .sub-menu li a' ).on( 'click', function(e) {
            	    e.preventDefault();
            	    window.location.href = jQuery(this).attr('href');
            	});
            	
            }
            jQuery(this).toggleClass('expand');
        }
    });
    
    // Fix Mobile menu when click on register button
    jQuery('.menu-item.register').click(function(event){
    	jQuery('#tyler-navigation').addClass('collapse').css('height', '0px');
    	jQuery('#backdrop').hide();
    });

    // expand footer menu on click
    jQuery('footer .col h4')
    .click(function(event) {
        event.preventDefault();
        jQuery(this).parents('.col').toggleClass('expand');
    });

    // init scrollbars
    jQuery('.scrollable').jScrollPane();

    /**
         * placeholder fix
         * @see https://gist.github.com/hagenburger/379601
         */
    jQuery('[placeholder]').focus(function() {
        var input = jQuery(this);
        if (input.val() == input.attr('placeholder')) {
            input.val('');
            input.removeClass('placeholder');
        }
    }).blur(function() {
        var input = jQuery(this);
        if (input.val() == '' || input.val() == input.attr('placeholder')) {
            input.addClass('placeholder');
            input.val(input.attr('placeholder'));
        }
    }).blur().parents('form').submit(function() {
        jQuery(this).find('[placeholder]').each(function() {
            var input = jQuery(this);
            if (input.val() == input.attr('placeholder')) {
                input.val('');
            }
        })
    });
    
    // scroll button up
    jQuery('#scroll-up').click(function(){
        window.scrollTo(0, 0);
        return false;
    });
    
});