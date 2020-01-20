var delete_id
var table1, table2, table3, table4

$(function () {
  hashUpdate(window.location.hash)
  table1 = $('.datatable-all').DataTable(setParam('all'))
  table2 = $('.datatable-active').DataTable(setParam('active'))
  table3 = $('.datatable-inactive').DataTable(setParam('inactive'))
  table4 = $('.datatable-allAccount').DataTable(setParamForAccount('all'))
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
$('.datatable-allAccount').on('draw.dt', function () {
  $('.allAccount_count').html(table4.ajax.json().recordsTotal)
})
function setParamForAccount(status) {
  let ajax = {
    url: `/admin/mail/domain/all/accounts`,
    type: 'get',
    data: { status: status }
  }

  let columns = [
    { data: 'domain_name', name: 'domain_name' },
    { data: 'email', name: 'email' },
    { data: 'name', name: 'name' },
    { data: 'quota', name: 'quota' },
    { data: 'status', name: 'status' },
    { data: 'action', name: 'action', orderable: false }
  ]

  return setTbl(ajax, columns, 0, false)
}
function setParam(status) {
  let ajax = {
    url: '/admin/mail/domain',
    type: 'get',
    data: { status: status }
  }

  let columns = [
    { data: 'domain', name: 'domain' },
    { data: 'max_aliases', name: 'max_aliases' },
    { data: 'max_mail_boxes', name: 'max_mail_boxes' },
    { data: 'max_quota_per_mailbox', name: 'max_quota_per_mailbox' },
    { data: 'total_quota', name: 'total_quota' },
    { data: 'status', name: 'status' },
    { data: 'action', name: 'action', orderable: false }
  ]

  return setTbl(ajax, columns, 6, false)
}
$(document).on('click', '.deleteBtn', function () {
  delete_id = $(this).data('id')

  askToast.question('Confirm', "Are you sure to delete this domain? This action can't be reverted.", 'deleteDomain')
})

function deleteDomain() {
  $.ajax({
    url: '/admin/mail/domain/delete',
    type: 'delete',
    data: { _token: token, id: delete_id },
    success: function (result) {
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
