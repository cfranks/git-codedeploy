var scrollSubmission = true;

$('.g-recaptcha').closest('form').submit(function(event) {
    if($('[name=g-recaptcha-response]').val() == '') {
        event.preventDefault();
        grecaptcha.execute();
    }
});

$('form.formify-form [type=submit], [data-submit="conversation-message"]').click(function(event) {
    event.preventDefault();
    grecaptcha.execute();
}); 

function onSubmit(token) {
    $('.g-recaptcha').closest('form').data("g-recaptcha-response", token).data("recaptcha-verified", true).submit();
}

function clearRecaptcha() {
     $(document).ajaxComplete(function() {
     
     	if($('.error, .formify-error').length > 0) {
		    grecaptcha.reset();
        }
            
	    setTimeout(function() {
            if($('.formify-message').length > 0 && scrollSubmission) {
                $('html, body').animate({
                    scrollTop: $(".formify-message").offset().top - 120
                }, 500);
                scrollSubmission = false;
            }
	    }, 500);
     });
}