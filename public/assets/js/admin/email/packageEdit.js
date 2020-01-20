var i = 0
var j = 0
var k = 0
var previewCropped = ''
var isInitialized = false
var cropper = ''
var file = ''
var del_id

$(document).ready(function () {
  hashUpdate(window.location.hash)
  $('.selectpicker').selectpicker()
  getPrice()
})
$('.addPriceBtn').click(function () {
  $('#edit_price').val(null)
  $('.disable_item').prop('disabled', false)
  $('#create_modal').modal('toggle')
})
$(document).on('blur', '.price', function () {
  if ($(this).val() !== '') {
    console.log($(this).val())
    $(this).val(parseFloat($(this).val()).toFixed(2))
  }
})
$(document).on('click', '#addImage', function () {
  $('#image_area').append(
    '<tr><td><input type="file" accept="image/*" name=\'images[]\' class="form-control m-input--square uploadImageBox" data-target=\'image-' +
      i +
      "'></td><td><img id='image-" +
      i +
      "' class='width-150' /></td><td><button class='btn btn-danger btn-sm delRowBtn'>X</button></td></tr>"
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
function getPrice() {
  $.ajax({
    url: '/admin/email/package/edit/' + item_id,
    method: 'get',
    dataType: 'JSON',
    contentType: false,
    cache: false,
    processData: false,
    success: function (result) {
      console.log(result)
      if (result.status === 0) {
        dispValidErrors(result.data)
      } else {
        $('.price_area').html(result.data)
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
}
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
          initialAspectRatio: 2 / 3,
          aspectRatio: 2 / 3,
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

$('#submit_form').on('submit', function (event) {
  event.preventDefault()
  var formData = new FormData(this)
  if (previewCropped !== '') {
    formData.append('thumbnail', previewCropped)
  }
  $('.smtBtn').html("<i class='fa fa-spinner fa-spin fa-2x fa-fw'></i>").attr('disabled', true)
  $.ajax({
    url: '/admin/email/package/edit/' + item_id,
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
          window.location.href = '/admin/email/package'
        }, 1000)
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
})
$('#meeting_form').on('submit', function (event) {
  event.preventDefault()
  var data = new FormData(this)
  console.log(data)
  $('.smtBtn').html("<i class='fa fa-spinner fa-spin fa-2x fa-fw'></i>").attr('disabled', true)
  $.ajax({
    url: '/admin/email/package/updateMeeting/' + item_id,
    method: 'post',
    data: data,
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
        itoastr('success', 'Successfully updated!')
        setTimeout(function () {
          window.location.href = '/admin/email/package'
        }, 1000)
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
})

$('#create_modal_form').on('submit', function (event) {
  event.preventDefault()
  $('.smtBtn').html("<i class='fa fa-spinner fa-spin fa-2x fa-fw'></i>").attr('disabled', true)
  $.ajax({
    url: $(this).attr('action'),
    method: 'post',
    data: new FormData(this),
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
        $('#create_modal').modal('toggle')
        getPrice()
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
})

$(document).on('click', '.editBtn', function () {
  var price = $(this).data('price')
  $('#edit_price').val(price.id)
  $('#payment_type').val(price.recurrent)
  $('#period').val(price.period)
  $('#period_unit').val(price.period_unit)
  $('#price').val(price.price)
  $('#slashed_price').val(price.slashed_price)
  $('#price_standard').prop('checked', price.standard == 1 ? true : false)
  $('#price_status').prop('checked', price.status == 1 ? true : false)

  $('.disable_item').prop('disabled', true)
  $('.selectpicker').selectpicker('refresh')

  if (price.recurrent == 0) {
    $('#price').prop('disabled', false)
  }
  $('#create_modal').modal('toggle')
})

$(document).on('click', '.delBtn', function () {
  del_id = $(this).data('id')
  askToast.question('Confirm', 'Do you want to delete this item?', 'delPerform')
})
function delPerform() {
  $.ajax({
    headers: {
      'X-CSRF-TOKEN': token
    },
    url: '/admin/email/package/deletePrice/' + item_id,
    method: 'delete',
    data: { id: del_id },
    success: function (result) {
      if (result.status === 0) {
        dispValidErrors(result.data)
      } else {
        itoastr('success', 'Successfully deleted!')
        getPrice()
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
}
