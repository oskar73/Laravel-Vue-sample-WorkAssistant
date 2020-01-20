
var slimOption, slimCropper

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

    slimOption = {
        ratio: ratio_width+':'+ratio_height,
        download: true,
        buttonRemoveTitle: 'upload',
        instantEdit: false,
        maxFileSize: maxImageSize,
        label: 'Drop or choose image'
    }

    let slimInput = $('#image')
    slimCropper = new Slim(slimInput[0], slimOption)
    if (window.imageUrl) {
        slimCropper.load(window.imageUrl + '?1')
    }
})

$('#submit_form').submit(function (event) {
  event.preventDefault()
  // tinyMCE.triggerSave()
  var formData = new FormData(this)
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

  btnLoading()
  $.ajax({
    url: $(this).attr('action'),
    method: 'POST',
    data: formData,
    dataType: 'JSON',
    contentType: false,
    cache: false,
    processData: false,
    success: function (result) {
      btnLoadingStop()
      clearError()
      if (result.status === 0) {
        dispErrors(result.data)
        dispValidErrors(result.data)
      } else {
        itoastr('success', 'Success!')
        // redirectAfterDelay('/admin/blog/post/edit/'+postId)
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
$('#status').change(function (event) {
  if ($(this).val() === 'denied') {
    $('.reason_area').show()
  } else {
    $('.reason_area').hide()
  }
})
