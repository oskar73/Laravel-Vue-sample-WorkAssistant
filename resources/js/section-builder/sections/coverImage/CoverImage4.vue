<template>
  <div class="bz-section-container bz-sec--cover-image-4-root" :class="{ [breakPoint]: true, full: setting.layouts.fullPage }">
    <bz-background :setting="background" :size="sectionSize">
      <bz-container>
        <div
          class="tw-flex items-center"
          :class="{
            'tw-h-[calc(100vh-60px)]': setting.layouts.fullPage && sectionSize === 's',
            'tw-h-[calc(100vh-100px)]': setting.layouts.fullPage && sectionSize === 'm',
            'tw-h-[calc(100vh-180px)]': setting.layouts.fullPage && sectionSize === 'l',
            'tw-h-[calc(100vh-280px)]': setting.layouts.fullPage && sectionSize === 'xl'
          }"
        >
          <bz-alignment :alignment="setting.layouts.alignment">
            <bz-subtitle v-if="setting.elements.subtitle" v-model="data.elements.subtitle" :shadow="true" />
            <bz-title v-if="setting.elements.title" v-model="data.elements.title" :shadow="true" style="font-size: 42px" />

            <bz-divider />

            <bz-subtitle v-if="setting.elements.description" v-model="data.elements.description" :shadow="true" />
            <bz-button-group v-if="setting.elements.buttons" v-model="data.elements.buttons" />
          </bz-alignment>
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
import BzSubtitle from '../../components/section/BzSubtitle.vue'
import BzTitle from '../../components/section/BzTitle.vue'
import BzButtonGroup from '../../components/section/BzButtonGroup.vue'
import BzDivider from '../../components/section/BzDivider.vue'

export default {
  components: { BzDivider, BzButtonGroup, BzTitle, BzSubtitle, BzAlignment, BzContainer, BzBackground },
  mixins: [sectionMixin]
}
</script>
<style lang="scss" scoped>
.bz-sec--cover-image-4-root {
  &.full {
    height: 100vh;
  }
}
</style>
