var switch_action
var checkbox_count
var alone = 0
var selected

$(function () {
  hashUpdate(window.location.hash)
  getDatatableTable()

  slimOption = {
    ratio: '2880:1300',
    download: true,
    buttonRemoveTitle: 'upload',
    instantEdit: false,
    maxFileSize: 50,
    label: 'Drop or choose image'
  }
  slimCropper = new Slim(document.getElementById('slimInput'), slimOption)
})

function getDatatableTable() {
  $.ajax({
    url: '/admin/home-sliders',
    type: 'get',
    dataType: 'JSON',
    contentType: false,
    cache: false,
    processData: false,
    success: function (result) {
      if (result.status === 1) {
        $('.m-portlet__body').html(result.table)
        $('.datatable').dataTable(dataTblSet())
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
}

$('.createBtn').click(function () {
  $('#slider_id').val(null)
  $('#title').val(null)
  $('#description').val(null)
  $('#create_modal').modal('toggle')
  slimCropper.remove()
})

$('#create_modal_form').submit(function (event) {
  event.preventDefault()
  mApp.block('#create_modal .modal-content', {})
  $.ajax({
    url: '/admin/home-sliders',
    method: 'POST',
    data: new FormData(this),
    dataType: 'JSON',
    contentType: false,
    cache: false,
    processData: false,
    success: function (result) {
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

$(document).on('change', 'input[type=checkbox]', function () {
  checkbox_count = $('.datatable tbody input[type=checkbox]:checked').length
  if (checkbox_count > 0) {
    $('.show_checked').removeClass('d-none')
  } else {
    $('.show_checked').addClass('d-none')
    $('.datatable thead input[type=checkbox]').prop('checked', false)
  }
})

$(document).on('focusin', function (e) {
  if ($(e.target).closest('.tox-textfield').length) e.stopImmediatePropagation()
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
  selected = $(this).parents('tr').find('.checkbox').data('id')
  switchAlert('this item')
})

$(document).on('click', '.edit_btn', function () {
  var item = $(this).data('item')
  $('#slider_id').val(item.id)
  $('#title').val(item.title)
  $('#description').val(item.description)
  $('#button').val(item.button)
  $('#link').val(item.link)
  slimCropper.load($(this).data('image') + '?1')
  if (item.status === 1) {
    $('#status').prop('checked', true)
  } else {
    $('#status').prop('checked', false)
  }

  $('#create_modal').modal('toggle')
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
    url: '/admin/home-sliders/switch',
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
