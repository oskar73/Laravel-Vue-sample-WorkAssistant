var switch_action
var checkbox_count
var alone = 0
var selected
var css, script

$(function () {
  hashUpdate(window.location.hash)
  getDatatableTable()

  css = ace.edit('css', {
    theme: 'ace/theme/twilight',
    mode: 'ace/mode/html',
    autoScrollEditorIntoView: true
  })

  script = ace.edit('script', {
    theme: 'ace/theme/twilight',
    mode: 'ace/mode/html',
    autoScrollEditorIntoView: true
  })
})
function getDatatableTable() {
  $.ajax({
    url: '/account/template/item',
    type: 'get',
    dataType: 'JSON',
    contentType: false,
    cache: false,
    processData: false,
    success: function (result) {
      if (result.status === 1) {
        $('.show_checked').addClass('d-none')

        $('#all_area .m-portlet__body').html(result.all)
        $('#active_area .m-portlet__body').html(result.active)
        $('#inactive_area .m-portlet__body').html(result.inactive)
        $('.all_count').html(result.count.all)
        $('.active_count').html(result.count.active)
        $('.inactive_count').html(result.count.inactive)
        $('.datatable').dataTable(dataTblSet())
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
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
$('.createBtn').click(function () {
  $('#category_id').val(null)
  $('#create_modal').modal('toggle')
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

$(document).on('submit', '#submit_form', function (event) {
    event.preventDefault()
    var formData = new FormData(this)
    const url = this.action
    $('.smtBtn').prepend("<i class='fa fa-spinner fa-spin fa-fw'></i>").attr('disabled', true)
    $.ajax({
        url: url,
        method: 'POST',
        data: formData,
        dataType: 'JSON',
        contentType: false,
        cache: false,
        processData: false,
        success: function (result) {
            $('.smtBtn').html('Submit').attr('disabled', false)
            $('.form-control-feedback').html('')

            if (result.status === 0) {
                dispValidErrors(result.data)
                dispErrors(result.data)
            } else {
                itoastr('success', 'Successfully Updated!')
                getDatatableTable()
                $('#editTemplateModal').modal('hide')
            }
        },
        error: function (e) {
            console.log(e)
        }
    })
})

$(document).on('click', '.palettes', function () {
  id = $(this).data('id')
  console.log(id)
  $('#palettes_' + id).toggle()
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
    case 'delete':
      msg = 'Do you want to delete ' + item + '?'
      break
    case 'featured':
      msg = 'Do you want to set as featured ' + item + '?'
      break
    case 'unfeatured':
      msg = 'Do you want to set as unfeatured ' + item + '?'
      break
    case 'new':
      msg = 'Do you want to set as new ' + item + '?'
      break
    case 'undonew':
      msg = 'Do you want to undo as new ' + item + '?'
      break
  }
  askToast.question('Confirm', msg, 'switchAction')
}
function switchAction() {
  $.ajax({
    url: '/account/template/item/switch',
    data: { ids: checkedIds(), action: switch_action },
    method: 'get',
    success: function (result) {
      console.log(result)
      if (result.status === 0) {
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