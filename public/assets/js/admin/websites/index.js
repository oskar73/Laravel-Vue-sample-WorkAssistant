var switch_action
var checkbox_count
var table1, table2, table3, table4
var alone = 0
var selected
var domain = ''

$(function () {
  hashUpdate(window.location.hash)
  table1 = $('.datatable-all').DataTable(setParam('all'))
  table2 = $('.datatable-active').DataTable(setParam('active'))
  table3 = $('.datatable-inactive').DataTable(setParam('inactive'))
  table4 = $('.datatable-expired').DataTable(setParam('expired'))
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
$('.datatable-expired').on('draw.dt', function () {
  $('.expired_count').html(table4.ajax.json().recordsTotal)
})
$('.createBtn').click(function () {
  modalClearToggle()
})
$(document).ready(function() {
    $(document).on('click', '.del-btn', function(e){
        var id = $(this).data('id')
        domain = $(this).data('domain')
        if (id && domain) {
            $('#id').val(id)
            $('.website_domain').html(domain)
            $('#confirm-modal').modal('toggle');
        }
    })
    $(document).on('keyup', '#domain_input', function() {
        if ($('#domain_input').val() == domain) $('#_confirm').attr('disabled', false)
        else $('#_confirm').attr('disabled', true)
    })
})
function setParam(status) {
  let ajax = {
    url: '/admin/website/list',
    type: 'get',
    data: { status: status, user: 'all' }
  }

  let columns = [
    { data: 'name', name: 'name' },
    { data: 'domain', name: 'domain' },
    { data: 'user', name: 'user', orderable: false, searchable: false },
    { data: 'status', name: 'status' },
    { data: 'status_by_owner', name: 'status_by_owner' },
    { data: 'storage', name: 'storage', orderable: false, searchable: false },
    { data: 'created_at', name: 'created_at' },
    { data: 'action', name: 'action', orderable: false }
  ]

  return setTbl(ajax, columns, 6, false)
}
