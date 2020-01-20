$('#edit_form').submit(function(event) {
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
      }
    },
    error: function(e) {
      console.log(e)
    }
  })
})