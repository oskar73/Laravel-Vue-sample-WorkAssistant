let deleteUrl
let sort_cat_id = 0
let slimCropper

$(function() {
  slimOption = {
    ratio: '1:1',
    download: true,
    buttonRemoveTitle: 'upload',
    instantEdit: false,
    maxFileSize: 50,
    label: 'Drop or choose images'
  }
  $('.slimInput').each((i, elem) => {
    slimCropper = new Slim(elem, slimOption)
  })
  getDatatableTable()
})

function getDatatableTable() {
  $.ajax({
    url: route('admin.graphics.index'),
    type: 'get',
    dataType: 'JSON',
    contentType: false,
    cache: false,
    processData: false,
    success: function(result) {
      if (result.status === 1) {
        $('.show_checked').addClass('d-none')
        $('#all_area .m-portlet__body').html(result.all)
        $('.all_count').html(result.count.all)
        $('.datatable').dataTable(dataTblSet())
      } else {
        console.error('admin.graphics.index', result)
      }
    },
    error: function(e) {
      console.log(e)
    }
  })
}

$('.createBtn').click(function() {
  $('#category_id').val(null)
  $('#name').val(null)
  $('#width').val(null)
  $('#height').val(null)
  $('#create_modal').modal('toggle')
  slimCropper.remove()
})

$('#create_modal_form').submit(function(event) {
  event.preventDefault()
  btnLoading()
  let data = new FormData(this)
  $.ajax({
    url: $(this).attr('action'),
    method: 'POST',
    data: data,
    dataType: 'JSON',
    contentType: false,
    cache: false,
    processData: false,
    success: function(result) {
      btnLoadingStop()
      clearError()

      if (result.status === 0) {
        dispErrors(result.data)
        dispValidErrors(result.data)
      } else {
        itoastr('success', 'Successfully Updated!')
        $('#create_modal').modal('toggle')
        getDatatableTable()
      }
    },
    error: function(e) {
      console.log(e)
    }
  })
})

$(document).on('click', '.edit_btn', function() {
  var category = $(this).data('category')
  $('#graphic_id').val(category.id)
  $('#name').val(category.title)
  $('#width').val(category.width)
  $('#height').val(category.height)
  $('#description').val(category.description)
  const thumbnail = $(this).data('thumbnail')
  if (thumbnail) {
    slimCropper?.load(thumbnail + '?1')
  } else {
    slimCropper?.remove()
  }
  $('#create_modal').modal('toggle')
})

$(document).on('click', '.deleteBtn', function(e) {
  e.preventDefault()
  deleteUrl = $(this).attr('href')
  askToast.question('Confirm', 'Are you sure?', () => {
    $.post(deleteUrl, { _method: 'DELETE' }).then(res => {
      itoastr('success', 'Successfully deleted!')
      getDatatableTable()
    })
  })
})

