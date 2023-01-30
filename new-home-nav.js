jQuery(document).ready(function ($) {
$(window).scroll(function() {
var scroll = $(window).scrollTop();
var topPosition = 0;

if (scroll >= 110 && $(window).width() > 1600) {
$(".pa-header").addClass("pa-fixed-header");
}
else{
	if(scroll >= 100){
		
		$(".pa-header").addClass("hide-header");
	}else{
		$(".pa-header").removeClass("hide-header");
		$(".pa-header").removeClass("pa-fixed-header");
	}
}
	//else{
//$(".pa-header").removeClass("pa-fixed-header");
//		$(".pa-header").addClass("hide-header");
//}
});
});