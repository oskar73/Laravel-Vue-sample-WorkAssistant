<template>
  <div class="bz-section-container bz-sec--price-list-2-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" :size="sectionSize">
      <bz-container>
        <bz-alignment :alignment="setting.layouts.alignment">
          <bz-title v-if="setting.elements.title" v-model="data.elements.title" :text-color="theme.primaryColor" />

          <bz-subtitle v-if="setting.elements.subtitle" v-model="data.elements.subtitle" />

          <bz-text v-if="setting.elements.description" v-model="data.elements.description" />
        </bz-alignment>

        <bz-items v-model="data.items" :cols="setting.column" :shadow="false" :styles="cardStyles" :enable-sort="edit">
          <template v-slot="{ item }">
            <bz-alignment :alignment="setting.layouts.alignment">
              <div class="bz-fl-pricing-card-top tw-w-[120px] tw-h-[120px] tw-rounded-[60px] tw-flex tw-justify-center tw-items-center tw-flex-col tw-mb-5" :style="planStyle">
                <bz-title v-if="setting.listElements.title" v-model="item.title" />

                <bz-text v-if="setting.listElements.price" v-model="item.price" />
              </div>
            </bz-alignment>

            <div class="bz-fl-pricing-card-bottom">
              <bz-alignment :alignment="setting.layouts.alignment">
                <bz-text v-if="setting.listElements.subtitle" v-model="item.subtitle" />

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
import BzBackground from '../../components/section/BzBackground.vue'
import BzContainer from '../../components/section/BzContainer.vue'
import BzAlignment from '../../components/section/BzAlignment.vue'
import BzTitle from '../../components/section/BzTitle.vue'
import BzSubtitle from '../../components/section/BzSubtitle.vue'

import BzItems from '../../components/section/BzItems.vue'
import BzButton from '../../components/section/BzButton.vue'

export default {
  name: 'PriceList2',
  components: { BzButton, BzItems, BzSubtitle, BzTitle, BzAlignment, BzContainer, BzBackground },
  mixins: [sectionMixin],
  computed: {
    planStyle() {
      const originalColor = tinycolor(this.theme[this.themeMode].primaryColor)
      const backgroundColor = originalColor.brighten(50).toString()

      return {
        backgroundColor
      }
    }
  },
  methods: {
    cardStyles(index) {
      return {
        card: {
          padding: '40px 20px'
        }
      }
    }
  }
}
</script>

<style lang="scss" scoped>
.bz-sec--price-list-2-root {
  .bz-fl-pricing-card-top {
    width: 120px;
    height: 120px;
    border-radius: 60px;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    margin-bottom: 20px;
  }
}
</style>
