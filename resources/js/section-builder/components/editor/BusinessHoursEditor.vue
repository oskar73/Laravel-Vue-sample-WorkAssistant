<template>
  <div class="bz-business-hours-root" :class="{ edit }">
    <bz-select v-model="appointmentCategory" class="mb-4" :options="appointmentCategories" />

    <div v-for="(hour, day) of businessHours" :key="day" class="w-100 open-hour-item">
      <div class="w-100 d-flex align-items-center justify-content-between">
        <div class="day-item">{{ day }}</div>
        <div class="hour-item">
          <div class="arrow-back-icon" @click="prev(day)">
            <arrow-back-ios-icon :size="14" :fill-color="'#555555'" />
          </div>
          <div class="d-flex align-items-center justify-content-center">
            <template v-if="hour.dayTime">
              <div class="day-time">
                <div class="start-time" @click="editDayStartTime(day)">{{ hour.dayTime.start }}</div>
                <span>-</span>
                <div class="end-time" @click="editDayEndTime(day)">{{ hour.dayTime.end }}</div>
              </div>
            </template>
            <template v-if="hour.type">
              <span style="font-size: 12px; padding: 0 10px">{{ hour.type }}</span>
            </template>
            <template v-if="hour.nightTime">
              <div class="night-time">
                <div class="start-time" @click="editNightStartTime(day)">{{ hour.nightTime.start }}</div>
                <span>-</span>
                <div class="end-time" @click="editNightEndTime(day)">{{ hour.nightTime.start }}</div>
              </div>
            </template>
          </div>
          <div class="arrow-forward-icon" @click="next(day)">
            <arrow-forward-ios-icon :size="14" :fill-color="'#555555'" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import ArrowBackIosIcon from '../icons/ArrowBackIos.vue'
import ArrowForwardIosIcon from '../icons/ArrowForwardIos.vue'
import BzSelect from '../page/BzSelect.vue'
import builderMixin from '../../mixins/builderMixin'

export default {
  name: 'BusinessHoursEditor',
  components: {
    ArrowForwardIosIcon,
    ArrowBackIosIcon,
    BzSelect
  },
  mixins: [builderMixin],
  props: {
    location: {
      type: String,
      default: ''
    }
  },
  data() {
    return {
      businessHours: {},
      appointmentCategory: '',
      appointmentCategories: [{ label: 'Select Category', value: 0 }],
      format: { year: 'numeric', month: 'long', day: 'numeric', hour: 'numeric', minute: '2-digit', timeZoneName: 'short' }
    }
  },
  watch: {
    appointmentCategory: {
      deep: true,
      immediate: true,
      handler(value) {
        if (value !== '' && typeof value === 'number' && value !== 0) {
          const tempAppointmentCategory = this.appointmentCategories.find((o) => o.value === value)
          const tempBusinessHours = this.businessHours
          const tempTime24To12 = this.time24To12

          tempAppointmentCategory.weekdays.forEach(function (obj) {
            const hours = obj.hours[0]
            let tempDayTime = {}
            if (typeof hours === 'undefined') {
              tempDayTime = { start: '09:00 Am', end: '05:00 Pm' }
            } else {
              tempDayTime = { start: tempTime24To12(hours.start), end: tempTime24To12(hours.end) }
            }

            if (obj.weekday === 'sun') {
              tempBusinessHours.sunday = { type: 'appointment only', dayTime: tempDayTime }
            } else if (obj.weekday === 'mon') {
              tempBusinessHours.monday = { type: 'appointment only', dayTime: tempDayTime }
            } else if (obj.weekday === 'tue') {
              tempBusinessHours.tuesday = { type: 'appointment only', dayTime: tempDayTime }
            } else if (obj.weekday === 'wed') {
              tempBusinessHours.wednesday = { type: 'appointment only', dayTime: tempDayTime }
            } else if (obj.weekday === 'thu') {
              tempBusinessHours.thursday = { type: 'appointment only', dayTime: tempDayTime }
            } else if (obj.weekday === 'fri') {
              tempBusinessHours.friday = { type: 'appointment only', dayTime: tempDayTime }
            } else if (obj.weekday === 'sat') {
              tempBusinessHours.saturday = { type: 'appointment only', dayTime: tempDayTime }
            }
          })
        }
      }
    }
  },
  mounted() {
    const selectedBusiness = this.templateSetting.businesses.find((business) => business.companyName === this.location)
    if (selectedBusiness) {
      this.businessHours = selectedBusiness.businessHours
    }

    axios.get('/account/appointment/category-for-builder').then(({ data }) => {
      if (data.status) {
        this.appointmentCategories = data.data || []
      }
    })
  },
  methods: {
    editDayStartTime(day) {
      this.$store.commit('openTimePicker', {
        time: this.businessHours[day].dayTime.start,
        onChange: (val) => {
          this.businessHours[day].dayTime.start = this.timeFormat(val)
        }
      })
    },
    editDayEndTime(day) {
      this.$store.commit('openTimePicker', {
        time: this.businessHours[day].dayTime.end,
        onChange: (val) => {
          this.businessHours[day].dayTime.end = this.timeFormat(val)
        }
      })
    },
    editNightStartTime(day) {
      this.$store.commit('openTimePicker', {
        time: this.businessHours[day].nightTime.start,
        onChange: (val) => {
          this.businessHours[day].nightTime.start = this.timeFormat(val)
        }
      })
    },
    editNightEndTime(day) {
      this.$store.commit('openTimePicker', {
        time: this.businessHours[day].nightTime.end,
        onChange: (val) => {
          this.businessHours[day].nightTime.end = this.timeFormat(val)
        }
      })
    },
    timeFormat(val) {
      const hh = val.split(':')[0]
      const mm = val.split(' ')[0].split(':')[1].slice(-2)
      const aa = val.split(' ')[1]
      return `${hh}:${mm} ${aa}`
    },
    next(day) {
      if (this.status(this.businessHours[day]) === 8) {
        this.changeHour(day, 1)
      } else {
        // this.changeHour(day, status + 1)
        this.changeHour(day, this.status(this.businessHours[day]) + 1)
      }
    },
    prev(day) {
      if (this.status(this.businessHours[day]) === 1) {
        this.changeHour(day, 8)
      } else {
        // this.changeHour(day, status - 1)
        this.changeHour(day, this.status(this.businessHours[day]) - 1)
      }
    },
    status(hour) {
      if (hour.type === 'open') {
        return 1
      }
      if (hour.type === 'closed') {
        return 2
      }

      if (hour.type === 'appointment only') {
        if (!hour.dayTime || !hour.nightTime) {
          return 3
        }
        if (hour.dayTime && !hour.nightTime) {
          return 5
        }
        if (!hour.dayTime && hour.nightTime) {
          return 6
        }
      }

      if (!hour.type && hour.dayTime && !hour.nightTime) {
        return 4
      }

      if (hour.dayTime && hour.nightTime) {
        return 7
      }

      if (hour.type === 'non-stop') {
        return 8
      }

      return 1
    },
    changeHour(day, status) {
      let hour = {}
      if (status === 1) {
        hour = {
          type: 'open',
          label: 'open'
        }
      } else if (status === 2) {
        hour = {
          type: 'closed',
          label: 'closed'
        }
      } else if (status === 3) {
        hour = {
          type: 'appointment only',
          label: 'appointment only'
        }
      } else if (status === 4) {
        hour = {
          dayTime: {
            start: '09:00 am',
            end: '12:00 pm'
          }
        }
      } else if (status === 5) {
        hour = {
          type: 'appointment only',
          dayTime: {
            start: '09:00 am',
            end: '12:00 pm'
          }
        }
      } else if (status === 6) {
        hour = {
          type: 'appointment only',
          nightTime: {
            start: '01:00 pm',
            end: '06:00 pm'
          }
        }
      } else if (status === 7) {
        hour = {
          dayTime: {
            start: '09:00 am',
            end: '12:00 pm'
          },
          nightTime: {
            start: '01:00 pm',
            end: '06:00 pm'
          }
        }
      } else if (status === 8) {
        hour = {
          type: 'non-stop'
        }
      }

      this.businessHours[day] = hour
    },
    handleApply() {},
    time24To12(timeString) {
      if (timeString.length === 4) {
        timeString = '0' + timeString
      }

      const H = +timeString.substr(0, 2)
      const h = H % 12 || 12
      const ampm = H < 12 ? ' Am' : ' Pm'
      timeString = h + timeString.substr(2, 3) + ampm

      return timeString
    }
  }
}
</script>

<style lang="scss" scoped>
.bz-business-hours-root {
  width: 100%;
  margin: 30px 0;
  border: solid 2px transparent;
  position: relative;

  .open-hour-item {
    padding: 15px 10px;
    border: solid 1px #aeaeae;
    border-top: none;

    &:first-child {
      border-top: solid 1px #aeaeae;
    }

    .day-item {
      width: 20%;
    }
    .hour-item {
      width: 80%;
      display: flex;
      justify-content: space-between;
      align-items: center;
      text-transform: capitalize;
    }

    .arrow-forward-icon,
    .arrow-back-icon {
      padding: 2px;
      cursor: pointer;
      &:hover {
        background-color: #00000010;
      }
    }

    .day-time,
    .night-time {
      display: flex;
      justify-content: center;
      align-items: center;
      cursor: pointer;

      .start-time,
      .end-time {
        border-bottom: solid 1px #8080807f;
        padding-bottom: 0;
        padding-right: 4px;
        padding-left: 4px;
        text-transform: uppercase;

        &:hover {
          border-color: #555555;
        }
      }

      span {
        padding: 0 5px;
      }
    }
  }
}
</style>
