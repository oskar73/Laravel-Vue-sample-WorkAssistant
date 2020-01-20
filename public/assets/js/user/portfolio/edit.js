let i = 0
let j = 0
let k = 0
let slimOption, slimCropper

$(function () {
  slimOption = {
    ratio: '1:1',
    download: true,
    buttonRemoveTitle: 'upload',
    instantEdit: false,
    maxFileSize: 50,
    label: 'Drop or choose image'
  }

  let slimInput = $('#slimInput')
  slimCropper = new Slim(slimInput[0], slimOption)
  if (window.thumbNailUrl) {
    slimCropper.load(window.thumbNailUrl + '?1')
  }

  $('#submit_form').on('submit', function (e) {
    e.preventDefault()
    var formData = new FormData(this)
    $('.smtBtn').html("<i class='fa fa-spinner fa-spin fa-2x fa-fw'></i>").attr('disabled', true)

    $.post(
      this.action,
      formData,
      (result) => {
        $('.smtBtn').html('Submit').attr('disabled', false)
        $('.form-control-feedback').html('')
        if (result.status === 0) {
          dispValidErrors(result.data)
          dispErrors(result.data)
        } else {
          itoastr('success', 'Success!')
          window.location.href = result.data.redirect_url;
        }
      },
      (error) => {
        $('.smtBtn').html('Submit').attr('disabled', false)
        $('.form-control-feedback').html('')
        dispValidErrors(error)
        dispErrors(error)
      }
    )
  })
})

$(document).on('click', '#addImage', function () {
  $('#image_area').append(
    '<tr><td><input type="file" accept="image/*" name=\'images[]\' class="form-control m-input--square uploadImageBox" data-target=\'image-' +
      i +
      "'></td><td><img id='image-" +
      i +
      "' class='w-150px' /></td><td><button class='btn btn-danger btn-sm delBtn'>X</button></td></tr>"
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
