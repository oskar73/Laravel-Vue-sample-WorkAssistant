$(function () {
  var hash = window.location.hash
  updateContent(hash)
})
function updateContent(hash) {
  var a = hash.split('/')
  var temp = a[1] === undefined ? 'basic' : a[1]
  var item = a[2] === undefined ? null : a[2]

  resetActiveClass(temp)

  $.ajax({
    url: '/account/tutorial/getData',
    data: { module: temp, tutorial: item },
    success: function (result) {
      if (result.status === 1) {
        $('.result_area').html(result.data)
      }
    }
  })
}
$('.tutorial_category').click(function () {
  var href = $(this).attr('href')
  updateContent(href)
})

$(document).on('click', '.tutorial_item', function () {
  var href = $(this).attr('href')
  updateContent(href)
})
function resetActiveClass(cat) {
  $('.tab-item .tab-link').removeClass('tab-active')
  $('.tab-item .tab-link[data-area="#' + cat + '"]').addClass('tab-active')
}
