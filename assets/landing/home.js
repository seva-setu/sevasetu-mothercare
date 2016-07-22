function initGallery(){
    // init gallery
    if(!isIE() || isIE() > 8) {
        var mediaboxLinks = jQuery('.mediabox a.post');
        mediaboxLinks.click(function (event) {
            event.preventDefault();
            blueimp.Gallery(mediaboxLinks, {
                index: this,
                event: event,
                titleElement: 'strong',
                youTubeClickToPlay: false
            });
        });
    }
    else {
        jQuery('.mediabox a.post').attr('target','_blank');
    }   
}  

jQuery(function(){

    jQuery('#tile_media .btn-group-header .btn').click(function(){
        jQuery('#tile_media .btn-group-header .btn').removeClass('active');
        jQuery(this).addClass('active');
        jQuery('#tile_media .carousel-indicators').empty();
        jQuery('#tile_media .carousel-inner').empty();
    });

    jQuery('#tile_media .btn-group-header .btn').click(function(e){
        var data_id = jQuery(this).attr('data-id');
        
        jQuery.ajax({
            type: "POST",
            dataType: "json",
            url: ajaxurl,
            data: {
                'action': 'get_media',
                'data-id': data_id
            },
            success: function(data) {
                var medias = '';
                if(data.media)
                    jQuery.each(data.media, function(index, media_chunk) {
                        var class_active = index == 0 ? ' class="active"' : '';
                        var active = index == 0 ? 'active' : '';
                        jQuery('#tile_media .carousel-indicators').append('<li data-target="#mediabox-carousel" data-slide-to="' + index + '"' + class_active + '></li>');
                        medias += '<div class="item ' + active + '">';
                        for(var key in media_chunk){
                            var media = media_chunk[key];

                            if(media.post_video_code != '')
                                medias += '<a class="post video" ' + media.post_video_attributes + '">';
                            else
                                medias += '<a class="post" href="' + media.post_image_big_url + '">';
                            medias += '<span class="wrapper">' + media.post_image + '</span>';
                            medias += '<span class="desc">';
                            medias += '<strong>' + media.post_title + '</strong>';
                            medias += media.post_content;
                            medias += '</span>';
                            medias += '</a>';
                        }
                        medias += '</div>';
                    });
                jQuery('#tile_media .carousel-inner').append(medias);
                initGallery();
            }
        });
        
        e.preventDefault();
    });
    
    jQuery('#tile_media .btn-group-header .btn:first').trigger('click');
    
    

    jQuery('#tile_contact form').submit(function() {
        var hasError = false;
        jQuery('.error', this).remove();
        jQuery('.requiredField', this).each(function() {
            if(jQuery.trim(jQuery(this).val()) == '') {
                jQuery(this).parent().append('<span class="error">' + contact_missingfield_error + '</span>');
                jQuery(this).addClass('inputError');
                hasError = true;
            } else if(jQuery(this).hasClass('email')) {
                var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?jQuery/;
                if(!emailReg.test(jQuery.trim(jQuery(this).val()))) {
                    jQuery(this).parent().append('<span class="error">' + contact_wrongemail_error+ '</span>');
                    jQuery(this).addClass('inputError');
                    hasError = true;
                }
            }
        });
        if(!hasError) {
            jQuery('#tile_contact .alert, #tile_contact .info').remove();
            jQuery.ajax({
                url: ajaxurl,
                data: jQuery(this).serialize(),
                dataType: 'json',
                type: 'POST',
                success: function(data) {
                    if(data.sent == true)
                        jQuery('#tile_contact form').slideUp("fast", function() {
                            jQuery('#tile_contact form').before('<p class="info">' + data.message + '</p>');
                        });
                    else
                        jQuery('#tile_contact form').before('<p class="alert">' + data.message + '</p>');
                },
                error: function(data) {
                    jQuery('#tile_contact form').before('<p class="alert">' + data.message + '</p>');
                }
            });
        }
        return false;	
    });

});