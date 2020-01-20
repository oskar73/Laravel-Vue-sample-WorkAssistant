var pageUrl = '/package' + '?page=1'

$(document).ready(function() {
  updateItems()
  viewSelectedModules()
  viewAvailableModules()
})

$(document).on('click', '.gotocart', function(e) {
  e.preventDefault()
  window.location.href = '/cart'
})
$(document).on('click', '.add_to_cart', function(e) {
  e.preventDefault()
  var obj = $(this)
  var id = obj.data('id')
  var limit = obj.parent().parent().parent().parent().data('limit')
  if (limit >= 0 && limit < selectedAppsCount) {
    itoastr('info', `Please note this has a limit of ${limit} Apps and your chosen Apps are greater than 5`)
  }
  obj.append('<i class="loading_div fas fa-spinner fa-spin fa-fw"></i>')
  $.ajax({
    url: `/package/${id}/addtocart`,
    data: { quantity: 1, price: 0 },
    success: function(result) {
      if (result.status === 0) {
        dispErrors(result.data)
      } else {
        itoastr('success', 'Successfully added!')
        $('.header_cart_area').html(result.data)
        obj.toggleClass('d-none')
        obj.next().toggleClass('d-none')
      }
    },
    error: function(e) {
      console.log(e)
    }
  })
})

$(document).on('click', '.pagination a.page-link', function(e) {
  e.preventDefault()
  pageUrl = $(this).attr('href')
  updateItems()
})

function updateItems() {
  $.ajax({
    url: pageUrl,
    success: function(result) {
      if (result.status === 1) {
        $('.items_result').html(result.view)
      }
    },
    error: function(e) {
      console.log(e)
    }
  })
}

function viewSelectedModules() {
  $.ajax({
    url: '/modules/selected-modules',
    success: function(result) {
      if (result.status) {
        $('#selected_apps').html(result.data.sidebar)
      }
    },
    error: function(e) {
      console.log(e)
    }
  })
}

function viewAvailableModules() {
  $.ajax({
    url: '/modules/available-modules',
    success: function(result) {
      if (result.status) {
        $('#available_apps').html(result.data)
        new DataTable('#dataTable', {
          searching: false,
          ordering: false,
          pageLength: 5,
          lengthChange: false,
          info: false
        })
      }
    },
    error: function(e) {
      console.log(e)
    }
  })
}

$(document).on('click', '.add_module_btn', function(e) {
  e.preventDefault()
  var obj = $(this)
  var id = obj.data('id')
  $.ajax({
    url: `/modules/${id}/addtocart`,
    data: { quantity: 1, price: 0 },
    success: function(result) {
      if (result.status === 0) {
        dispErrors(result.data)
      } else {
        itoastr('success', 'Successfully added!')
        selectedAppsCount++
        viewSelectedModules()
        viewAvailableModules()
        fadeLimitedPackages()
      }
    },
    error: function(e) {
      console.log(e)
    }
  })
})

$(document).on('click', '.del_module_btn', function(e) {
  const module = $(this).data('module')
  $.ajax({
    url: `/modules/deltocart`,
    data: { module },
    success: function(result) {
      if (result.status === 0) {
        dispErrors(result.data)
      } else {
        itoastr('success', 'Successfully deleted!')
        selectedAppsCount--
        viewSelectedModules()
        viewAvailableModules()
        fadeLimitedPackages()
      }
    },
    error: function(e) {
      console.log(e)
    }
  })
})

function fadeLimitedPackages() {
  $('.package-card').each(function() {
    const limit = $(this).data('limit')
    if (limit >= 0 && limit < selectedAppsCount) {
      $(this).addClass('tw-opacity-40')
    } else {
      $(this).removeClass('tw-opacity-40')
    }
  })
}