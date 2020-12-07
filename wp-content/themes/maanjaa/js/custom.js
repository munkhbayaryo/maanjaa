jQuery.noConflict()(function($) {
$(window).load(function() {
    "use strict";
    
    $('.switcher').click(function() {
      $(".product-loader-wrapper").show().delay(1000).fadeOut();
    });
});

$(document).ready(function(){

  $('.products-wrapper').addClass('grid-mode');

  var mode = localStorage.getItem('mode');
  
  if(mode){
      $('.products-wrapper').addClass(mode === 'grid-mode' ? 'grid-mode' : 'list-mode');
      $('.products-wrapper').removeClass(mode === 'list-mode' ? 'grid-mode' : 'list-mode');
  }
  
  $('#listview').click(function(e) {
    e.preventDefault();
    $('.products-wrapper').removeClass('grid-mode');
    $('.products-wrapper').addClass('list-mode');
    $('#listview').addClass('active');
    $('#gridview').removeClass('active');
    localStorage.setItem('mode', 'list-mode');
  });
  $('#gridview').click(function(e) {
    e.preventDefault();
    $('.products-wrapper').removeClass('list-mode');
    $('.products-wrapper').addClass('grid-mode');
    $('#gridview').addClass('active');
    $('#listview').removeClass('active');
    localStorage.setItem('mode', 'grid-mode');
  });

  $('div.brands-list').listnav();

    $('#show-filter').on( 'click', function() {
        $('.canvas-filter').toggleClass('open');
        $('.canvas-overlay').addClass('open');
    });

    $('.canvas-overlay').on( 'click', function() {
        $('.canvas-filter').removeClass('open');
        $('.canvas-overlay').removeClass('open');
    });

});


/*=========================================================================
            Home Slider
=========================================================================*/
$(document).ready(function() {
    "use strict";
  
    

});

$(function(){
    "use strict";

    /*=========================================================================
            Scroll to Top
    =========================================================================*/
    $(window).scroll(function() {
        if ($(this).scrollTop() >= 250) {        // If page is scrolled more than 50px
            $('#return-to-top').fadeIn(200);    // Fade in the arrow
        } else {
            $('#return-to-top').fadeOut(200);   // Else fade out the arrow
        }
    });
    $('#return-to-top').click(function() {      // When arrow is clicked
        $('body,html').animate({
            scrollTop : 0                       // Scroll to top of body
        }, 400);
    });

});
});