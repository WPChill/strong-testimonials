jQuery(document).ready(function () {
    jQuery('.strong-view').randomize('.wpmtst-testimonial');
});

jQuery.fn.randomize = function(selector){
    (selector ? this.find(selector) : this).parent().each(function(){
        jQuery(this).children(selector).sort(function(){
            return Math.random() - 0.5;
        }).detach().appendTo(this);
    });

    return this;
};