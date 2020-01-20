var switch_action
var checkbox_count
var table1, table2, table3, table4
var alone = 0
var selected

$(function () {
  hashUpdate(window.location.hash)
  table1 = $('.datatable-all').DataTable(setParam(0))
  table2 = $('.datatable-active').DataTable(setParam(1))
  table3 = $('.datatable-expired').DataTable(setParam(2))
  table4 = $('.datatable-transferred').DataTable(setParam(3))
})
$('.datatable-all').on('draw.dt', function () {
  $('.all_count').html(table1.ajax.json().recordsTotal)
})
$('.datatable-active').on('draw.dt', function () {
  $('.active_count').html(table2.ajax.json().recordsTotal)
})
$('.datatable-expired').on('draw.dt', function () {
  $('.expired_count').html(table3.ajax.json().recordsTotal)
})
$('.datatable-transferred').on('draw.dt', function () {
  $('.transferred_count').html(table4.ajax.json().recordsTotal)
})
$('.createBtn').click(function () {
  modalClearToggle()
})
function setParam(status) {
  let ajax = {
    url: '/admin/domainList',
    type: 'get',
    data: { status: status, user: 'all' }
  }

  let columns = [
    { data: 'domainID', name: 'domainID' },
    { data: 'name', name: 'name' },
    { data: 'user', name: 'user', orderable: false, searchable: false },
    { data: 'website', name: 'website' },
    { data: 'orderID', name: 'orderID' },
    { data: 'transactionID', name: 'transactionID' },
    { data: 'chargedAmountNC', name: 'chargedAmountNC' },
    { data: 'chargedAmountBB', name: 'chargedAmountBB' },
    { data: 'created_at', name: 'created_at' },
    { data: 'expired_at', name: 'expired_at' },
    { data: 'action', name: 'action', orderable: false }
  ]

  return setTbl(ajax, columns, 8, false)
}
$(document).on('click', '.renew_btn', function () {
  $.ajax({
    url: '/admin/domainList/renew/' + $(this).data('id'),
    success: function (result) {
      if (result.error) {
        dispErrors(result.message)
      } else {
        $('.renew_result').html(result.view)
      }
    }
  })
})
$(document).on('click', '.renew_confirm_btn', function () {
  $.ajax({
    url: '/admin/domainList/renewConfirm',
    data: { duration: $(this).data('duration') },
    success: function (result) {
      if (result.error) {
        dispErrors(result.message)
      } else {
        $('.renew_confirm_result').html(result.view)
      }
    }
  })
})
$(document).on('click', '.renewNowBtn', function () {
  var dns
  $('.renewNowBtn').html("<i class='fa fa-spinner fa-spin fa-2x fa-fw'></i>").attr('disabled', true)
  $.ajax({
    url: '/admin/domainList/renewNow',
    type: 'post',
    data: { _token: token },
    success: function (result) {
      console.log(result)
      $('.renewNowBtn').html('Renew Now').attr('disabled', false)
      if (result.error) {
        dispValidErrors(result.message)
      } else {
        itoastr('success', 'Successfully renewed')
        setTimeout(function () {
          window.location.href = '/admin/domainList'
        }, 1000)
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
})
