var previewCropped = ''
var isInitialized = false
var cropper = ''
var file = ''
var slimOption, slimCropper

function insert_contents(inst){
  inst.setContent(introduction);
}
$(document).ready(function () {
  tinymce.init({
    selector: "#intro", // change this value according to the HTML
    plugins: 'link autolink emoticons wordcount paste',
    toolbar: 'bold link unlink emoticons blockquote',
    menubar: false,
    statusbar: false,
    height: 385,
    paste_preprocess: function(plugin, args) {
      console.log('Attempted to paste: ', args.content)
      // replace copied text with empty string
      args.content = ''
    },
    init_instance_callback: "insert_contents"
  })
})


$(function () {
  hashUpdate(window.location.hash)
  $('#date_of_birth').inputmask('9999-99-99', {
    placeholder: 'YYYY-MM-DD'
  })
  $('.selectpicker').selectpicker()

    slimOption = {
        ratio: `1:1`,
        download: true,
        buttonRemoveTitle: 'upload',
        instantEdit: false,
        maxFileSize: maxImageSize,
        label: 'Drop or choose profile photo'
    }

    let slimInput = $('#image')
    slimCropper = new Slim(slimInput[0], slimOption)
    if (window.thumbNailUrl) {
        slimCropper.load(window.thumbNailUrl + '?1')
    }
})
// $('#image').change(function (event) {
//   var file = this.files[0]
//   if (file) {
//     var img = new Image()

//     img.src = window.URL.createObjectURL(file)

//     img.onload = function () {
//       var oFReader = new FileReader()
//       oFReader.readAsDataURL(file)
//       window.URL.revokeObjectURL(img.src)
//       oFReader.onload = function () {
//         $('#avatar').attr('src', this.result)

//         if (isInitialized === true) {
//           cropper.destroy()
//         }

//         cropper = new Cropper(document.getElementById('avatar'), {
//           viewMode: 2,
//           dragMode: 'crop',
//           autoCropArea: 1,
//           aspectRatio: 1,
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

$('#introForm').on('submit', function (event) {
  event.preventDefault()
  var profileForm = new FormData($('#profileForm')[0])
  if (previewCropped !== '') {
    profileForm.append('image', previewCropped)
  }

  const profileFormKeys = [...profileForm.keys()]

  var introForm = new FormData(this)
  const description = tinymce.get("intro").getContent();
  introForm.append('introduction', description)

  for (const [key, value] of introForm) {
    if (!profileFormKeys.includes(key)) {
      profileForm.append(key, value)
    }
  }

  btnLoading()
  $.ajax({
    url: $(this).attr('action'),
    method: 'POST',
    data: profileForm,
    dataType: 'JSON',
    contentType: false,
    cache: false,
    processData: false,
    success: function (result) {
      console.log(result)
      btnLoadingStop()
      clearError()

      if (result.status === 0) {
        dispValidErrors(result.data)
        dispErrors(result.data)
      } else {
        itoastr('success', 'Success!')
        reloadAfterDelay()
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
})
$('#profileForm').on('submit', function (event) {
  event.preventDefault()
  var profileForm = new FormData(this)
  if (previewCropped !== '') {
    profileForm.append('image', previewCropped)
  }

  const profileFormKeys = [...profileForm.keys()]

  var introForm = new FormData($('#introForm')[0])
  const description = tinymce.get("intro").getContent();
  introForm.append('introduction', description)

  for (const [key, value] of introForm) {
    if (!profileFormKeys.includes(key)) {
      profileForm.append(key, value)
    }
  }

  btnLoading()
  $.ajax({
    url: $(this).attr('action'),
    method: 'POST',
    data: profileForm,
    dataType: 'JSON',
    contentType: false,
    cache: false,
    processData: false,
    success: function (result) {
      console.log(result)
      btnLoadingStop()
      clearError()

      if (result.status === 0) {
        dispValidErrors(result.data)
        dispErrors(result.data)
      } else {
        itoastr('success', 'Success!')
        reloadAfterDelay()
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
})
$('#passwordForm').on('submit', function (event) {
  event.preventDefault()

  btnLoading('.pswBtn')
  $.ajax({
    url: $(this).attr('action'),
    method: 'POST',
    data: new FormData(this),
    dataType: 'JSON',
    contentType: false,
    cache: false,
    processData: false,
    success: function (result) {
      btnLoadingStop('.pswBtn')
      clearError()

      if (result.status === 0) {
        dispValidErrors(result.data)
        dispErrors(result.data)
      } else {
        itoastr('success', result.success);
        if(result.redirect_url){
          window.location.href = result.redirect_url
        } else {
          reloadAfterDelay();
        }
        // $('#password_area').html(result.data)
      }
    },
    error: function (err) {
      if( typeof err.responseJSON !== 'undefined' ){
        let errors = err.responseJSON.errors;

        btnLoadingStop('.pswBtn');
        clearError();
        dispValidErrors(errors);
        // dispErrors(errors);
      }
    }
  })
})
