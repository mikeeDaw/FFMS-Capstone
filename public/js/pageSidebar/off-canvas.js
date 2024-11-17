// (function($) {
//   'use strict';
//   $(function() {
//     $('[data-toggle="offcanvas"]').on("click", function() {
//       $('.sidebar-offcanvas').toggleClass('active')
//     });
//   });
// })(jQuery);


'use strict';

const oc_btn = document.querySelector("[offcanvas]");
const pg_side = document.querySelector("[page-sidebar]")

oc_btn.addEventListener("click", function () {
  pg_side.classList.toggle("active");

});