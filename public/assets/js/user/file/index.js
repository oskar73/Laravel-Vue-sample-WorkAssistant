var switch_action
var checkbox_count
var table1
var alone = 0
var selected

$(function () {
  hashUpdate(window.location.hash)
  table1 = $('.datatable-all').DataTable(setParam())
})

function setParam() {
  let ajax = {
    url: '/account/file',
    type: 'get'
  }

  let columns = [
    { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false },
    { data: 'domain', name: 'domain' },
    { data: 'name', name: 'name' },
    { data: 'status', name: 'status' },
    { data: 'storage', name: 'storage', orderable: false, searchable: false },
    { data: 'created_at', name: 'created_at' },
    { data: 'action', name: 'action', orderable: false }
  ]

  return setTbl(ajax, columns)
}
