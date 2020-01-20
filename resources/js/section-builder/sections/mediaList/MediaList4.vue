<template>
  <div class="bz-section-container bz-sec--media-list-4-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" :size="sectionSize">
      <bz-container>
        <div style="width: 100%; margin: auto">
          <bz-alignment :alignment="setting.layouts.alignment">
            <bz-title v-if="setting.elements.title" v-model="data.elements.title" :text-color="theme.primaryColor" />

            <bz-subtitle v-if="setting.elements.subtitle" v-model="data.elements.subtitle" />

            <bz-text v-if="setting.elements.description" v-model="data.elements.description" />
          </bz-alignment>
          <bz-items v-model="data.items" :cols="3" :styles="cardStyle" class="p-2" :shadow="setting.layouts.shadow" :enable-sort="edit">
            <template v-slot="{ item }">
              <bz-aspect-view v-if="setting.listElements.image" :ratio="3 / 5">
                <bz-image v-model="item.image" />
              </bz-aspect-view>

              <div class="card-text-wrapper" :style="{ backgroundColor: secondaryColor }">
                <bz-alignment>
                  <bz-title v-if="setting.listElements.title" v-model="item.title" />

                  <bz-text v-if="setting.listElements.subtitle" v-model="item.subtitle" />

                  <bz-divider v-if="setting.listElements.dividerLine" :line="true" />

                  <bz-text v-if="setting.listElements.description" v-model="item.description" />
                </bz-alignment>
              </div>
            </template>
          </bz-items>
        </div>
      </bz-container>
    </bz-background>
  </div>
</template>

<script>
import BzBackground from '../../components/section/BzBackground.vue'
import BzContainer from '../../components/section/BzContainer.vue'
import BzAlignment from '../../components/section/BzAlignment.vue'
import BzTitle from '../../components/section/BzTitle.vue'
import BzSubtitle from '../../components/section/BzSubtitle.vue'

import BzItems from '../../components/section/BzItems.vue'
import BzDivider from '../../components/section/BzDivider.vue'
import MediaList1 from './MediaList1.vue'

export default {
  name: 'MediaList4',
  components: { BzDivider, BzItems, BzSubtitle, BzTitle, BzAlignment, BzContainer, BzBackground },
  extends: MediaList1,
  methods: {
    cardStyle(index) {
      return {
        card: {
          borderRadius: '10px',
          overflow: 'hidden'
        }
      }
    }
  }
}
</script>

<style lang="scss" scoped>
.bz-sec--media-list-4-root {
  .card-text-wrapper {
    padding: 4px 10px;
  }
}
</style>
