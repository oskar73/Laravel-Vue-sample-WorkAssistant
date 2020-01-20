var switch_action
var checkbox_count
var table1, table2, table3, table4
var alone = 0
var selected

$(function () {
  hashUpdate(window.location.hash)
  table1 = $('.datatable-all').DataTable(setParam(0))
  table2 = $('.datatable-active').DataTable(setParam(1))
  table3 = $('.datatable-inactive').DataTable(setParam(2))
  table4 = $('.datatable-recommend').DataTable(setParam(3))
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
$('.datatable-recommend').on('draw.dt', function () {
  $('.recommend_count').html(table4.ajax.json().recordsTotal)
})
function setParam(status) {
  let ajax = {
    url: '/admin/domainTld',
    type: 'get',
    data: { status: status }
  }

  let columns = [
    { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false },
    { data: 'Name', name: 'Name' },
    { data: 'price', name: 'Price', orderable: false, searchable: false },
    { data: 'MinRegisterYears', name: 'MinRegisterYears' },
    { data: 'MinRenewYears', name: 'MinRenewYears' },
    { data: 'MaxRegisterYears', name: 'MaxRegisterYears' },
    { data: 'MaxRenewYears', name: 'MaxRenewYears' },
    { data: 'status', name: 'Status' },
    { data: 'recommend', name: 'recommend' },
    { data: 'action', name: 'action', orderable: false }
  ]

  return setTbl(ajax, columns)
}

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
    case 'active':
      msg = 'Do you want to activate ' + item + '?'
      break
    case 'inactive':
      msg = 'Do you want to make inactivate ' + item + '?'
      break
    case 'recommend':
      msg = 'Do you want to recommend ' + item + '?'
      break
    case 'unrecommend':
      msg = 'Do you want to unrecommend ' + item + '?'
      break
  }
  askToast.question('Confirm', msg, 'switchAction')
}
function switchAction() {
  $.ajax({
    url: '/admin/domainTld/switch',
    data: { ids: checkedIds(), action: switch_action },
    method: 'get',
    success: function (result) {
      console.log(result)
      if (result.error) {
        dispErrors(result.message)
      } else {
        itoastr('success', result.message)
        table1.ajax.reload()
        table2.ajax.reload()
        table3.ajax.reload()
        table4.ajax.reload()
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
}
