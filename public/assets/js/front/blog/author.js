$(document).ready(function () {
  $('.all_author_post_area').load('/blog/ajaxAuthor/' + username)
})

$(document).on('click', '.all_author_post_area .pagination a', function (e) {
  e.preventDefault()
  $('.all_author_post_area').load($(this).attr('href'))
})
