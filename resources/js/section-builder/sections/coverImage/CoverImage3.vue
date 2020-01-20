<template>
  <div class="bz-section-container bz-sec--cover-image-3-root" :class="{ [breakPoint]: true, full: setting.layouts.fullPage }">
    <bz-background :setting="background" :size="sectionSize">
      <div v-if="setting.elements.description" class="tw-py-0">
        <div :style="{ backgroundColor: secondaryColor }">
          <bz-divider />
          <bz-divider :line="true" />

          <div class="lg:tw-p-4 tw-p-2">
            <bz-text v-model="data.elements.description" />
          </div>

          <bz-divider :line="true" />
          <bz-divider />
        </div>

        <bz-container class="tw-py-20">
          <bz-title v-if="setting.elements.title" v-model="data.elements.title" :shadow="true" />
          <bz-text v-if="setting.elements.subtitle" v-model="data.elements.subtitle" :shadow="true" />

          <bz-divider />

          <div class="tw-flex tw-flex-items-center tw-justify-center">
            <bz-divider :line="true" background-color="#000000" :style="{ marginRight: setting.elements.buttons ? '20px' : 0 }" />
            <bz-button-group v-if="setting.elements.buttons" v-model="data.elements.buttons" />
          </div>
        </bz-container>
      </div>
    </bz-background>
  </div>
</template>

<script>
import sectionMixin from '../../mixins/sectionMixin'
import BzBackground from '../../components/section/BzBackground.vue'
import BzDivider from '../../components/section/BzDivider.vue'

import BzTitle from '../../components/section/BzTitle.vue'
import BzContainer from '../../components/section/BzContainer.vue'
import BzButtonGroup from '../../components/section/BzButtonGroup.vue'

export default {
  components: { BzButtonGroup, BzContainer, BzTitle, BzDivider, BzBackground },
  mixins: [sectionMixin]
}
</script>
<style lang="scss" scoped>
.bz-sec--cover-image-3-root {
  &.full {
    height: 100vh;
  }
}
</style>
