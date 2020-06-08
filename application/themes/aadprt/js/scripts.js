// --- SWUP TRANSITIONS --- //
$(document).ready(function(){
	
	 //-- SR(05/21/2020) chart #74245 --
   $('[data-toggle="popover"]').popover();
   
	 // -- MENU MOBILE --//
	  $("#btn-menu" ).click(function(){
			$("#menu").toggleClass("open");
			$("#btn-menu").toggleClass("open");
	  });
	  $("#btn-close" ).click(function(){
			$("#menu").toggleClass("open");
	  });
	   $(window).scroll(function() {
		  var x = $(this).scrollTop();
		  $(".parallax-1").css("-webkit-transform","translateY(" +  (x/1.25)  + "px)");
		  $(".parallax-2").css("-webkit-transform","translateY(" +  (x/2)  + "px)");
	  });
		  
	  $("#menu-slide a" ).click(function(){
		  $("#menu-slide").toggleClass("open");
		  $(".btn-menu").toggleClass("btn-close");
	  });
    
    //SR(05/15/2020) -- ADD POPOVER --- //
    $('[data-toggle="popover"]').popover();
  
    // -- ADD CLASS ON SCROLL --//
	$(window).scroll(function() {    
		var scroll = $(window).scrollTop();
		if (scroll > 25) {
			$("#site-header").addClass("scroll");
		} else {
			$("#site-header").removeClass("scroll");
		}
	});

$('.clients').slick({
  dots: false,
  infinite: true,
  arrows: false,
  speed: 1000,
  autoplay: true,
  autoplaySpeed: 500,
  
  slidesToShow: 5,
  slidesToScroll: 1,
  responsive: [
    {
      breakpoint: 1024,
      settings: {
        slidesToShow: 4,
        slidesToScroll: 1,
      }
    },
    {
      breakpoint: 600,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 1
      }
    },
    {
      breakpoint: 480,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1
      }
    }
    // You can unslick at a given breakpoint now by adding:
    // settings: "unslick"
    // instead of a settings object
  ]
});

});


// -- ANIMATE INTO VIEW -- //
$(document).on("scroll", function () {
  var pageTop = $(document).scrollTop()
  var pageBottom = pageTop + $(window).height()
  var tags = $(".animate-in")

  for (var i = 0; i < tags.length; i++) {
    var tag = tags[i]

    if ($(tag).position().top < pageBottom) {
      $(tag).addClass("visible")
    } 
  }
})

