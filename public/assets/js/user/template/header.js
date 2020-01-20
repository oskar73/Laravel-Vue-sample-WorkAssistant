var switch_action
var checkbox_count
var alone = 0
var selected

var content, css, script

$(function () {
  hashUpdate(window.location.hash)
  getDatatableTable()
  content = ace.edit('content', {
    theme: 'ace/theme/twilight',
    mode: 'ace/mode/html',
    autoScrollEditorIntoView: true
  })

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
    url: '/admin/template/header',
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
$('#create_modal_form').submit(function (event) {
  event.preventDefault()
  var formData = new FormData(this)
  formData.append('content', content.getValue())
  formData.append('css', css.getValue())
  formData.append('script', script.getValue())

  mApp.block('#create_modal .modal-content', {})
  $.ajax({
    url: '/admin/template/header',
    method: 'POST',
    data: formData,
    dataType: 'JSON',
    contentType: false,
    cache: false,
    processData: false,
    success: function (result) {
      console.log(result)
      mApp.unblock('#create_modal .modal-content')
      if (result.status === 0) {
        dispErrors(result.data)
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

$(document).on('click', '.edit_btn', function () {
  $.ajax({
    url: '/admin/template/header/edit/' + $(this).data('id'),
    success: function (result) {
      if (result.status === 1) {
        $('#header_id').val(result.header.id)
        $('#name').val(result.header.name)
        $('#description').val(result.header.description)
        if (result.header.content !== null) {
          content.setValue(result.header.content)
        } else {
          content.setValue('')
        }
        if (result.header.css !== null) {
          css.setValue(result.header.css)
        } else {
          css.setValue('')
        }
        if (result.header.script !== null) {
          script.setValue(result.header.script)
        } else {
          script.setValue('')
        }
        if (result.header.status === 1) {
          $('#status').prop('checked', true)
        } else {
          $('#status').prop('checked', false)
        }
        $('#create_modal').modal('toggle')
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
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
$('.createBtn').click(function () {
  $('#header_id').val(null)
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
  }
  askToast.question('Confirm', msg, 'switchAction')
}
function switchAction() {
  $.ajax({
    url: '/admin/template/header/switch',
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
