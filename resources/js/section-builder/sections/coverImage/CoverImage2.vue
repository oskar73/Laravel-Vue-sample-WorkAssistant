<template>
  <div class="bz-section-container bz-sec--cover-image-2-root" :class="{ [breakPoint]: true, full: setting.layouts.fullPage }">
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
          <div class="tw-w-full">
            <bz-alignment :alignment="setting.layouts.alignment">
              <bz-subtitle v-if="setting.elements.subtitle" v-model="data.elements.subtitle" />
              <bz-title v-if="setting.elements.title" v-model="data.elements.title" />
              <bz-subtitle v-if="setting.elements.description" v-model="data.elements.description" />
              <bz-button-group v-if="setting.elements.buttons" v-model="data.elements.buttons" />
            </bz-alignment>
          </div>
          <div class="tw-w-full">
            <bz-aspect-view :ratio="2 / 5">
              <bz-image v-model="data.elements.image" />
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
import BzAlignment from '../../components/section/BzAlignment.vue'
import BzAspectView from '../../components/section/BzAspectView.vue'

export default {
  components: { BzAspectView, BzAlignment, BzButtonGroup, BzTitle, BzSubtitle, BzContainer, BzBackground },
  mixins: [sectionMixin]
}
</script>
<style lang="scss" scoped>
.bz-sec--cover-image-2-root {
  &.full {
    height: 100vh;
  }
}
</style>
