<template>
  <div class="bz-section-container bz-sec--video-4-root" :class="{ [breakPoint]: true }">
    <bz-background :size="sectionSize" :setting="background">
      <div
        class="tw-absolute tw-top-0 tw-w-full tw-h-[30%] lg:tw-w-1/4 lg:tw-h-full"
        :class="{ 'tw-right-auto tw-top-auto tw-bottom-0 md:tw-right-0 md:tw-top-0 md:tw-bottom-auto': setting.alignment === 'right' }"
        :style="{ backgroundColor: headingColor }"
      ></div>
      <bz-container style="z-index: 1; position: relative">
        <div class="tw-flex tw-gap-6 max-lg:tw-flex-col" :class="{ 'bz-row-reverse tw-flex-row-reverse max-lg:tw-flex-col-reverse': setting.alignment === 'right' }">
          <div class="lg:tw-w-7/12 tw-w-full">
            <div class="md:tw-h-[300px] lg:tw-h-[350px]">
              <bz-video v-model="setting.video" />
            </div>
          </div>
          <div class="lg:tw-w-5/12 tw-w-full">
            <bz-alignment :alignment="setting.layouts.alignment">
              <bz-title v-if="setting.elements.title" v-model="data.elements.title" />

              <bz-subtitle v-if="setting.elements.subtitle" v-model="data.elements.subtitle" />

              <bz-text v-if="setting.elements.description" v-model="data.elements.description" />

              <bz-button-group v-if="setting.elements.buttons" v-model="data.elements.buttons" />
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
.bz-sec--video-4-root {
  .video-container {
    width: 100%;
    height: 250px;
  }

  &.bz-md {
    .video-container {
      height: 300px;
    }
  }

  &.bz-lg {
    .video-container {
      height: 350px;
    }
  }

  .background-attachment {
    position: absolute;
    top: 0;
    width: 100%;
    height: 30%;

    &.right {
      right: auto;
      top: auto;
      bottom: 0;
    }
  }

  &.bz-md {
    .background-attachment {
      top: 0;
      width: 30%;
      height: 100%;

      &.right {
        right: 0;
        top: 0;
        bottom: auto;
      }
    }
  }
}
</style>
