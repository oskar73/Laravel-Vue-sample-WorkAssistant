<template>
  <div class="bz-section-container bz-sec--media-text-6-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" :size="sectionSize">
      <bz-container>
        <div class="tw-grid md:tw-grid-cols-2 tw-gap-8">
          <div class="max-md:tw-h-[320px]">
            <bz-image v-if="setting.elements.image" v-model="data.elements.image" :ratio="4 / 3" />
          </div>
          <div>
            <bz-title v-if="setting.elements.title" v-model="data.elements.title" />

            <bz-subtitle v-if="setting.elements.subtitle" v-model="data.elements.subtitle" />

            <bz-divider :line="true" />

            <bz-text v-if="setting.elements.description" v-model="data.elements.description" />

            <bz-divider :line="setting.elements.description" />

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
import BzTitle from '../../components/section/BzTitle.vue'
import BzSubtitle from '../../components/section/BzSubtitle.vue'
import BzDivider from '../../components/section/BzDivider.vue'

import BzButtonGroup from '../../components/section/BzButton.vue'

export default {
  components: { BzButtonGroup, BzDivider, BzSubtitle, BzTitle, BzContainer, BzBackground },
  mixins: [sectionMixin]
}
</script>

