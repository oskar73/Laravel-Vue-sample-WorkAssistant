<template>
  <div class="bz-section-container bz-sec--media-text-1-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" :size="sectionSize">
      <bz-container>
        <bz-aspect-view v-if="setting.elements.image" :ratio="2 / 5">
          <bz-image v-model="data.elements.image" />
        </bz-aspect-view>
        <div class="tw-mt-8">
          <bz-alignment :alignment="setting.layouts.alignment">
            <bz-subtitle v-if="setting.elements.subtitle" v-model="data.elements.subtitle" />

            <bz-title v-if="setting.elements.title" v-model="data.elements.title" />

            <bz-divider v-if="setting.elements.dividerLine" :line="true" />

            <bz-text v-if="setting.elements.description" v-model="data.elements.description" />

            <bz-button-group v-if="setting.elements.buttons" v-model="data.elements.buttons" class="tw-mt-6" />
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
import BzSubtitle from '../../components/section/BzSubtitle.vue'
import BzTitle from '../../components/section/BzTitle.vue'
import BzDivider from '../../components/section/BzDivider.vue'

import BzButtonGroup from '../../components/section/BzButton.vue'
import BzAlignment from '../../components/section/BzAlignment.vue'
import BzAspectView from '../../components/section/BzAspectView.vue'

export default {
  components: { BzAspectView, BzAlignment, BzButtonGroup, BzDivider, BzTitle, BzSubtitle, BzContainer, BzBackground },
  mixins: [sectionMixin]
}
</script>

