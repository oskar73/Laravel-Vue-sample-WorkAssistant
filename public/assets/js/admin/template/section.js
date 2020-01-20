var switch_action
var checkbox_count
var alone = 0
var selected

var content, css, script

$(async function () {
  hashUpdate(window.location.hash)
  await getDatatableTable()
  // const app = new Vue({ el: '#app',store: window.store});
})

async function getDatatableTable() {
  return new Promise((resolve) => {
    $.ajax({
      url: '/admin/template/section',
      type: 'get',
      dataType: 'JSON',
      contentType: false,
      cache: false,
      processData: false,
      success: function (result) {
        if (result.status === 1) {
          $('.show_checked').addClass('d-none')

          $('#category_area .m-portlet__body').html(result.category)
          $('#section_area .m-portlet__body').html(result.section)
          $('.category_count').html(result.count.category)
          $('.section_count').html(result.count.section)
          $('.datatable').dataTable(dataTblSet())
          resolve(true)
        }
      },
      error: function (e) {
        console.log(e)
      }
    })
  })
}
$('#submit_form').submit(function (event) {
  event.preventDefault()
  btnLoading()
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
      btnLoadingStop()
      clearError()
      if (result.status === 0) {
        dispErrors(result.data)
      } else {
        itoastr('success', 'Success!')
        $('#create_modal').modal('toggle')
        getDatatableTable()
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
})

$(document).on('click', '.edit_btn', function (e) {
  e.preventDefault()
  var category = $(this).data('category')
  $('#create_modal').modal('toggle')
  $('#category_id').val(category.id)
  $('#name').val(category.name)
  $('#limit_per_page').val(category.limit_per_page)
  $('#description').val(category.description)

  if (category.recommended === 1) {
    $('#recommended').prop('checked', true)
  } else {
    $('#recommended').prop('checked', false)
  }
  if (category.status === 1) {
    $('#status').prop('checked', true)
  } else {
    $('#status').prop('checked', false)
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
$('.createBtn').click(function () {
  $('#category_id').val(null)
  $('#name').val(null)
  $('#description').val(null)
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
  let switchUrl
  if ($('.tab-link.tab-active').data('area') === '#section') {
    switchUrl = '/admin/template/section/item/switch'
  } else {
    switchUrl = '/admin/template/section/category/switch'
  }
  $.ajax({
    url: switchUrl,
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
