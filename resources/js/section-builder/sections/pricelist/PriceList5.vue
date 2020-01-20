<template>
  <div class="bz-section-container bz-sec--price-list-5-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" :size="sectionSize">
      <bz-container>
        <bz-alignment :alignment="setting.layouts.alignment">
          <bz-title v-if="setting.elements.title" v-model="data.elements.title" :text-color="theme.primaryColor" />

          <bz-subtitle v-if="setting.elements.subtitle" v-model="data.elements.subtitle" />

          <bz-text v-if="setting.elements.description" v-model="data.elements.description" />
        </bz-alignment>

        <bz-items v-model="data.items" :cols="setting.column" :shadow="false" :enable-sort="edit">
          <template v-slot="{ item }">
            <div class="bz-fl-pricing-card-top tw-bg-black">
              <bz-alignment :alignment="setting.layouts.alignment">
                <bz-title v-if="setting.listElements.title" v-model="item.title" />

                <bz-text v-if="setting.listElements.subtitle" v-model="item.subtitle" />
              </bz-alignment>
            </div>
            <div class="bz-fl-pricing-card-bottom tw-px-[30px] tw-py-5">
              <bz-alignment :alignment="setting.layouts.alignment">
                <bz-text v-if="setting.listElements.price" v-model="item.price" />

                <bz-text v-if="setting.listElements.description" v-model="item.description" />
              </bz-alignment>
            </div>

            <bz-button v-model="item.button" style="width: 100%" :link="false" :width="'100%'" />
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
import BzButton from '../../components/section/BzButton.vue'

export default {
  name: 'PriceList5',
  components: { BzButton, BzItems, BzSubtitle, BzTitle, BzAlignment, BzContainer, BzBackground },
  mixins: [sectionMixin]
}
</script>

<style lang="scss" scoped>
.bz-sec--price-list-5-root {
  .bz-fl-pricing-card-top {
    background-color: #00000000;
  }
  .bz-fl-pricing-card-bottom {
    padding: 30px 20px;
  }
  .bz-shadow {
    box-shadow: none !important;
  }
}
</style>
