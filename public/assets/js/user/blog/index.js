var table1

$(function () {
  hashUpdate(window.location.hash)
  table1 = $('.datatable-all').DataTable(setParam())
})
$('.datatable-all').on('draw.dt', function () {
  $('.all_count').html(table1.ajax.json().recordsTotal)
})
function modalClearToggle() {
  $('.notify_area').hide()
  $('#item_id').val(null)
  $('#item_modal').modal('toggle')
}

$(document).on('click', '.delete', function () {
  askToast.question('Confirm', 'Do you want to delete this item?', 'switchAction')
})

function setParam() {
  let ajax = {
    url: '/account/blog',
    type: 'get'
  }

  let columns = [
    { data: 'title', name: 'title' },
    { data: 'view_count', name: 'view_count' },
    { data: 'favoriters', name: 'favoriters' },
    { data: 'comments', name: 'comments' },
    { data: 'subscribers', name: 'subscribers' },
    { data: 'is_free', name: 'is_free' },
    { data: 'is_published', name: 'is_published' },
    { data: 'status', name: 'status' },
    { data: 'visible_date', name: 'visible_date' },
    { data: 'created_at', name: 'created_at' },
    { data: 'action', name: 'action', orderable: false }
  ]

  return setTbl(ajax, columns, 9, false)
}

$(document).on('click', '.switchOne', function () {
    switch_action = $(this).data('action')
    alone = 1
    selected = $(this).data('id')
    switchAlert('this item')
  })
  function switchAlert(item) {
    var msg
    switch (switch_action) {
      case 'approve':
        msg = 'Do you want to approve ' + item + '?'
        break
      case 'deny':
        msg = 'Do you want to deny ' + item + '?'
        break
      case 'publish':
        msg = 'Do you want to publish ' + item + '?'
        break
      case 'featured':
        msg = 'Do you want to set as featured ' + item + '?'
        break
      case 'unfeatured':
        msg = 'Do you want to set as unfeatured ' + item + '?'
        break
      case 'draft':
        msg = 'Do you want to set as draft ' + item + '?'
        break
      case 'delete':
        msg = 'Do you want to delete ' + item + '?'
        break
    }
    askToast.question('Confirm', msg, 'switchAction')
  }
  function switchAction() {
      console.log(checkedIds());
    $.ajax({
      url: '/account/blog/post/switchPost',
      data: { ids: checkedIds(), action: switch_action },
      method: 'get',
      success: function (result) {
        console.log(result)
        if (result.status === 0) {
          dispErrors(result.data)
        } else {
          itoastr('success', 'Successfully updated')
          table1.ajax.reload()
          table2.ajax.reload()
          table3.ajax.reload()
          table4.ajax.reload()
          table5.ajax.reload()
        }
      },
      error: function (e) {
        console.log(e)
      }
    })
  }
