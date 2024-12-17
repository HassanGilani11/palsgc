jQuery(window).bind('load', function() {
    // Get the modal
    var modal = jQuery('#modalDialog');

    // Get the button that opens the modal
    var btn = jQuery("#mbtn");

    // Get the <span> element that closes the modal
    var span = jQuery(".close");

    debugger;
    // When the user clicks the button, open the modal 
    btn.on('click', function() {
        modal.show();
    });
    
    // When the user clicks on <span> (x), close the modal
    span.on('click', function() {
        modal.fadeOut();
    });

});



// When the user clicks anywhere outside of the modal, close it
jQuery('body').bind('click', function(e){
    if(jQuery(e.target).hasClass("modal")){
        modal.fadeOut();
    }
});