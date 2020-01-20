var session
var restoredSession = 0
var page = 1
var msgdiv = $('#messages')
var scrollEnd = 0
var m = $('#m')
var perpage = 30
var from_name = ''
var typingTimeout

const socket = io(socket_server)

socket.on('connect', () => {
  socket.emit('online-status', { status: 1, id: session, type: 'guest' })
})
$(document).ready(function () {
  initAutoSize()
  initChat()
})
function initAutoSize() {
  var msg = document.getElementById('m')
  autosize(msg)
}
function initChat(scroll = 1) {
  $.ajax({
    url: `/livechat/getSession`,
    method: 'POST',
    data: { _token: token, page: page, perpage: perpage },
    success: function (result) {
      console.log(result)
      if (result.status === 1) {
        if (result.data.session === 1) {
          restoreSession(result.data)
          if (result.data.messages.data.length < perpage) {
            scrollEnd = 1
          } else {
            scrollEnd = 0
          }
          if (scroll === 1) {
            messageScrollDown()
          }
        } else {
          msgdiv.html(result.data.messages)
        }
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
}
function restoreSession(data) {
  restoredSession = 1
  session = data.session_id
  from_name = data.name
  joinChannel()
  $.each(data.messages.data, function (index, item) {
    pushMsg(item, 1)
  })
}
function getServices() {
  $.ajax({
    url: '/livechat/getService',
    method: 'POST',
    data: { _token: token },
    success: function (result) {
      if (result.status === 1) {
        msgdiv.html(result.data.data)
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
}
function createMsgObj(type = 'text', msg) {
  return {
    type: type,
    file_path: '',
    from_type: 'guest',
    from_id: session,
    from_name: from_name,
    message: msg,
    user_image: '',
    datetime: c_datetime()
  }
}
function messageScrollDown() {
  msgdiv.animate({ scrollTop: msgdiv.prop('scrollHeight') + 1000 }, 0)
  return false
}

function htmlEntities(str) {
  return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;')
}

function pushMsg(msg, prepend = 0) {
  var text, message
  if (msg.type === 'text') {
    text = `<div class="message-div">${htmlEntities(msg.message)}</div>`
  } else if (msg.type === 'image') {
    text = `<div class="message-div p-1"><a href="${msg.image}" target="_new"><img class="w-100" src="${msg.image}"/></a></div>`
  } else if (msg.type === 'file') {
    text = `<div class="message-div p-0 text-center bg-transparent"><a href="${
      msg.image
    }" target="_new" class="text-black font-size20"><img class="width-100px" src="/assets/img/file.jpg"/> <br/>${msg.message.split('.').pop()}</a></a></div>`
  }
  if (msg.type === 'alert') {
    message = `<li class="text-center">---------- ${msg.message} ----------</li>`
  } else {
    if (msg.from_id === session) {
      message = `<p class="biz-livechat-time-txt text-right">You at ${convertDateTime(msg.datetime)}</p><li class="user_answer">${text}</li>`
    } else {
      message = `<p class="biz-livechat-time-txt text-left">${msg.from_name} at ${convertDateTime(msg.datetime)}</p><li class="support_answer">${text}</li>`
    }
  }
  if (prepend === 1) {
    msgdiv.prepend(message)
  } else {
    msgdiv.append(message)
  }
}
function uuid() {
  var dt = new Date().getTime()
  return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
    var r = (dt + Math.random() * 16) % 16 | 0
    dt = Math.floor(dt / 16)
    return (c == 'x' ? r : (r & 0x3) | 0x8).toString(16)
  })
}
function c_datetime() {
  return new moment().format('YYYY-MM-DD hh:mm:ss')
}
$(document).on('click', '.letsgo', function () {
  getServices()
})
$(document).on('click', '.reinit_btn', function () {
  initChat()
})
$(document).on('submit', '#live_chat_request_form', function (e) {
  e.preventDefault()
  $('.live_chat_request_form_submit_btn').prop('disabled', true)
  $.ajax({
    url: '/livechat/submitService',
    method: 'POST',
    data: new FormData(this),
    dataType: 'JSON',
    contentType: false,
    cache: false,
    processData: false,
    success: function (result) {
      $('.live_chat_request_form_submit_btn').prop('disabled', false)
      $('.error-div').html('')
      if (result.status === 1) {
        $('#messages').html(result.data)
      } else {
        $.each(result.data, function (index, item) {
          console.log(index)
          $('.error-' + index).html(item)
        })
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
})

$(document).on('change', 'select[name="service"]', function (e) {
  var text = $(this).find('option:selected').text()
  if (text.includes('Never received verificaton email')) {
    $('#verification_fields').append(`
      <div id="new_content">
        <label style="margin-bottom: 0;margin-top: 0.5rem;">Date of Purchase</label>
        <input type="date" name="date" class="form-control livechat-req-form" autocomplete="off" placeholder="Date of Purchase" required>
        <span class="text-danger error-date error-div"></span>
        <label style="margin-bottom: 0;margin-top: 0.5rem;">Approximate time of Purchase</label>
        <input type="time" name="time" class="form-control livechat-req-form" autocomplete="off" placeholder="Approximate time of Purchase">
        <span class="text-danger error-time error-div"></span>
        <select name="type" class="form-control livechat-req-form">
          <option value="paypal">Paypal Order</option>
          <option value="stripe">Credit Card</option>
        </select>
        <input type="text" name="order" class="form-control livechat-req-form" autocomplete="off" placeholder="Paypal Order Number" required>
        <span class="text-danger error-order error-div"></span>
      </div>
    `)
  } else {
    $('#new_content').remove()
  }
})

$(document).on('change', 'select[name="type"]', function () {
  $('input[name="order"]').attr('placeholder', $(this).val() === 'paypal' ? 'Paypal Order Number' : 'Last 4 digits of Credit Card')
})

$(document).on('submit', '#live_chat_start_form', function (e) {
  e.preventDefault()
  $('.live_chat_request_form_submit_btn').prop('disabled', true)

  var formData = new FormData(this)
  var timezone = moment.tz.guess()
  session = uuid()
  formData.append('timezone', timezone)
  formData.append('session', session)

  $.ajax({
    url: '/livechat/createSession',
    method: 'POST',
    data: formData,
    dataType: 'JSON',
    contentType: false,
    cache: false,
    processData: false,
    success: function (result) {
      $('.live_chat_request_form_submit_btn').prop('disabled', false)
      $('.error-div').html('')
      console.log(result)
      if (result.status === 1) {
        joinChannel()
        msgdiv.html('')
        from_name = result.data.name
        var msgObj = createMsgObj('text', result.data.service)
        submitMsg(msgObj)
        if (result.data.data) {
          var msg = '';
          for (let key in result.data.data) {
            msg += `${key}: ${result.data.data[key]}\n`
          }
          var msgObj = createMsgObj('text', msg)
          submitMsg(msgObj)
        }
      } else {
        $.each(result.data, function (index, item) {
          $('.error-' + index).html(item)
        })
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
})
m.on('keydown', function (event) {
  if (event.key === 'Enter' && !event.shiftKey) {
    event.preventDefault()
    document.getElementById('submit_btn').click()
  } else {
    typing(1)
    clearTimeout(typingTimeout)
    typingTimeout = setTimeout(function () {
      typing(0)
    }, 1000)
  }
})
function typing(status = 1) {
  socket.emit('guest-typing', { status: status, name: from_name, to_id: session, to_type: 'guest', from_id: session, from_type: 'guest' })
}
socket.on('guest-typing', function (data) {
  var obj = $('.biz-chat-bottom .biz-livechat-typing-text')
  if (data.from_type !== 'guest' || data.from_id !== session) {
    if (data.status === 1) {
      obj.html(`<p>${data.name} is typing...</p>`)
    } else {
      obj.html('')
    }
  }
})

$('#submit_btn').click(function () {
  var $message = m.val()

  if ($message !== '') {
    m.val('')
    m.css('height', '40px')

    var msgObj = createMsgObj('text', $message)
    submitMsg(msgObj)
  }
})
function submitMsg(msgObj) {
  $.ajax({
    url: '/livechat/sendMessage',
    method: 'POST',
    data: { _token: token, msgObj: msgObj },
    success: function (result) {
      $('#file-icon').show()
      $('#file-loading').hide()
    },
    error: function (e) {
      console.log(e)
    }
  })
}
function joinChannel() {
  $('.input_element').prop('disabled', false)
  $('#biz-chat-form').show()

  socket.emit('joinguest', session)
}
socket.on('getMessage', function (data) {
  console.log(JSON.parse(data))
  pushMsg(JSON.parse(data))
  messageScrollDown()
})
$('#livechat-file-upload').change(function (event) {
  var file = event.target.files[0]
  if (validateSize(file)) {
    $('#file-icon').hide()
    $('#file-loading').show()
    let fileFormat = file.type.split('/')[0]
    let reader = new FileReader()
    reader.onload = function () {
      var type

      if (fileFormat === 'image') {
        type = 'image'
      } else {
        type = 'file'
      }
      let msg = createMsgObj(type, reader.result)

      submitMsg(msg)
    }.bind(this)
    reader.readAsDataURL(file)
  } else {
    event.target.value = ''
    console.log('info', 'File size exceeds 10 MB')
  }
})
function validateSize(file) {
  var fileSize = file.size / 1024 / 1024
  return fileSize <= 10
}

msgdiv.scroll(function () {
  if (restoredSession === 1 && scrollEnd === 0) {
    if (msgdiv.scrollTop() <= 600) {
      page++
      scrollEnd = 1
      initChat(0)
    }
  }
})

$('.close_btn').click(function () {
  console.log('clicked')
  if (session != null) {
    console.log('trigger worked.')
    var msgObj = createMsgObj('alert', `Chat ended by ${from_name}`)
    submitMsg(msgObj)

    socket.emit('guest-end', { id: session })
    sessionClear()
  } else {
    console.log('session none.')
  }
})
function sessionClear() {
  session = null
  $.ajax({
    url: '/livechat/sessionClear',
    method: 'POST',
    data: { _token: token }
  })
}
socket.on('guest-end', function (data) {
  console.log('guest end', data)
  $('.input_element').prop('disabled', true)
  sessionClear()
})
function convertDateTime(datetime) {
  console.log(datetime)
  var format1 = 'YYYY-MM-DD'
  var format2 = 'hh:mm A'
  var format3 = 'MM/DD/YYYY'

  var today = moment().format(format1).toString()
  var date = moment(datetime).format(format1).toString()

  if (date === today) {
    return moment(datetime).format(format2).toString()
  } else {
    return moment(datetime).format(format3).toString()
  }
}
