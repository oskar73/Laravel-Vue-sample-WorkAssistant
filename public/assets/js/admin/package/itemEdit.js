var i = 0
var j = 0
var k = 0
var slimOption, slimCropper

$(document).ready(function () {
  $('.select2_item').select2({ width: '100%' })
  hashUpdate(window.location.hash)

    slimOption = {
        ratio: `1:1`,
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
        itoastr('success', 'Successfully updated!')
        reloadAfterDelay()
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
})

$('#setItemForm').on('submit', function (event) {
  event.preventDefault()
  btnLoading('.setItemSmtBtn')
  $.ajax({
    url: $(this).attr('action'),
    method: 'post',
    data: new FormData(this),
    dataType: 'JSON',
    contentType: false,
    cache: false,
    processData: false,
    success: function (result) {
      btnLoadingStop('.setItemSmtBtn')
      clearError()

      if (result.status === 0) {
        dispValidErrors(result.data)
        dispErrors(result.data)
      } else {
        itoastr('success', 'Successfully updated!')
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
})
