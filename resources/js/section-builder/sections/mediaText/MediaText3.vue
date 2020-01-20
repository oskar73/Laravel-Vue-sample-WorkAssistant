<template>
  <div class="bz-section-container bz-sec--media-text-3-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" :size="sectionSize">
      <bz-container>
        <div class="tw-grid md:tw-grid-cols-2 tw-gap-6">
          <div class="max-md:tw-h-[300px]">
            <bz-image v-if="setting.elements.image" v-model="data.elements.image" :ratio="2 / 3" />
          </div>
          <div>
            <bz-title v-if="setting.elements.title" v-model="data.elements.title" />

            <bz-subtitle v-if="setting.elements.subtitle" v-model="data.elements.subtitle" />

            <bz-text v-if="setting.elements.description" v-model="data.elements.description" />

            <bz-button-group v-if="setting.elements.buttons" v-model="data.elements.buttons" class="mt-3" />
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
import BzButtonGroup from '../../components/section/BzButton.vue'

import BzSubtitle from '../../components/section/BzSubtitle.vue'
import BzTitle from '../../components/section/BzTitle.vue'

export default {
  components: { BzTitle, BzSubtitle, BzButtonGroup, BzContainer, BzBackground },
  mixins: [sectionMixin]
}
</script>
<style lang="scss" scoped>
.bz-sec--media-text-3-root {
}
</style>
