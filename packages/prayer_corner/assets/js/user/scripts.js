// JavaScript Document

jQuery(document).ready(function(){
    function resizeForm(){
        var width = (window.innerWidth > 0) ? window.innerWidth : document.documentElement.clientWidth;
        if(width > 1024){
		$('[data-type="parallax"]').each(function(){
		  var $bgobj = $(this);
		  $(window).scroll(function(){
			  var yPos = $(window).scrollTop();
			  $bgobj.css('background-position','50%' + (yPos*0.2-125) +'px');
		  });
		});	
        } else {

        }    
    }
    window.onresize = resizeForm;
    resizeForm();
});

$(function(){
    $('.card-select input[type=radio]').change(function() {
       $(this).closest('div')
              .addClass('active')
              .siblings('div')
              .removeClass('active');              
    });
});
 	
$(window).scroll(function() {
	  var x = $(this).scrollTop();
	  $(".parallax-1").css("-webkit-transform","translateY(" +  (x/2)  + "px)");
	  $(".parallax-2").css("-webkit-transform","translateY(" +  (x/1.75)  + "px)");
});

// Language Dropdown //
$("#lang" ).click(function(){
		$("#hdr-lang-list").toggleClass("open");
		$("#lang").toggleClass("open");
  });

// Card Image Select Modal //
$("#card-modal-btn" ).click(function(){
	  $(".card-modal").addClass("open");
});
$(".card-close" ).click(function(){
	  $(".card-modal").removeClass("open");
});

// Language Dropdown //
$("#card-flip" ).click(function(){
		$(".card-flip").toggleClass("hover");
});
  
// EXPLORE SCROLL //
(function($) {
$('a[href*=\\#]:not([href=\\#])').click(function() 
	{
	  if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') 
		  || location.hostname == this.hostname) 
	  {
		
		var target = $(this.hash),
		headerHeight = $("#header").height() + 30; // Get fixed header height
			  
		target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
				
		if (target.length) 
		{
		  $('html,body').animate({
			scrollTop: target.offset().top - headerHeight
		  }, 1000,'swing');
		  return false;
		}
	  }
	});
  })(jQuery);

