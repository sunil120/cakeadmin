$(document).ready(function(){
    if($('.error-message').length) {
        $('html, body').animate({
           scrollTop: $(".error-message:first").offset().top
       }, 2000);
    }
   
    $(".sub-module").each(function(){
        if($(this).hasClass('active')) {
            $(this).parent('ul').parent('li').addClass('active');
        }
    }) 
    
});


