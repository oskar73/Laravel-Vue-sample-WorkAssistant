var switch_action
var checkbox_count
var table1, table2
var alone = 0
var selected

$(function () {
  hashUpdate(window.location.hash)
  table1 = $('.datatable-unread').DataTable(setParam('unread'))
  table2 = $('.datatable-all').DataTable(setParam('all'))
})

function setParam(status) {
  let ajax = {
    url: (isAdmin ? '/admin/' : '/account/') + 'notifications',
    type: 'get',
    data: { status: status }
  }

  let columns = [
    { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false },
    { data: 'subject', name: 'subject' },
    { data: 'created_at', name: 'created_at' },
    { data: 'read_at', name: 'read_at' },
    { data: 'link', name: 'link' },
    { data: 'action', name: 'action', orderable: false }
  ]

  return setTbl(ajax, columns, 3, false)
}

$('.datatable-unread').on('draw.dt', function () {
  $('.unread_count').html(table1.ajax.json().recordsTotal)
})

$('.datatable-all').on('draw.dt', function () {
  $('.all_count').html(table2.ajax.json().recordsTotal)
})

$(document).on('change', 'input[type=checkbox]', function () {
  checkbox_count = $('.datatable tbody input[type=checkbox]:checked').length
  if (checkbox_count > 0) {
    $('.show_checked').removeClass('d-none')
  } else {
    $('.show_checked').addClass('d-none')
    $('.datatable thead input[type=checkbox]').prop('checked', false)
  }
})

$(document).on('click', '.switchBtn', function () {
  switch_action = $(this).data('action')
  var item = checkbox_count + ' items'
  alone = 0
  switchAlert(item)
})
$(document).on('click', '.switchOne', function () {
  switch_action = $(this).data('action')
  alone = 1
  selected = $(this).parent().parent().find('.checkbox').data('id')
  switchAlert('this item')
})
function switchAlert(item) {
  var msg
  switch (switch_action) {
    case 'read':
      msg = 'Do you want to mark as read ' + item + '?'
      break
    case 'unread':
      msg = 'Do you want to mark as unread ' + item + '?'
      break
    case 'delete':
      msg = 'Do you want to delete ' + item + '?'
      break
  }
  askToast.question('Confirm', msg, 'switchAction')
}
function switchAction() {
  console.log(checkedIds())
  $.ajax({
    url: '/account/notifications/switch',
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
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
}
