<template>
  <div class="bz-section-container bz-sec--price-list-4-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" :size="sectionSize">
      <bz-container>
        <bz-alignment :alignment="setting.layouts.alignment">
          <bz-title v-if="setting.elements.title" v-model="data.elements.title" :text-color="theme.primaryColor" />

          <bz-subtitle v-if="setting.elements.subtitle" v-model="data.elements.subtitle" />

          <bz-text v-if="setting.elements.description" v-model="data.elements.description" />
        </bz-alignment>

        <bz-items v-model="data.items" :cols="setting.column" :shadow="setting.layouts.shadow" :styles="cardStyle" :enable-sort="edit">
          <template v-slot="{ item }">
            <bz-alignment :alignment="setting.layouts.alignment">
              <bz-title v-if="setting.listElements.title" v-model="item.title" />

              <bz-icon v-model="item.icon" :background-color="theme.primaryColor" :text-color="'#00000000'" :rounded="false" :border-radius="0" :full="true" />

              <bz-text v-if="setting.listElements.price" v-model="item.price" :text-color="theme.primaryColor" />

              <bz-text v-if="setting.listElements.subtitle" v-model="item.subtitle" />

              <bz-text v-if="setting.listElements.description" v-model="item.description" />

              <bz-button v-model="item.button" :link="false" />
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
import BzButton from '../../components/section/BzButton.vue'
import BzIcon from '../../components/section/BzIcon.vue'

export default {
  name: 'PriceList4',
  components: { BzIcon, BzButton, BzItems, BzSubtitle, BzTitle, BzAlignment, BzContainer, BzBackground },
  mixins: [sectionMixin],
  methods: {
    cardStyle(index) {
      return {
        card: {
          padding: '30px 30px'
        }
      }
    }
  }
}
</script>

<style lang="scss" scoped>
.bz-sec--price-list-4-root {
}
</style>
