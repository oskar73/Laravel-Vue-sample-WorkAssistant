var isInitialized = false
var previewCropped = ''
var cropper = ''
var file = ''

var i = 0
var j = 0
var k = 0

$(document).on('click', '#addImage', function () {
  $('#image_area').append(
    '<tr class="mt-3"><td><label for="image-btn-'+i+'" class="btn btn-outline-success btn-sm">Upload</label><input id="image-btn-' + i + '" type="file" accept="image/*" name=\'images[]\' class="form-control m-input--square uploadImageBox d-none" data-target=\'image-' +
      i +
      "'></td><td><img id='image-" +
      i +
      "' class='width-80px height-80px object-fit' /></td><td><button class='btn btn-danger btn-sm delBtn'>X</button></td></tr>"
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

var slimOption, slimCropper

$(document).ready(function () {
    slimOption = {
        ratio: `${ratio_width}:${ratio_height}`,
        download: true,
        buttonRemoveTitle: 'upload',
        instantEdit: false,
        maxFileSize: 10,
        label: 'Drop or choose image'
    }

    let slimInput = $('#thumbnail')
    slimCropper = new Slim(slimInput[0], slimOption)
    if (window.thumbNailUrl) {
        slimCropper.load(window.thumbNailUrl + '?1')
    }
})

