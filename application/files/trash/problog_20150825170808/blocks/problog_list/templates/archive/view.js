$(document).ready(function(){
	$('#sidebar-archives h4').click(function(){
		$('.archived_list').slideUp();
		$(this).next('.archived_list').slideDown();
	});
});