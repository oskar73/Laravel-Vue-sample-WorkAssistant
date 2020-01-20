var isInitialized = false
var previewCropped = ''
var cropper = ''
var file = ''
var i = 0
var j = 0
var k = 0

var convertFormDataToObject = (formData) => {
  var object = {}
  formData.forEach((value, key) => {
    // Reflect.has in favor of: object.hasOwnProperty(key)
    if(!Reflect.has(object, key)){
      object[key] = value
      return
    }
    if(!Array.isArray(object[key])){
      object[key] = [object[key]]
    }
    object[key].push(value)
  })

  return object
}

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

  $('#visible_date').datepicker({
    format: 'yyyy-mm-dd',
    todayHighlight: '!0',
    autoclose: !0
  })
})
$('#thumbnail').change(function (event) {
  var file = this.files[0]
  if (file) {
    var img = new Image()

    img.src = window.URL.createObjectURL(file)

    img.onload = function () {
      var oFReader = new FileReader()
      oFReader.readAsDataURL(file)
      window.URL.revokeObjectURL(img.src)
      oFReader.onload = function () {
        $('#thumbnail_image').attr('src', this.result)

        if (isInitialized === true) {
          cropper.destroy()
        }

        cropper = new Cropper(document.getElementById('thumbnail_image'), {
          viewMode: 2,
          dragMode: 'crop',
          initialAspectRatio: 4 / 3,
          aspectRatio: 4 / 3,
          checkOrientation: false,
          cropBoxMovable: true,
          cropBoxResizable: true,
          zoomOnTouch: true,
          zoomOnWheel: true,
          guides: true,
          highlight: true,
          crop: function (event) {
            const canvas = cropper.getCroppedCanvas()
            previewCropped = canvas.toDataURL()
          }
        })
        isInitialized = true
      }
    }
  }
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

$('#submit_form').submit(function (event) {
  event.preventDefault()
  // tinyMCE.triggerSave()
  var formData = new FormData(this)
  if (previewCropped !== '') {
    formData.append('image', previewCropped)
  }
  var formObject = convertFormDataToObject(formData)

  if (formObject['video']) {
    if (formObject['links[]']?.includes(formObject['video']) || formObject['links[]'] === formObject['video']) {
      formData.delete('links[]')
      formData.append('links[]', formObject['video'])
      if (typeof formObject['links[]'] !== 'string') {
        formObject['links[]'].forEach(function (link) {
          if (link !== formObject['video']) {
            formData.append('links[]', link)
          }
        })
      }
    } else {
      formData.append('links[]', formObject['video'])
    }
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
        itoastr('success', 'Successfully Updated!')
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
