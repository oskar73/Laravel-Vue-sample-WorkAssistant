var calendar = $('#calendar')
calendar.fullCalendar({
  header: {
    left: 'prev,next',
    center: 'title',
    right: 'month,agendaWeek,agendaDay'
  },
  timezone: 'America/New_York',
  slotLabelFormat: 'HH:mm',
  height: 'auto',
  defaultView: 'month',
  selectable: true,
  dayNamesShort: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
  durationEditable: false,
  eventDurationEditable: false,
  bootstrap: false,
  events: '/account/appointment',
  eventRender: function (eventObj, $el) {
    console.log(eventObj)
    $el.popover({
      title: eventObj.title,
      content: eventObj.status,
      trigger: 'hover',
      placement: 'right',
      container: 'body'
    })
    renderTable(eventObj)
  },
  eventOverlap: false,
  eventClick: function (e) {
    window.location.href = `/account/appointment/edit/${e.id}`
  },
  viewRender: function () {
    resetTableBody()
  }
})

function renderTable(eventObj) {
  var color
  if (eventObj.status === 'Approved') {
    color = 'c-badge-success'
  } else if (eventObj.status === 'Pending') {
    color = 'c-badge-info'
  } else if (eventObj.status === 'Canceled') {
    color = 'c-badge-danger'
  } else {
    color = 'c-badge-warning'
  }
  $('.table_body').append(`
        <tr>
            <td>${eventObj.date}</td>
            <td><span class="c-badge c-badge-warning">${eventObj.start_time} - ${eventObj.end_time}</span></td>
            <td><span class="c-badge ${color}">${eventObj.status}</span></td>
            <td>${eventObj.product}</td>
            <td>
                <a href="/account/appointment/detail/${eventObj.id}"
                class="m-portlet__nav-link btn m-btn m-btn--hover-brand m-btn--icon m-btn--icon-only m-btn--pill tooltip_3"
                title="Detail">
                   <i class="la la-eye"></i>
                </a>
                <a href="/account/appointment/edit/${eventObj.id}"
                class="m-portlet__nav-link btn m-btn m-btn--hover-brand m-btn--icon m-btn--icon-only m-btn--pill tooltip_3"
                title="Edit">
                   <i class="la la-edit"></i>
                </a>
            </td>
        </tr>
    `)
}
function resetTableBody() {
  $('.table_body').html('')
}
