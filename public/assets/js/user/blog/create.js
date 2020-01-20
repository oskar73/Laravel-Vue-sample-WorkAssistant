var isInitialized = false
var previewCropped = ''
var cropper = ''
var file = ''

var i = 0
var j = 0
var k = 0

$(document).ready(function () {
  $('#category').select2({
    width: '100%',
    placeholder: 'Choose Category',
    minimumResultsForSearch: -1
  })
  $('#tag').select2({
    width: '100%',
    placeholder: 'Choose Tags',
    minimumInputLength: 1
  })
  // tinymceInit('#description')
  Laraberg.init('description')
})

$('#submit_form').submit(function (event) {
  event.preventDefault()
  // tinyMCE.triggerSave()
  var formData = new FormData(this)
  if (formData.get('video')) {
    formData.append('links[]', formData.get('video'))
  }

  $('.smtBtn').prop('disabled', true).html("<i class='fa fa-spinner fa-spin'></i>")
  $.ajax({
    url: $(this).attr('action'),
    method: 'POST',
    data: formData,
    dataType: 'JSON',
    contentType: false,
    cache: false,
    processData: false,
    success: function (result) {
      $('.smtBtn').prop('disabled', false).html('Submit')
      console.log(result)
      if (result.status === 0) {
        dispErrors(result.data)
        dispValidErrors(result.data)
      } else {
        itoastr('success', result.data)

        setTimeout(function () {
          window.location.href = '/account/blog'
        }, 1000)
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
})

$('#category').change(function (event) {
  var $tags = $(this).find(':selected').attr('data-tags')
  $('#tag').val(JSON.parse($tags)).trigger('change.select2')
})

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
