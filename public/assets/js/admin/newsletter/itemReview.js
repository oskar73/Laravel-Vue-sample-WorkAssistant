$('#test_btn').on('click', function() {
  $('#test_modal').modal('toggle')
})

$('#send_btn').on('click', function() {
  askToast.question('Confirm', 'Are you sure you want to send this newsletter to all subscribers?', 'sendItem')
})

function sendItem() {
  $.ajax({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    url: sendURL,
    method: 'post',
    success: function(result) {
      if (result.status === 0) {
        dispValidErrors(result.data)
      } else {
        itoastr('success', 'Newsletters will be sent to all subscribers in the background. You would check the result in the newsletter page!')
        window.location.href = '/admin/newsletter/items'
      }
    },
    error: function(e) {
      console.log(e)
    }
  })
}

$('#test_modal_form').submit(function(event) {
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

      console.log(result)
      if (result.status === 0) {
        dispErrors(result.data)
        dispValidErrors(result.data)
      } else {
        itoastr('success', 'Newsletter Successfully Sent!')
        $('#test_modal').modal('toggle')
      }
    },
    error: function(e) {
      console.log(e)
    }
  })
})