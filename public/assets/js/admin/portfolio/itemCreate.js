$(document).ready(function () {
  $('.selectpicker').selectpicker()
})
var i = 0
var j = 0
var k = 0
var previewCropped = ''
var isInitialized = false
var cropper = ''
var file = ''

$(document).on('click', '#addImage', function () {
  $('#image_area').append(
    '<tr><td><input type="file" accept="image/*" name=\'images[]\' class="form-control m-input--square uploadImageBox" data-target=\'image-' +
      i +
      "'></td><td><img id='image-" +
      i +
      "' class='width-150' /></td><td><button class='btn btn-danger btn-sm delBtn'>X</button></td></tr>"
  )
  i++
})
$(document).on('click', '#addVideo', function () {
  $('#video_area').append(
    '<tr><td><input type="file" accept="video/*" name=\'videos[]\' class="form-control m-input--square"></td><td><button class=\'btn btn-danger btn-sm delBtn\'>X</button></td></tr>'
  )
  j++
})
$(document).on('click', '#addLink', function () {
  $('#link_area').append(
    '<tr><td><input type="url" name=\'links[]\' class="form-control m-input--square"></td><td><button class=\'btn btn-danger btn-sm delBtn\'>X</button></td></tr>'
  )
  k++
})
$(document).on('click', '.delBtn', function () {
  $(this).parent().parent().remove()
})

$('#submit_form').on('submit', function (event) {
  event.preventDefault()
  var formData = new FormData(this)
  $('.smtBtn').html("<i class='fa fa-spinner fa-spin fa-2x fa-fw'></i>").attr('disabled', true)
  $.ajax({
    url: '/admin/portfolio/item/create',
    method: 'POST',
    data: formData,
    dataType: 'JSON',
    contentType: false,
    cache: false,
    processData: false,
    success: function (result) {
      console.log(result)
      $('.smtBtn').html('Submit').attr('disabled', false)
      $('.form-control-feedback').html('')

      if (result.status === 0) {
        dispValidErrors(result.data)
        dispErrors(result.data)
      } else {
        itoastr('success', 'Success!')
        setTimeout(function () {
          window.location.href = '/admin/portfolio/item'
        }, 1000)
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
})
