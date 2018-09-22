(function($) {
    $("#home-contact").on('click', function(e) { 
        e.preventDefault();   
        $('#home-contact-form').slideToggle('slow');
    });
})( jQuery );