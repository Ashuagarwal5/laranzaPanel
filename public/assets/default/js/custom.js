
/* checkout js */
$(".showlogin").click(function(){
  $(".login-card").slideToggle();
});
$(".cr-acc").click(function(){
  $(".cr-account").slideToggle();
});
$(".ship-add").click(function(){
  $(".shipp-add").slideToggle();
});

$(".ship-add-user").change(function(){
	
	$('.shipping_address_input').prop('checked',false);
	
	
  $(".shipp-add").slideToggle();
});

$(".shipping_address_input").change(function(){
	
	 $('.ship-add-user').prop('checked',false);
     $(".shipp-add").hide();
});



$(".check-add").click(function(){
  $(".billing-address").slideUp();
});
/* cart js */
(function(){
 
 /* $("#cart").on("click", function() {
    $(".shopping-cart").fadeToggle( "fast");
  }); */
  
  $(document).on("click", function () {
    $(".shopping-cart").hide();
});
$(".shopping-cart").on("click", function (event) {
    event.stopPropagation();
});
$("#cart").on("click", function (event) {
    event.stopPropagation();
    $(".shopping-cart").slideToggle("fast");
});
  
  
})();
/* cart js end */
/* Menu js start */
(function($) {
$.fn.menumaker = function(options) {  
 var cssmenu = $(this), settings = $.extend({
   format: "dropdown",
   sticky: false
 }, options);
 return this.each(function() {
   $(this).find(".button").on('click', function(){
     $(this).toggleClass('menu-opened');
     var mainmenu = $(this).next('ul');
     if (mainmenu.hasClass('open')) { 
       mainmenu.slideToggle().removeClass('open');
     }
     else {
       mainmenu.slideToggle().addClass('open');
       if (settings.format === "dropdown") {
         mainmenu.find('ul').show();
       }
     }
   });
   cssmenu.find('li ul').parent().addClass('has-sub');
multiTg = function() {
     cssmenu.find(".has-sub").prepend('<span class="submenu-button"></span>');
     cssmenu.find('.submenu-button').on('click', function() {
       $(this).toggleClass('submenu-opened');
       if ($(this).siblings('ul').hasClass('open')) {
         $(this).siblings('ul').removeClass('open').slideToggle();
       }
       else {
         $(this).siblings('ul').addClass('open').slideToggle();
       }
     });
   };
   if (settings.format === 'multitoggle') multiTg();
   else cssmenu.addClass('dropdown');
   if (settings.sticky === true) cssmenu.css('position', 'fixed');
resizeFix = function() {
  var mediasize = 1000;
     if ($( window ).width() > mediasize) {
       cssmenu.find('ul').show();
     }
     if ($(window).width() <= mediasize) {
       cssmenu.find('ul').hide().removeClass('open');
     }
   };
   resizeFix();
   return $(window).on('resize', resizeFix);
 });
  };
})(jQuery);

(function($){
$(document).ready(function(){
$("#cssmenu").menumaker({
   format: "multitoggle"
});
});
})(jQuery);

/* Menu js end */

/* slider product */
// Init fancyBox
$().fancybox({
  selector : '.slick-slide:not(.slick-cloned)',
  hash     : false
});

// Init Slick
$(".main-slider").slick({
  slidesToShow   : 4,
  slidesToScroll : 2,
  autoplay: true,
    autoplaySpeed: 3000,
  infinite : true,
  dots     : false,
  arrows   : false,
  responsive : [
    {
      breakpoint : 960,
      settings : {
        slidesToShow   : 1,
        slidesToScroll : 1
      }
    }
  ]
});



/* product zoom */
new Drift(document.querySelector('.drift-demo-trigger-0'), {
  paneContainer: document.querySelector('.details'),
  inlinePane: 769,
  inlineOffsetY: -85,
  containInline: true,
  hoverBoundingBox: true
});
new Drift(document.querySelector('.drift-demo-trigger-1'), {
  paneContainer: document.querySelector('.details'),
  inlinePane: 769,
  inlineOffsetY: -85,
  containInline: true,
  hoverBoundingBox: true
});
new Drift(document.querySelector('.drift-demo-trigger-2'), {
  paneContainer: document.querySelector('.details'),
  inlinePane: 769,
  inlineOffsetY: -85,
  containInline: true,
  hoverBoundingBox: true
});
new Drift(document.querySelector('.drift-demo-trigger-3'), {
  paneContainer: document.querySelector('.details'),
  inlinePane: 769,
  inlineOffsetY: -85,
  containInline: true,
  hoverBoundingBox: true
});
new Drift(document.querySelector('.drift-demo-trigger-4'), {
  paneContainer: document.querySelector('.details'),
  inlinePane: 769,
  inlineOffsetY: -85,
  containInline: true,
  hoverBoundingBox: true
});
new Drift(document.querySelector('.drift-demo-trigger-5'), {
  paneContainer: document.querySelector('.details'),
  inlinePane: 769,
  inlineOffsetY: -85,
  containInline: true,
  hoverBoundingBox: true
});
new Drift(document.querySelector('.drift-demo-trigger-6'), {
  paneContainer: document.querySelector('.details'),
  inlinePane: 769,
  inlineOffsetY: -85,
  containInline: true,
  hoverBoundingBox: true
});
new Drift(document.querySelector('.drift-demo-trigger-7'), {
  paneContainer: document.querySelector('.details'),
  inlinePane: 769,
  inlineOffsetY: -85,
  containInline: true,
  hoverBoundingBox: true
});
new Drift(document.querySelector('.drift-demo-trigger-8'), {
  paneContainer: document.querySelector('.details'),
  inlinePane: 769,
  inlineOffsetY: -85,
  containInline: true,
  hoverBoundingBox: true
});
new Drift(document.querySelector('.drift-demo-trigger-9'), {
  paneContainer: document.querySelector('.details'),
  inlinePane: 769,
  inlineOffsetY: -85,
  containInline: true,
  hoverBoundingBox: true
});


