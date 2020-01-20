var i = 0
var j = 0
var k = 0
var previewCropped = ''
var isInitialized = false
var cropper = ''
var file = ''
var slimOption, slimCropper

$(document).ready(function () {
  hashUpdate(window.location.hash)
  $('#category').val(category).trigger('change')
  $('.selectpicker').selectpicker()

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
$(document).on('click', '#addImage', function () {
  $('#image_area').append(
    '<tr><td><input type="file" accept="image/*" name=\'images[]\' class="form-control m-input--square border-0 uploadImageBox" data-target=\'image-' +
      i +
      "'></td><td><img id='image-" +
      i +
      "' class='width-80px height-80px object-fit' /></td><td><button class='btn btn-danger btn-sm delRowBtn'>X</button></td></tr>"
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
// $('#thumbnail').change(function (event) {
//   var file = this.files[0]
//   if (file) {
//     var img = new Image()

//     img.src = window.URL.createObjectURL(file)

//     img.onload = function () {
//       var oFReader = new FileReader()
//       oFReader.readAsDataURL(file)
//       window.URL.revokeObjectURL(img.src)
//       oFReader.onload = function () {
//         $('#thumbnail_image').attr('src', this.result)

//         if (isInitialized === true) {
//           cropper.destroy()
//         }

//         cropper = new Cropper(document.getElementById('thumbnail_image'), {
//           viewMode: 2,
//           dragMode: 'crop',
//           initialAspectRatio: 2 / 3,
//           aspectRatio: 2 / 3,
//           checkOrientation: false,
//           cropBoxMovable: true,
//           cropBoxResizable: true,
//           zoomOnTouch: true,
//           zoomOnWheel: true,
//           guides: true,
//           highlight: true,
//           crop: function (event) {
//             const canvas = cropper.getCroppedCanvas()
//             previewCropped = canvas.toDataURL()
//           }
//         })
//         isInitialized = true
//       }
//     }
//   }
// })

$('#submit_form').on('submit', function (event) {
  event.preventDefault()
  var formData = new FormData(this)
  if (previewCropped !== '') {
    formData.append('thumbnail', previewCropped)
  }
  $('.smtBtn').html("<i class='fa fa-spinner fa-spin fa-2x fa-fw'></i>").attr('disabled', true)
  $.ajax({
    url: '/admin/module/item/edit/' + item_id,
    method: 'POST',
    data: formData,
    dataType: 'JSON',
    contentType: false,
    cache: false,
    processData: false,
    success: function (result) {
      $('.smtBtn').html('Submit').attr('disabled', false)
      $('.form-control-feedback').html('')

      if (result.status === 0) {
        dispValidErrors(result.data)
        dispErrors(result.data)
      } else {
        itoastr('success', 'Successfully updated!')
        setTimeout(function () {
          window.location.href = '/admin/module/item'
        }, 1000)
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
})
