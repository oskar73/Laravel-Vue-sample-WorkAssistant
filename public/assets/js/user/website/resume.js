/**
 * Define variables
 */
var module = currentPackage.module || 0
var fmodule = currentPackage.featured_module || 0
var modules = module_wishes
var storage = currentPackage.storage || 0
var package = currentPackage.id || 0
var progress = currentProgress?.id
var page = currentPackage.page || 0
var step = currentProgress?.step || 2
var template = 0
var header = 0
var footer = 0
var email
var password
var credentials = 1
var status = 'active'
var totalModules = []
var selectedModules = []
var category = null
var data = currentProgress?.data || {}
var subdomain = data.subdomain
var domain_type = data?.domain_type || 'subdomain'
var domain_step = data?.subdomain ? 2 : 1
var domain = data?.domain
var availablePackagesOnly = true

var hash
var orderBy = 'featured'
var keyword = ''

var target_package_id = null

/**
 * Initialize
 */
$(document).ready(function() {
  // Choose Name
  $('#name').val(data.name)
  //Choose Domain
  $('#subdomain').val(subdomain)

  //Choose Template
  hash = window.location.hash
  var selectedTemplate = data.template || 0
  template = data.template === '' ? 0 : data.template
  template = !selectedTemplate ? template > 0 ? template : selectedTemplate : selectedTemplate

  $('#storage').val(storage)
  $('#page_limit').val(page)

  $('input[type=radio][name=navbar][value=' + header + ']').prop('checked', true)
  $('input[type=radio][name=footer][value=' + footer + ']').prop('checked', true)

  modules = JSON.parse(data.modules || '[]')
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

  if (domain_step == 2) {
    $('.domain_type_section').show()
    if (!domain_type) domain_type = 'subdomain'
  }

  getDomains()
  getTemplates()
  updateDomainText()
  loadCustomDomains()
  getModules()
  getPackages()
  viewSelectedModules()
  viewAvailableModules()

  getReviewDetail()
  updateModuleLimitDisplay()
  updatePercentage()
  hashUpdate(hash)
})

$(window).on('hashchange', function() {
  hash = window.location.hash
  hashUpdate(hash)
})

/**
 * Widget Toggle
 */
$(document).on('click', '.widget-toggle-btn', function(e) {
  $('.sidebar-tab').toggle()
})

/**
 * Choose Name
 */
$(document).on('click', '.name_area_next', function(e) {
  e.preventDefault()
  const name = $('#name').val()
  if (name === '') return itoastr('error', 'Please type the name of website.')
  if (step < 3) {
    step = 3
  }
  saveProgress()
  hashUpdate('#/domain')
  updatePercentage()
})

/**
 * Choose Domain
 */
function getDomains() {
  $.ajax({
    url: userTypeUrl + '/website/getting-started/getDomains',
    success: function(result) {
      if (result.status === 1) {
        $('.purchased_domain_append').html(result.data)
        if (data.domain_type === 'subdomain') {
          subdomain = data.subdomain
          $('#subdomain').val(subdomain)
          if (subdomain) {
            domain = subdomain + '.' + root_domain
          }
        } else if (data.domain_type === 'connected') {
          domain = data.domain
          $('input[data-domain=\'' + data.domain + '\']').prop('checked', true)
        } else if (data.domain_type === 'hosted') {
          domain = data.domain
          $('input[data-domain=\'' + data.domain + '\']').prop('checked', true)
        }
        $('.chosen_domain_name').html(domain)
      }
    },
    error: function(e) {
      console.log(e)
    }
  })
}

$(document).on('change', 'input[type=radio][name=hosted_domain]', function() {
  const selector = $('input[type=radio][name=hosted_domain]:checked')
  domain = selector.data('domain')
  $('.chosen_domain_name').html(selector.data('domain'))
})
$(document).on('change', 'input[type=radio][name=connected_domain]', function() {
  const selector = $('input[type=radio][name=connected_domain]:checked')
  domain = selector.data('domain')
  $('.chosen_domain_name').html(selector.data('domain'))
})
$(document).on('keyup', '#template_search', function() {
  $('#category').val('all').selectpicker('refresh')
  getTemplates()
})
$(document).on('click', '.domain-connect-btn', function() {
  var area = $(this).data('area')

  $('.domain_area').addClass('d-none')
  $('.' + area).removeClass('d-none')
  domain_type = $(this).data('value')
  updateDomainText()
})
$(document).on('click', '#connect_domain_btn', function(e) {
  e.preventDefault()
  var domain = $('#connect_domain').val()
  if (domain === '') return itoastr('error', 'Please input domain name.')

  btnLoading('#connect_domain_btn')
  $.ajax({
    url: userTypeUrl + '/website/connectDomain',
    type: 'post',
    data: { _token: $('meta[name="csrf-token"]').attr('content'), domain: domain },
    success: function(result) {
      btnLoadingStop('#connect_domain_btn')
      if (result.status === 1) {
        $('#connect_domain').val('')
        loadCustomDomains()
      } else {
        dispErrors(result.data)
      }
    },
    error: function(e) {
      console.log(e)
    }
  })
})
$(document).on('click', '#sub_domain_btn', function(e) {
  e.preventDefault()
  subdomain = $('#subdomain').val()
  if (subdomain === '') return itoastr('error', 'Please input subdomain name.')

  btnLoading('#sub_domain_btn')
  $.ajax({
    url: userTypeUrl + '/website/getting-started/checkSubDomain',
    data: { subdomain: subdomain },
    success: function(result) {
      btnLoadingStop('#sub_domain_btn')
      if (result.status === 1) {
        itoastr('success', 'Success!')
        domain = subdomain + '.' + root_domain
        $('.chosen_domain_name').html(domain)
      } else {
        dispErrors(result.data)
      }
    },
    error: function(e) {
      console.log(e)
    }
  })
})
$(document).on('click', '.domain_area_next', function(e) {
  e.preventDefault()

  subdomain = $('#subdomain').val()
  if (!subdomain) {
    return itoastr('error', 'Please insert subdomain.')
  }

  if (domain_step == 1) {
    if (!domain) {
      return itoastr('error', 'Please check availability of this subdomain.')
    }

    domain_step = 2
    domain_type = 'subdomain'
    return updateDomainText()
  }

  if (domain_type == 'hosted') {
    domain = $('input[name="hosted_domain"]:checked').data('domain')
  }

  if (domain_type == 'connected') {
    domain = $('input[name="connected_domain"]:checked').data('domain')
  }

  if (!domain) {
    return itoastr('error', 'Please choose a domain.')
  }

  if (step < 4) {
    step = 4
  }
  saveProgress()
  hashUpdate('#/template')
  updatePercentage()
})
$(document).on('click', '.reload_domain_btn', function(e) {
  e.preventDefault()
  $('.purchased_domain_append').html(`<div class="text-center"><img src="/assets/img/loading_div.gif" alt=""></div>`)
  getDomains()
  domain = null
  $('.chosen_domain_name').html('')
})

function updateDomainText() {
  if (domain_type && subdomain) {
    $('#subdomain').val(subdomain)
    domain = subdomain + '.' + root_domain
  }

  if (subdomain) {
    $('.domain_input_section').hide()
  }
  $('.domain-connect-btn.btn-info').addClass('btn-outline-info')
  $('.domain-connect-btn.btn-info').removeClass('btn-info')

  $('#btn_domain_' + domain_type).removeClass('btn-outline-info')
  $('#btn_domain_' + domain_type).addClass('btn-info')

  if (domain_type && subdomain) {
    $('.domain_type_section').show()
  }
}

function loadCustomDomains() {
  $.ajax({
    url: userTypeUrl + '/website/loadCustom',
    type: 'get',
    success: function(result) {
      if (result.status === 1) {
        $('.connected_domain_append').html(result.data)
        updateDomainText()
      }
    },
    error: function(e) {
      console.log(e)
    }
  })
}

/**
 * Template Preview
 */
$(document).on('click', '.category-menu li a', function() {
  hash = $(this).attr('href')
  keyword = ''
  $('#keyword').val('')
  getTemplates()
})
$(document).on('click', '.breadcrumb li a', function() {
  hash = $(this).attr('href')
  keyword = ''
  $('#keyword').val('')
  getTemplates()
})
$(document).on('change', 'input[type=radio][name=filterBy]', function() {
  orderBy = $(this).val()
  getTemplates()
})
$(document).on('keyup', '#keyword', function() {
  keyword = $(this).val()
  getTemplates()
  resetActiveClass('all', 'all')
  hash = 'all'
})
$(document).on('click', '.pagination a.page-link', function(e) {
  e.preventDefault()
  pageUrl = $(this).attr('href')
  getTemplates()
})
$(document).on('click', '.choose_temp_btn', function(e) {
  template = $(this).data('id')
  getTemplates()
})

function resetActiveClass(cat, sub) {
  $('.category-menu li').removeClass('menu-active')
  if (cat) $('.category-menu .' + cat).addClass('menu-active')
  if (sub) $('.category-menu .' + sub).addClass('menu-active')
}

function getCurrentTemplate() {
  var pageUrl = userTypeUrl + '/website/getting-started/current-template-view'
  $.ajax({
    url: pageUrl,
    data: { template },
    success: function(result) {
      $('.chosen_template_result').html(result)
    },
    error: function(e) {
      console.log(e)
    }
  })
}

function getTemplates() {
  var pageUrl = userTypeUrl + '/website/getting-started/templates?page=1'
  var a = hash.split('/')

  var temp = a[1] === undefined ? 'all' : a[1]
  var cat = a[2] === undefined ? temp : a[2]
  if (['name', 'template', 'domain', 'package', 'module', 'change_package', 'setting', 'review'].includes(cat)) {
    cat = 'all'
  }

  resetActiveClass(temp, cat)

  $.ajax({
    url: pageUrl,
    data: { category: cat, orderBy, keyword, template },
    success: function(result) {
      if (result.status === 1) {
        $('.templates_result').html(result.view)
        $('.chosen_template_result').html(result.selected)
      } else {
        console.log('error')
      }
    },
    error: function(e) {
      console.log(e)
    }
  })
}

$(document).on('click', '.template_item_choose', function(e) {
  e.preventDefault()
  $.ajax({
    type: 'get',
    url: '/template/preview/' + $(this).data('slug'),
    success: function(result) {
      if (result.status === 1) {
        window.document.getElementsByTagName('html')[0].style.overflow = 'hidden'
        $('.template_preview_window').addClass('active').html(result.data)
      }
    },
    error: function(e) {
      console.error(e)
    }
  })
})
$(document).on('click', '.preview_header .view_switch_btn', function() {
  $('.parent_iframe_area')
    .removeClass()
    .addClass('parent_iframe_area')
    .addClass($(this).data('hook') + '_area')
})
$(document).on('click', '.preview_header .back_btn', function(e) {
  e.preventDefault()
  $('.template_preview_window').removeClass('active')
  window.document.getElementsByTagName('html')[0].style.overflow = ''
})
$(document).on('click', '.preview_header .choose_btn', function(e) {
  e.preventDefault()
  template = $(this).data('id')
  $('.template_preview_window').removeClass('active')
  window.document.getElementsByTagName('html')[0].style.overflow = ''
  getTemplates()
})
$(document).on('click', '.template_area_next', function(e) {
  e.preventDefault()
  // if (template === 0) return itoastr('error', 'Please select template.');
  if (step < 5) {
    step = 5
  }
  saveProgress()
  hashUpdate('#/module')
  updatePercentage()
})
$(document).on('click', '.templates_container .pagination a.page-link', function(e) {
  e.preventDefault()
  getTemplates($(this).attr('href'))
})

/**
 * Choose Modules
 */
$(document).on('click', '.del_module_btn', function(e) {
  var module_slug = $(this).data('module')
  var index = modules.findIndex(m => m === module_slug)
  if (index !== -1) {
    modules.splice(index, 1)
    viewSelectedModules()
    viewAvailableModules()
    getModules()
    getPackages()
    updateModuleLimitDisplay()
  }
})
$(document).on('click', '.apps_btn', function(e) {
  $('#apps_modal').modal('show')
})
$(document).on('click', '.choose_module_btn', function(e) {
  var module_slug = $(this).data('slug')
  modules.push(module_slug)
  const cardElement = $(`.choose_module_btn:not(.add_module_btn)[data-slug="${module_slug}"]`)
  cardElement.html('Chosen')
  cardElement.prop('disabled', true)
  viewSelectedModules()
  viewAvailableModules()
  getPackages()
  updateModuleLimitDisplay()
  $('.app_chosen_count').html(modules.length)
})

$(document).on('click', '.view_btn', function(e) {
  var id = $(this).data('id')

  $.ajax({
    url: userTypeUrl + '/website/getting-started/module-detail',
    data: { id },
    success: function(result) {
      if (result.status) {
        $('#view_content').html(result.data)
        $('#view_modal').modal('toggle')
      }
    },
    error: function(e) {
      console.log(e)
    }
  })
})
$(document).on('click', '.video_btn', function(e) {
  var video = $(this).data('video')
  var youtube = $(this).data('youtube')
  if (youtube) {
    const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/
    const match = youtube.match(regExp)

    if (match && match[2].length == 11) {
      youtube = match[2]
    }
  }

  $('#video_content').html(video ? `
    <video controls>
      <source src="${video}">
      Your browser does not support HTML5 video.
    </video>
  ` : `
    <iframe class="iframe-video" src="https://www.youtube.com/embed/${youtube}" frameborder="0" allowfullscreen></iframe>
  `)
  $('#video_modal').modal('toggle')
})
$(document).on('click', '.pagination a.page-link', function(e) {
  e.preventDefault()
  pageUrl = $(this).attr('href')
  getModules(pageUrl)
})
$(document).on('click', '.category-menu li a', function() {
  hash = $(this).attr('href')
  keyword = ''
  $('#keyword').val('')
  getModules()
})

function getModules(pageUrl = userTypeUrl + '/website/getting-started/modules?page=1') {
  var a = hash.split('/')

  var temp = a[1] === undefined ? 'all' : a[1]
  var cat = a[2] === undefined ? temp : a[2]
  if (['name', 'template', 'domain', 'package', 'module', 'change_package', 'setting', 'review'].includes(cat)) {
    cat = 'all'
  }

  resetActiveClass(temp, cat)
  $.ajax({
    url: pageUrl,
    data: { category: cat, orderBy, keyword, modules, dashboard: true },
    success: function(result) {
      if (result.status === 1) {
        $('.modules_result').html(result.view)
      } else {
      }
    },
    error: function(e) {
      console.log(e)
    }
  })
}

// old
$(document).on('click', '.module_deselect_btn', function(e) {
  e.preventDefault()
  var module_slug = $(this).data('slug')
  modules = modules.filter(function(i) {
    return i !== module_slug
  })
  $(this).parents('tr').remove()
  updateModuleLimitDisplay()
})
$(document).on('keyup', '#module_search', function() {
  getModules()
})

function updateModuleLimitDisplay() {
  var available_module, available_fmodule
  if (module == -1) {
    available_module = 'unlimited'
  } else {
    available_module = module - modules.length
  }
  if (fmodule == -1) {
    available_fmodule = 'unlimited'
  } else {
    available_fmodule = fmodule - getCurrentFmodules()
  }
  if (available_fmodule !== 'unlimited' && available_fmodule > available_module) {
    available_fmodule = available_module
  }
  $('.total_module_count').html(available_module)
  $('.total_fmodule_count').html(available_fmodule)
  if (JSON.stringify(modules.sort()) === JSON.stringify(module_recommended.sort())) {
    $('#recommended_alert').remove()
  }
}

function moduleSlugToArray(slug) {
  var result = []
  $.each(JSON.parse(module_array), function(index, item) {
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
  $.each(modules, function(index, item) {
    var module_obj = moduleSlugToArray(item)
    if (module_obj['featured'] === 1) result++
  })
  return result
}

function viewSelectedModules() {
  $.ajax({
    url: '/modules/selected-modules',
    data: { modules, hasModules: true },
    success: function(result) {
      if (result.status) {
        $('#apps_content').html(result.data.modal)
        $('.selected_apps').html(result.data.sidebar)

        const galleryContainer = $('#chosen_app_gallery')
        if (galleryContainer.data('owl.carousel')) {
          galleryContainer.trigger('destroy.owl.carousel')
        }
        if (result.data.gallery) {
          galleryContainer.addClass('owl-carousel')
          galleryContainer.html(result.data.gallery)
          galleryContainer.owlCarousel({
            loop: false,
            dots: false,
            nav: false,
            autoplay: true,
            autoplayTimeout: 5000,
            responsiveClass: true,
            autoplayHoverPause: false,
            responsive: {
              0: { items: 1, margin: 0 },
              481: { items: 2, margin: 5 },
              500: { items: 2, margin: 20 },
              992: { items: 3, margin: 30 },
              1200: { items: 4, margin: 30 }
            }
          })
        } else {
          galleryContainer.removeClass('owl-carousel')
          galleryContainer.html('<div class="tw-flex tw-justify-center"><p class="tw-text-lg">No Chosen Apps</p></div>')
        }
      }
    },
    error: function(e) {
      console.log(e)
    }
  })
}

function viewAvailableModules() {
  $.ajax({
    url: '/modules/available-modules',
    data: { modules, hasModules: true },
    success: function(result) {
      if (result.status) {
        $('.available_apps').html(result.data)
      }
    },
    error: function(e) {
      console.log(e)
    }
  })
}

function getPackages() {
  $.ajax({
    url: '/package',
    data: { dashboard: true, selectedModulesNum: modules.length, availableOnly: availablePackagesOnly },
    success: function(result) {
      if (result.status === 1) {
        $('.package_result').html(result.view)
      }
    },
    error: function(e) {
      console.log(e)
    }
  })
}

$(document).on('click', '#switch_package_visibility', function() {
  availablePackagesOnly = !availablePackagesOnly
  $(this).data('available', 'false').html(availablePackagesOnly ? 'View All' : 'View Available Only')
  $('#package_label').html(availablePackagesOnly ? 'Available Packages For Your Chosen Number Of Apps' : 'All Packages')
  getPackages()
})

$(document).on('click', '.module_area_next', function(e) {
  e.preventDefault()
  var exceed
  if (module != -1 && modules.length - module > 0) {
    exceed = modules.length - module
    itoastr('info', `Please deselect ${exceed} modules.`)
    return false
  } else {
    var current_fmodule = getCurrentFmodules()
    if (fmodule != -1 && current_fmodule - fmodule > 0) {
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
$(document).on('change', 'input[type=radio][name=template_filter]', function() {
  getTemplates()
})
$(document).on('change', '#category', function() {
  $('#template_search').val('')
  getTemplates()
})
$(document).on('change', 'input[type=radio][name=module_filter]', function() {
  getModules()
})

/**
 * Basic Setting
 */
$('#credentials').change(function() {
  if ($(this).prop('checked') === true) {
    $('.custom_credential').addClass('d-none')
  } else {
    $('.custom_credential').removeClass('d-none')
  }
})
$('#submit_form').submit(function(event) {
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
    success: function(result) {
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
        $('.tab_step_btn').attr('href', userTypeUrl + '/website/getting-started').removeClass('tab_step_btn')
        setTimeout(() => {
          window.location.href = userTypeUrl + '/website/getting-started/finish/' + currentPackage.id
        }, 1500)
      }
    },
    error: function(e) {
      console.log(e)
    }
  })
})
$(document).on('click', '.setting_area_next', function(e) {
  e.preventDefault()
  status = $('#status').val()
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

/**
 * Review Details
 */
function getReviewDetail() {
  $('.review_domain').html(domain)
  $('.review_name').html($('#name').val())
  $('.review_storage').html($('#storage').val() + ' GB')
  $('.review_page').html($('#page_limit').val())
  $('.review_status').html(capitalize(status))
  $('.review_template').html($('input[type=radio][name=template_item][value=' + template + ']').data('name'))
  $('.review_credentials').html(credentials == 1 ? 'Same credentials' : email + ', ********')
  $('.review_module').html('')

  $.each(modules, function(index, item) {
    var module = moduleSlugToArray(item)
    $('.review_module').append(module['name'] + ', ')
  })
}

/**
 * Common Functions
 */
function saveProgress() {
  $.ajax({
    url: userTypeUrl + '/website/getting-started/saveStep',
    data: {
      step: step,
      name: $('#name').val(),
      package: package,
      progress: progress,
      template: template,
      header: header,
      footer: footer,
      domain_type: domain_type,
      domain: domain,
      subdomain: subdomain,
      modules: JSON.stringify(modules),
      credentials: credentials,
      email: email,
      password: password,
      status: status
    },
    success: function(result) {
      if (result.status === 1) {
        progress = result.data
      }
    }
  })

  getReviewDetail()
}

function validateEmail(email) {
  const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
  return re.test(String(email).toLowerCase())
}

const capitalize = (s) => {
  if (typeof s !== 'string') return ''
  return s.charAt(0).toUpperCase() + s.slice(1)
}

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
    if (k > step) $(`.tab_step_btn[data-step='${k}'] .check_mark_area`).html('<span class="fa fa-lock tw-text-red-500"></span>')
  }
}

$(document).on('click', '.tab_step_btn', function(e) {
  e.preventDefault()
  if ($(this).data('step') <= step) {
    hashUpdate('#/' + $(this).data('area').substring(1))
    updatePercentage()
  } else {
    return false
  }
})
$(document).on('click', '.learn_more_btn', function(e) {
  e.preventDefault()
  $.ajax({
    url: userTypeUrl + '/website/getting-started/getModuleFeatures',
    data: { id: $(this).data('id') },
    success: function(result) {
      if (result.status === 1) {
        // $('.list_features_append').html(result.data.feature)
        // $('.about_module_append').html(result.data.view)
        $('.about_module_append').html(result.data.feature)
      }
    },
    error: function(e) {
      console.log(e)
    }
  })
})
$(document).on('click', '#use_recommended_btn', function(e) {
  e.preventDefault()
  module_wishes = module_recommended
  modules = module_recommended
  viewSelectedModules()
  getModules()
  getPackages()
  updateModuleLimitDisplay()
})

$(document).on('click', '.choose_package', function(e) {
  e.preventDefault()
  target_package_id = $(this).data('id')
  if (target_package_id === JSON.parse(currentPackage.item).id) return itoastr('error', 'You are already using this package.')
  askToast.question('Confirm', 'Do you want to change to this package?', 'switchPackage')
})

function switchPackage() {
  $('.choose_package').addClass('disabled').html('<i class="fa fa-spinner fa-spin fa-1x"></i>')
  $.ajax({
    headers: {
      'X-CSRF-TOKEN': token
    },
    url: userTypeUrl + '/website/getting-started/switchPackage',
    method: 'post',
    data: { id: currentPackage.id, target_package_id, modules },
    success: function(result) {
      if (result.status === 0) {
        dispValidErrors(result.data)
        $('.choose_package').removeClass('disabled')
      } else {
        if (result.data.gateway === 'stripe') {
          itoastr('success', 'Successfully deleted!')
          window.location.hash = '/module'
          window.location.reload()
        } else {
          if (result.data.result) {
            window.location.href = result.data.result
          }
        }
      }
    },
    error: function(e) {
      console.log(e)
    }
  })
}