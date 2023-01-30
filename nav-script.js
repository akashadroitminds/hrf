jQuery(document).ready(function ($) {
$(window).scroll(function() {
var scroll = $(window).scrollTop();

if (scroll >= 100 ) {
$(".pa-header").addClass("pa-fixed-header");
}
else{
$(".pa-header").removeClass("pa-fixed-header");
}
});
});

