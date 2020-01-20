<template>
  <div class="bz-section-container bz-sec--count-down-1-root" :class="{ [breakPoint]: true }" :style="{ '--bz-count-down-item-color': secondaryColor }">
    <bz-background :setting="background" :size="sectionSize">
      <bz-container>
        <bz-text-element v-model="data.elements" :setting="setting.elements" :alignment="setting.layouts.alignment" :shadow="true" />

        <bz-count-down v-model="data.countTime">
          <div class="tw-grid tw-grid-cols-4 tw-gap-4">
            <div style="color: var(--bz-count-down-item-color, white)">
              <bz-aspect-view>
                <div class="tw-w-full tw-h-full tw-text-center tw-mt-8">
                  <div
                    class="tw-flex tw-w-full tw-h-full tw-justify-center tw-items-center tw-rounded-full tw-border-2 tw-max-w-[150px] tw-max-h-[150px] tw-m-auto tw-text-3xl sm:tw-text-4xl"
                    style="border-color: var(--bz-count-down-item-color, white)"
                  >
                    {{ dates }}
                  </div>
                  <div class="text-white tw-mt-4">Days</div>
                </div>
              </bz-aspect-view>
            </div>
            <div style="color: var(--bz-count-down-item-color, white)">
              <bz-aspect-view>
                <div class="tw-w-full tw-h-full tw-text-center tw-mt-8">
                  <div
                    class="tw-flex tw-w-full tw-h-full tw-justify-center tw-items-center tw-rounded-full tw-border-2 tw-max-w-[150px] tw-max-h-[150px] tw-m-auto tw-text-3xl sm:tw-text-4xl"
                    style="border-color: var(--bz-count-down-item-color, white)"
                  >
                    {{ hours }}
                  </div>
                  <div class="tw-text-white tw-mt-4">Hours</div>
                </div>
              </bz-aspect-view>
            </div>
            <div style="color: var(--bz-count-down-item-color, white)">
              <bz-aspect-view>
                <div class="tw-w-full tw-h-full tw-text-center tw-mt-8">
                  <div
                    class="tw-flex tw-w-full tw-h-full tw-justify-center tw-items-center tw-rounded-full tw-border-2 tw-max-w-[150px] tw-max-h-[150px] tw-m-auto tw-text-3xl sm:tw-text-4xl"
                    style="border-color: var(--bz-count-down-item-color, white)"
                  >
                    {{ minutes }}
                  </div>
                  <div class="tw-text-white tw-mt-4">Minutes</div>
                </div>
              </bz-aspect-view>
            </div>
            <div style="color: var(--bz-count-down-item-color, white)">
              <bz-aspect-view>
                <div class="tw-w-full tw-h-full tw-text-center tw-mt-8">
                  <div
                    class="tw-flex tw-w-full tw-h-full tw-justify-center tw-items-center tw-rounded-full tw-border-2 tw-max-w-[150px] tw-max-h-[150px] tw-m-auto tw-text-3xl sm:tw-text-4xl"
                    style="border-color: var(--bz-count-down-item-color, white)"
                  >
                    {{ seconds }}
                  </div>
                  <div class="tw-text-white tw-mt-4">Seconds</div>
                </div>
              </bz-aspect-view>
            </div>
          </div>
        </bz-count-down>
      </bz-container>
    </bz-background>
  </div>
</template>

<script>
import sectionMixin from '../../mixins/sectionMixin'
import BzBackground from '../../components/section/BzBackground.vue'
import BzContainer from '../../components/section/BzContainer.vue'
import BzTextElement from '../../components/section/BzTextElement.vue'
import BzCountDown from '../../components/section/BzCountDown.vue'
import BzAspectView from '../../components/section/BzAspectView.vue'
import moment from 'moment'

export default {
  name: 'CountDown1',
  components: {
    BzAspectView,
    BzCountDown,
    BzContainer,
    BzTextElement,
    BzBackground
  },
  mixins: [sectionMixin],
  data() {
    return {
      refresh: true,
      dates: '00',
      hours: '00',
      minutes: '00',
      seconds: '00'
    }
  },
  mounted() {
    const _this = this
    _this.updateCounter()
    setInterval(function () {
      _this.updateCounter()
    }, 1000)
  },
  methods: {
    updateCounter() {
      const currentTime = moment()
      const countTime = moment(this.data.countTime)

      if (countTime.isAfter(currentTime)) {
        const duration = moment.duration(countTime.diff(currentTime))._data
        this.dates = duration.days < 10 ? '0' + duration.days : duration.days
        this.hours = duration.hours < 10 ? '0' + duration.hours : duration.hours
        this.minutes = duration.minutes < 10 ? '0' + duration.minutes : duration.minutes
        this.seconds = duration.seconds < 10 ? '0' + duration.seconds : duration.seconds
      } else {
        this.dates = '00'
        this.hours = '00'
        this.minutes = '00'
        this.seconds = '00'
      }
    }
  }
}
</script>
