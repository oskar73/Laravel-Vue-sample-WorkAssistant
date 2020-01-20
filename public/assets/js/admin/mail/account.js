var delete_id
var table1, table2, table3

$(function () {
  hashUpdate(window.location.hash)
  table1 = $('.datatable-all').DataTable(setParam('all'))
})
$('.datatable-all').on('draw.dt', function () {
  $('.all_count').html(table1.ajax.json().recordsTotal)
})
function setParam(status) {
  let ajax = {
    url: `/admin/mail/domain/${domain_id}/accounts`,
    type: 'get',
    data: { status: status }
  }

  let columns = [
    { data: 'email', name: 'email' },
    { data: 'name', name: 'name' },
    { data: 'quota', name: 'quota' },
    { data: 'status', name: 'status' },
    { data: 'action', name: 'action', orderable: false }
  ]

  return setTbl(ajax, columns, 1, false)
}
$(document).on('click', '.deleteBtn', function () {
  delete_id = $(this).data('id')

  askToast.question('Confirm', "Are you sure to delete this account? This action can't be reverted.", 'deleteAccount')
})

function deleteAccount() {
  $.ajax({
    url: '/admin/mail/domain/accounts/delete',
    type: 'delete',
    data: { _token: token, id: delete_id },
    success: function (result) {
      if (result.status === 1) {
        itoastr('success', 'Successfully deleted!')
        table1.ajax.reload()
      } else {
        dispErrors(result.data)
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
}
