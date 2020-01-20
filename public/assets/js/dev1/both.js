$(document).on('click', '.tab-link', function() {
  var hash = this.hash
  hashUpdate(hash)
})

$('.loadBtn').click(function() {
  $(this).addClass('m-loader m-loader--light m-loader--right').attr('disabled', true)
})

function hashUpdate(hash) {
  if (hash !== '') {
    var a = hash.split('/')
    $('.tab-link').removeClass('tab-active')
    $('.tab_area').removeClass('area-active')
    $('.tab-link[data-area="' + a[0] + a[1] + '"]').addClass('tab-active')
    $(a[0] + a[1] + '_area').addClass('area-active')
    if (a[2] == null) {
      $(a[0] + a[1] + '_area').addClass('area-active')
    } else {
      $('.tab-link[data-area="' + a[0] + a[1] + a[2] + '"]').addClass('tab-active')
      $(a[0] + a[1] + a[2] + '_area').addClass('area-active')
    }
    window.location.hash = hash
  }
}

function dispErrors($errors) {
  for (var key in $errors) {
    var error = $errors[key]
    itoastr('error', error)
  }
}

function clearError() {
  $('.form-control-feedback').html('')
}

function btnLoading(object = '.smtBtn') {
  $(object).append(' <i class=\'fa fa-spinner fa-spin loading_div\'></i>').attr('disabled', true)
}

function btnLoadingStop(object = '.smtBtn') {
  $(object).attr('disabled', false)
  $(object).find('.loading_div').remove()
}

function redirectAfterDelay(url) {
  setTimeout(function() {
    window.location.href = url
  }, 1000)
}

function reloadAfterDelay(url) {
  setTimeout(function() {
    window.location.reload()
  }, 1000)
}

iziToast.settings({
  timeout: 3000
})

function itoastr(title, message, type = null) {
  if (type == null) {
    type = title.toLowerCase()
  }
  if (type === 'info') {
    iziToast.info({
      title: title,
      message: message,
      position: 'topCenter'
    })
  } else if (type === 'error') {
    iziToast.error({
      title: title,
      message: message,
      position: 'topCenter'
    })
  } else if (type === 'success') {
    iziToast.success({
      title: title,
      message: message,
      position: 'topCenter'
    })
  }
}

window.itoastr = itoastr

const askToast = {
  info: function(title, msg, action) {
    var obj = setObj(title, msg, action)
    iziToast.info(obj)
  },
  success: function(title, msg, action) {
    var obj = setObj(title, msg, action)
    iziToast.success(obj)
  },
  question: function(title, msg, action, closed) {
    var obj = setObj(title, msg, action, closed)
    iziToast.question(obj)
  },
  error: function(title, msg, action) {
    var obj = setObj(title, msg, action)
    iziToast.error(obj)
  }
}
window.askToast = askToast

function setObj(title, msg, action, closed) {
  return {
    timeout: 20000,
    close: false,
    overlay: true,
    displayMode: 'once',
    id: 'question',
    zindex: 999,
    message: msg,
    title: title,
    position: 'center',
    buttons: [
      [
        '<button><b>YES</b></button>',
        function(instance, toast) {
          instance.hide({ transitionOut: 'fadeOut' }, toast, 'button')
          if (typeof action === 'function') {
            action()
          } else {
            window[action]()
          }
        },
        true
      ],
      [
        '<button>NO</button>',
        function(instance, toast) {
          instance.hide({ transitionOut: 'fadeOut' }, toast, 'button')
          if (typeof closed === 'function') {
            closed()
          }
        }
      ]
    ]
  }
}

function dispValidErrors(message) {
  $.each(message, function(index, item) {
    $('.error-' + index).html(item)
  })
}

function pickDate(picked, period) {
  var result = []
  var start = 0
  var end = 0
  if (period === 365) {
    start = moment(picked).startOf('year')
    end = moment(picked).endOf('year')
  } else if (period === 180) {
    var month = moment(picked).month()
    if (month < 3) {
      start = moment(picked).startOf('year')
      end = moment(picked).add(3, 'M').endOf('quarter')
    } else if (month < 6) {
      start = moment(picked).startOf('year')
      end = moment(picked).endOf('quarter')
    } else if (month < 9) {
      start = moment(picked).startOf('quarter')
      end = moment(picked).endOf('year')
    } else {
      start = moment(picked).sub(3, 'M').startOf('quarter')
      end = moment(picked).endOf('year')
    }
  } else if (period === 90) {
    start = moment(picked).startOf('quarter')
    end = moment(picked).endOf('quarter')
  } else if (period === 30) {
    start = moment(picked).startOf('month')
    end = moment(picked).endOf('month')
  } else if (period === 14) {
    var weeknumber = moment(picked).week()
    if (isEven(weeknumber)) {
      start = moment(picked).weekday(-7)
      end = moment(picked).endOf('week')
    } else {
      start = moment(picked).startOf('week')
      end = moment(picked).weekday(13)
    }
  } else if (period === 7) {
    start = moment(picked).startOf('week')
    end = moment(picked).endOf('week')
  } else if (period === 1) {
    start = moment(picked)
    end = moment(picked)
  }
  result[0] = start.format('YYYY-MM-DD')
  result[1] = end.format('YYYY-MM-DD')

  return result
}

function isEven(value) {
  if (value % 2 === 0) return true
  else return false
}

function roundFloat(num) {
  return Math.round((num + Number.EPSILON) * 100) / 100
}

function uuidv4() {
  return ([1e7] + -1e3 + -4e3 + -8e3 + -1e11).replace(/[018]/g, (c) => (c ^ (crypto.getRandomValues(new Uint8Array(1))[0] & (15 >> (c / 4)))).toString(16))
}

$(document).on('click', '.slim-btn.slim-btn-remove', function(e) {
  e.preventDefault()
  $(this).parents('div.slimdiv').find('input[type=file]').click()
})

$(document).on('click', '.slimdiv .slim-file-hopper img', function(e) {
  e.preventDefault()
  $('div.slimdiv input[type=file]').click()
})

$(document).ajaxStart(function() {
  $('.log').text('Triggered ajaxStart handler.')
})

$(document).ajaxStart(function() {
  const loader = document.getElementById('loader')
  if (loader) {
    loader.style.display = 'flex'
  }
})

$(document).ajaxComplete(function() {
  const loader = document.getElementById('loader')
  if (loader) {
    loader.style.display = 'none'
  }
})

$.post = (url, formData = null, fnSuccess, fnError) => {
  return new Promise((resolve) => {
    $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url,
      type: formData._method ? formData._method : 'POST',
      data: formData,
      contentType: false,
      cache: false,
      processData: false,
      success: (res) => {
        console.log(res)
        if (res.status) {
          if (typeof fnSuccess === 'function') {
            fnSuccess(res)
          }
        } else {
          if (typeof fnError === 'function') {
            fnError(res.data)
          }
          if (res.errors && Array.isArray(res.errors)) {
            res.errors.forEach((error) => {
              itoastr('error', error)
            })
          }
        }
        resolve(res)
      },
      error: function(e) {
        resolve(e)
      }
    })
  })
}

$.fn.extend({
  loading(loading = true) {
    if (loading) {
      $(this).append(' <i class=\'fa fa-spinner fa-spin loading_div\'></i>').attr('disabled', true)
    } else {
      $(this).attr('disabled', false)
      $(this).find('.loading_div').remove()
    }
  },
  clearForm() {
    $(this).find('input').val('')
    $(this).find('textarea').text('')
  }
})

$.existFile = (path, fnSuccess) => {
  if (path) {
    $.ajax({
      type: 'get',
      url: '/check-file-existence/?path=' + path,
      success: fnSuccess,
      error: function(e) {
        console.log('check file existence error', e)
      }
    })
  }
}

window.fog = function(formData) {
  let data = {}
  formData.forEach((value, key) => {
    data[key] = value
  })
  console.log(data)
}
