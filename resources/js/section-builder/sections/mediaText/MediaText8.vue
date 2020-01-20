<template>
  <div class="bz-section-container bz-sec--media-text-8-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" :size="sectionSize">
      <bz-container>
        <bz-alignment :alignment="setting.layouts.alignment">
          <bz-title v-if="setting.elements.title" v-model="data.elements.title" />

          <bz-subtitle v-if="setting.elements.subtitle" v-model="data.elements.subtitle" />

          <bz-text v-if="setting.elements.description" v-model="data.elements.description" />

          <bz-button-group v-if="setting.elements.buttons" v-model="data.elements.buttons" class="mt-3" />
        </bz-alignment>
        <div v-if="setting.elements.image" class="tw-h-[360px] tw-mt-10"><bz-image v-model="data.elements.image" :ratio="2 / 5" /></div>
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

export default {
  components: { BzAlignment, BzButtonGroup, BzSubtitle, BzTitle, BzContainer, BzBackground },
  mixins: [sectionMixin]
}
</script>

