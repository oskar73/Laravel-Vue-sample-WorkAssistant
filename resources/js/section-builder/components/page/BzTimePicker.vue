<template>
  <div class="bz-time-picker_label">
    <transition>
      <div class="bz-time-picker_wrap">
        <div class="bz-time-picker_picker">
          <div class="bz-time-picker_header">
            <span :class="{ active: mode === 3 }" class="bz-time-picker_text bz-time-picker_text--pointer" @click.prevent="mode = 3">{{ hour }}</span>
            <span class="bz-time-picker_text">:</span>
            <span :class="{ active: mode === 4 }" class="bz-time-picker_text bz-time-picker_text--pointer" @click.prevent="mode = 4">{{ minute }}</span>
            <div class="bz-time-picker_pm-or-am">
              <span class="bz-time-picker_text bz-time-picker_text--sm bz-time-picker_text--pointer" :class="{ active: !isPm }" @click="isPm = false"> AM </span>
              <span class="bz-time-picker_text bz-time-picker_text--sm bz-time-picker_text--pointer" :class="{ active: isPm }" @click="isPm = true"> PM </span>
            </div>
          </div>
          <div class="bz-time-picker_body">
            <div class="bz-time-picker_clock">
              <ul class="bz-time-picker_items" @mousedown="handleMouseDown" @mousemove="handleMouseMove" @mouseup="handleMouseUp">
                <li
                  v-for="time in templateTimePoints"
                  :key="`${time.point}-clockItem`"
                  class="bz-time-picker_item minute-step_default"
                  :class="{ active: mode === 3 ? hour === time.point : minute === time.point }"
                >
                  {{ time.point }}
                </li>
              </ul>
              <div class="bz-time-picker_center-point"></div>
              <div class="bz-time-picker_arrow" :class="{ pressed: isPressed }" :style="{ transform: `rotate(${degree}deg)` }"></div>
            </div>
          </div>
          <div class="bz-time-picker_buttons">
            <button class="bz-time-picker_button" @click="handleCancel">Cancel</button>
            <button class="bz-time-picker_button" @click="handleOk">OK</button>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
import helpers from '../../helpers'
export default {
  name: 'BzTimePicker',
  components: {},
  props: {
    modelValue: {
      type: [String, Number, Date, Object],
      required: false,
      default: ''
    },
    valueFormatted: {
      type: [String, Number, Date, Object],
      required: false,
      default: ''
    },
    minuteStep: {
      type: Number,
      required: false,
      default: 1,
      validator: (v) => [1, 5, 15, 30, 45, 60].includes(v)
    },
    hourStep: {
      type: Number,
      required: false,
      default: 1
    },
    disabledDatesAndTimes: {
      type: [Array, Object],
      required: false
    },
    selectedDay: {
      type: Number,
      default: 0
    },
    selectedYear: {
      type: Number,
      default: 0
    },
    selectedMoth: {
      type: Number,
      default: 0
    }
  },
  data: () => ({
    isPickerShown: false,
    MODE: { HOUR: 3, MINUTE: 4 },
    isPressed: false,
    XC: null,
    YC: null,
    degree: 0,
    hours: null,
    minutes: null,
    isPm: false,
    mode: 3
  }),
  computed: {
    hour() {
      return this.hours !== null ? this.hours : '--'
    },
    minute() {
      let minutes = this.minutes !== null ? this.minutes : '--'
      if (Number.isInteger(minutes) && minutes < 10) minutes = `0${minutes}`
      return minutes
    },
    pmOrAm() {
      return this.isPm ? 'PM' : 'AM'
    },
    listeners() {
      return {
        ...this.$listeners,
        input: (event) => this.$emit('update:modelValue', event)
      }
    },
    displayedValue() {
      const isValueFormattedPassed = this.$options.propsData.hasOwnProperty('valueFormatted')
      return isValueFormattedPassed ? this.valueFormatted : this.value
    },
    disabled() {
      return (this.mode === 4 || this.mode === 3) && !this.canFinish
    },
    isHourMode() {
      return this.mode === this.MODE.HOUR
    },
    realMinutes() {
      const minutes = helpers.createReal60MinutesArray()
      const params = {
        forHours: false,
        isPm: this.isPm,
        year: this.selectedYear,
        month: this.selectedMoth,
        day: this.selectedDay,
        hours: this.hours,
        disabledDatesAndTimes: this.disabledDatesAndTimes,
        timePoints: minutes
      }

      if (this.minuteStep >= 5) {
        const disabled = this.disabledDatesAndTimes.length > 0 ? helpers.createDisabledTimesArray(params) : []
        const stepsParams = {
          minutes,
          step: this.minuteStep,
          disabledMinutes: disabled
        }
        return helpers.filterMinutesWithSteps(stepsParams)
      } else {
        return minutes
      }
    },
    realHours() {
      if (this.isPm) {
        const arr = Array.from({ length: 12 }, (v, k) => k + 1)
        return arr.map((item) => ({ disabled: false, point: item }))
      } else {
        const arr = Array.from({ length: 12 }, (v, k) => k)
        arr.push(arr.shift())
        return arr.map((item) => ({ disabled: false, point: item }))
      }
    },
    disabledHours() {
      if (!this.disabledDatesAndTimes) return []
      const params = {
        forHours: true,
        isPm: this.isPm,
        year: this.selectedYear,
        month: this.selectedMoth,
        day: this.selectedDay,
        hours: null,
        disabledDatesAndTimes: this.disabledDatesAndTimes,
        timePoints: this.realHours
      }
      return helpers.createDisabledTimesArray(params)
    },
    disabledMinutes() {
      if (!this.disabledDatesAndTimes) return []
      const params = {
        forHours: false,
        isPm: this.isPm,
        year: this.selectedYear,
        month: this.selectedMoth,
        day: this.selectedDay,
        hours: this.hours,
        disabledDatesAndTimes: this.disabledDatesAndTimes,
        timePoints: this.realMinutes
      }
      return helpers.createDisabledTimesArray(params)
    },
    allHours() {
      const hours = this.realHours.map((h) => (this.disabledHours.find((k) => k && k.point === h.point) ? this.disabledHours.find((k) => k.point === h.point) : h))
      return hours
    },
    allMinutes() {
      const minutes = this.realMinutes.map((h) => (this.disabledMinutes.find((k) => k && k.point === h.point) ? this.disabledMinutes.find((k) => k.point === h.point) : h))
      return minutes
    },
    enabledHours() {
      return this.disabledHours.length > 0 ? this.allHours.filter((h) => !h.disabled) : this.allHours
    },
    enabledMinutes() {
      return this.disabledMinutes.length > 0 ? this.allMinutes.filter((h) => !h.disabled) : this.allMinutes.filter((h) => !h.disabled)
    },
    templateTimePoints() {
      if (this.isHourMode) {
        return this.allHours
      } else {
        return this.allMinutes.filter((m) => m.point % 5 === 0)
      }
    },
    enabledPoints() {
      return this.isHourMode ? this.enabledHours : this.enabledMinutes
    },
    isEnabledAMFormatForHours() {
      if (!this.disabledDatesAndTimes) return true
      const params = {
        forHours: true,
        isPm: false,
        year: this.selectedYear,
        month: this.selectedMoth,
        day: this.selectedDay,
        hours: null,
        disabledDatesAndTimes: this.disabledDatesAndTimes,
        timePoints: this.realHours
      }
      const hours = helpers.createDisabledTimesArray(params)
      return hours.length < 12
    },
    isEnabledAMFormatForMinutes() {
      if (!this.disabledDatesAndTimes) return true
      const params = {
        forHours: false,
        isPm: false,
        year: this.selectedYear,
        month: this.selectedMoth,
        day: this.selectedDay,
        hours: this.hours,
        disabledDatesAndTimes: this.disabledDatesAndTimes,
        timePoints: this.realMinutes
      }
      const minutes = helpers.createDisabledTimesArray(params)
      return minutes.length < 60
    },
    isEnabledAM() {
      return this.isHourMode ? this.isEnabledAMFormatForHours : this.isEnabledAMFormatForMinutes
    }
  },
  watch: {
    isHourMode: {
      immediate: true,
      handler(v) {
        if (v) this.hours ? this.$emit('hour', this.hours) : this.findInitialPoint()
      }
    },
    degree: {
      immediate: true,
      handler(v) {
        if (this.isHourMode) {
          const hours = Math.round(v / 30)
          if (this.isPm) {
            if (hours === 0) {
              this.hours = helpers.isHourDisabled(12, this.allHours) ? this.moveArrowToClosestPoint() : 12
            } else {
              this.hours = helpers.isHourDisabled(hours, this.allHours) ? this.moveArrowToClosestPoint() : hours
            }
          } else {
            this.hours = hours === 12 ? 0 : hours
          }
          this.$emit('hour', this.hours)
        } else {
          const rounded = Math.round(v / 6)
          const minutes = rounded !== 60 ? rounded : 0
          if (!minutes) {
            this.minutes = helpers.isMinuteDisabled(minutes, this.allMinutes) ? this.moveArrowToClosestPoint() : minutes
          } else {
            this.minutes = minutes
          }
          this.$emit('minute', this.minutes)
        }
      }
    },
    mode(v) {
      if (v === 3) this.degree = this.hours * 30
      if (v === 4) this.degree = this.minutes * 6
    },
    hours: {
      immediate: true,
      handler(v) {
        const areHoursSet = v !== null
        const areMinutesSet = !!this.minutes
        return areHoursSet && areMinutesSet ? this.$emit('update-can-finish', true) : this.$emit('update-can-finish', false)
      }
    },
    minutes: {
      immediate: true,
      handler(v) {
        const areHoursSet = this.hours !== null
        const areMinutesSet = v !== null
        return areHoursSet && areMinutesSet ? this.$emit('update-can-finish', false) : this.$emit('update-can-finish', true)
      }
    },
    isPressed: {
      handler(v) {
        return this.$emit('update-can-finish', !v)
      }
    },
    isPm: {
      handler(v) {
        this.hours = v ? (this.hours === 0 ? 12 : this.hours) : this.hours === 12 ? 0 : this.hours
        this.$emit('hour', this.hours)
      }
    }
  },
  mounted() {
    const time = this.$store.state.timePicker.time || '09:25 AM'
    if (/[0-9]{2}:[0-9]{2} [a,A,p,P][M,m]/.test(time)) {
      this.hours = parseInt(time.split(':')[0])
      this.minutes = parseInt(time.split(':')[1].split(' ')[0])
      this.isPm = time.split(':')[1].split(' ')[1].toLowerCase() === 'pm'
      this.degree = this.hours * 30
    }
  },
  methods: {
    handleMouseDown(event) {
      this.isPressed = true
      this.XC = event.offsetX
      this.YC = event.offsetY
      this.calculateDeg()
    },
    handleMouseUp() {
      this.isPressed = false
      if (this.isHourMode) {
        if (this.minuteStep === 60) {
          this.minutes = 0
          this.$emit('minute', this.minutes)
        } else {
          if (this.disabledDatesAndTimes) {
            this.moveArrowToClosestPoint()
            setTimeout(() => this.$emit('mode', this.MODE.MINUTE), 750)
          } else {
            this.$emit('mode', this.MODE.MINUTE)
          }
        }
      } else {
        // move clock arrow to closest value
        if (this.minuteStep === 1) {
          if (this.minutes === 0) {
            this.$emit('minute', this.minutes)
          } else {
            this.moveArrowToClosestPoint()
            this.$emit('minute', this.minutes)
          }
        } else {
          this.moveArrowToClosestPoint()
        }
      }
      if (this.mode === 3) {
        this.mode = 4
      }
    },
    findInitialPoint() {
      const time = this.allHours[this.allHours.length - 1]
      const { disabled } = time
      this.disabledDatesAndTimes ? (disabled ? this.moveArrowToClosestPoint() : (this.hours = time.point)) : (this.hours = time.point)
    },
    moveArrowToClosestPoint() {
      const clockItemsAsNumbers = [...this.enabledPoints].map((p) => p.point)
      if (this.isHourMode) {
        const hours = this.findClosest(clockItemsAsNumbers, this.hours)
        this.hours = this.isPm ? (hours === 0 ? 12 : hours) : hours === 12 ? 0 : hours
        this.degree = this.calcDegByHours(this.hours)
      } else {
        clockItemsAsNumbers.push(60)
        this.minutes = this.findClosest(clockItemsAsNumbers, this.minutes)
        this.minutes = this.minutes === 60 ? 0 : this.minutes

        const minutesForDegreeCalculation = this.minutes === 0 ? 60 : this.minutes
        this.degree = this.calcDegByMinutes(minutesForDegreeCalculation)
      }
    },
    findClosest(arr, target) {
      return arr.reduce((prev, curr) => (Math.abs(curr - target) < Math.abs(prev - target) ? curr : prev))
    },
    handleMouseMove(event) {
      if (event.offsetX > 250 || event.offsetY > 250) {
        this.isPressed = false
        return
      }
      if (this.isPressed) {
        this.XC = event.offsetX
        this.YC = event.offsetY
        this.calculateDeg()
      }
    },
    calcDegByMinutes(minutes) {
      return (minutes / 60) * 360
    },
    calcDegByHours(hours) {
      return (hours / 12) * 360
    },
    calculateDeg() {
      const XA = 125
      const YA = 125
      const XB = 125
      const YB = 0
      const XC = this.XC
      const YC = this.YC
      const vectorAB = [XB - XA, YB - YA]
      const vectorAC = [XC - XA, YC - YA]
      const fractionUpperPart = vectorAB[0] * vectorAC[0] + vectorAB[1] * vectorAC[1]
      const fractionLowerPart0 = Math.pow(vectorAB[0], 2) + Math.pow(vectorAB[1], 2)
      const fractionLowerPart1 = Math.pow(vectorAC[0], 2) + Math.pow(vectorAC[1], 2)
      const fractionLowerPart = Math.sqrt(fractionLowerPart0) * Math.sqrt(fractionLowerPart1)
      const arcCosARadians = Math.acos(fractionUpperPart / fractionLowerPart)
      const xRight = XC >= 0 && XC < 125
      const xLeft = XC >= 125 && XC <= 250
      const yTop = YC >= 0 && YC < 125
      const yBottom = YC >= 125 && YC <= 250
      let degree = 0
      if (xLeft && (yTop || yBottom)) {
        degree = Math.floor((180 / Math.PI) * arcCosARadians)
      }
      if (xRight && (yTop || yBottom)) {
        degree = 360 - Math.floor((180 / Math.PI) * arcCosARadians)
      }

      if (this.mode === 4) {
        this.degree = degree
      } else {
        this.degree = [30, 60, 90, 120, 150, 180, 210, 240, 270, 300, 330, 360].reduce((a, b) => {
          return Math.abs(b - degree) < Math.abs(a - degree) ? b : a
        })
      }
    },
    handleClick() {
      this.isPickerShown = true
    },
    handleClose() {
      this.isPickerShown = false
    },
    handleSet(date) {
      this.isPickerShown = false
      this.$emit('update:modelValue', date)
    },
    handleCancel() {
      this.$store.commit('closeTimePicker')
    },
    handleOk() {
      const hours = this.hour < 10 ? '0' + this.hour : this.hour
      const minutes = this.minute < 10 ? '0' + this.minute : this.minute
      const a = this.isPm ? 'pm' : 'am'
      const time = `${hours}:${minutes} ${a}`
      this.$store.state.timePicker.onChange(time)
      this.$store.commit('closeTimePicker')
    }
  }
}
</script>

<style lang="scss" scoped>
$c-white: #ffffff;
$c-blue: #2793f3;
$c-blue-darken: #0076df;
$c-black: #545454;
$c-gray: #e0e0e0;
$c-gray-darken: #9e9e9e;

.bz-time-picker_input {
  box-sizing: border-box;
  width: 100%;
  padding: 8px 12px;
  font-size: 1rem;
  line-height: 1.25;
  border: 1px solid black;
  border-radius: 4px;
  overflow: hidden;
  &[disabled='disabled'] {
    background-color: transparent;
  }
}

.bz-time-picker_wrap {
  position: fixed;
  z-index: 4;
  width: 100vw;
  height: 100vh;
  background-color: rgba($c-black, 0.25);

  .bz-time-picker_picker {
    position: absolute;
    z-index: 5;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 290px;
    background-color: $c-white;
    box-shadow: 0 0 4px 0 rgba($c-black, 0.3);

    .bz-time-picker_header {
      display: flex;
      align-items: center;
      justify-content: flex-end;
      background-color: $c-blue-darken;
      color: $c-white;
      padding: 16px;

      .bz-time-picker_pm-or-am {
        display: flex;
        flex-direction: column;
        align-items: center;
        height: 40px;
        justify-content: space-between;
      }

      .bz-time-picker_text {
        margin: 0;
        font-family: 'Roboto', sans-serif;
        font-size: 36px;
        line-height: 36px;
        font-weight: 900;
        text-align: right;
        opacity: 0.5;
        &.active {
          opacity: 1;
        }
        &--pointer {
          cursor: pointer;
          padding: 5px;
          border-radius: 3px;

          &:hover {
            background-color: $c-blue;
          }
        }
        &--sm {
          margin-left: 8px;
          font-size: 14px;
          line-height: 14px;
          font-weight: 400;
        }
      }
    }

    .bz-time-picker_body {
      height: 290px;
      overflow: auto;
      padding: 0 12px;
      display: flex;
      align-items: center;
      justify-content: center;

      .bz-time-picker_clock {
        position: relative;
        width: 250px;
        height: 250px;
        border-radius: 50%;
        background-color: rgba(0, 0, 0, 0.07);

        .bz-time-picker_center-point {
          position: absolute;
          z-index: 100;
          top: 50%;
          left: 50%;
          transform: translate(-50%, -50%);
          width: 8px;
          height: 8px;
          border-radius: 50%;
          background-color: $c-blue-darken;
        }

        .bz-time-picker_arrow {
          pointer-events: none;
          height: calc(50% - 36px);
          width: 3px;
          bottom: 50%;
          left: calc(50% - 1px);
          transform-origin: center bottom;
          position: absolute;
          z-index: 3;
          background-color: $c-blue-darken;
          transition: all 0.75s cubic-bezier(0.19, 1, 0.22, 1);

          &.pressed {
            transition: none;
          }

          &:before {
            display: block;
            content: '';
            position: absolute;
            left: -15px;
            top: -32px;
            z-index: 100;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: $c-blue-darken;
            cursor: grab;
          }
        }
        .bz-time-picker_items {
          position: absolute;
          z-index: 11;
          top: 0;
          left: 0;
          right: 0;
          bottom: 0;
          padding: 0;
          margin: 0;
          list-style: none;
          border-radius: 50%;
        }
        .bz-time-picker_item {
          pointer-events: none;
          position: absolute;
          z-index: 1;
          left: 50%;
          top: 50%;
          padding: 0;
          margin: 0;
          width: 30px;
          height: 30px;
          display: flex;
          align-items: center;
          justify-content: center;
          font-family: sans-serif;
          font-size: 17px;
          font-weight: 500;
          transform: translate(-50%, -50%);
          color: black;
          border-radius: 50%;
          &.active {
            color: white;
          }
          &.minute-step_default {
            &:nth-child(1) {
              transform: translate(125%, -350%);
            }
            &:nth-child(2) {
              transform: translate(255%, -220%);
            }
            &:nth-child(3) {
              transform: translate(300%, -50%);
            }
            &:nth-child(4) {
              transform: translate(255%, 130%);
            }
            &:nth-child(5) {
              transform: translate(130%, 255%);
            }
            &:nth-child(6) {
              transform: translate(-48%, 305%);
            }
            &:nth-child(7) {
              transform: translate(-222%, 259%);
            }
            &:nth-child(8) {
              transform: translate(-350%, 130%);
            }
            &:nth-child(9) {
              transform: translate(-400%, -50%);
            }
            &:nth-child(10) {
              transform: translate(-355%, -220%);
            }
            &:nth-child(11) {
              transform: translate(-227%, -351%);
            }
            &:nth-child(12) {
              transform: translate(-50%, -400%);
            }
          }
        }
      }
    }

    .bz-time-picker_buttons {
      width: 100%;
      padding: 8px 16px;
      display: flex;
      justify-content: flex-end;
      align-items: center;

      .bz-time-picker_button {
        padding: 4px 8px;
        margin: 0 0 0 10px;
        border: none;
        font-size: 14px;
        line-height: 22px;
        font-weight: 600;
        color: $c-blue;
        cursor: pointer;
        outline: none;
        border-radius: 2px;

        &:hover {
          background-color: rgba($c-blue-darken, 0.1);
        }
      }
    }
  }
}
</style>
