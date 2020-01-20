let switch_action
let checkbox_count
let alone = 0
let selected
let slimCropper
let thumbNailImage

$(function () {
  $('.select_picker').selectpicker()
  getDatatableTable()
  slimCropper = new Slim(document.getElementById('slimInput'), {
    ratio: '2:3',
    download: true,
    buttonRemoveTitle: 'upload',
    instantEdit: false,
    maxFileSize: 50,
    label: 'Drop or choose image'
  })
})

function getDatatableTable() {
  $.ajax({
    url: '/admin/slider',
    type: 'get',
    dataType: 'JSON',
    contentType: false,
    cache: false,
    processData: false,
    success: function (result) {
      if (result.status === 1) {
        $('#all_area .m-portlet__body').html(result.all)
        $('.all_count').html(result.count.all)
        $('.datatable').dataTable(dataTblSet())
        $('.selectpicker').selectpicker('refresh')
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
}

$('.createBtn').click(function () {
  modalInit()
})
function modalInit() {
  $('#item_id').val(null)
  $('#item_modal_form select').val('')
  $('.select_picker').selectpicker('refresh')
  $('#name').val('')
  slimCropper.remove()
  $('#item_modal').modal('toggle')
}

$(document).on('change', '#type', function () {
  let type = $(this).val()
  if (type === 'url') {
    $('.url_area').removeClass('d-none')
    $('.product_area').addClass('d-none')
  } else {
    $('.url_area').addClass('d-none')
    $('.product_area').removeClass('d-none')
    getType(type, 0)
  }
})

$(document).on('change', '#product', function () {
  let image = $(this).find(':selected').data('image')
  if (image) {
    slimCropper.load(image+'?1')
  }
})
function getType(type, value) {
  $.ajax({
    url: '/admin/slider/product',
    data: { type: type },
    method: 'get',
    success: function (result) {
      console.log(result)
      if (result.status === 0) {
        dispErrors(result.data)
      } else {
        let obj = $('#product')
        obj.html(result.data)
        obj.val(value)
        obj.selectpicker('refresh')
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
}

$('#item_modal_form').submit(function (event) {
  event.preventDefault()
  let formData = new FormData(this)
  mApp.block('#item_modal .modal-content', {})
  $.ajax({
    url: '/admin/slider',
    method: 'POST',
    data: formData,
    dataType: 'JSON',
    contentType: false,
    cache: false,
    processData: false,
    success: function (result) {
      console.log(result)
      mApp.unblock('#item_modal .modal-content')
      if (result.status === 0) {
        dispErrors(result.data)
      } else {
        itoastr('success', 'Successfully Updated!')
        $('#item_modal').modal('toggle')
        getDatatableTable()
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
})
$(document).on('click', '.edit_btn', function () {
  let item = $(this).data('item')
  let type = $(this).data('type')
  $('#item_id').val(item.id)
  $('#name').val(item.name)
  $('#featured_name').val(item.featured_name)
  $('#type').val(type).change()
  if (type === 'url') {
    $('.url_area').removeClass('d-none')
    $('.product_area').addClass('d-none')
    $('#url').val(item.model_id)
  } else {
    $('.product_area').removeClass('d-none')
    $('.url_area').addClass('d-none')
    getType(type, item.model_id)
  }
  slimCropper.load($(this).data('thumbnail') + '?1')
  $('.select_picker').selectpicker('refresh')
  $('#item_modal').modal('toggle')
})

function switchAlert(item) {
  let msg
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
    url: '/admin/slider/switch',
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

$(document).on('click', '.switchOne', function () {
  switch_action = $(this).data('action')
  alone = 1
  selected = $(this).data('id')
  switchAlert('this item')
})

$('.sortBtn').click(function () {
  mApp.blockPage()
  $.ajax({
    url: '/admin/slider/sort',
    method: 'GET',
    success: function (result) {
      console.log(result)
      mApp.unblockPage()
      $('#sortable').html(result.view)
      $('#sort-modal').modal('toggle')
      $('#sortable').sortable()
      $('#sortable').disableSelection()
    },
    error: function (err) {
      console.log('Error!', err)
    }
  })
})
$('#sort_submit').click(function () {
  mApp.block('#sort-modal .modal-content', {})
  let sorts = []
  $('#sortable li').each(function (index) {
    sorts.push($(this).data('id'))
  })
  $.ajax({
    url: '/admin/slider/sort',
    method: 'POST',
    data: { _token: token, sorts: sorts },
    success: function (result) {
      itoastr('success', 'Successfully Updated!')
      mApp.unblock('#sort-modal .modal-content', {})
      $('#sort-modal').modal('toggle')
    },
    error: function (err) {
      console.log('Error!', err)
    }
  })
})
