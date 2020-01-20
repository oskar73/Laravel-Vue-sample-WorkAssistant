var disconnect, table1

$(function () {
  table1 = $('.datatable-all').DataTable(setParam())
})

$('.datatable-all').on('draw.dt', function () {
  $('.all_count').html(table1.ajax.json().recordsTotal)
})

function setParam() {
  let ajax = {
    url: '/admin/domain/connectList',
    type: 'get'
  }

  let columns = [
    { data: 'name', name: 'name' },
    { data: 'user', name: 'user', orderable: false, searchable: false },
    { data: 'website', name: 'website', orderable: false, searchable: false },
    { data: 'created_at', name: 'created_at' },
    { data: 'action', name: 'action', orderable: false }
  ]

  return setTbl(ajax, columns, 3, false)
}
$(document).on('click', '.disconnect', function () {
  disconnect = $(this).data('id')
  askToast.question('Warning!', 'Are you sure to disconnect this domain?', 'disconnectDomain')
})
function disconnectDomain() {
  console.log(disconnect)
  $.ajax({
    url: '/admin/domain/disconnect',
    type: 'get',
    data: { id: disconnect },
    success: function (result) {
      if (result.status === 1) {
        itoastr('success', 'Successfully disconnected!')
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
