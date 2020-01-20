<template>
  <div class="bz-section-container bz-sec--video-1-root" :class="{ [breakPoint]: true }">
    <div class="tw-relative md:tw-h-[400px] lg:tw-h-[600px] tw-w-full tw-h-[250px] tw-z-[1]">
      <div class="tw-absolute tw-z-[2] tw-flex tw-items-center tw-w-full sm:tw-w-[40%] tw-h-full sm:tw-right-[5%]">
        <div class="tw-w-full tw-px-6">
          <bz-alignment :alignment="setting.layouts.alignment">
            <bz-title v-if="setting.elements.title" v-model="data.elements.title" :text-color="'#ffffff'" />

            <bz-divider v-if="setting.elements.dividerLine" :line="true" :thickness="2" :height="30" :width="80" line-color="#ffffff" />

            <bz-subtitle v-if="setting.elements.subtitle" v-model="data.elements.subtitle" :text-color="'#ffffff'" />
            <bz-text v-if="setting.elements.description" v-model="data.elements.description" :text-color="'#ffffff'" />
            <bz-button-group v-model="data.elements.buttons" />
          </bz-alignment>
        </div>
      </div>
      <bz-video v-model="setting.video" />
    </div>
  </div>
</template>

<script>
import sectionMixin from '../../mixins/sectionMixin'
import BzAlignment from '../../components/section/BzAlignment.vue'
import BzTitle from '../../components/section/BzTitle.vue'

import BzButtonGroup from '../../components/section/BzButton.vue'
import BzVideo from '../../components/section/BzVideo.vue'
import BzDivider from '../../components/section/BzDivider.vue'
import BzSubtitle from '../../components/section/BzSubtitle.vue'

export default {
  components: { BzSubtitle, BzDivider, BzVideo, BzButtonGroup, BzTitle, BzAlignment },
  mixins: [sectionMixin]
}
</script>
