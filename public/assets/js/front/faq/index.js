var hash
var keyword = ''
var pageUrl = '/faq' + '?page=1'

$(document).ready(function () {
  hash = window.location.hash
  updateItems()
})
$(document).on('click', '.category-menu li a', function () {
  hash = $(this).attr('href')
  keyword = ''
  $('#keyword').val('')
  updateItems()
})
$(document).on('click', '.clickable-item a', function () {
  hash = $(this).attr('href')
  keyword = ''
  $('#keyword').val('')
  updateItems()
})
$(document).on('change', 'input[type=radio][name=filterBy]', function () {
  orderBy = $(this).val()
  updateItems()
})
$(document).on('keyup', '#keyword', function () {
  keyword = $(this).val()
  updateItems()
  resetActiveClass('all', 'all')
  hash = 'all'
})
$(document).on('click', '.pagination a.page-link', function (e) {
  e.preventDefault()
  pageUrl = $(this).attr('href')
  updateItems()
})
function updateItems() {
  var a = hash.split('/')

  var temp = a[1] === undefined ? 'all' : a[1]
  var item = a[2] === undefined ? null : a[2]

  resetActiveClass(temp, item)

  $.ajax({
    url: pageUrl,
    data: { category: temp, item: item, keyword: keyword },
    success: function (result) {
      console.log(result)
      if (result.status === 1) {
        $('.items_result').html(result.view)
        $('.lightgallery').lightGallery()
      } else {
        // itoastr("info", "Something went wrong!");
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
}
function resetActiveClass(cat, sub) {
  $('.category-menu li').removeClass('menu-active')
  $('.category-menu .' + cat).addClass('menu-active')
  $('.category-menu .' + sub).addClass('menu-active')
}
