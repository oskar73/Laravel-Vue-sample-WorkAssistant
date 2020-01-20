<template>
  <div class="bz-section-container bz-sec--menu-5-root" :class="{ [breakPoint]: true }">
    <bz-background size="m">
      <bz-container>
        <bz-alignment :alignment="setting.layouts.alignment">
          <bz-title v-if="setting.elements.title" v-model="data.elements.title" />

          <bz-subtitle v-if="setting.elements.subtitle" v-model="data.elements.subtitle" />

          <bz-text v-if="setting.elements.description" v-model="data.elements.description" />
        </bz-alignment>

        <bz-items v-model="data.items" :cols="setting.column" :shadow="false" :spacing="false" :enable-sort="edit">
          <template slot-scope="{ item, index }">
            <bz-alignment :alignment="setting.layouts.alignment">
              <div class="bz-col-8 py-0">
                <div class="item-title-price-wrapper">
                  <bz-text v-if="setting.listElements.title" v-model="item.title" :bold="true" :size="'1.1em'" />

                  <div v-if="setting.column < 3" class="horizontal-bar"></div>

                  <bz-text v-if="setting.listElements.price" v-model="item.price" :bold="true" />
                </div>
              </div>
              <div class="bz-col-8 py-0">
                <bz-text v-if="setting.listElements.description" v-model="item.subtitle" />
              </div>
            </bz-alignment>
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
import BzDivider from '../../components/section/BzDivider.vue'

export default {
  name: 'Menu5',
  components: { BzDivider, BzItems, BzSubtitle, BzTitle, BzAlignment, BzContainer, BzBackground },
  mixins: [sectionMixin]
}
</script>

<style lang="scss" scoped>
.bz-sec--menu-5-root {
  .item-title-price-wrapper {
    display: flex;
    align-items: center;
    width: 100%;
    justify-content: space-between;
  }

  .horizontal-bar {
    flex-grow: 1;
    height: 1px;
    margin: 0 10px 8px 10px;
    background-color: #555555;
  }
}
</style>
