let slimCropper
let previewCropped = '';

$(document).ready(function () {
  $('.selectpicker').selectpicker()
  tinymceInit('#description')
  slimCropper = new Slim(document.getElementById('thumbnail'), {
    ratio: '3:2',
    download: true,
    buttonRemoveTitle: 'upload',
    instantEdit: false,
    maxFileSize: 50,
    label: 'Drop or choose image'
  })
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
      btnLoadingStop()
      clearError()

      if (result.status === 0) {
        dispValidErrors(result.data)
        dispErrors(result.data)
      } else {
        itoastr('success', 'Success!')
        redirectAfterDelay('/admin/video/item')
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
})
