var switch_action
var checkbox_count
var alone = 0
var selected

$(document).ready(function () {
  slimOption = {
    ratio: `360:240`,
    download: true,
    buttonRemoveTitle: 'upload',
    instantEdit: false,
    maxFileSize: 1,
    label: 'Drop or choose image'
  }

  let slimInput = $('#preview-image')
  slimCropper = new Slim(slimInput[0], slimOption)
  const thumbNailUrl = slimInput.data('url')
  if (thumbNailUrl) {
    slimCropper.load(thumbNailUrl + '?1')
  }
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
    url: '/admin/template/page/switch/edit',
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

$('.addPageBtn').click(function () {
  $('#page_id').val(null)
  $('#create_modal').modal('toggle')
})
$(document).on('click', '.edit_page_btn', function () {
  var page_id = $(this).data('id')
  $.ajax({
    url: '/admin/template/page/edit/' + template_id,
    type: 'get',
    data: { page_id: page_id },
    success: function (result) {
      if (result.status === 1) {
        $('#page_id').val(result.data.id)
        $('#parent_id').val(result.data.parent_id)
        $('#page_name').val(result.data.name)
        $('#url').val(result.data.url)
        if (result.data.css !== null) {
          page_css.setValue(result.data.css)
        } else {
          page_css.setValue('')
        }
        if (result.data.script !== null) {
          page_script.setValue(result.data.script)
        } else {
          page_script.setValue('')
        }
        if (result.data.status === 1) {
          $('#page_status').prop('checked', true)
        } else {
          $('#page_status').prop('checked', false)
        }
        $('#create_modal').modal('toggle')
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
})
$('#create_modal_form').submit(function (event) {
  event.preventDefault()
  var formData = new FormData(this)
  formData.append('css', page_css.getValue())
  formData.append('script', page_script.getValue())

  $('.submit_btn').html("<i class='fa fa-spinner fa-spin fa-2x fa-fw'></i>").attr('disabled', true)

  mApp.block('#create_modal .modal-content', {})
  $.ajax({
    url: '/admin/template/page/' + template_id,
    method: 'POST',
    data: formData,
    dataType: 'JSON',
    contentType: false,
    cache: false,
    processData: false,
    success: function (result) {
      mApp.unblock('#create_modal .modal-content')

      $('.submit_btn').html('Submit').attr('disabled', false)
      $('.form-control-feedback').html('')

      if (result.status === 0) {
        dispValidErrors(result.data)
        dispErrors(result.data)
      } else {
        itoastr('success', 'Success!')
        if (result.data.redirect) {
          redirectAfterDelay(result.data.route)
        }
        $('#create_modal').modal('toggle')
        getPageTable()
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
})
