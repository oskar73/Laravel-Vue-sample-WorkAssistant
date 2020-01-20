const hashMap = {
  '#/setup-username': 25,
  '#/setup-demographic': 50,
  '#/setup-time': 75,
  '#/profile': 100,
}
$(document).ready(function () {
  hashUpdate(window.location.hash)
  updateProgressPercentage(hashMap[window.location.hash])
  $('#timezone').val(timezone).selectpicker()
  $('#date_of_birth').inputmask('9999-99-99', {
    placeholder: 'YYYY-MM-DD'
  })

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
  })
})

$('a.tab-link').on('click', function (e){
    let key = $(e.currentTarget).attr('href')
    if(hashMap[key]){
        updateProgressPercentage(hashMap[key])
    }
})

$('#username_form').on('submit', function (e) {
  e.preventDefault()
  btnLoading()
  $.ajax({
    url: $(this).attr('action'),
    method: 'post',
    data: new FormData(this),
    dataType: 'json',
    contentType: false,
    cache: false,
    processData: false,
    success: function (result) {
      console.log(result)
      btnLoadingStop()
      clearError()
      if (result.status === 1) {
        updateProgressPercentage(25)
        hashUpdate('#/setup-demographic')
      } else {
        dispValidErrors(result.data)
      }
    }
  })
})

$('#demographic_form').on('submit', function (e) {
  e.preventDefault()
  btnLoading()
  $.ajax({
    url: $(this).attr('action'),
    method: 'post',
    data: new FormData(this),
    dataType: 'json',
    contentType: false,
    cache: false,
    processData: false,
    success: function (result) {
      console.log(result)
      btnLoadingStop()
      clearError()
      if (result.status === 1) {
        updateProgressPercentage(50)
        hashUpdate('#/setup-time')
      } else {
        dispValidErrors(result.data)
      }
    }
  })
})

$('#time_form').on('submit', function (e) {
  e.preventDefault()
  btnLoading()
  $.ajax({
    url: $(this).attr('action'),
    method: 'post',
    data: new FormData(this),
    dataType: 'json',
    contentType: false,
    cache: false,
    processData: false,
    success: function (result) {
      console.log(result)
      btnLoadingStop()
      clearError()
      if (result.status === 1) {
        updateProgressPercentage(75)
        hashUpdate('#/profile')
        // hashUpdate('#/setup-complete')
      } else {
        dispValidErrors(result.data)
      }
    }
  })
})

$('#profileForm').on('submit', function (e) {
  e.preventDefault()
  btnLoading()
  $.ajax({
    url: $(this).attr('action'),
    method: 'post',
    data: new FormData(this),
    dataType: 'json',
    contentType: false,
    cache: false,
    processData: false,
    success: function (result) {
      btnLoadingStop()
      clearError()
      if (result.status === 1) {
        updateProgressPercentage(100)
        hashUpdate('#/intro')
      } else {
        dispValidErrors(result.data)
      }
    }
  })
})

$('#introForm').on('submit', function (e) {
  e.preventDefault()
  var profileForm = new FormData($('#profileForm')[0])
  const profileFormKeys = [...profileForm.keys()]

  console.log(profileForm)
  for (const [key, value] of profileForm) {
    console.log(key, value)
  }

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
    method: 'post',
    data: profileForm,
    dataType: 'json',
    contentType: false,
    cache: false,
    processData: false,
    success: function (result) {
      btnLoadingStop()
      clearError()
      if (result.status === 1) {
        updateProgressPercentage(result.data)
        hashUpdate('#/setup-complete')
      } else {
        dispValidErrors(result.data)
      }
    }
  })
})

function updateProgressPercentage(value) {
  $('.progress_percentage').html(value)
  $('.progress_bar').css('width', value + '%')
}
