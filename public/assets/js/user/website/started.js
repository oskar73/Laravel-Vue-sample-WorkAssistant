var module = 0
var fmodule = 0
var modules = module_wishes
var storage = 0
var package = 0
var progress
var page = 0
var step = 1
var template = 0
var header = 0
var footer = 0
var domain
var subdomain
var email
var password
var credentials = 1
var status = 'active'
var totalModules = []
var selectedModules = []
var category = null

$(document).ready(function () {
  getTemplates()
  getDomains()
  loadCustomDomains()
  getModules()
  // minimizeSidebar();
})
function getTemplates(pageUrl = userTypeUrl+'/website/getting-started/getTemplates') {
  const orderBy = $('input[name=template_filter]:checked').val()
  const keyword = $('#template_search').val()
  category = $('#category').val()
  $.ajax({
    url: pageUrl,
    data: { category, orderBy, keyword },
    success: function (result) {
      if (result.status === 1) {
        $('.templates_container').html(result.data)
      } else {
          console.error('getTemplates', result)
      }
    },
    error: function (e) {
      console.error(e)
    }
  })
}

function getDomains() {
  $.ajax({
    url: userTypeUrl+'/website/getting-started/getDomains',
    success: function (result) {
      if (result.status === 1) {
        $('.purchased_domain_append').html(result.data)
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
}

function getModules() {
  const orderBy = $('input[name=module_filter]:checked').val()
  const keyword = $('#module_search').val()
  $.ajax({
    url: userTypeUrl+'/website/getting-started/getModules',
    data: { orderBy: orderBy, keyword: keyword },
    success: function (result) {
      if (result.status === 1) {
        totalModules = result.data.modules
        viewModules()
        $('.module_count').html(result.data.count)
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
}

function viewModules() {
  let view = `<div class="table-responsive module_container_area custom-scroll-h">
    <table class="table table-hover table-bordered table-item-center table-item-padding-0">
        <thead>
            <tr>
                <td class="p-2">Action</td>
                <td class="p-2">Featured</td>
                <td class="p-2">Module Name</td>
                <td class="p-2">Learn more</td>
            </tr>
        </thead>
        <tbody>
  `
  if (totalModules?.length) {
    totalModules.filter(({ slug }) => modules.includes(slug)).forEach(module => {
      view += `<tr>
          <td class="py-1 m-1 btn-sm text-success">
              Selected
          </td>
          <td>${module.featured ? '<span class="c-badge c-badge-success">F</span>' : ''}</td>
          <td>
              ${module.name} ${module.new ? '<span class="c-badge c-badge-danger h-default" title="New">N</span>' : ''}
          </td>
          <td><a href="#" class="m-1 underline learn_more_btn" data-id="${module.id}">Learn more</a></td>
        </tr>
      `
    })
    totalModules.filter(({ slug }) => !modules.includes(slug)).forEach(module => {
      view += `<tr>
          <td>
              <a href="#" class="btn btn-outline-info btn-sm m-1 py-1 module_select_btn"
                  data-id="${module.id}"
                  data-featured="${module.featured}"
                  data-name="${module.name}"
                  data-slug="${module.slug}"
              >Select</a>
          </td>
          <td>${module.featured ? '<span class="c-badge c-badge-success">F</span>' : ''}</td>
          <td>
              ${module.name} ${module.new ? '<span class="c-badge c-badge-danger h-default" title="New">N</span>' : ''}
          </td>
          <td><a href="#" class="m-1 underline learn_more_btn" data-id="${module.id}">Learn more</a></td>
        </tr>
      `
    })
  }
  view += `</tbody></table></div>`

  $('.modules_append').html(view)
}

$(document).on('click', '.templates_container .pagination a.page-link', function (e) {
  e.preventDefault()
  getTemplates($(this).attr('href'))
})

$(document).on('click', '.reload_domain_btn', function (e) {
  e.preventDefault()
  $('.purchased_domain_append').html(`<div class="text-center"><img src="/assets/img/loading_div.gif" alt=""></div>`)
  getDomains()
  domain = null
  $('.chosen_domain_name').html('')
})

$(document).on('click', '.package_start_btn', function () {
  module = $(this).data('module')
  fmodule = $(this).data('fmodule')
  var recommend_modules = $(this).data('modules')
  var selectedTemplate = $(this).data('sltemplate')
  $('input[type=radio][name=template_item][value=' + selectedTemplate + ']').prop('checked', true)
  if (recommend_modules.length) {
    modules = recommend_modules
    viewModules()
  }
  $('.selected_modules_append').html('')
  $.each(modules, function (index, item) {
    var module_obj = moduleSlugToArray(item)
    appendSelectedModules(module_obj['featured'], module_obj['name'], module_obj['slug'])
  })
  storage = $(this).data('storage')
  page = $(this).data('page')
  package = $(this).data('id')
  $('#storage').val(storage)
  $('#page_limit').val(page)
  step = 2
  updateModuleLimitDisplay()
  updatePercentage()
})

$(document).on('click', '.package_resume_btn', function () {
  progress = $(this).data('resume')
  module = $(this).data('module')
  fmodule = $(this).data('fmodule')
  storage = $(this).data('storage')
  page = $(this).data('page')
  package = $(this).data('id')

  $('#storage').val(storage)
  $('#page_limit').val(page)
  step = $(this).data('step')
  var data = $(this).data('data')
  $('#name').val(data.name)
  var selectedTemplate = $(this).data('sltemplate')

  header = data.header === '' ? 0 : data.header
  footer = data.footer === '' ? 0 : data.footer
  template = data.template === '' ? 0 : data.template
  template = !selectedTemplate ? template > 0 ? template : selectedTemplate : selectedTemplate;
  console.log('template', template)
  $('input[type=radio][name=navbar][value=' + header + ']').prop('checked', true)
  $('input[type=radio][name=footer][value=' + footer + ']').prop('checked', true)
  $('input[type=radio][name=template_item][value=' + template + ']').prop('checked', true)
  $('input[type=radio][name=domain_type][value=' + data.domain_type + ']').click()
  if (data.domain_type === 'subdomain') {
    subdomain = data.subdomain
    $('#subdomain').val(subdomain)
    domain = subdomain + '.' + root_domain
  } else if (data.domain_type === 'connected') {
    domain = data.domain
    console.log(domain)
    $("input[data-domain='" + data.domain + "']").prop('checked', true)
  } else if (data.domain_type === 'hosted') {
    domain = data.domain
    $("input[data-domain='" + data.domain + "']").prop('checked', true)
  }
  $('.chosen_domain_name').html(domain)

  modules = JSON.parse(data.modules)
  $('.selected_modules_append').html('')
  $.each(modules, function (index, item) {
    var module_obj = moduleSlugToArray(item)
    appendSelectedModules(module_obj['featured'], module_obj['name'], module_obj['slug'])
  })
  $('#status').val(data.status).selectpicker('refresh')
  $('#email').val(data.email)
  $('#password').val(data.password)
  if (data.credentials == 1) {
    $('#credentials').prop('checked', true)
    $('.custom_credential').addClass('d-none')
  } else {
    $('#credentials').prop('checked', false)
    $('.custom_credential').removeClass('d-none')
  }
  getReviewDetail()
  updateModuleLimitDisplay()
  updatePercentage()
  viewModules()
})

$(document).on('click', '.name_area_next', function (e) {
  e.preventDefault()
  const name = $('#name').val()
  if (name === '') return itoastr('error', 'Please type the name of website.')
  if (step < 3) {
    step = 3
  }
  saveProgress()
  hashUpdate('#/template')
  updatePercentage()
})

$(document).on('click', '.template_area_next', function (e) {
  e.preventDefault()
  // if (template === 0) return itoastr('error', 'Please select template.');
  if (step < 4) {
    step = 4
  }
  saveProgress()
  hashUpdate('#/domain')
  updatePercentage()
})

$(document).on('click', '.setting_area_next', function (e) {
  e.preventDefault()
  status = $('#status').val()
  console.log(status)
  if (status == 'null') return itoastr('error', 'Please choose status')
  if ($('#credentials').prop('checked')) {
    credentials = 1
    email = ''
    password = ''
  } else {
    email = $('#email').val()
    password = $('#password').val()
    if (email === '' || password === '') return itoastr('error', 'Please input admin email and password.')
    if (!validateEmail(email)) return itoastr('error', 'Invalid email address')
    credentials = 0
  }
  if (step < 7) {
    step = 7
  }
  saveProgress()
  hashUpdate('#/review')
  updatePercentage()
})

function validateEmail(email) {
  const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
  return re.test(String(email).toLowerCase())
}
const capitalize = (s) => {
  if (typeof s !== 'string') return ''
  return s.charAt(0).toUpperCase() + s.slice(1)
}

function getReviewDetail() {
  $('.review_domain').html(domain)
  $('.review_name').html($('#name').val())
  $('.review_storage').html($('#storage').val() + ' GB')
  $('.review_page').html($('#page_limit').val())
  $('.review_status').html(capitalize(status))
  $('.review_template').html($('input[type=radio][name=template_item][value=' + template + ']').data('name'))
  $('.review_credentials').html(credentials == 1 ? 'Same credentials' : email + ', ********')
  $('.review_module').html('')

  $.each(modules, function (index, item) {
    var module = moduleSlugToArray(item)
    $('.review_module').append(module['name'] + ', ')
  })
}

function saveProgress() {
  $.ajax({
    url: userTypeUrl+'/website/getting-started/saveStep',
    data: {
      step: step,
      name: $('#name').val(),
      package: package,
      progress: progress,
      template: template,
      header: header,
      footer: footer,
      domain_type: $('input[name=domain_type]:checked').val(),
      domain: domain,
      subdomain: subdomain,
      modules: JSON.stringify(modules),
      credentials: credentials,
      email: email,
      password: password,
      status: status
    },
    success: function (result) {
      if (result.status === 1) {
        progress = result.data
      }
    }
  })

  getReviewDetail()
}

$(document).on('click', '.tab_step_btn', function (e) {
  e.preventDefault()
  if ($(this).data('step') <= step) {
    hashUpdate('#/' + $(this).data('area').substring(1))
    updatePercentage()
  } else {
    return false
  }
})

$(document).on('click', '.domain_area_next', function (e) {
  e.preventDefault()
  if (domain == null) {
    if (domain_type === 'subdomain') {
      return itoastr('error', 'Please check availability of this subdomain.')
    } else {
      return itoastr('error', 'Please choose a domain.')
    }
  }
  if (step < 5) {
    step = 5
  }
  saveProgress()
  hashUpdate('#/module')
  updatePercentage()
})

$(document).on('click', '.module_area_next', function (e) {
  e.preventDefault()
  var exceed
  if (module !== -1 && modules.length - module > 0) {
    exceed = modules.length - module
    itoastr('info', `Please deselect ${exceed} modules.`)
    return false
  } else {
    var current_fmodule = getCurrentFmodules()
    if (fmodule !== -1 && current_fmodule - fmodule > 0) {
      exceed = current_fmodule - fmodule
      itoastr('info', `Please deselect ${exceed} featured modules.`)
      return false
    }
  }

  if (step < 6) {
    step = 6
  }

  saveProgress()
  hashUpdate('#/setting')
  updatePercentage()
})

$(document).on('change', 'input[type=radio][name=template_filter]', function () {
  getTemplates()
})
$(document).on('change', '#category', function () {
  $('#template_search').val('')
  getTemplates()
})
$(document).on('change', 'input[type=radio][name=module_filter]', function () {
  getModules()
})

$(document).on('click', '.learn_more_btn', function (e) {
  e.preventDefault()
  $.ajax({
    url: userTypeUrl+'/website/getting-started/getModuleFeatures',
    data: { id: $(this).data('id') },
    success: function (result) {
      if (result.status === 1) {
        console.log(result.data.view)
        // $('.list_features_append').html(result.data.feature)
        // $('.about_module_append').html(result.data.view)
        $('.about_module_append').html(result.data.feature)
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
})
$(document).on('click', '.module_select_btn', function (e) {
  e.preventDefault()
  var module_slug = $(this).data('slug')
  if (!modules.includes(module_slug)) {
    if (module === -1 || module - modules.length > 0) {
      const featured = $(this).data('featured')
      const name = $(this).data('name')
      if (featured === 1) {
        if (fmodule === -1 || fmodule - getCurrentFmodules() > 0) {
          appendSelectedModules(featured, name, module_slug)
          modules.push(module_slug)
          updateModuleLimitDisplay()
          viewModules(module_slug)
        } else {
          itoastr('info', 'please check the featured module limits.')
        }
      } else {
        appendSelectedModules(featured, name, module_slug)
        modules.push(module_slug)
        updateModuleLimitDisplay()
        viewModules(module_slug)
      }
    } else {
      itoastr('info', 'Please deselect module to choose this one.')
    }
  }
})

function appendSelectedModules(is_featured, name, slug) {
  var featured
  if (is_featured) {
    featured = "<span class='c-badge c-badge-success'>F</span>"
  } else {
    featured = ''
  }
  $('.selected_modules_append').append(`<tr><td>${featured}</td><td>${name}</td><td>
        <a href="#" class="btn btn-outline-danger btn-sm m-1 py-1 module_deselect_btn" data-slug="${slug}">Deselect</a></td></tr>`)
}
$(document).on('click', '.module_deselect_btn', function (e) {
  e.preventDefault()
  var module_slug = $(this).data('slug')
  modules = modules.filter(function (i) {
    return i !== module_slug
  })
  viewModules()
  $(this).parents('tr').remove()
  updateModuleLimitDisplay()
})

$(document).on('keyup', '#module_search', function () {
  getModules()
})

/**
 * Template Preview
 */
$(document).on('click', '.template_item_choose', function (e) {
  e.preventDefault()
  $.ajax({
    url: userTypeUrl+'/website/getting-started/previewTemplate',
    data: { id: $(this).data('id') },
    success: function (result) {
      if (result.status === 1) {
        // window.document.getElementsByTagName('html')[0].style.overflow = 'hidden'
        $('.template_preview_window').addClass('active').html(result.data)
      }
    },
    error: function (e) {
      console.error(e)
    }
  })
})

$(document).on('click', '.preview_header .view_switch_btn', function () {
  $('.parent_iframe_area')
    .removeClass()
    .addClass('parent_iframe_area')
    .addClass($(this).data('hook') + '_area')
})

$(document).on('click', '.preview_header .back_btn', function (e) {
  e.preventDefault()
  $('.template_preview_window').removeClass('active')
  window.document.getElementsByTagName('html')[0].style.overflow = ''
})

$(document).on('click', '.preview_header .choose_btn', function (e) {
  e.preventDefault()
  $('.template_preview_window').removeClass('active')
  $('.selected_template').html(
    `<div><p class="underline">Selected Template:</p><a href="/preview/${$(this).data(
      'slug'
    )}" class="preview_bg w-100 height-250 view_template d-block position-relative bgimg progressive replace" target="_blank" style="background-image:url(${$(this).data(
      'img'
    )})"></a><div class="text-center">${$(this).data('name')}</div></div>`
  )
  template = $(this).data('id')

  header = $(this).data('header') === '' ? 0 : $(this).data('header')
  footer = $(this).data('footer') === '' ? 0 : $(this).data('footer')

  $('input[type=radio][name=navbar][value=' + header + ']').prop('checked', true)
  $('input[type=radio][name=footer][value=' + footer + ']').prop('checked', true)
})
$(document).on('change', 'input[type=radio][name=template_item]', function () {
  $('.selected_template').html(
    `<div><p class="underline">Selected Template:</p><a href="/preview/${$(this).data(
      'slug'
    )}" class="preview_bg w-100 height-250 view_template d-block position-relative bgimg progressive replace" target="_blank" style="background-image:url(${$(this).data(
      'img'
    )})"></a><div class="text-center">${$(this).data('name')}</div></div>`
  )
  template = $(this).data('id')
  header = $(this).data('header') === '' ? 0 : $(this).data('header')
  footer = $(this).data('footer') === '' ? 0 : $(this).data('footer')

  $('input[type=radio][name=navbar][value=' + header + ']').prop('checked', true)
  $('input[type=radio][name=footer][value=' + footer + ']').prop('checked', true)
})
$(document).on('change', 'input[type=radio][name=navbar]', function () {
  header = $(this).val()
  console.log(header)
})
$(document).on('change', 'input[type=radio][name=footer]', function () {
  footer = $(this).val()
})

$(document).on('change', 'input[type=radio][name=hosted_domain]', function () {
  const selector = $('input[type=radio][name=hosted_domain]:checked')
  domain = selector.data('domain')
  $('.chosen_domain_name').html(selector.data('domain'))
})
$(document).on('change', 'input[type=radio][name=connected_domain]', function () {
  const selector = $('input[type=radio][name=connected_domain]:checked')
  domain = selector.data('domain')
  $('.chosen_domain_name').html(selector.data('domain'))
})

$(document).on('keyup', '#template_search', function () {
  $('#category').val('all').selectpicker('refresh')
  getTemplates()
})
$(document).on('change', 'input[type=radio][name=domain_type]', function () {
  var area = $(this).prop('checked', true).data('area')

  $('.domain_area').addClass('d-none')
  $('.' + area).removeClass('d-none')
  domain_type = $('input[type=radio][name=domain_type]:checked').val()
})

$(document).on('click', '#connect_domain_btn', function (e) {
  e.preventDefault()
  var domain = $('#connect_domain').val()
  if (domain === '') return itoastr('error', 'Please input domain name.')

  btnLoading('#connect_domain_btn')
  $.ajax({
    url: userTypeUrl+'/website/connectDomain',
    type: 'post',
    data: { _token: $('meta[name="csrf-token"]').attr('content'), domain: domain },
    success: function (result) {
      console.log(result)
      btnLoadingStop('#connect_domain_btn')
      if (result.status === 1) {
        $('#connect_domain').val('')
        loadCustomDomains()
      } else {
        dispErrors(result.data)
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
})
$(document).on('click', '#sub_domain_btn', function (e) {
  e.preventDefault()
  subdomain = $('#subdomain').val()
  if (subdomain === '') return itoastr('error', 'Please input subdomain name.')

  btnLoading('#sub_domain_btn')
  $.ajax({
    url: userTypeUrl+'/website/getting-started/checkSubDomain',
    data: { subdomain: subdomain },
    success: function (result) {
      btnLoadingStop('#sub_domain_btn')
      if (result.status === 1) {
        itoastr('success', 'Success!')
        domain = subdomain + '.' + root_domain
        $('.chosen_domain_name').html(domain)
      } else {
        dispErrors(result.data)
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
})
function loadCustomDomains() {
  $.ajax({
    url: userTypeUrl+'/website/loadCustom',
    type: 'get',
    success: function (result) {
      console.log(result)
      if (result.status === 1) {
        $('.connected_domain_append').html(result.data)
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
}
function updateModuleLimitDisplay() {
  var available_module, available_fmodule
  if (module === -1) {
    available_module = 'unlimited'
  } else {
    available_module = module - modules.length
  }
  if (fmodule === -1) {
    available_fmodule = 'unlimited'
  } else {
    available_fmodule = fmodule - getCurrentFmodules()
  }
  if (available_fmodule !== 'unlimited' && available_fmodule > available_module) {
    available_fmodule = available_module
  }
  $('.total_module_count').html(available_module)
  $('.total_fmodule_count').html(available_fmodule)
}
function moduleSlugToArray(slug) {
  var result = []
  $.each(JSON.parse(module_array), function (index, item) {
    if (item.slug === slug) {
      result['id'] = item.id
      result['slug'] = item.slug
      result['name'] = item.name
      result['featured'] = item.featured
      return false
    }
  })
  return result
}
function getCurrentFmodules() {
  var result = 0
  $.each(modules, function (index, item) {
    var module_obj = moduleSlugToArray(item)
    if (module_obj['featured'] === 1) result++
  })
  return result
}

$('#credentials').change(function () {
  if ($(this).prop('checked') === true) {
    $('.custom_credential').addClass('d-none')
  } else {
    $('.custom_credential').removeClass('d-none')
  }
})

function updatePercentage() {
  var percent
  if (step > 2) {
    percent = step - 2
  } else {
    percent = 0
  }
  const value = parseFloat((percent * 100) / 5).toFixed(0)
  $('.progress_percentage').html(value)
  $('.progress_bar').css('width', value + '%')
  $('.check_mark_area').html('')
  for (var k = 1; k < 8; k++) {
    if (k < step) $(`.tab_step_btn[data-step='${k}'] .check_mark_area`).html('<span class="la la-check-circle"></span>')
  }
}

$('#submit_form').submit(function (event) {
  event.preventDefault()
  const formData = new FormData(this)
  formData.append('package', package)
  formData.append('name', $('#name').val())
  formData.append('template', template)
  formData.append('header', header)
  formData.append('footer', footer)
  formData.append('progress', progress)
  formData.append('status', status)
  formData.append('email', email)
  formData.append('password', password)
  formData.append('credentials', credentials)

  const domain_type = $('input[name=domain_type]:checked').val()
  formData.append('domain_type', domain_type)
  if (domain_type === 'subdomain') {
    formData.append('subdomain', subdomain)
  } else if (domain_type === 'connected') {
    formData.append('connected_domain', domain)
  } else if (domain_type === 'hosted') {
    formData.append('hosted_domain', domain)
  }
  formData.append('modules', JSON.stringify(modules))

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
      } else {
        itoastr('success', 'Success!')
        step = 8
        // updatePercentage();
        hashUpdate('#/launch')
        $('.launch_append').html(result.data)
        $('.tab_step_btn').attr('href', userTypeUrl+'/website/getting-started').removeClass('tab_step_btn')
        setTimeout(() => {
          window.location.reload();
        }, 1500)
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
})
