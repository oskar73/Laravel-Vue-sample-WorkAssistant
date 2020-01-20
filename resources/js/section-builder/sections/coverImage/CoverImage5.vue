<template>
  <div class="bz-section-container bz-sec--cover-image-5-root" :class="{ [breakPoint]: true, full: setting.layouts.fullPage }">
    <bz-background :setting="background" :size="sectionSize">
      <bz-container>
        <div class="tw-flex tw-flex-col lg:tw-flex-row" :class="{ 'tw-flex-col-reverse lg:tw-flex-row-reverse': setting.alignment === 'right' }">
          <div class="tw-w-full tw-flex tw-flex-col tw-justify-center">
            <bz-subtitle v-if="setting.elements.subtitle" v-model="data.elements.subtitle" />
            <bz-title v-if="setting.elements.title" v-model="data.elements.title" />

            <bz-divider />

            <bz-subtitle v-if="setting.elements.description" v-model="data.elements.description" />
            <bz-button-group v-if="setting.elements.buttons" v-model="data.elements.buttons" />
          </div>
          <div class="tw-w-full lg:tw-h-min-[360px]">
            <bz-aspect-view>
              <bz-image v-if="setting.elements.image" v-model="data.elements.image" :ratio="2 / 3" />
            </bz-aspect-view>
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
import BzSubtitle from '../../components/section/BzSubtitle.vue'
import BzTitle from '../../components/section/BzTitle.vue'
import BzButtonGroup from '../../components/section/BzButtonGroup.vue'
import BzDivider from '../../components/section/BzDivider.vue'
import BzAspectView from '../../components/section/BzAspectView.vue'

export default {
  components: { BzAspectView, BzDivider, BzButtonGroup, BzTitle, BzSubtitle, BzContainer, BzBackground },
  mixins: [sectionMixin]
}
</script>
<style lang="scss" scoped>
.bz-sec--cover-image-5-root {
  &.full {
    height: 100vh;
  }
}
</style>
