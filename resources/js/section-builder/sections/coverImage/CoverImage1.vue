<template>
  <div class="bz-section-container bz-sec--cover-image-1-root" :class="{ [breakPoint]: true, full: setting.layouts.fullPage }">
    <bz-background :setting="background" :size="sectionSize">
      <bz-container>
        <div
          class="tw-flex tw-flex-col lg:tw-flex-row tw-items-center tw-justify-center"
          :class="{
            'tw-flex-col-reverse lg:tw-flex-row-reverse': setting.alignment === 'right',
            'tw-h-[calc(100vh-60px)]': setting.layouts.fullPage && sectionSize === 's',
            'tw-h-[calc(100vh-100px)]': setting.layouts.fullPage && sectionSize === 'm',
            'tw-h-[calc(100vh-180px)]': setting.layouts.fullPage && sectionSize === 'l',
            'tw-h-[calc(100vh-280px)]': setting.layouts.fullPage && sectionSize === 'xl'
          }"
        >
          <div class="tw-flex tw-flex-col tw-justify-center tw-w-full">
            <bz-text v-if="setting.elements.subtitle" v-model="data.elements.subtitle" :shadow="true" />
            <bz-text v-if="setting.elements.description" v-model="data.elements.description" :shadow="true" />
            <bz-button-group v-if="setting.elements.buttons" v-model="data.elements.buttons" />
          </div>
          <div class="tw-flex tw-align-center tw-w-full">
            <bz-title v-if="setting.elements.title" v-model="data.elements.title" :shadow="true" style="font-size: 72px" :mb="0" />
          </div>
        </div>
      </bz-container>
    </bz-background>
  </div>
</template>

<script>
import sectionMixin from '../../mixins/sectionMixin'
import BzBackground from '../../components/section/BzBackground.vue'

import BzButtonGroup from '../../components/section/BzButtonGroup.vue'
import BzTitle from '../../components/section/BzTitle.vue'
import BzContainer from '../../components/section/BzContainer.vue'

export default {
  components: { BzContainer, BzTitle, BzButtonGroup, BzBackground },
  mixins: [sectionMixin]
}
</script>
<style lang="scss" scoped>
.bz-sec--cover-image-1-root {
  &.full {
    height: 100vh;
    display: flex;
    align-items: center;
  }
}
</style>
