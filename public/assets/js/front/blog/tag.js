$(document).ready(function () {
  $('.all_tag_post_area').load('/blog/ajaxTag/' + tag_id)
  getBlogAds('category', tag_id)
})

$(document).on('click', '.all_tag_post_area .pagination a', function (e) {
  e.preventDefault()
  $('.all_tag_post_area').load($(this).attr('href'))
})
