let previewCropper

$(function () {
  let previewImage = $('#preview_image')
  let previewImageUrl = previewImage.data('url')
  previewCropper = new Slim(previewImage[0], {
    ratio: '2:1',
    download: true,
    buttonRemoveTitle: 'upload',
    instantEdit: false,
    maxFileSize: 50,
    label: 'Drop or choose image'
  })
  if (previewImageUrl) {
    previewCropper.load(previewImageUrl + '?' + new Date().getTime())
  }

  $('#category_ids').select2({
    width: '100%',
    placeholder: 'Select Categories'
  })
})

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
      btnLoadingStop()
      clearError()

      if (result.status === 0) {
        dispErrors(result.data)
        dispValidErrors(result.data)
      } else {
        itoastr('success', 'Success!')

        redirectAfterDelay('/admin/graphics/design')
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
})
