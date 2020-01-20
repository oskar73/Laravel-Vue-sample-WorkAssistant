window.categoryData = {
  categories: window.categories || [],
  sections: window.sections,
  onCategoryChange(e) {
    let category = this.categories.find((item) => item.id == e.target.value)
    this.sections = Object.assign([], category.sections)
    window.location.href = '/admin/template/section/edit/' + this.sections[0].id
  },
  onSectionChange(e) {
    window.location.href = '/admin/template/section/edit/' + e.target.value
  }
}

let jsonData

$(function () {
  jsonData = ace.edit('jsonData', {
    theme: 'ace/theme/twilight',
    mode: 'ace/mode/json',
    autoScrollEditorIntoView: true
  })
  jsonData.setValue(JSON.stringify(window.sectionData, null, '\t'))
})

$(document).on('click', '.panel_area_toggle_btn', function () {
  console.log(1)
  $('#panel_area').toggleClass('m-quick-sidebar--on')
  $('.toggle_rest_area').toggleClass('panel_toggled')
})

$(document).on('click', '.panel_tab_btn', function (e) {
  e.preventDefault()
  $('.panel_tab_btn').removeClass('panel_tab_active')
  $('.panel_tab_content_area').removeClass('area_active')
  $(this).addClass('panel_tab_active')
  $($(this).data('area')).addClass('area_active')
})

$(document).on('click', '.panel_tab_content_section .m-accordion__item-head', function (e) {
  e.preventDefault()
  $(this).toggleClass('collapsed')
  $($(this).data('area')).toggleClass('show')
})

$(document).on('change', '#css', function (e) {
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
        // redirectAfterDelay("/admin/template/section");
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
})
$(document).on('change', '.multi-switch-input', function () {
  let target = $(this).data('target')

  if ($(this).prop('checked')) {
    $(`.${target}`).show()
  } else {
    $(`.${target}`).hide()
  }
})
