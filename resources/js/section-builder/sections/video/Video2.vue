<template>
  <div class="bz-section-container bz-sec--video-2-root" :class="{ [breakPoint]: true }">
    <bz-background :size="!setting.layouts.fullPage && sectionSize" :setting="background">
      <bz-container>
        <div class="tw-h-[250px] md:tw-h-[350px] lg:tw-h-[450px]"><bz-video v-model="setting.video" /></div>

        <bz-alignment class="tw-m-[30px]" :alignment="setting.layouts.alignment">
          <bz-title v-if="setting.elements.title" v-model="data.elements.title" />
          <bz-subtitle v-if="setting.elements.subtitle" v-model="data.elements.subtitle" />
          <bz-text v-if="setting.elements.description" v-model="data.elements.description" />
          <bz-button-group v-if="setting.elements.buttons" v-model="data.elements.buttons" />
        </bz-alignment>
      </bz-container>
    </bz-background>
  </div>
</template>

<script>
import sectionMixin from '../../mixins/sectionMixin'
import BzBackground from '../../components/section/BzBackground.vue'
import BzContainer from '../../components/section/BzContainer.vue'
import BzAlignment from '../../components/section/BzAlignment.vue'
import BzTitle from '../../components/section/BzTitle.vue'

import BzButtonGroup from '../../components/section/BzButton.vue'
import BzVideo from '../../components/section/BzVideo.vue'
import BzSubtitle from '../../components/section/BzSubtitle.vue'

export default {
  components: { BzSubtitle, BzVideo, BzButtonGroup, BzTitle, BzAlignment, BzContainer, BzBackground },
  mixins: [sectionMixin]
}
</script>
<style lang="scss" scoped>
.bz-sec--video-2-root {
  .fullPage {
    padding-top: 0 !important;
  }
}
</style>
