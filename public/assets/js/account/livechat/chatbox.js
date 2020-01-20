var c_tab = 'guest'
var a_tab = 'guest'
var c_item = 0
var c_perPage = 30
var i_perPage = 15
var c_page = 1
var from_type = 'user'
var from_id = my_id
var msgdiv = $('#messages')
var m = $('#m')
var typingTimeout
var scrollEnd = 0
var guest_page = 1
var user_page = 1
var team_page = 1
var guest_scrollEnd = 0
var user_scrollEnd = 0
var team_scrollEnd = 0
var input_el = $('.input_element')

const socket = io(socket_server)

socket.on('connect', () => {
  console.log('connected')
})

$(document).ready(function () {
  var msg = document.getElementById('m')
  autosize(msg)
  joinMyRoom()
  getChatList('guest')
  getChatList('user')
  getChatList('team')
  initContext()
})
$(document).on('click', '.panel-heading-item:not(.active)', function () {
  var tab = $(this).data('tab')
  a_tab = tab
  $('.panel-heading-item').removeClass('active')
  $(this).addClass('active')
  $('.panel_list_div').removeClass('active')
  $(`.panel_${tab}_list_area`).addClass('active')
})
$(document).on('mousedown', '.chat_list_item:not(.active)', function () {
  $('.chat_list_item').removeClass('active')
  $(this).addClass('active')
  c_item = $(this).data('id')
  c_tab = a_tab

  input_el.prop('disabled', false)
  console.log($(this).attr('data-status'))
  if (c_tab === 'guest' && $(this).attr('data-status') == 0) {
    input_el.prop('disabled', true)
  }
  readMessage()
  resetContent()
  updateChatContent()
  messageScrollDown()
})
function initContext() {
  $.contextMenu({
    selector: '.panel_guest_list_area .chat_list_item[data-status="1"]',
    callback: function (key, options) {
      var id = $(this).data('id')
      handleGuestContext(id, key)
    },
    items: {
      end: { name: 'End Chat' },
      transcript: { name: 'Transcript' },
      view: { name: 'View Detail' }
    }
  })
  $.contextMenu({
    selector: '.panel_guest_list_area .chat_list_item[data-status="0"]',
    callback: function (key, options) {
      var id = $(this).data('id')
      handleGuestContext(id, key)
    },
    items: {
      transcript: { name: 'Transcript' },
      view: { name: 'View Detail' }
    }
  })
  $.contextMenu({
    selector: '.panel_user_list_area .chat_list_item',
    callback: function (key, options) {
      var id = $(this).data('id')
      if (key === 'view') {
        getDetail(id, 'user')
      }
    },
    items: {
      view: { name: 'View Detail' }
    }
  })
  $.contextMenu({
    selector: '.panel_team_list_area .chat_list_item',
    callback: function (key, options) {
      var id = $(this).data('id')
      if (key === 'view') {
        getDetail(id, 'team')
      }
    },
    items: {
      view: { name: 'View Detail' }
    }
  })
}
function getDetail(id, tab) {
  $.ajax({
    url: `/${role}/chatbox/getDetail`,
    data: { item: id, tab: tab },
    success: function (result) {
      if (result.status === 1) {
        $('.data_area').html(result.data)
        $('.detail-panel-area').toggleClass('show')
      }
    }
  })
}
function handleGuestContext(id, key) {
  if (key === 'view') {
    getDetail(id, 'guest')
  } else {
    if (window.confirm('Are you sure?')) {
      if (key === 'end') {
        var msgObj = createMsgObj('alert', `Chat ended by ${my_name}`)
        submitMsg(msgObj)
        endGuestChat(id)
        socket.emit('guest-end', { id: id })
      } else if (key === 'transcript') {
        transcriptChat(id)
      }
    }
  }
}
function transcriptChat(id) {
  $.ajax({
    url: `/${role}/chatbox/transcriptChat`,
    data: { item: id },
    success: function (result) {
      console.log(result)
    }
  })
}
function endGuestChat(id) {
  $.ajax({
    url: `/${role}/chatbox/endGuestChat`,
    data: { item: id },
    success: function (result) {
      console.log(result)
    }
  })
}
function resetContent() {
  msgdiv.html('')
  c_page = 1
  c_perPage = 30
}
$(document).on('click', '.toggle_btn', function (event) {
  event.stopPropagation()
  $('.chat_list').toggleClass('show-panel')
})
function getChatList(tab) {
  let page = eval(tab + '_page')
  $.ajax({
    url: `/${role}/chatbox`,
    data: { tab: tab, perPage: i_perPage, page: page },
    success: function (result) {
      if (result.status === 1) {
        if (result.data.data.length < i_perPage) {
          eval(tab + '_scrollEnd = 1;')
        } else {
          eval(tab + '_scrollEnd = 0;')
        }
        $.each(result.data.data, function (index, item) {
          renderChatList(item, tab)
        })
      }
    }
  })
}
$('.panel-list-area').scroll(function () {
  if (eval(a_tab + '_scrollEnd') === 0) {
    let scroll = parseFloat($(this).scrollTop()) + parseFloat($(this).innerHeight()) + 10
    let height = parseFloat($(this)[0].scrollHeight)

    if (scroll >= height) {
      eval(a_tab + '_page++')
      eval(a_tab + '_scrollEnd = 1;')
      getChatList(a_tab)
    }
  }
})
function joinMyRoom() {
  socket.emit('joinuser', my_id)
}
function renderChatList(item, tab, prepend = 0) {
  if (tab !== 'user') {
    socket.emit('join' + tab, item.id)
  }

  var status, last_msg

  if (tab === 'guest') {
    status = item.status === 0 ? 'ended' : 'active'
  } else {
    status = ''
  }
  if (item.last_msg != null && item.last_msg.message != null) {
    var full_msg = item.last_msg.message
    if (full_msg.length > 30) {
      last_msg = full_msg.substring(0, 29) + '...'
    } else {
      last_msg = full_msg
    }
  } else {
    last_msg = 0
  }
  let content = `
    <div class="chat_list_item" data-id="${item.id}" data-status="${item.status === 0 ? 0 : 1}">
        <div class="left_area">
            <div class="user_thumbnail">
                <span class="item_status ${status}" title="${status}">
                </span>
                <img src="${getImage(item)}" alt="">
            </div>
        </div>
        <div class="user_chat_detail">
            <div class="user-chat-detail-container">
                ${item.name}!
                <div class="unread_count">${item.unread_count != null && item.unread_count != 0 ? '<span>' + item.unread_count + '</span>' : ''}</div>
                <span class="date_area">${item.last_msg != null && item.last_msg.datetime != null ? convertDateTime(item.last_msg.datetime) : ''}</span>
                <div class="last_msg"><p>${last_msg}</p></div>
            </div>
        </div>
    </div>`
  if (prepend === 1) {
    $(`.panel_${tab}_list_area`).prepend(content)
  } else {
    $(`.panel_${tab}_list_area`).append(content)
  }
}
function convertDateTime(datetime) {
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
function getImage(item) {
  if (item.image !== undefined && item.image !== '') {
    return item.image
  } else {
    return 'https://ui-avatars.com/api/?size=300&&name=' + item.name
  }
}
function updateChatContent(scroll = 1) {
  $.ajax({
    url: `/${role}/chatbox/getContent`,
    data: { tab: c_tab, item: c_item, page: c_page, perpage: c_perPage },
    success: function (result) {
      console.log(result)
      if (result.status === 1) {
        if (result.data.data.length < c_perPage) {
          scrollEnd = 1
        } else {
          scrollEnd = 0
        }

        $.each(result.data.data, function (index, item) {
          pushMsg(item, 1)
        })
        if (scroll === 1) {
          updateUnreadsFun()
          messageScrollDown()
        }
      }
    }
  })
}
function readMessage(tab = c_tab, item = c_item) {
  $.ajax({
    url: `/${role}/chatbox/readMessage`,
    data: { tab: tab, item: item },
    success: function (result) {
      if (result.status === 1) {
        updateUnreadsFun(tab, item)
      }
    }
  })
}
function updateUnreadsFun(tab = c_tab, item = c_item) {
  $.ajax({
    url: `/${role}/chatbox/updateUnreads`,
    data: { tab: tab, item: item },
    success: function (result) {
      console.log(result)
    }
  })
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
    message = `<div class="test-message text-center mt-3">---------- ${msg.message} ----------</div>`
  } else {
    if (msg.from_id == my_id) {
      message = `
                <div class="biz-chat-message text-right mt-3">
                    <p class="datetime">${convertDateTime(msg.datetime)}</p>
                    <div class="d-inline-block my_message_div maxw-70 p-2">${text}</div>
                </div>`
    } else {
      message = `
                <div class="biz-chat-message text-left mt-3">
                    <div class="msg_img_area">
                        <img src="${getMsgUerImage(msg)}" alt="" />
                    </div>
                    <div class="msg_img_right">
                        <p class="datetime">${msg.from_name}, ${convertDateTime(msg.datetime)}</p>
                        <div class="d-inline-block other_message_div maxw-70 p-2">${text}</div>
                    </div>
                </div>`
    }
  }
  if (prepend === 1) {
    msgdiv.prepend(message)
  } else {
    msgdiv.append(message)
  }
}
function getMsgUerImage(msg) {
  if (msg.from_type === 'guest') {
    return `https://ui-avatars.com/api/?size=100&&name=${msg.from_name ?? msg.from_user.name}`
  } else {
    return msg.user_image ?? `https://ui-avatars.com/api/?size=100&&name=${msg.from_name}`
  }
}
function htmlEntities(str) {
  return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;')
}
msgdiv.scroll(function () {
  if (scrollEnd === 0) {
    if (msgdiv.scrollTop() <= 600) {
      c_page++
      scrollEnd = 1
      updateChatContent(0)
    }
  }
})

socket.on('getMessage', function (data) {
  var msgObj = JSON.parse(data)
  console.log(msgObj, 'getMessage')
  var type, item
  if (msgObj.to_type === 'user') {
    type = msgObj.from_type
    item = msgObj.from_id
  } else {
    type = msgObj.to_type
    item = msgObj.to_id
  }
  if (type === 'user' && item == my_id) {
    pushMsg(msgObj)
    messageScrollDown()
  }
  if (type === c_tab && item == c_item) {
    readMessage()
    pushMsg(msgObj)
    messageScrollDown()
  } else {
    updateUnreadsFun(type, item)
  }
})
socket.on('pushList', function (data) {
  let msgObj = JSON.parse(data)
  msgObj.status = 1
  msgObj.unread_count = 1
  renderChatList(msgObj, 'guest', 1)
})
socket.on('updateUnreads-' + my_id, function (data) {
  let unread = JSON.parse(data)
  $(`.panel_${unread.tab}_list_area .chat_list_item[data-id="${unread.item}"] .unread_count`).html(`${unread.count == 0 ? '' : '<span>' + unread.count + '</span>'}`)
})

socket.on('guest-typing', function (data) {
  handleTyping(data)
})
socket.on('user-typing', function (data) {
  if (data.from_type !== 'user' || data.from_id !== my_id) {
    if (c_tab === data.from_type && c_item == data.from_id) {
      var obj = $(`.answer-div .typing-watch p[data-id="${data.from_id}"][data-type="${data.from_type}"]`)
      if (data.status === 1) {
        if (obj.length === 0) {
          $('.answer-div .typing-watch').append(
            `<p class='live-chat-typing' data-id="${data.from_id}" data-type="${data.from_type}">${data.name} is typing <img class="typing_img" src="${loading_icon}"></p>`
          )
        }
      } else {
        obj.remove()
      }
    } else if (a_tab === data.from_type && c_item !== data.from_id) {
      var selector = $(`.chat_list_item[data-id="${data.from_id}"] .last_msg .typing_div`)
      if (data.status === 1) {
        if (selector.length === 0) {
          $(`.chat_list_item[data-id="${data.from_id}"] .last_msg`).prepend(`<p class="typing_div"><img class="typing_img" src="${loading_icon}"></p>`)
        }
      } else {
        selector.remove()
      }
    }
  }
})
socket.on('team-typing', function (data) {
  handleTyping(data)
})
function handleTyping(data) {
  if (data.from_type !== 'user' || data.from_id !== my_id) {
    if (c_tab === data.to_type && c_item == data.to_id) {
      var obj = $(`.answer-div .typing-watch p[data-id="${data.from_id}"][data-type="${data.from_type}"]`)
      if (data.status === 1) {
        if (obj.length === 0) {
          $('.answer-div .typing-watch').append(
            `<p class='live-chat-typing' data-id="${data.from_id}" data-type="${data.from_type}">${data.name} is typing <img class="typing_img" src="${loading_icon}"></p>`
          )
        }
      } else {
        obj.remove()
      }
    } else if (a_tab === data.to_type && c_item !== data.to_id) {
      var selector = $(`.chat_list_item[data-id="${data.to_id}"] .last_msg .typing_div`)
      if (data.status === 1) {
        if (selector.length === 0) {
          $(`.chat_list_item[data-id="${data.to_id}"] .last_msg`).prepend(`<p class="typing_div"><img class="typing_img" src="${loading_icon}"></p>`)
        }
      } else {
        selector.remove()
      }
    }
  }
}
socket.on('guest-end', function (data) {
  var dd
  try {
    dd = JSON.parse(data)
  } catch (e) {
    dd = data
  }

  if (c_tab === 'guest' && c_item == dd.id) {
    input_el.prop('disabled', true)
  }
  $(`.panel_guest_list_area .chat_list_item[data-id="${dd.id}"]`).attr('data-status', 0)
  $(`.panel_guest_list_area .chat_list_item[data-id="${dd.id}"] .item_status`).removeClass('active').addClass('ended')
  resortGuestList()
})

function resortGuestList() {
  $('.panel_guest_list_area .chat_list_item')
    .sort(function (a, b) {
      return parseInt(b.dataset.status) - parseInt(a.dataset.status)
    })
    .prependTo('.panel_guest_list_area')
}
$(document).on('submit', '#submit_form', function (e) {
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

$('#livechat-file-upload').change(function (event) {
  var file = event.target.files[0]
  if (validateSize(file)) {
    let fileFormat = file.type.split('/')[0]
    let reader = new FileReader()
    $('#file-icon').hide()
    $('#file-loading').show()
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
  console.log(`${c_tab}-typing`)
  socket.emit(`${c_tab}-typing`, { status: status, name: my_name, to_id: c_item, to_type: c_tab, from_id: my_id, from_type: 'user' })
}
$('#submit_btn').click(function () {
  var $message = m.val()

  if ($message !== '') {
    m.val('')
    m.css('height', '30px')

    var msgObj = createMsgObj('text', $message)
    submitMsg(msgObj)
  }
})
function submitMsg(msgObj) {
  $.ajax({
    url: `/${role}/chatbox/sendMessage`,
    method: 'POST',
    data: { _token: token, msgObj: msgObj, tab: c_tab, item: c_item },
    success: function (result) {
      console.log(result)
      $('#file-icon').show()
      $('#file-loading').hide()
    },
    error: function (e) {
      console.log(e)
    }
  })
}
function messageScrollDown() {
  msgdiv.animate({ scrollTop: msgdiv.prop('scrollHeight') + 1000 }, 0)
  return false
}
function createMsgObj(type = 'text', msg) {
  return {
    type: type,
    file_path: '',
    from_type: 'user',
    from_id: my_id,
    from_name: my_name,
    message: msg,
    user_image: my_image,
    datetime: c_datetime()
  }
}

function c_datetime() {
  return new moment().format('YYYY-MM-DD hh:mm:ss')
}

$(document).on('click', '.detail_content .closeBtn', function () {
  $('.detail-panel-area').toggleClass('show')
})
