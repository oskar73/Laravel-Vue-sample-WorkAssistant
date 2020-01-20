var deleteItemSlug

$(function() {
  hashUpdate(window.location.hash)
  getDatatableTable()
})

function getDatatableTable() {
  $.ajax({
    url: '/admin/newsletter/items',
    type: 'get',
    dataType: 'JSON',
    contentType: false,
    cache: false,
    processData: false,
    success: function(result) {
      if (result.status === 1) {
        $('.show_checked').addClass('d-none')

        $('#all_area .m-portlet__body').html(result.all)
        $('#draft_area .m-portlet__body').html(result.draft)
        $('#archive_area .m-portlet__body').html(result.archive)
        $('#failed_area .m-portlet__body').html(result.failed)
        $('.all_count').html(result.count.all)
        $('.draft_count').html(result.count.draft)
        $('.archive_count').html(result.count.archive)
        $('.failed_count').html(result.count.failed)
        $('.datatable').dataTable(dataTblSet())
      }
    },
    error: function(e) {
      console.log(e)
    }
  })
}

$('#create_btn').on('click', function() {
  $('#create_modal').modal('toggle')
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
        itoastr('success', 'Successfully Created!')
        $('#create_modal').modal('toggle')
        window.location.href = result.redirect
      }
    },
    error: function(e) {
      console.log(e)
    }
  })
})

$(document).on('click', '.delete_btn', function() {
  deleteItemSlug = $(this).data('slug')

  askToast.question('Are you sure?', 'This item will be deleted!', 'deleteItem')
})

function deleteItem() {
  console.log('here')
  $.ajax({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    url: '/admin/newsletter/item/delete/' + deleteItemSlug,
    method: 'delete',
    success: function(result) {
      if (result.status === 0) {
        dispValidErrors(result.data)
      } else {
        itoastr('success', 'Newsletters deleted successfully!')
        getDatatableTable()
      }
    },
    error: function(e) {
      console.log(e)
    }
  })
}