var i = 0
var j = 0
var k = 0

$(document).ready(function () {
  hashUpdate(window.location.hash)
  $('.selectpicker').selectpicker()
})
$(document).on('click', '#addImage', function () {
  $('#image_area').append(
    '<tr><td><input type="file" accept="image/*" name=\'images[]\' class="form-control m-input--square uploadImageBox" data-target=\'image-' +
      i +
      "'></td><td><img id='image-" +
      i +
      "' class='w-150px' /></td><td><button class='btn btn-danger btn-sm delRowBtn'>X</button></td></tr>"
  )
  i++
})
$(document).on('click', '#addVideo', function () {
  $('#video_area').append(
    '<tr><td><input type="file" accept="video/*" name=\'videos[]\' class="form-control m-input--square"></td><td><button class=\'btn btn-danger btn-sm delRowBtn\'>X</button></td></tr>'
  )
  j++
})
$(document).on('click', '#addLink', function () {
  $('#link_area').append(
    '<tr><td><input type="url" name=\'links[]\' class="form-control m-input--square"></td><td><button class=\'btn btn-danger btn-sm delRowBtn\'>X</button></td></tr>'
  )
  k++
})
$(document).on('click', '.delRowBtn', function () {
  $(this).parent().parent().remove()
})
$('#submit_form').on('submit', function (event) {
  event.preventDefault()
  var formData = new FormData(this)

  btnLoading()
  $.ajax({
    url: '/admin/directory/package/edit/' + item_id,
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
        window.location.hash = '#/price'
        reloadAfterDelay()
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
})
