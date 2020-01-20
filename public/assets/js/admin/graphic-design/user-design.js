let table1, table2, table3
let deleteUrl, switchUrl

$(function () {
  hashUpdate(window.location.hash)
  table1 = $('.datatable-all').DataTable(setParam('all'))
  table2 = $('.datatable-active').DataTable(setParam('active'))
  table3 = $('.datatable-inactive').DataTable(setParam('inactive'))
})
$('.datatable-all').on('draw.dt', function () {
  $('.all_count').html(table1.ajax.json().recordsTotal)
})
$('.datatable-active').on('draw.dt', function () {
  $('.active_count').html(table2.ajax.json().recordsTotal)
})
$('.datatable-inactive').on('draw.dt', function () {
  $('.inactive_count').html(table3.ajax.json().recordsTotal)
})

function setParam(status) {
  let ajax = {
    // url: route('admin.graphics.design.user-index'),
    type: 'get',
    data: { status: status }
  }

  let columns = [
    { data: 'type', name: 'type', orderable: false, searchable: false },
    { data: 'name', name: 'name' },
    { data: 'preview', name: 'preview' },
    { data: 'download', name: 'download' },
    { data: 'version', name: 'version' },
    { data: 'live_edit', name: 'live_edit' },
    { data: 'created_at', name: 'created_at' },
  ]

  return setTbl(ajax, columns, 6, false)
}
$(document).on('click', '.deleteBtn', function (e) {
  e.preventDefault()
  deleteUrl = $(this).attr('href')
  askToast.question('Confirm', 'Are you sure?', 'switchAction')
})
function switchAction() {
  $.ajax({
    url: deleteUrl,
    data: { _token: token },
    method: 'delete',
    success: function (result) {
      console.log(result)
      if (result.status === 1) {
        itoastr('success', 'Successfully deleted!')

        table1.ajax.reload()
        table2.ajax.reload()
        table3.ajax.reload()
      } else {
        dispErrors(result.data)
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
}

$('.sortBtn').click(function () {
  mApp.blockPage()
  $.ajax({
    url: '/admin/graphics/item/sort',
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
})
$('#sort_submit').click(function () {
  mApp.block('#sort-modal .modal-content', {})
  var sorts = []
  $('#sortable li').each(function (index) {
    sorts.push($(this).data('id'))
  })
  $.ajax({
    url: '/admin/graphics/item/sort',
    method: 'POST',
    data: { _token: token, sorts: sorts },
    success: function (result) {
      mApp.unblock('#sort-modal .modal-content', {})
      if (result.status === 1) {
        itoastr('success', 'Successfully Updated!')
        $('#sort-modal').modal('toggle')
        getDatatableTable()
      } else {
        dispErrors(result.data)
      }
    },
    error: function (err) {
      console.log('Error!', err)
    }
  })
})

$(document).on('click', '.switchBtn', function (e) {
  e.preventDefault()
  switchUrl = $(this).attr('href')
  askToast.question('Confirm', 'Are you sure?', 'switchLogoType')
})

function switchLogoType() {
  $.ajax({
    url: switchUrl,
    data: { _token: token },
    method: 'put',
    success: function (result) {
      console.log(result)
      if (result.status === 1) {
        itoastr('success', 'Successfully deleted!')
        table1.ajax.reload()
        table2.ajax.reload()
        table3.ajax.reload()
      } else {
        dispErrors(result.data)
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
}
