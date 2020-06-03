function chnCountry()
{
    if($('.country').val() == "US" || $('.country').val() == "CA"){
        $(".stateDropdown").show();
        $(".stateText").hide();
    } else if ($('.country').val() == ""){
        $(".stateDropdown").hide();
        $(".stateText").show();
    } else {
        $(".stateDropdown").hide();
        $(".stateText").show();
    }
}
chnCountry();
$('.country').change(function(){
    chnCountry()
});
$('.datepickercustom').datepicker({
    dateFormat: 'm/d/yy'
});

$('[data-toggle="tooltip"]').tooltip();

$(document).ready(function() {
    if ($(".alert").length) {
        $('html, body').animate({
            scrollTop: $(".alert").offset().top - 120
        }, 500);
    } 
  if($('#loading').length) {
        $('#loading').hide();
  }    
  $('#payment_form').submit(function(e) {  
     $('#loading').show(); 
  });  
});

function displayLoader()
{
  document.getElementById("loading").style.display = "block";
}

