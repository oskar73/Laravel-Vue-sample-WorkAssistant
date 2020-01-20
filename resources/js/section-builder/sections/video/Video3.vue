<template>
  <div class="bz-section-container bz-sec--video-3-root">
    <bz-background :size="sectionSize" :setting="background">
      <bz-container>
        <div class="tw-relative md:tw-h-[350px] lg:tw-h-[400px]">
          <div class="tw-w-full md:tw-absolute md:tw-w-1/2 md:tw-h-[80%] tw-h-[240px]" :class="{ 'tw-right-0': setting.alignment === 'right' }">
            <bz-video v-model="setting.video" />
          </div>
          <div
            class="tw-w-full tw-inline-block md:tw-w-[calc(100%-50px)] md:tw-h-[calc(100%-50px)] md:tw-mt-[50px]"
            :class="{ 'md:tw-ml-[50px]': setting.alignment === 'left', 'md:tw-mr-[50px]': setting.alignment === 'right' }"
            :style="{ backgroundColor: secondaryColor }"
          >
            <div class="tw-w-full tw-p-[30px] md:tw-w-1/2" :class="{ 'tw-ml-auto': setting.alignment === 'left' }">
              <bz-alignment :alignment="setting.layouts.alignment">
                <bz-title v-if="setting.elements.title" v-model="data.elements.title" />
                <bz-subtitle v-if="setting.elements.subtitle" v-model="data.elements.subtitle" />
                <bz-text v-if="setting.elements.description" v-model="data.elements.description" />
                <bz-button-group v-if="setting.elements.buttons" v-model="data.elements.buttons" :button-color="theme.secondaryColor" />
              </bz-alignment>
            </div>
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
