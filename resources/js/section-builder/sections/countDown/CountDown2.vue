<template>
  <div class="bz-section-container bz-sec--count-down-2-root" :class="{ [breakPoint]: true }" :style="{ '--secondary': secondaryColor }">
    <bz-background :setting="background" :size="sectionSize">
      <bz-container>
        <bz-card :bg-color="primaryBoxColor" class="tw-p-0">
          <div class="tw-w-full tw-h-full" style="background-color: var(--secondary);"></div>
          <div class="lg:tw-p-12 tw-p-2">
            <bz-text-element v-model="data.elements" :setting="setting.elements" :alignment="setting.layouts.alignment" />

            <bz-count-down v-model="data.countTime" :edit="edit && isActive">
              <div class="tw-grid lg:tw-grid-cols-4 md:tw-grid-cols-2 tw-text-center">
                <div class="tw-flex tw-items-center tw-flex-col tw-w-full tw-items-center">
                  <div class="tw-text-[5.4em] tw-leading-[1.5em] tw-font-bold tw-text-center md:tw-w-full" style="color: var(--secondary)">
                    {{ dates }}
                  </div>
                  <span>Days</span>
                  <div class="md:tw-hidden tw-flex tw-w-2.5 tw-h-2.5 tw-bg-[#8080803f] tw-rounded-full tw-mt-4"></div>
                </div>
                <div class="tw-flex tw-items-center tw-flex-col tw-w-full">
                  <div class="tw-text-[5.4em] tw-leading-[1.5em] tw-font-bold tw-text-center md:tw-w-full" style="color: var(--secondary)">
                    {{ hours }}
                  </div>
                  <span>Hours</span>
                  <div class="md:tw-hidden tw-flex tw-w-2.5 tw-h-2.5 tw-bg-[#8080803f] tw-rounded-full tw-mt-4"></div>
                </div>
                <div class="tw-flex tw-items-center tw-flex-col tw-w-full">
                  <div class="tw-text-[5.4em] tw-leading-[1.5em] tw-font-bold tw-text-center md:tw-w-full" style="color: var(--secondary)">
                    {{ minutes }}
                  </div>
                  <span>Minutes</span>
                  <div class="md:tw-hidden tw-flex tw-w-2.5 tw-h-2.5 tw-bg-[#8080803f] tw-rounded-full tw-mt-4"></div>
                </div>
                <div class="tw-flex tw-items-center tw-flex-col tw-w-full">
                  <div class="tw-text-[5.4em] tw-leading-[1.5em] tw-font-bold tw-text-center md:tw-w-full" style="color: var(--secondary)">
                    {{ seconds }}
                  </div>
                  <span>Seconds</span>
                </div>
              </div>
            </bz-count-down>
          </div>
        </bz-card>
      </bz-container>
    </bz-background>
  </div>
</template>

<script>
import BzBackground from '../../components/section/BzBackground.vue'
import BzContainer from '../../components/section/BzContainer.vue'
import BzTextElement from '../../components/section/BzTextElement.vue'
import CountDown1 from './CountDown1.vue'
import BzCard from '../../components/section/BzCard.vue'

export default {
  name: 'CountDown2',
  components: {
    BzCard,
    BzTextElement,
    BzContainer,
    BzBackground
  },
  extends: CountDown1
}
</script>
