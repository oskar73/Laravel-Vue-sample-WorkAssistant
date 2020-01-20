var hash
var orderBy = 'featured'
var keyword = ''
var pageUrl = '/modules' + '?page=1'

$(document).ready(function() {
  hash = window.location.hash
  updateItems()
})
$(document).on('click', '.category-menu li a', function() {
  hash = $(this).attr('href')
  keyword = ''
  $('#keyword').val('')
  updateItems()
})
$(document).on('click', '.gotocart', function(e) {
  e.preventDefault()
  window.location.href = '/cart'
})
$(document).on('click', '.choose_module_btn', function(e) {
  e.preventDefault()
  var obj = $(this)
  var id = obj.data('id')
  var button = $(this)
  $.ajax({
    url: `/modules/${id}/addtocart`,
    data: { quantity: 1, price: 0 },
    success: function(result) {
      if (result.status === 0) {
        dispErrors(result.data)
      } else {
        itoastr('success', 'Successfully added!')
        button.html('Chosen')
        button.prop('disabled', true)
        var modules = $('.app_chosen_count').first().text()
        $('.app_chosen_count').html(Number(modules) + 1)
        viewModules()
      }
    },
    error: function(e) {
      console.log(e)
    }
  })
})

$(document).on('click', '.breadcrumb li a', function() {
  hash = $(this).attr('href')
  keyword = ''
  $('#keyword').val('')
  updateItems()
})

$(document).on('change', 'input[type=radio][name=filterBy]', function() {
  orderBy = $(this).val()
  updateItems()
})
$(document).on('keyup', '#keyword', function() {
  keyword = $(this).val()
  updateItems()
  resetActiveClass('all', 'all')
  hash = 'all'
})
$(document).on('click', '.pagination a.page-link', function(e) {
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
    success: function(result) {
      if (result.status === 1) {
        $('.modules_result').html(result.view)
      } else {
      }
    },
    error: function(e) {
      console.log(e)
    }
  })
}

function resetActiveClass(cat, sub) {
  $('.category-menu li').removeClass('menu-active')
  $('.category-menu .' + cat).addClass('menu-active')
  $('.category-menu .' + sub).addClass('menu-active')
}

$(document).on('click', '.video_btn', function(e) {
  var video = $(this).data('video')
  var youtube = $(this).data('youtube')
  if (youtube) {
    const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/
    const match = youtube.match(regExp)

    if (match && match[2].length == 11) {
      youtube = match[2]
    }
  }

  $('#video_content').html(video ? `
    <video controls>
      <source src="${video}">
      Your browser does not support HTML5 video.
    </video>
  ` : `
    <iframe class="iframe-video" src="https://www.youtube.com/embed/${youtube}" frameborder="0" allowfullscreen></iframe>
  `)
  $('#video_modal').modal('toggle')
})
$(document).on('click', '.view_btn', function(e) {
  var id = $(this).data('id')

  $.ajax({
    url: '/modules/module-detail',
    data: { id },
    success: function(result) {
      if (result.status) {
        $('#view_content').html(result.data)
        $('#view_modal').modal('toggle')
      }
    },
    error: function(e) {
      console.log(e)
    }
  })
})
$(document).on('click', '.apps_btn', function(e) {
  $('#apps_modal').modal('show')
})

viewModules()

$(document).on('click', '.del_module_btn', function(e) {
  var module = $(this).data('module')
  $.ajax({
    url: `/modules/deltocart`,
    data: { module },
    success: function(result) {
      if (result.status === 0) {
        dispErrors(result.data)
      } else {
        itoastr('success', 'Successfully deleted!')
        viewModules()
        updateItems()
      }
    },
    error: function(e) {
      console.log(e)
    }
  })
})

function viewModules(showModel = false) {
  $.ajax({
    url: '/modules/selected-modules',
    success: function(result) {
      if (result.status) {
        $('#apps_content').html(result.data.modal)
        $('#sidebar_apps_content').html(result.data.sidebar)
        if (showModel) {
          $('#apps_modal').modal('show')
        }
      }
    },
    error: function(e) {
      console.log(e)
    }
  })
}
