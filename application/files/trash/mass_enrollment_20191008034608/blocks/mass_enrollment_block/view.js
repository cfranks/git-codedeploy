// alert("hello");
if($('.country').val() == "USA"){
        $(".stateDropdown").show();
        $(".stateText").hide();
} else if ($('.country').val() == ""){
    $(".stateDropdown").hide();
    $(".stateText").hide();
} else {
    $(".stateDropdown").hide();
    $(".stateText").show();
}
$('.country').change(function(){
    if($(this).val() == "USA"){
        $(".stateDropdown").show();
        $(".stateText").hide();
    } else if ($('.country').val() == ""){
        $(".stateDropdown").hide();
        $(".stateText").hide();
    } else {
        $(".stateDropdown").hide();
        $(".stateText").show();
    }
});