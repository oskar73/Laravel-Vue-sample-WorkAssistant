let slimCropper1

$(document).ready(function () {
  let slimOption1 = {
    ratio: '48:10',
    download: true,
    buttonRemoveTitle: 'upload',
    instantEdit: false,
    maxFileSize: 50,
    label: 'Drop or choose image'
  }

  let slimInput1 = $('#slimInput1')
  const url = slimInput1.data('url')
  slimCropper1 = new Slim(slimInput1[0], slimOption1)
  if (url) {
    slimCropper1.load(url)
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
