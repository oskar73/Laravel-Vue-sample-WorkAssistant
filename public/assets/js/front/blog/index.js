$(document).ready(function () {
  $('.all_post_area').load('/blog/ajaxPage')
  getBlogAds('home')
})
$(document).on('click', '.pagination a', function (e) {
  e.preventDefault()
  $('.all_post_area').load($(this).attr('href'))
})
