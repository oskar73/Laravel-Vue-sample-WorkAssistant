<template>
  <div v-click-outside="hiddenCal" :style="{ width: width }" class="datetime-picker" @click="calendarClicked($event)">
    <div v-if="$slots.default" @click="toggleCal">
      <slot />
    </div>
    <input v-else id="tj-datetime-input" type="text" :readonly="readonly" :required="required" :value="date" :name="name" autocomplete="off" @click="toggleCal" />
    <div class="calender-div" :class="{ noDisplay: hideCal }">
      <div class="calender-wrapper" :class="{ noDisplay: hideDate }">
        <div class="year-month-wrapper">
          <div class="month-setter">
            <button type="button" class="nav-l" @click="leftYear">
              <arrow-back-icon class="text-white" />
            </button>
            <span class="year">{{ year }}</span>
            <button type="button" class="nav-r text-white" @click="rightYear">
              <arrow-forward-icon />
            </button>
          </div>
          <div class="month-setter">
            <button type="button" class="nav-l" @click="leftMonth">
              <arrow-back-icon />
            </button>
            <span class="month">{{ month }}</span>
            <button type="button" class="nav-r" @click="rightMonth">
              <arrow-forward-icon />
            </button>
          </div>
        </div>

        <table class="calender-table">
          <thead>
            <tr>
              <th v-for="port in days" :key="port" class="days">{{ port }}</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(week, weekIndex) in weeks" :key="weekIndex" class="week">
              <td
                v-for="(day, dayIndex) in week"
                :key="dayIndex"
                class="port"
                :class="{ active: weekIndex * 7 + dayIndex === activePort }"
                @click="setDay(weekIndex * 7 + dayIndex, day)"
              >
                {{ day }}
              </td>
            </tr>
          </tbody>
        </table>

        <div class="time-picker" :class="{ noDisplay: hideTime }">
          <div class="hour-selector">
            <bz-select v-model="hour" label="Hours" variant="white" :options="hours" />
          </div>
          <div class="minute-selector">
            <bz-select v-model="minute" label="Minutes" variant="white" :options="minutes" />
          </div>
        </div>

        <div class="button-group">
          <button type="button" class="cancel-button" @click="clearDate">Cancel</button>
          <button type="button" class="save-button" @click="setDate">Save</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import endOfMonth from 'date-fns/endOfMonth'
import eachDay from 'date-fns/eachDayOfInterval'
import getDay from 'date-fns/getDay'
import format from 'date-fns/format'
import startOfDay from 'date-fns/startOfDay'
import isEqual from 'date-fns/isEqual'
import ArrowBackIcon from '../icons/ArrowBack.vue'
import ArrowForwardIcon from '../icons/ArrowForward.vue'
import BzSelect from '../page/BzSelect.vue'
const days = ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa']
const AM = 'AM'
const PM = 'PM'
export default {
  name: 'BzDatetimePicker',
  components: { BzSelect, ArrowForwardIcon, ArrowBackIcon },
  props: {
    format: {
      type: String,
      default: 'YYYY-MM-DD H:i:s'
    },
    name: {
      type: String,
      default: ''
    },
    width: {
      type: String,
      default: '300px'
    },
    modelValue: {
      type: String,
      default: ''
    },
    required: {
      type: Boolean,
      default: false
    },
    readonly: {
      type: Boolean,
      default: false
    },
    firstDayOfWeek: {
      default: 0,
      validator: function (value) {
        try {
          const val = parseInt(value, 10)
          return val >= 0 && val <= 1
        } catch (e) {
          console.warn(e.message)
          return false
        }
      },
      message: 'Only 0 (Sunday) and 1 (Monday) are supported.'
    },
    onCancel: {
      type: Function,
      required: true
    }
  },
  data() {
    return {
      date: this.modelValue,
      hideCal: true,
      activePort: null,
      timeStamp: new Date(),
      months: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
      days: [],
      monthIndex: 0,
      hourIndex: 0,
      minuteIndex: 0,
      year: 2017,
      portsHolder: [],
      minute: '00',
      hour: '01',
      day: 1,
      minuteSelectorVisible: false,
      hourSelectorVisible: false,
      period: AM,
      periodStyle: 24
    }
  },
  computed: {
    normalizedFirstDayOfWeek: function () {
      return parseInt(this.firstDayOfWeek, 10)
    },
    ports: {
      get: function () {
        let p = []
        if (this.portsHolder.length === 0) {
          for (let i = 0; i < 42; i++) {
            p.push('')
          }
        } else {
          p = this.portsHolder
        }
        return p
      },
      set: function (newValue) {
        this.portsHolder = newValue
      }
    },
    month() {
      return this.months[this.monthIndex]
    },
    dateTime() {
      return this.timeStamp.getFullYear() + '-' + (this.timeStamp.getMonth() + 1) + '-' + this.timeStamp.getUTCDay()
    },
    minutes() {
      const arr = []
      for (let i = 0; i < 60; i++) {
        i < 10 ? arr.push('0' + i) : arr.push('' + i)
      }
      return arr
    },
    hours() {
      const arr = []
      if (this.periodStyle === 24) {
        for (let i = 0; i < this.periodStyle; i++) {
          i < 10 ? arr.push('0' + i) : arr.push('' + i)
        }
      } else {
        for (let i = 1; i <= this.periodStyle; i++) {
          i < 10 ? arr.push('0' + i) : arr.push('' + i)
        }
      }
      return arr
    },
    dateFormat() {
      let f = 'YYYY-MM-DD h:i:s'
      const allowedFormats = [
        'YYYY-MM-DD h:i:s',
        'DD-MM-YYYY h:i:s',
        'MM-DD-YYYY h:i:s',
        'YYYY-MM-DD h:i',
        'DD-MM-YYYY h:i',
        'MM-DD-YYYY h:i',
        'YYYY-MM-DD H:i:s',
        'DD-MM-YYYY H:i:s',
        'MM-DD-YYYY H:i:s',
        'YYYY-MM-DD H:i',
        'DD-MM-YYYY H:i',
        'MM-DD-YYYY H:i',
        'YYYY-MM-DD',
        'DD-MM-YYYY',
        'MM-DD-YYYY',
        'YYYY/MM/DD',
        'DD/MM/YYYY',
        'MM/DD/YYYY',
        'h:i:s',
        'H:i:s',
        'h:i',
        'H:i',
        'YYYY/MM/DD h:i:s',
        'DD/MM/YYYY h:i:s',
        'MM/DD/YYYY h:i:s',
        'YYYY/MM/DD h:i',
        'DD/MM/YYYY h:i',
        'MM/DD/YYYY h:i',
        'YYYY/MM/DD H:i:s',
        'DD/MM/YYYY H:i:s',
        'MM/DD/YYYY H:i:s',
        'YYYY/MM/DD H:i',
        'DD/MM/YYYY H:i',
        'MM/DD/YYYY H:i'
      ]
      if (this.format) {
        f = this.format
      }
      if (allowedFormats.indexOf(f) < 0) {
        console.warn('Invalid date format supplied. Current default date format is being used.')
        // return default date format if date format is invalid
        return 'YYYY-MM-DD h:i:s'
      } else {
        return f
      }
    },
    hideTime() {
      return this.dateFormat.indexOf('h:i:s') === -1 && this.dateFormat.indexOf('H:i:s') === -1 && this.dateFormat.indexOf('h:i') === -1 && this.dateFormat.indexOf('H:i') === -1
    },
    hideDate() {
      return this.dateFormat === 'h:i:s' || this.dateFormat === 'H:i:s' || this.dateFormat === 'h:i' || this.dateFormat === 'H:i'
    }
  },
  watch: {
    value(newVal, oldVal) {
      if (newVal) {
        try {
          this.timeStamp = this.makeDateObject(this.modelValue)
        } catch (e) {
          console.warn(e.message + '. Current date is being used.')
          this.timeStamp = new Date()
        }
        this.year = this.timeStamp.getFullYear()
        this.monthIndex = this.timeStamp.getMonth()
        this.day = this.timeStamp.getDate()
        this.hour = this.timeStamp.getHours()
        this.hour = this.hour < 10 ? '0' + this.hour : '' + this.hour
        this.minute = this.timeStamp.getMinutes()
        this.minute = this.minute < 10 ? '0' + this.minute : '' + this.minute
        this.updateCalendar()
        this.setDate()
      }
    }
  },
  created() {
    if (this.modelValue) {
      try {
        this.timeStamp = this.makeDateObject(this.modelValue)
        // set #period (am or pm) based on date hour
        if (this.timeStamp.getHours() >= 12) {
          this.period = PM
        } else {
          this.period = AM
        }
      } catch (e) {
        this.timeStamp = new Date()
        console.log(e)
      }
    }

    this.year = this.timeStamp.getFullYear()
    this.monthIndex = this.timeStamp.getMonth()
    this.day = this.timeStamp.getDate()
    this.hour = this.timeStamp.getHours()
    this.hour = this.hour < 10 ? '0' + this.hour : '' + this.hour
    this.minute = this.timeStamp.getMinutes()
    this.minute = this.minute < 10 ? '0' + this.minute : '' + this.minute

    this.updateCalendar()
    days.forEach((day, idx) => {
      this.days[(idx - this.normalizedFirstDayOfWeek + 7) % 7] = day
    })
    this.setPeriodStyle()
  },
  methods: {
    leftMonth() {
      const index = this.months.indexOf(this.month)
      if (index === 0) {
        this.monthIndex = 11
      } else {
        this.monthIndex = index - 1
      }
      this.updateCalendar()
    },
    rightMonth() {
      const index = this.months.indexOf(this.month)
      if (index === 11) {
        this.monthIndex = 0
      } else {
        this.monthIndex = index + 1
      }
      this.updateCalendar()
    },
    rightYear() {
      this.year++
      this.updateCalendar()
    },
    leftYear() {
      this.year--
      this.updateCalendar()
    },
    updateActivePortFromWeek(week, weekIndex) {
      const currentActive = startOfDay(this.timeStamp)
      const index = week.findIndex((day) => isEqual(currentActive, day))
      if (index !== -1) {
        this.activePort = weekIndex * 7 + index
      }
    },
    updateCalendar() {
      const date = new Date(this.year, this.monthIndex, 1, 0, 0, 0)
      const weeks = []
      let week = null
      let weekDays = new Array(7)
      // let index = 0;
      this.activePort = null

      eachDay({ start: date, end: endOfMonth(date) }).forEach((day) => {
        const weekday = getDay(day)
        if (weekday === this.normalizedFirstDayOfWeek) {
          if (week) {
            weeks.push(week)
            // Add those days that were not part of the month to the index
            // index += week.filter(d => d === null).length;
            this.updateActivePortFromWeek(weekDays, weeks.length - 1)
            weekDays = new Array(7)
          }
          week = new Array(7)
        } else if (week === null) {
          week = new Array(7)
        }
        const idx = (weekday - this.normalizedFirstDayOfWeek + 7) % 7
        week[idx] = format(day, 'dd')
        weekDays[idx] = day
      })
      if (week && week.some((n) => n)) {
        weeks.push(week)
        this.updateActivePortFromWeek(weekDays, weeks.length - 1)
      }

      this.weeks = weeks
    },
    setDay(index, port) {
      if (port) {
        this.activePort = index
        this.day = parseInt(port, 10)
        this.timeStamp = new Date(this.year, this.monthIndex, this.day)
      }
    },
    setMinute(index, closeAfterSet) {
      this.minuteIndex = index
      this.minute = this.minutes[index]
      if (closeAfterSet) {
        this.minuteSelectorVisible = false
      }
    },
    setHour(index, closeAfterSet) {
      this.hourIndex = index
      this.hour = this.hours[index]
      if (closeAfterSet) {
        this.hourSelectorVisible = false
      }
    },
    showHourSelector() {
      this.hourSelectorVisible = true
      this.minuteSelectorVisible = false
    },
    showMinuteSelector() {
      this.minuteSelectorVisible = true
      this.hourSelectorVisible = false
    },
    scrollTopMinute() {
      const mHeight = this.$refs.minuteScroller.scrollHeight
      const wHeight = this.$refs.minuteScrollerWrapper.clientHeight
      const top = mHeight * (this.minuteIndex / (this.minutes.length - 1)) - wHeight / 2
      this.$refs.minuteScroller.scrollTop = top
    },
    scrollTopHour() {
      const mHeight = this.$refs.hourScroller.scrollHeight
      const wHeight = this.$refs.hourScrollerWrapper.clientHeight
      const top = mHeight * (this.hourIndex / (this.hours.length - 1)) - wHeight / 2
      this.$refs.hourScroller.scrollTop = top
    },
    changePeriod() {
      this.period = this.period === AM ? PM : AM
    },
    calendarClicked(event) {
      if (event.target.id !== 'j-hour' && event.target.id !== 'j-minute') {
        this.minuteSelectorVisible = false
        this.hourSelectorVisible = false
      }
      event.cancelBubble = true
      if (event.stopPropagation) {
        event.stopPropagation()
      }
    },
    toggleCal() {
      this.hideCal = !this.hideCal
      if (this.hideCal && typeof this.onCancel === 'function') {
        this.onCancel()
      }
    },
    hiddenCal(e) {
      this.hideCal = true
      if (this.hiddenCal && typeof this.onCancel === 'function') {
        this.onCancel()
      }
    },
    setPeriodStyle() {
      if (this.dateFormat.indexOf('H') !== -1) {
        this.periodStyle = 24
        this.period = null
      } else {
        this.periodStyle = 12
      }
    },
    setDate() {
      let d = null
      this.setPeriodStyle()
      let h = this.hour + ''
      if (this.periodStyle === 12) {
        if (h === '12') {
          h = this.period === AM ? '00' : '12'
        } else if (this.period === PM && parseInt(h) < 12) {
          h = parseInt(h) + 12
          h = '' + h
        } else if (this.period === AM && parseInt(h) > 12) {
          h = parseInt(h) - 12
          h = '' + h
        }
      }
      d = this.dateFormat
      d = d.replace('YYYY', this.year)
      d = d.replace('DD', this.day < 10 ? '0' + this.day : this.day)
      const m = this.monthIndex + 1
      d = d.replace('MM', m < 10 ? '0' + m : m)
      this.minute += ''
      d = d.replace(this.periodStyle === 24 ? 'H' : 'h', h.length < 2 ? '0' + h : '' + h)
      d = d.replace('i', this.minute.length < 2 ? '0' + this.minute : '' + this.minute)
      d = d.replace('s', '00')
      this.$emit('update:modelValue', d)
      this.date = d
      this.hideCal = true
    },
    /**
         `*Creates a date object from a given date string
         */
    makeDateObject(val) {
      // handle support for eu date format
      const dateAndTime = val.split(' ')
      let arr = []
      if (this.format.indexOf('-') !== -1) {
        arr = dateAndTime[0].split('-')
      } else {
        arr = dateAndTime[0].split('/')
      }
      let year = 0
      let month = 0
      let day = 0
      if (this.format.indexOf('DD/MM/YYYY') === 0 || this.format.indexOf('DD-MM-YYYY') === 0) {
        year = arr[2]
        month = arr[1]
        day = arr[0]
      } else if (this.format.indexOf('YYYY/MM/DD') === 0 || this.format.indexOf('YYYY-MM-DD') === 0) {
        year = arr[0]
        month = arr[1]
        day = arr[2]
      } else {
        year = arr[2]
        month = arr[0]
        day = arr[1]
      }
      let date = new Date()
      if (this.hideDate) {
        // time only
        const splitTime = dateAndTime[0].split(':')
        // handle date format without seconds
        const secs = splitTime.length > 2 ? parseInt(splitTime[2]) : 0
        date.setHours(parseInt(splitTime[0]), parseInt(splitTime[1]), secs, 0)
      } else if (this.hideTime) {
        // date only
        date = new Date(parseInt(year), parseInt(month) - 1, parseInt(day))
      } else {
        // we have both date and time
        const splitTime = dateAndTime[1].split(':')
        // handle date format without seconds
        const secs = splitTime.length > 2 ? parseInt(splitTime[2]) : 0
        date = new Date(parseInt(year), parseInt(month) - 1, parseInt(day), parseInt(splitTime[0]), parseInt(splitTime[1]), secs)
      }
      return date
    },
    clearDate() {
      this.toggleCal()
    }
  }
}
</script>

<style lang="scss" scoped>
input {
  min-width: 226px;
  width: 100%;
  height: 30px;
  padding: 3px;
  border: 1px solid #ddd;
}
.datetime-picker {
  * {
    color: black;
  }

  position: relative;
  display: flex;
  justify-content: center;
  .calender-div {
    min-width: 270px;
    box-shadow: 1px 2px 5px #ccc;
    background: #fff;
    position: absolute;
    display: inline-block;
    top: 100px;
    color: #444;
    font-size: 14px;
    padding-bottom: 10px;
    border-radius: 3px;
    z-index: 100;

    &.noDisplay {
      display: none !important;
    }

    .calender-wrapper {
      display: flex;
      align-items: center;
      flex-direction: column;

      .year-month-wrapper {
        background-color: transparent;
        display: flex;
        flex-direction: column;
        width: 100%;
        align-items: center;

        .month-setter,
        .year-setter {
          width: 90%;
          color: white;
          font-weight: 900;
          display: flex;
          margin-top: 10px;

          .nav-r {
            border-top-right-radius: 3px;
            border-bottom-right-radius: 3px;
          }

          .nav-l {
            border-top-left-radius: 3px;
            border-bottom-left-radius: 3px;
          }

          .nav-l,
          .nav-r {
            width: 30px;
            height: 30px;
            justify-content: center;
            align-items: center;
            display: flex;
            color: white;
            background-color: var(--bz-section-edit-active-color);

            &:hover {
              background-color: var(--bz-section-edit-active-hover-color);
            }
          }
          .month,
          .year {
            background-color: #0000000d;
            color: #00000099;
            width: calc(100% - 60px);
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 0;
          }
        }
      }

      .calender-table {
        width: 90%;
        border-collapse: collapse;
        margin-top: 10px;
        border-top-right-radius: 3px;
        border-top-left-radius: 3px;
        table-layout: fixed;

        thead {
          background-color: #f3f3f3;
        }

        th,
        td {
          height: 30px;
          text-align: center;
          vertical-align: middle;
          cursor: pointer;
          border: solid 1px #f3f3f3;
        }

        th {
          font-size: 13px;
          font-weight: 300;
        }

        td {
          font-size: 12px;
          font-weight: 100;

          &:hover {
            background-color: var(--bz-section-edit-active-hover-color);
            transition: all 0.4s;
            color: white;
          }

          &.active {
            background-color: var(--bz-section-edit-active-color);
            color: white;
          }
        }
      }

      .time-picker {
        display: flex;
        width: 90%;
        align-items: center;
        justify-content: space-between;
        margin-top: 15px;

        .minute-selector,
        .hour-selector {
          width: 45%;
          text-align: center;
          position: relative;
          cursor: pointer;
        }
      }

      .button-group {
        width: 90%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 10px;

        .cancel-button,
        .save-button {
          width: 45%;
          padding: 6px;
          align-items: center;
          justify-content: center;
          display: flex;
          border-radius: 3px;
          border: solid 1px var(--bz-section-edit-active-color);
        }
        .cancel-button {
          background-color: white;
          color: var(--bz-section-edit-active-color);
          &:hover {
            background-color: var(--bz-section-edit-active-color-opacity);
          }
        }

        .save-button {
          background-color: var(--bz-section-edit-active-color);
          color: white;
          &:hover {
            background-color: var(--bz-section-edit-active-hover-color);
          }
        }
      }
    }
  }
}
.noDisplay {
  display: none;
}
</style>
