<template>
  <div class="bz-section-container bz-sec--price-list-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" :size="sectionSize">
      <bz-container>
        <bz-alignment :alignment="setting.layouts.alignment">
          <bz-title v-if="setting.elements.title" v-model="data.elements.title" :text-color="theme.primaryColor" />

          <bz-subtitle v-if="setting.elements.subtitle" v-model="data.elements.subtitle" />

          <bz-text v-if="setting.elements.description" v-model="data.elements.description" />
        </bz-alignment>

        <bz-items v-model="data.items" :cols="setting.column" :shadow="setting.layouts.shadow" :enable-sort="edit">
          <template v-slot="{ item }">
            <div class="bz-fl-pricing-card-top tw-px-[30px] tw-py-5">
              <bz-alignment :alignment="setting.layouts.alignment">
                <bz-title v-if="setting.listElements.title" v-model="item.title" :background-color="theme.primaryColor" />

                <bz-text v-if="setting.listElements.price" v-model="item.price" :text-color="'#ffffff'" />

                <bz-text v-if="setting.listElements.subtitle" v-model="item.subtitle" :background-color="theme.primaryColor" />
              </bz-alignment>
            </div>
            <div class="bz-fl-pricing-card-bottom tw-px-[30px] tw-py-5">
              <bz-alignment :alignment="setting.layouts.alignment">
                <bz-text v-if="setting.listElements.description" v-model="item.description" />

                <bz-button v-model="item.button" :link="false" />
              </bz-alignment>
            </div>
          </template>
        </bz-items>
      </bz-container>
    </bz-background>
  </div>
</template>

<script>
import sectionMixin from '../../mixins/sectionMixin'
import BzSubtitle from '../../components/section/BzSubtitle.vue'

import BzTitle from '../../components/section/BzTitle.vue'
import BzAlignment from '../../components/section/BzAlignment.vue'
import BzContainer from '../../components/section/BzContainer.vue'
import BzBackground from '../../components/section/BzBackground.vue'
import BzItems from '../../components/section/BzItems.vue'
import BzButton from '../../components/section/BzButton.vue'

export default {
  name: 'PriceList1',
  components: { BzButton, BzItems, BzBackground, BzContainer, BzAlignment, BzTitle, BzSubtitle },
  mixins: [sectionMixin]
}
</script>

<style lang="scss" scoped>
.bz-sec--price-list-root {
  .bz-fl-pricing-card-top {
    padding: 30px 20px;
    background-color: var(--bz-primary-color);
  }
  .bz-fl-pricing-card-bottom {
    padding: 30px 20px;
  }
}
</style>
