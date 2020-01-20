var previewCropped = ''
var isInitialized = false
var cropper = ''
var file = ''

$(function () {
  hashUpdate(window.location.hash)
  $('.selectpicker').selectpicker()
})

$('#user').select2(ajaxSelect2(`/admin/teamManage/team/selectUser?user=user&&slug=${slug}`, 'Search user by name or email', 'id', 'nameEmail'))
$('#client').select2(ajaxSelect2(`/admin/teamManage/team/selectUser?user=client&&slug=${slug}`, 'Search user by name or email', 'id', 'nameEmail'))
$('#employee').select2(ajaxSelect2(`/admin/teamManage/team/selectUser?user=employee&&slug=${slug}`, 'Search user by name or email', 'id', 'nameEmail'))

$('#thumbnail_image').change(function (event) {
  var file = this.files[0]
  if (file) {
    var img = new Image()

    img.src = window.URL.createObjectURL(file)

    img.onload = function () {
      var oFReader = new FileReader()
      oFReader.readAsDataURL(file)
      window.URL.revokeObjectURL(img.src)
      oFReader.onload = function () {
        $('#thumbnail').attr('src', this.result)

        if (isInitialized === true) {
          cropper.destroy()
        }

        cropper = new Cropper(document.getElementById('thumbnail'), {
          viewMode: 2,
          dragMode: 'crop',
          autoCropArea: 1,
          aspectRatio: 1,
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
$('#submitForm').submit(function (event) {
  event.preventDefault()

  var formData = new FormData(this)
  if (previewCropped !== '') {
    formData.append('thumbnail', previewCropped)
  }
  $('.smtBtn').attr('disabled', true).html("<i class='fa fa-spinner fa-spin'></i>")
  $.ajax({
    url: $(this).attr('action'),
    method: 'POST',
    data: formData,
    dataType: 'JSON',
    contentType: false,
    cache: false,
    processData: false,
    success: function (result) {
      console.log(result)
      $('.smtBtn').attr('disabled', false).html('Submit')
      if (result.status === 0) {
        dispErrors(result.data)
      } else {
        itoastr('success', 'Successfully Created!')
        window.location.href = '/admin/teamManage/team'
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
})
