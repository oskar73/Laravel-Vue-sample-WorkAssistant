var hash
var orderBy = 'featured'
var keyword = ''
var pageUrl = '/readymade' + '?page=1'

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

$(document).on('click', '.breadcrumb li a', function () {
  hash = $(this).attr('href')
  keyword = ''
  $('#keyword').val('')
  updateItems()
})

$(document).on('change', 'input[type=radio][name=filterBy]', function () {
  orderBy = $(this).val()
  updateItems()
})

$(document).on('click', '.gotocart', function (e) {
  e.preventDefault()
  window.location.href = '/cart'
})
$(document).on('click', '.add_to_cart', function (e) {
  e.preventDefault()
  var obj = $(this)
  var id = obj.data('id')
  obj.append('<i class="loading_div fas fa-spinner fa-spin fa-fw"></i>')
  $.ajax({
    url: `/readymade/${id}/addtocart`,
    data: { quantity: 1, price: 0 },
    success: function (result) {
      if (result.status === 0) {
        dispErrors(result.data)
      } else {
        itoastr('success', 'Successfully added!')
        $('.header_cart_area').html(result.data)
        obj.toggleClass('d-none')
        obj.next().toggleClass('d-none')
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
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
  var cat = a[2] === undefined ? temp : a[2]

  resetActiveClass(temp, cat)

  $.ajax({
    url: pageUrl,
    data: { category: cat, orderBy: orderBy, keyword: keyword },
    success: function (result) {
      console.log(result)
      if (result.status === 1) {
        $('.items_result').html(result.view)
      } else {
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
