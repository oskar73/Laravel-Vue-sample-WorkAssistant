<template>
  <div class="bz-section-container bz-sec--business-hours-4-root" :class="{ [breakPoint]: true }">
    <bz-background :background-color="mainBackgroundColor" :setting="background" :size="sectionSize">
      <bz-container>
        <bz-card :bg-color="secondaryColor" :shadow="false">
          <div class="tw-flex tw-flex-col lg:tw-flex-row tw-gap-4" :class="{ 'lg:tw-flex-row-reverse': setting.alignment === 'right' }">
            <div v-if="setting.elements.image" class="tw-w-full max-lg:tw-h-[360px] max-sm:tw-h-[240px]">
              <bz-image v-if="setting.elements.image" v-model="data.elements.image" resize-mode="full" :max-height="500" />
            </div>
            <div class="tw-w-full">
              <div class="tw-w-full tw-h-full tw-flex tw-justify-around tw-flex-col tw-pt-10">
                <bz-text-element v-model="data.elements" :setting="setting.elements" />
                <bz-divider />
                <bz-business-hours :location="setting.businessInformation.location" :design="2" />
              </div>
            </div>
          </div>
        </bz-card>
      </bz-container>
    </bz-background>
  </div>
</template>

<script>
import sectionMixin from '../../mixins/sectionMixin'
import BzBackground from '../../components/section/BzBackground.vue'
import BzBusinessHours from '../../components/section/BzBusinessHours.vue'
import BzContainer from '../../components/section/BzContainer.vue'
import BzDivider from '../../components/section/BzDivider.vue'
import BzCard from '../../components/section/BzCard.vue'
import BzTextElement from '../../components/section/BzTextElement.vue'

export default {
  name: 'BusinessHours4',
  components: {
    BzTextElement,
    BzCard,
    BzDivider,
    BzContainer,
    BzBusinessHours,
    BzBackground
  },
  mixins: [sectionMixin],
  computed: {
    mainBackgroundColor() {
      const originalColor = window.tinycolor(this.backgroundColor)
      return originalColor.darken(4).toString()
    }
  }
}
</script>
