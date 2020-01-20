var sort_gradient
var deleteId
$(function () {
  getDatatableTable()
  hashUpdate(window.location.hash)

  $.contextMenu({
    selector: '.palette_item_div',
    callback: function (key, options) {
      let id = $(this).data('id')
      let name = $(this).data('name')
      handleContext(id, name, key)
    },
    items: {
      edit: { name: 'Edit' },
      delete: { name: 'Delete' }
    }
  })
})

function handleContext(id, name, key) {
  if (key === 'edit') {
    window.location.href = '/account/color-palettes/' + id
  } else if (key === 'delete') {
    deleteId = id
    askToast.question('Confirm', `Are you sure to delete "${name}" category?`, 'switchAction')
  }
}

function switchAction() {
  $.ajax({
    url: '/account/color-palettes/' + deleteId,
    data: { _token: token, _mothod: 'delete' },
    method: 'delete',
    success: function (result) {
      console.log(result)
      if (result.status === 1) {
        itoastr('success', 'Success!')

        getDatatableTable()
      } else {
        dispErrors(result.data)
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
}

function getDatatableTable() {
  $.ajax({
    url: '/account/color-palettes',
    type: 'get',
    dataType: 'JSON',
    contentType: false,
    cache: false,
    processData: false,
    success: function (result) {
      console.log(result)
      if (result.status === 1) {
        $('.gradient_body').html(result.gradient)
        $('.gradient_count').html(result.count.gradient)

        $('.solid_body').html(result.solid)
        $('.solid_count').html(result.count.solid)
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
}

$('.sortBtn').click(function (e) {
  e.preventDefault()
  getOrder($(this).data('gradient'))
})

function getOrder(sort_gradient) {
  const type = sort_gradient ? 'gradient' : 'solid'
  $.ajax({
    url: '/account/color-palettes/sort/get/' + type,
    method: 'GET',
    success: function (result) {
      mApp.unblockPage()
      if (result.status === 1) {
        $('#sortable').html(result.data)
        $('#sort-modal').modal('toggle')
        $('#sortable').sortable()
        $('#sortable').disableSelection()
      } else {
        dispErrors(result.data)
      }
    },
    error: function (err) {
      console.log('Error!', err)
    }
  })
}

$('#sort_submit').click(function () {
  mApp.block('#sort-modal .modal-content', {})
  var sorts = []
  $('#sortable li').each(function (index) {
    sorts.push($(this).data('id'))
  })
  $.ajax({
    url: '/account/color-palettes/sort/store',
    method: 'POST',
    data: { _token: token, sorts: sorts },
    success: function (result) {
      mApp.unblock('#sort-modal .modal-content', {})
      if (result.status === 1) {
        itoastr('success', 'Success!')
        $('#sort-modal').modal('toggle')
        setTimeout(() => {
          window.location.reload()
        }, 1000)
      } else {
        dispErrors(result.data)
      }
    },
    error: function (err) {
      console.log('Error!', err)
    }
  })
})
