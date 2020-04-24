jQuery(function($){
    var canBeLoaded = true,
        bottomOffset = 2000;

    $(window).scroll(function(){
        var data = {
            action: 'infinitescroll',
            page : params.current_page,
            atts : params.atts
        };
        
        if( $(document).scrollTop() > ( $(document).height() - bottomOffset ) && canBeLoaded == true ) {
            $.ajax({
                url : params.ajaxurl,
                data: data,
                type:'POST',
                beforeSend: function( xhr ){
                        canBeLoaded = false; 
                },
                success:function(data){
                    console.log(data);
                        if( data ) {
                            $('.strong-content').find('.wpmtst-testimonial:last').after( data ); 
                            canBeLoaded = true;
                            params.current_page++;
                        }
                }
            });
        }
    });
});