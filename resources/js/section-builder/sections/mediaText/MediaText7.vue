<template>
  <div class="bz-section-container bz-sec--media-text-7-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" :size="sectionSize">
      <bz-container horizontal-center>
        <div class="tw-p-2.5 tw-max-w-[800px] tw-border-[5px]" :style="{ borderColor: `rgba(0,0,0,.4` }">
          <div class="sm:tw-p-[100px] tw-p-6 tw-w-full" :style="{ backgroundColor: `rgba(0,0,0,.4` }">
            <bz-alignment :alignment="setting.layouts.alignment" :style="{ backgroundColor: backgroundColor + '00' }">
              <bz-title v-if="setting.elements.title" v-model="data.elements.title" />

              <bz-subtitle v-if="setting.elements.subtitle" v-model="data.elements.subtitle" />

              <bz-divider :line="true" :style="{ backgroundColor: `rgba(0,0,0,.4)` }" :thickness="0.1" />

              <bz-text v-if="setting.elements.description" v-model="data.elements.description" />

              <bz-button-group v-if="setting.elements.buttons" v-model="data.elements.buttons" class="mt-3" />
            </bz-alignment>
          </div>
        </div>
      </bz-container>
    </bz-background>
  </div>
</template>

<script>
import sectionMixin from '../../mixins/sectionMixin'
import BzBackground from '../../components/section/BzBackground.vue'
import BzContainer from '../../components/section/BzContainer.vue'
import BzTitle from '../../components/section/BzTitle.vue'
import BzSubtitle from '../../components/section/BzSubtitle.vue'

import BzButtonGroup from '../../components/section/BzButton.vue'
import BzAlignment from '../../components/section/BzAlignment.vue'
import BzDivider from '../../components/section/BzDivider.vue'

export default {
  components: { BzDivider, BzAlignment, BzButtonGroup, BzSubtitle, BzTitle, BzContainer, BzBackground },
  mixins: [sectionMixin]
}
</script>
<style lang="scss" scoped>
.bz-sec--media-text-7-root {
  .bz-ss-container-inner {
    backdrop-filter: blur(20px) saturate(125%);
  }
}
</style>
