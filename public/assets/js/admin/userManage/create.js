$(function () {
  $('.selectpicker').selectpicker()
  $('.select2').select2({
    width: '100%'
  })
})
$('#submit_form').on('submit', function (event) {
  event.preventDefault()
  var formData = new FormData(this)
  if (previewCropped !== '') {
    formData.append('image', previewCropped)
  }
  btnLoading()
  $.ajax({
    url: $(this).attr('action'),
    method: 'POST',
    data: formData,
    dataType: 'JSON',
    contentType: false,
    cache: false,
    processData: false,
    success: function (result) {
      btnLoadingStop()
      clearError()

      if (result.status === 0) {
        dispValidErrors(result.data)
        dispErrors(result.data)
      } else {
        itoastr('success', 'Success!')
        redirectAfterDelay('/admin/userManage')
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
})
