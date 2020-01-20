<template>
  <div class="bz-section-container bz-sec--media-list-1-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" :size="sectionSize">
      <bz-container>
        <bz-alignment :alignment="setting.layouts.alignment">
          <bz-title v-if="setting.elements.title" v-model="data.elements.title" />

          <bz-subtitle v-if="setting.elements.subtitle" v-model="data.elements.subtitle" />

          <bz-text v-if="setting.elements.description" v-model="data.elements.description" />
        </bz-alignment>

        <bz-items v-model="data.items" :cols="setting.column" :shadow="setting.layouts.shadow" :enable-sort="edit">
          <template v-slot="{ item }">
            <bz-aspect-view v-if="setting.listElements.image" :ratio="aspectRatio">
              <bz-image v-model="item.image" />
            </bz-aspect-view>
            <div class="tw-p-2.5" :style="{ backgroundColor: secondaryColor }">
              <bz-title v-if="setting.listElements.title" v-model="item.title" />

              <bz-text v-if="setting.listElements.subtitle" v-model="item.subtitle" />

              <bz-text v-if="setting.listElements.description" v-model="item.description" />
            </div>
          </template>
        </bz-items>
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
import BzSubtitle from '../../components/section/BzSubtitle.vue'

import BzItems from '../../components/section/BzItems.vue'
import BzAspectView from '../../components/section/BzAspectView.vue'

export default {
  name: 'MediaList1',
  components: { BzAspectView, BzItems, BzSubtitle, BzTitle, BzAlignment, BzContainer, BzBackground },
  mixins: [sectionMixin],
  data() {
    return {
      aspectRatio: 1
    }
  },
  watch: {
    setting: {
      deep: true,
      immediate: true,
      handler(value) {
        switch (value.layouts.aspectRatio) {
          case 'landscape': {
            this.aspectRatio = 2 / 3
            break
          }
          case 'portrait': {
            this.aspectRatio = 4 / 3
            break
          }
          case 'square': {
            this.aspectRatio = 1
            break
          }
          default: {
            this.aspectRatio = 1
          }
        }
      }
    }
  }
}
</script>

