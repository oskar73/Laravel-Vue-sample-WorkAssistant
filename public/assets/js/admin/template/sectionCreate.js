window.initialData = {
  panelOpen: true,
  show: true,
  tab: true,
  property: {},
  change() {
    jsonData.setValue(JSON.stringify(this.property, null, '\t'))
  }
}

$(function () {
  jsonData = ace.edit('jsonData', {
    theme: 'ace/theme/twilight',
    mode: 'ace/mode/json',
    autoScrollEditorIntoView: true
  })
})

$(document).on('click', '.panel_tab_content_section .m-accordion__item-head', function (e) {
  e.preventDefault()
  $(this).toggleClass('collapsed')
  $($(this).data('area')).toggleClass('show')
})

$(document).on('change', '#content', function (e) {
  $('.section_render_result').html(content.getValue())
})
$(document).on('change', '#css', function (e) {
  $('.section_render_style').html(css.getValue())
})
$(document).on('click', '#render_refresh_btn', function (e) {
  $('.section_render_result').html(content.getValue())
  $('.section_render_style').html(css.getValue())
})

$('#submit_form').submit(function (event) {
  event.preventDefault()
  var formData = new FormData(this)
  formData.append('data', jsonData.getValue().replace(/[\n\t]/g, ''))

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
      console.log(result)
      btnLoadingStop()
      clearError()
      if (result.status === 0) {
        dispErrors(result.data)
        dispValidErrors(result.data)
      } else {
        itoastr('success', 'Success!')
        redirectAfterDelay('/admin/template/section')
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
})
