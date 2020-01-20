$(document).ready(function () {
  $('.all_category_post_area').load('/blog/ajaxCategory/' + category_id)
  getBlogAds('category', category_id)
})

$(document).on('click', '.all_category_post_area .pagination a', function (e) {
  e.preventDefault()
  $('.all_category_post_area').load($(this).attr('href'))
})
