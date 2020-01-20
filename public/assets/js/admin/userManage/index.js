var switch_action
var checkbox_count
var table1, table2, table3
var alone = 0
var selected

$(function () {
  hashUpdate(window.location.hash)
  table1 = $('.datatable-all').DataTable(setParam('all'))
  table2 = $('.datatable-active').DataTable(setParam('active'))
  table3 = $('.datatable-inactive').DataTable(setParam('inactive'))

  $('.filter_form').on('submit', (event) => {
    event.preventDefault();

    const formData = new FormData(event.target);
    let query = Array.from(formData.entries()).filter((val) => val[1]).reduce((obj, v) => {
        obj.push(v[0].replace('f_', '') + "=" + v[1])
        return obj
    }, []).join('&')
    if(this.querySelector.length){
      query = '?' + query
    }

    table1?.destroy()
    table1 = $('.datatable-all').DataTable(setParam('all', query))
    table2?.destroy()
    table2 = $('.datatable-active').DataTable(setParam('active', query))
    table3?.destroy()
    table3 = $('.datatable-inactive').DataTable(setParam('inactive', query))
  })
})

function setParam(status, query = '') {
  let ajax = {
    url: '/admin/userManage' + query,
    type: 'get',
    data: { status: status }
  }

  let columns = [
    { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false },
    { data: 'pin_number', name: 'pin_number' },
    // { data: 'avatar', name: 'avatar' },
    { data: 'username', name: 'username' },
    { data: 'name', name: 'name', orderable: false, searchable: false },
    { data: 'email', name: 'email' },
    { data: 'role', name: 'role' },
    { data: 'status', name: 'status' },
    { data: 'created_at', name: 'created_at' },
    { data: 'action', name: 'action', orderable: false }
  ]

  return setTbl(ajax, columns, 7, false)
}

$('.datatable-all').on('draw.dt', function () {
  $('.all_count').html(table1.ajax.json().recordsTotal)
})
$('.datatable-active').on('draw.dt', function () {
  $('.active_count').html(table2.ajax.json().recordsTotal)
})
$('.datatable-inactive').on('draw.dt', function () {
  $('.inactive_count').html(table3.ajax.json().recordsTotal)
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
$(document).on('click', '.switchOne', function (e) {
  e.preventDefault()
  switch_action = $(this).data('action')
  alone = 1
  selected = $(this).parent().parent().find('.checkbox').data('id')
  switchAlert('this item')
})
$(document).on('click', '.send_verification', function () {
  switch_action = $(this).data('action')
  alone = 1
  selected = $(this).parent().parent().find('.checkbox').data('id')
  switchAlert('this item')
})

function switchAlert(item) {
  var msg

  switch (switch_action) {
    case 'active':
      msg = 'Do you want to activate ' + item + '?'
      break
    case 'inactive':
      msg = 'Do you want to inactivate ' + item + '?'
      break
    case 'delete':
      msg = 'Do you want to delete ' + item + '?'
      break
    case 'send_verification':
      msg = 'Do you want to send email verification to ' + item + '?'
      break
    case 'account_creation':
      msg = 'Do you want to resend email verification for account creation to ' + item + '?'
      break
  }
  askToast.question('Confirm', msg, 'switchAction')
}
function switchAction() {
  $.ajax({
    url: '/admin/userManage/switchStatus',
    data: { ids: checkedIds(), action: switch_action },
    method: 'get',
    success: function (result) {
      console.log(result)
      if (result.status === 0) {
        dispErrors(result.data)
      } else {
        itoastr('success', 'Success!')
        table1.ajax.reload()
        table2.ajax.reload()
        table3.ajax.reload()
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
}

$(document).on('click', 'a[data-action="user.delete"]', function (e) {
  e.preventDefault()
    let url = $(this).attr('href');
    window.deleteAction = function() {
        $.ajax({
            url: url,
            data: { _token: token },
            method: 'delete',
            success: function (result) {
                if (result.status === 0) {
                    dispErrors(result.data)
                } else {
                    itoastr('success', 'Success!')
                    table1.ajax.reload()
                    table2.ajax.reload()
                    table3.ajax.reload()
                }
            },
            error: function (e) {
                console.log(e)
            }
        })
    }
    askToast.question('Confirm', 'Do you want to delete the user?', 'deleteAction')
});
