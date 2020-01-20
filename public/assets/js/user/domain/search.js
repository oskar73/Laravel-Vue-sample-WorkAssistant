var typed4 = new Typed('.domain_search_box', {
  strings: ['perfectdomain.com', 'bestwebdev.com', 'mydream.co', 'howtoworkathome.com', 'mybusiness.us', 'howtowinbusiness.co'],
  typeSpeed: 50,
  backSpeed: 20,
  backDelay: 1000,
  startDelay: 500,
  attr: 'placeholder',
  bindInputFocusEvents: true,
  loop: true
})

$(document).ready(function () {
  var hash = window.location.hash
  hashUpdate(hash)
  if (hash === '#/duration') {
    getDuration()
  } else if (hash === '#/confirm') {
    getConfirm()
  }
  $('.selectpicker').selectpicker()
})
$('#search-form').on('submit', function (e) {
  e.preventDefault()
  let domain = $('#domain_input').val()
  if (!CheckIsValidDomain(domain)) return itoastr('error', 'Domain name is invalid')
  loadBtn()
  $.ajax({
    url: '/account/domain/search',
    method: 'post',
    data: new FormData(this),
    dataType: 'JSON',
    contentType: false,
    cache: false,
    processData: false,
    success: function (result) {
      enableBtn()
      console.log(result)
      if (result.status === 0) {
        dispErrors(result.data)
      } else {
        $('.recom_domain_area').remove()
        $('.result_area').html('')
        $.each(result.views, function (index, value) {
          $('.result_area').append(value)
        })
        $('.loadMoreBtn').removeClass('d-none')
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
})
$(document).on('click', '.loadMoreBtn', function () {
  moreBtn()
  $.ajax({
    url: '/account/domain/loadMore',
    method: 'get',
    success: function (result) {
      console.log(result)
      dismoreBtn()
      if (result.status === 0) {
        dispErrors(result.data)
      } else {
        $.each(result.views, function (index, value) {
          $('.result_area').append(value)
        })
        if (result.views.length < 20) {
          $('.loadMoreBtn').addClass('d-none')
        }
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
})
$(document).on('click', '.purchase_btn', function () {
  let domain = $(this).data('domain')
  getDuration(domain)
})
$(document).on('click', '.duration_btn', function () {
  let duration = $(this).data('duration')
  $.ajax({
    url: '/account/domain/setDuration',
    method: 'get',
    data: { duration: duration },
    success: function (result) {
      console.log(result)
      if (result.status == 0) {
        dispErrors(result.data)
      } else {
        $('.duration_num').html(result.data + ' Years')
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
})
$(document).on('change', '#saveThis', function () {
  if ($(this).prop('checked') === true) {
    $('.contact_name_area').removeClass('d-none')
  } else {
    $('.contact_name_area').addClass('d-none')
  }
})
$(document).on('change', '#contact', function () {
  let data = $(this).find(':selected').data('contact')
  $.each(data, function (index, item) {
    $('#' + index).val(item)
  })
  $('.selectpicker').selectpicker('refresh')
})

$('#contactForm').on('submit', function (e) {
  e.preventDefault()
  $('.formSmtBtn').html("<i class='fa fa-spinner fa-spin fa-2x fa-fw'></i>").attr('disabled', true)
  $.ajax({
    url: '/account/domain/setContact',
    method: 'post',
    data: new FormData(this),
    dataType: 'JSON',
    contentType: false,
    cache: false,
    processData: false,
    success: function (result) {
      $('.form-control-feedback').html('')
      $('.formSmtBtn').html('Submit').attr('disabled', false)
      console.log(result)
      if (result.status === 0) {
        dispValidErrors(result.data)
      } else {
        hashUpdate('#/confirm')
        window.location.hash = '#/confirm'
        getConfirm()
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
})

function getConfirm() {
  $.ajax({
    url: '/account/domain/getConfirm',
    method: 'get',
    success: function (result) {
      console.log(result)
      if (result.status === 0) {
        dispValidErrors(result.data)
      } else {
        $('.confirm_result').html(result.data)
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
}
$(document).on('click', '.getNowBtn', function () {
  var dns
  if ($('#registerBiz').prop('checked') === true) {
    dns = 1
  } else {
    dns = 0
  }

  $('.getNowBtn').html("<i class='fa fa-spinner fa-spin fa-2x fa-fw'></i>").attr('disabled', true)
  $.ajax({
    url: '/account/domain/getNow',
    type: 'post',
    data: { _token: token, dns: dns },
    success: function (result) {
      console.log(result)
      $('.getNowBtn').html('Get Now').attr('disabled', false)
      if (result.status === 0) {
        dispValidErrors(result.data)
      } else {
        itoastr('success', 'Successfully registered')
        setTimeout(function () {
          window.location.href = '/account/domainList'
        }, 1000)
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
})
function getDuration(domain = null) {
  $.ajax({
    url: '/account/domain/duration',
    method: 'get',
    data: { domain: domain },
    success: function (result) {
      console.log(result)
      if (result.status === 0) {
        dispErrors(result.data)
      } else {
        $('.duration_result').html(result.view)
        $('.duration_domain').html(result.domain)
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
}
function loadBtn() {
  $('.smtBtn').html("<i class='fa fa-spinner fa-spin fa-2x fa-fw'></i>").attr('disabled', true)
}
function enableBtn() {
  $('.smtBtn').html("<i class='fa fa-search fa-2x fa-fw'></i>").attr('disabled', false)
}
function moreBtn() {
  $('.loadMoreBtn').html("<i class='fa fa-spinner fa-spin fa-2x fa-fw'></i>").attr('disabled', true)
}
function dismoreBtn() {
  $('.loadMoreBtn').html('Load More...').attr('disabled', false)
}
function CheckIsValidDomain(domain) {
  var re = new RegExp(/^((?:(?:(?:\w[\.\-\+]?)*)\w)+)((?:(?:(?:\w[\.\-\+]?){0,62})\w)+)\.(\w{2,10})$/)
  return domain.match(re)
}
