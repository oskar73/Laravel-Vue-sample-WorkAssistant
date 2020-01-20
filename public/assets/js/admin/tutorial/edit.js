
var slimOption, slimCropper

$(document).ready(function () {
  $('.selectpicker').selectpicker()
  tinymceInit('#description')

    slimOption = {
        ratio: `3:4`,
        download: true,
        buttonRemoveTitle: 'upload',
        instantEdit: false,
        maxFileSize: maxImageSize,
        label: 'Drop or choose thumbnail'
    }

    let slimInput = $('#thumbnail')
    slimCropper = new Slim(slimInput[0], slimOption)
    if (window.thumbNailUrl) {
        slimCropper.load(window.thumbNailUrl + '?1')
    }
})

$('#submit_form').on('submit', function (event) {
  event.preventDefault()
  tinyMCE.triggerSave()
  var formData = new FormData(this)
  if (previewCropped !== '') {
    formData.append('thumbnail', previewCropped)
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
      console.log(result)
      btnLoadingStop()
      clearError()

      if (result.status === 0) {
        dispValidErrors(result.data)
        dispErrors(result.data)
      } else {
        itoastr('success', 'Successfully updated!')
        redirectAfterDelay('/admin/tutorial/item')
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
})
