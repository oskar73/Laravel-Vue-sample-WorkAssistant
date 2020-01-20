var deleteUrl
var sort_cat_id = 0
let slimCropper

$(function () {
  $('.select2').select2({
    width: '100%',
    placeholder: 'Select Category'
  })

  slimOption = {
    ratio: '1:1',
    download: true,
    buttonRemoveTitle: 'upload',
    instantEdit: false,
    maxFileSize: 50,
    label: 'Drop or choose image'
  }
  slimCropper = new Slim(document.getElementById('slimInput'), slimOption)

  getDatatableTable()
})

$('#graphic_id').selectpicker().on('change', function () {
  window.location.href = route('admin.graphics.category.index', { slug: this.value })
})

function getDatatableTable () {
  $.ajax({
    url: route('admin.graphics.category.index', { slug: graphic.slug }),
    type: 'get',
    dataType: 'JSON',
    contentType: false,
    cache: false,
    processData: false,
    success: function (result) {
      if (result.status === 1) {
        $('.show_checked').addClass('d-none')
        $('#tbl_subcategories').html(result.all)
        $('.all_count').html(result.count.all)
        $('.datatable').dataTable(dataTblSet())
      } else {
        console.error('admin.graphics.categoryerror', result)
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
}

$('.createBtn').click(function () {
  $('#category_id').val(null)
  $('#name').val(null)
  $('#description').val(null)
  $('#create_modal').modal('toggle')
  slimCropper.remove()
  $('.select2').trigger('change')
})

$('#create_modal_form').submit(function (event) {
  event.preventDefault()
  btnLoading()
  const formData = new FormData(this)
  formData.append('graphic_id', graphic.id)
  $.post(this.action, formData, () => {
    btnLoadingStop()
    clearError()
    itoastr('success', 'Successfully Updated!')
    $('#create_modal').modal('toggle')
    getDatatableTable()
  })
})

$(document).on('click', '.edit_btn', function () {
  var category = $(this).data('category')
  $('#category_id').val(category.id)
  $('#name').val(category.name)
  $('#description').val(category.description)
  const thumbnail = $(this).data('thumbnail')
  if (thumbnail) {
    slimCropper.load(thumbnail + '?1')
  } else {
    slimCropper.remove()
  }
  $('#create_modal').modal('toggle')
})

$('.sortBtn').click(function () {
  mApp.blockPage()
  sort_cat_id = 0
  getOrder()
})
$(document).on('click', '.cat_sort_order', function (e) {
  e.preventDefault()
  sort_cat_id = $(this).data('id')
  getOrder()
})

function getOrder () {
  $.ajax({
    url: '/admin/logotypes/category/sort',
    method: 'GET',
    data: { cat_id: sort_cat_id },
    success: function (result) {
      mApp.unblockPage()
      if (result.status === 1) {
        $('#sortable').html(result.data)
        $('#sort-modal').modal('toggle')
        $('#sortable').sortable()
        $('#sortable').disableSelection()
      } else {
        dispErrors(result.data)
      }
    },
    error: function (err) {
      console.log('Error!', err)
    }
  })
}

$('#sort_submit').click(function () {
  mApp.block('#sort-modal .modal-content', {})
  var sorts = []
  $('#sortable li').each(function (index) {
    sorts.push($(this).data('id'))
  })
  $.ajax({
    url: '/admin/logotypes/category/sort',
    method: 'POST',
    data: {
      _token: token,
      sorts: sorts,
      cat_id: sort_cat_id
    },
    success: function (result) {
      mApp.unblock('#sort-modal .modal-content', {})
      if (result.status === 1) {
        itoastr('success', 'Successfully Updated!')
        $('#sort-modal').modal('toggle')
        getDatatableTable()
      } else {
        dispErrors(result.data)
      }
    },
    error: function (err) {
      console.log('Error!', err)
    }
  })
})

$(document).on('click', '.deleteBtn', function (e) {
  e.preventDefault()
  deleteUrl = $(this).attr('href')
  askToast.question('Confirm', 'Are you sure?', 'deleteCategory')
})

function deleteCategory () {
  $.ajax({
    url: deleteUrl,
    data: { _token: token },
    method: 'delete',
    success: function (result) {
      console.log(result)
      if (result.status === 1) {
        itoastr('success', 'Successfully deleted!')
        getDatatableTable()
      } else {
        dispErrors(result.data)
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
}
