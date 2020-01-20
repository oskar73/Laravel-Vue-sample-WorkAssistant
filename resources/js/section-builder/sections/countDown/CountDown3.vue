<template>
  <div class="bz-section-container bz-sec--count-down-3-root" :class="{ [breakPoint]: true }" :style="{ '--primary': primaryBoxColor, '--secondary': secondaryColor }">
    <bz-background :setting="background" :size="sectionSize">
      <bz-container>
        <div class="tw-w-[70%] max-lg:tw-w-[70%] max-sm:tw-w-full max-xs:tw-w-full tw-max-w-[1000px] tw-m-auto">
          <bz-text-element v-model="data.elements" :setting="setting.elements" :alignment="setting.layouts.alignment" :shadow="true" />
          <div class="tw-mt-5" style="background-color: var(--primary)">
            <bz-count-down v-model="data.countTime" :edit="edit && isActive" class="bz-mt-3">
              <div class="tw-grid md:tw-grid-cols-2 lg:tw-grid-cols-4 tw-pb-[30px]" :style="{ backgroundColor: primaryBoxColor }">
                <div class="tw-flex tw-items-center tw-flex-col tw-w-full">
                  <div class="tw-text-[5.4em] tw-leading-[1.5em] tw-w-full md:tw-w-[1.5em] tw-font-bold tw-text-center" style="color: var(--secondary);">
                    {{ dates }}
                  </div>
                  <span class="font-bold" style="color: var(--secondary);">Days</span>
                </div>
                <div class="tw-flex tw-items-center tw-flex-col tw-w-full">
                  <div class="tw-text-[5.4em] tw-leading-[1.5em] tw-w-full md:tw-w-[1.5em] tw-font-bold tw-text-center" style="color: var(--secondary);">
                    {{ hours }}
                  </div>
                  <span class="font-bold" style="color: var(--secondary);">Hours</span>
                </div>
                <div class="tw-flex tw-items-center tw-flex-col tw-w-full">
                  <div class="tw-text-[5.4em] tw-leading-[1.5em] tw-w-full md:tw-w-[1.5em] tw-font-bold tw-text-center" style="color: var(--secondary);">
                    {{ minutes }}
                  </div>
                  <span class="font-bold" style="color: var(--secondary);">Minutes</span>
                </div>
                <div class="tw-flex tw-items-center tw-flex-col tw-w-full">
                  <div class="tw-text-[5.4em] tw-leading-[1.5em] tw-w-full md:tw-w-[1.5em] tw-font-bold tw-text-center" style="color: var(--secondary);">
                    {{ seconds }}
                  </div>
                  <span class="font-bold" style="color: var(--secondary);">Seconds</span>
                </div>
              </div>
            </bz-count-down>
          </div>
        </div>
      </bz-container>
    </bz-background>
  </div>
</template>

<script>
import BzBackground from '../../components/section/BzBackground.vue'
import BzContainer from '../../components/section/BzContainer.vue'
import BzTextElement from '../../components/section/BzTextElement.vue'
import CountDown1 from './CountDown1.vue'

export default {
  name: 'CountDown3',
  components: {
    BzTextElement,
    BzContainer,
    BzBackground
  },
  extends: CountDown1
}
</script>
