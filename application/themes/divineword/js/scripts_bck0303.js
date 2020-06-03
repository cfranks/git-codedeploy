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

/* Mobile Header Affix */
$(window).scroll(function() {    
	var scroll = $(window).scrollTop();
	if (scroll > 169) {
		$("#header").addClass("scroll");
	} else {
		$("#header").removeClass("scroll");
	}
});	

$(window).scroll(function() {
	  var x = $(this).scrollTop();
	  $(".parallax-1").css("-webkit-transform","translateY(" +  (x/2)  + "px)");
	  $(".parallax-2").css("-webkit-transform","translateY(" +  (x/1.75)  + "px)");
});

// When the user scrolls down 20px from the top of the document, show the button
	window.onscroll = function() {scrollFunction()};
	
	function scrollFunction() {
		if (document.body.scrollTop > 200 || document.documentElement.scrollTop > 200) {
			document.getElementById("btn-top").style.opacity = "1";
		} else {
			document.getElementById("btn-top").style.opacity = "0";
		}
	}
	
	// When the user clicks on the button, scroll to the top of the document
	function scrollToTop(scrollDuration) {
	const   scrollHeight = window.scrollY,
			scrollStep = Math.PI / ( scrollDuration / 15 ),
			cosParameter = scrollHeight / 2;
	var     scrollCount = 0,
			scrollMargin,
			scrollInterval = setInterval( function() {
				if ( window.scrollY != 0 ) {
					scrollCount = scrollCount + 1;  
					scrollMargin = cosParameter - cosParameter * Math.cos( scrollCount * scrollStep );
					window.scrollTo( 0, ( scrollHeight - scrollMargin ) );
				} 
				else clearInterval(scrollInterval); 
			}, 15 );
	}
	
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
		headerHeight = $("#header").height() - 30; // Get fixed header height
			  
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

