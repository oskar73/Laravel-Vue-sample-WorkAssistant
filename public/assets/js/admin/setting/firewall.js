var switch_action
var checkbox_count
var alone = 0
var selected

$(function () {
  hashUpdate(window.location.hash)
  getDatatableTable()
})

function getDatatableTable() {
  $.ajax({
    url: '/admin/setting/firewall',
    type: 'get',
    dataType: 'JSON',
    contentType: false,
    cache: false,
    processData: false,
    success: function (result) {
      if (result.status === 1) {
        $('.show_checked').addClass('d-none')

        $('#whitelisted_area .m-portlet__body').html(result.whitelisted)
        $('#blacklisted_area .m-portlet__body').html(result.blacklisted)
        $('.whitelisted_count').html(result.count.whitelisted)
        $('.blacklisted_count').html(result.count.blacklisted)
        $('.datatable').dataTable(dataTblSet())
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
}
$('#create_modal_form').submit(function (event) {
  event.preventDefault()
  $(this).find('.smtBtn').prop('disabled', true).html("<i class='fa fa-spinner fa-spin'></i>")

  $.ajax({
    url: $(this).attr('action'),
    method: 'POST',
    data: new FormData(this),
    dataType: 'JSON',
    contentType: false,
    cache: false,
    processData: false,
    success: function (result) {
      console.log(result)
      $('.smtBtn').prop('disabled', false).html('Submit')
      $('.form-control-feedback').html('')
      if (result.status === 0) {
        dispErrors(result.data)
        dispValidErrors(result.data)
      } else {
        itoastr('success', 'Successfully Updated!')
        $('#create_modal').modal('toggle')
        getDatatableTable()
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
})

$('.createBtn').click(function () {
  $('#firewall_id').val(null)
  $('#name').val(null)
  $('#ip_address').val(null)
  $('#create_modal').modal('toggle')
})

$(document).on('click', '.edit_btn', function () {
  var firewall = $(this).data('firewall')
  $('#firewall_id').val(firewall.id)
  $('#name').val(firewall.name)
  $('#ip_address').val(firewall.ip_address)
  $('#whitelisted').val(firewall.whitelisted)
  $('#whitelisted').selectpicker('refresh')
  $('#create_modal').modal('toggle')
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
    case 'delete':
      msg = 'Do you want to delete ' + item + '?'
      break
  }
  askToast.question('Confirm', msg, 'switchAction')
}
function switchAction() {
  $.ajax({
    url: '/admin/setting/firewall/switch',
    data: { ids: checkedIds(), action: switch_action },
    method: 'get',
    success: function (result) {
      console.log(result)
      if (result.error) {
        dispErrors(result.message)
      } else {
        itoastr('success', 'Successfully updated!')
        getDatatableTable()
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
}
