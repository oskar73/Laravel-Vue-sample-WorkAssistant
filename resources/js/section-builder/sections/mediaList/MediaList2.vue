<template>
  <div class="bz-section-container bz-sec--media-list-2-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" :size="sectionSize">
      <bz-container>
        <bz-alignment :alignment="setting.layouts.alignment">
          <bz-title v-if="setting.elements.title" v-model="data.elements.title" />

          <bz-subtitle v-if="setting.elements.subtitle" v-model="data.elements.subtitle" />

          <bz-text v-if="setting.elements.description" v-model="data.elements.description" />
        </bz-alignment>

        <bz-items v-model="data.items" :cols="setting.column" :shadow="setting.layouts.shadow" :enable-sort="edit">
          <template v-slot="{ item }">
            <div class="tw-p-2.5 tw-rounded" :style="{ backgroundColor: boxColor }">
              <bz-aspect-view v-if="setting.listElements.image" :ratio="aspectRatio">
                <bz-image v-model="item.image" />
              </bz-aspect-view>
              <div class="card-text-wrapper">
                <bz-title v-if="setting.listElements.title" v-model="item.title" />

                <bz-text v-if="setting.listElements.subtitle" v-model="item.subtitle" />

                <bz-text v-if="setting.listElements.description" v-model="item.description" />
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
import MediaList1 from './MediaList1.vue'

export default {
  name: 'MediaList2',
  components: { BzItems, BzSubtitle, BzTitle, BzAlignment, BzContainer, BzBackground },
  extends: MediaList1
}
</script>

<style lang="scss" scoped>
.bz-sec--media-list-2-root {
  .media-card {
    padding: 10px;
    border-radius: 4px;
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;

    .media-image {
      max-width: 250px;
    }

    .card-text-wrapper {
      width: 100%;
    }
  }
}
</style>
