var switch_action
var checkbox_count
var alone = 0
var selected

$(function () {
  hashUpdate(window.location.hash)
  getDatatableTable()

  slimOption = {
    ratio: '4:3',
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
    url: '/admin/blog/category',
    type: 'get',
    dataType: 'JSON',
    contentType: false,
    cache: false,
    processData: false,
    success: function (result) {
      console.log(result);
      if (result.status === 1) {
        $('.show_checked').addClass('d-none')

        $('#all_area .m-portlet__body').html(result.all)
        $('#active_area .m-portlet__body').html(result.active)
        $('#inactive_area .m-portlet__body').html(result.inactive)
        $('#subcategory_area .m-portlet__body').html(result.subcategory)
        $('.all_count').html(result.count.all)
        $('.active_count').html(result.count.active)
        $('.inactive_count').html(result.count.inactive)
        $('.subcategory_count').html(result.count.subcategory)
        $('#parent').html(result.parents)
        $('.datatable').dataTable(dataTblSet())
        $('.selectpicker').selectpicker('refresh')
        $('.select2').select2({
          placeholder: 'Choose recommended tags',
          width: '100%'
        })
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

  $('#name').val(null)
  $('#description').val(null)
  $('.selectpicker').selectpicker('refresh')
  $('#tag').val(null).trigger('change.select2')
  $('#create_modal').modal('toggle')
  slimCropper.remove()
})
$('#create_modal_form').submit(function (event) {
  event.preventDefault()
  var formData = new FormData(this)
  mApp.block('#create_modal .modal-content', {})

  $.ajax({
    url: '/admin/blog/category',
    method: 'POST',
    data: formData,
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
$(document).on('click', '.edit_btn', function () {
  var category = $(this).data('category')
  var tags = $(this).data('tags')
  $('#category_id').val(category.id)
  $('#parent').val(category.parent_id)
  $('#parent').selectpicker('refresh')
  $('#name').val(category.name)
  $('#description').val(category.description)
  if (category.status === 1) {
    $('#status').prop('checked', true)
  } else {
    $('#status').prop('checked', false)
  }

  slimCropper.load($(this).data('thumbnail')+'?1')

  $('#tag').val(tags).trigger('change.select2')
  $('#create_modal').modal('toggle')
})
$('.sortBtn').click(function () {
  mApp.blockPage()
  $.ajax({
    url: '/admin/blog/category/sort',
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
  var sorts = []
  $('#sortable li').each(function (index) {
    sorts.push($(this).data('id'))
  })
  $.ajax({
    url: '/admin/blog/category/sort',
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
    url: '/admin/blog/category/switch',
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
