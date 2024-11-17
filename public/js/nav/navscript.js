'use strict';

$(function() {


  const overlay = $('[data-overlay]');
  const navOpenBtn = $("[data-nav-open-btn]");
  const navbar = $("[data-navbar]");
  const navCloseBtn = $("[data-nav-close-btn]");
  const header = $("[data-header]");
  const pfDrop = $("[pf-dropdown]");
  
  const navElems = [overlay, navOpenBtn, navCloseBtn];
  
  navElems.forEach(function(elem, idx) {
    $(elem).on('click', function(){
      navbar.toggleClass("active");
      overlay.toggleClass("active");

      if($(window).scrollTop() >= 20){
        header.css({"backgroundColor":"#FFFFFF", "opacity" : "1"});
      }
    })
  })

  // pfDrop.on("click", function() {
  //   header.css({"top" : "40px", "opacity" : "1"});
  // })

  
  
  /**
   * header active on page scroll
   */
  
  $(window).scroll(function(){
    var pos = $(window).scrollTop();
    if(navbar.hasClass("active")){
      header.removeClass("active");
      header.css("backgroundColor", "transparent");
      header.css({"backgroundColor":"#FFFFFF", "opacity" : "1"});
    } else {
      if(pos >= 20){
        header.addClass("active");
        header.css({"top" : "10px", "opacity" : "0.35"});
        
        header.hover( function() {
          header.css({"opacity" : "1"});
        }, function() {
          header.css({"opacity" : "0.35"});
        })

      } else {
        header.removeClass("active");
        header.css({"top" : "40px", "opacity" : "1"});
        header.unbind("mouseenter mouseleave");
      }
    }
  })

})