var deleteItemSlug
$(document).ready(() => {
  updateItems()
})

const updateItems = () => {
  $.ajax({
    url: '/admin/newsletter/templates',
    type: 'get',
    dataType: 'JSON',
    contentType: false,
    cache: false,
    processData: false,
    success: function(result) {
      if (result.status) {
        $('#content').html(result.view)
      } else {
        console.log(result)
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

$(document).on('click', '.rename_btn', function() {
  console.log('here')
  $('#rename_modal_form').data('slug', $(this).data('slug'))
  $('#new_name').val($(this).data('name'))
  $('#rename_modal').modal('toggle')
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

$('#rename_modal_form').submit(function(event) {
  event.preventDefault()
  btnLoading()
  let data = new FormData(this)
  $.ajax({
    url: '/admin/newsletter/template/rename/' + $(this).data('slug'),
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
        itoastr('success', 'Successfully Renamed!')
        $('#rename_modal').modal('toggle')
        updateItems()
      }
    },
    error: function(e) {
      console.log(e)
    }
  })
})

$(document).on('click', '.delete_btn', function() {
  deleteItemSlug = $(this).data('slug')
  askToast.question('Confirm', 'Are you sure you want to delete?', 'deleteItem')
})

function deleteItem() {
  $.ajax({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    url: '/admin/newsletter/template/delete/' + deleteItemSlug,
    method: 'delete',
    success: function(result) {
      if (result.status === 0) {
        dispValidErrors(result.data)
      } else {
        itoastr('success', 'Successfully deleted!')
        updateItems()
      }
    },
    error: function(e) {
      console.log(e)
    }
  })
}