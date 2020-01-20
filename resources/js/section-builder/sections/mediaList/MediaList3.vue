<template>
  <div class="bz-section-container bz-sec--media-list-3-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" :size="sectionSize">
      <bz-container>
        <bz-alignment :alignment="setting.layouts.alignment">
          <bz-title v-if="setting.elements.title" v-model="data.elements.title" />

          <bz-subtitle v-if="setting.elements.subtitle" v-model="data.elements.subtitle" />

          <bz-text v-if="setting.elements.description" v-model="data.elements.description" />
        </bz-alignment>

        <bz-items v-model="data.items" :cols="column" :shadow="setting.layouts.shadow" :enable-sort="edit">
          <template v-slot="{ item }">
            <div class="card-body">
              <div class="card-image-wrapper">
                <bz-aspect-view v-if="setting.listElements.image" :ratio="aspectRatio">
                  <bz-image v-model="item.image" />
                </bz-aspect-view>
              </div>
              <div class="card-text-wrapper">
                <bz-alignment>
                  <bz-title v-if="setting.listElements.title" v-model="item.title" />

                  <bz-text v-if="setting.listElements.subtitle" v-model="item.subtitle" />

                  <bz-divider v-if="setting.listElements.dividerLine" :line="true" />

                  <bz-text v-if="setting.listElements.description" v-model="item.description" />
                </bz-alignment>
              </div>
            </div>
          </template>
        </bz-items>
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
  name: 'MediaList3',
  components: { BzDivider, BzItems, BzSubtitle, BzTitle, BzAlignment, BzContainer, BzBackground },
  extends: MediaList1,
  computed: {
    column() {
      if (this.setting.column > 2) {
        return 2
      }
      return this.setting.column
    },
    imageStyle() {
      if (this.setting.layouts.shadow) {
        return {
          boxShadow: '0 .5rem 1rem -.25rem #0000007f'
        }
      } else {
        return {}
      }
    }
  }
}
</script>

<style lang="scss" scoped>
.bz-sec--media-list-3-root {
  .card-body {
    display: flex;
    width: 100%;
    flex-direction: row;
    align-items: center;

    .card-image-wrapper {
      width: 300px;
    }
    .card-text-wrapper {
      text-align: center;
      width: 100%;
      padding: 0 10px;
      display: flex;
    }
  }
}
</style>
