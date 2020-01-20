<template>
  <div class="bz-section-container bz-sec-call-to-action-6-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" :size="sectionSize">
      <bz-container>
        <div class="col-12">
          <div class="tw-flex tw-flex-col lg:tw-flex-row" :class="{ 'tw-flex-col-reverse lg:tw-flex-row-reverse': setting.alignment === 'right' }">
            <div class="col-lg-6 d-flex align-items-center">
              <div class="image-base">
                <bz-image v-model="data.elements.image" resize-mode="auto" />
              </div>
            </div>
            <div class="col-lg-6 d-flex align-items-center">
              <div class="content" :class="{ [setting.layouts.alignment]: true }">
                <bz-title v-if="setting.elements.title" v-model="data.elements.title" />

                <bz-subtitle v-if="setting.elements.subtitle" v-model="data.elements.subtitle" />

                <bz-text v-if="setting.elements.description" v-model="data.elements.description" />

                <bz-button-group v-if="setting.elements.buttons" v-model="data.elements.buttons" class="mt-3" />
              </div>
            </div>
          </div>
        </div>
      </bz-container>
    </bz-background>
  </div>
</template>

<script>
import BzBackground from '../../components/section/BzBackground.vue'
import BzContainer from '../../components/section/BzContainer.vue'
import sectionMixin from '../../mixins/sectionMixin'
import BzTitle from '../../components/section/BzTitle.vue'
import BzSubtitle from '../../components/section/BzSubtitle.vue'

import BzButton from '../../components/section/BzButton.vue'
import BzButtonGroup from '../../components/section/BzButtonGroup.vue'
export default {
  components: { BzButtonGroup, BzButton, BzSubtitle, BzTitle, BzContainer, BzBackground },
  mixins: [sectionMixin]
}
</script>
<style lang="scss">
.bz-sec-call-to-action-6-root {
  .image-base {
    border-radius: 10px;
    overflow: hidden;
    transform: rotate3d(0.5, 0.7, 0, 16.4deg) rotateZ(-2deg);
    transform-origin: left bottom;
    transform-style: preserve-3d;
  }

  .content {
    display: flex;
    flex-direction: column;

    &.left {
      align-items: flex-start;
      text-align: left;
    }

    &.center {
      align-items: center;
      text-align: center;
    }

    &.right {
      align-items: flex-end;
      text-align: right;
    }
  }
}
</style>
