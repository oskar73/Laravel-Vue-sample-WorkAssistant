$(document).on('submit', '#submit_form', function (e) {
  e.preventDefault()
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
        dispValidErrors(result.data)
      } else {
        itoastr('success', 'Successfully Updated!')
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
})
$('.checkbox_item').on('change', function () {
  if ($(this).prop('checked')) {
    $($(this).data('area')).removeClass('d-none')
  } else {
    $($(this).data('area')).addClass('d-none')
  }
})
