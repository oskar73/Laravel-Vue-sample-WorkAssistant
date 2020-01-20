$(function () {
  setRandomColor()
})

var target
var canvas = $('#canvas_picker')[0]
var context = canvas.getContext('2d')

$('.from_file').change(function (e) {
  target = $(this).data('id')
  var F = this.files[0]
  var reader = new FileReader()
  reader.onload = imageIsLoaded
  reader.readAsDataURL(F)
  $('#myModal').modal('show')
})

$('#canvas_picker').click(function (event) {
  var x = event.pageX - $(this).offset().left
  var y = event.pageY - $(this).offset().top
  var img_data = context.getImageData(x, y, 1, 1).data
  var R = img_data[0]
  var G = img_data[1]
  var B = img_data[2]
  var rgb = R + ',' + G + ',' + B
  var hex = rgbToHex(R, G, B)
  $('.jgjpickedcolor').val('#' + hex)
  document.getElementById(target).jscolor.fromRGB(R, G, B)
  $('#' + target)
    .parent()
    .parent()
    .find('.hexcolor h3')
    .html(hex)
})

$('#submit_form').submit(function (e) {
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
      btnLoadingStop()
      clearError()

      if (result.status === 0) {
        dispErrors(result.data)
      } else {
        itoastr('success', 'Success')
        redirectAfterDelay('/account/color-palettes#/solid')
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
})
