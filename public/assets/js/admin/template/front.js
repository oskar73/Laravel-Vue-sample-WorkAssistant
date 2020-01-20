let slimCropper1, slimCropper2
$(document).ready(function () {
  let slimInput1 = $('#slimInput1')
  const url1 = slimInput1.data('url')
  slimCropper1 = new Slim(slimInput1[0], {
    ratio: '48:10',
    download: true,
    buttonRemoveTitle: 'upload',
    instantEdit: false,
    maxFileSize: 50,
    label: 'Drop or choose image'
  })
  console.log(url1)
  if (url1) {
    slimCropper1.load(url1)
  }

  let slimInput2 = $('#slimInput2')
  const url2 = slimInput2.data('url')
  slimCropper2 = new Slim(slimInput2[0], {
    ratio: '716:648',
    download: true,
    buttonRemoveTitle: 'upload',
    instantEdit: false,
    maxFileSize: 50,
    label: 'Drop or choose image'
  })
  if (url2) {
    slimCropper2.load(url2)
  }
})

$(document).on('submit', '#submit_form', function (e) {
  e.preventDefault()
  $('.smtBtn').loading()
  $.post(this.action, new FormData(this), (result) => {
    $('.form-control-feedback').html('')
    itoastr('success', 'Successfully Updated!')
  }).then(() => {
    $('.smtBtn').loading(false)
  })
})
