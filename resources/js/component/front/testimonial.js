import 'owl.carousel'
;(function () {
  $(function () {
    $('.testimonials-style1').owlCarousel({
      loop: true,
      responsiveClass: true,
      nav: true,
      dots: false,
      autoplay: true,
      margin: 0,
      navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"],
      responsive: { 0: { items: 1 }, 600: { items: 1 }, 1000: { items: 1 } }
    })
  })
})()
