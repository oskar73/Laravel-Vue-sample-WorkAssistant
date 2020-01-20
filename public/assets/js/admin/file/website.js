var switch_action
var checkbox_count
var table1
var alone = 0
var selected

$(function () {
  table1 = $('.datatable-all').DataTable(setParam())
})

function setParam() {
  let ajax = {
    url: '/admin/file/website',
    type: 'get'
  }

  let columns = [
    { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false },
    { data: 'domain', name: 'domain' },
    { data: 'name', name: 'name' },
    { data: 'user_id', name: 'user_id', orderable: false, searchable: false },
    { data: 'status', name: 'status' },
    { data: 'storage', name: 'storage', orderable: false, searchable: false },
    { data: 'created_at', name: 'created_at' },
    { data: 'action', name: 'action', orderable: false }
  ]

  return setTbl(ajax, columns)
}
