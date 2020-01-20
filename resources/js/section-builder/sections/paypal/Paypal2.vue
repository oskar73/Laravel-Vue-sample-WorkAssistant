<template>
  <div class="bz-section-container bz-sec--sectionName-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" :size="sectionSize">
      <bz-container>
        <bz-alignment :alignment="setting.layouts.alignment">
          <bz-title v-if="setting.elements.title" v-model="data.elements.title" />

          <bz-subtitle v-if="setting.elements.subtitle" v-model="data.elements.subtitle" />

          <bz-text v-if="setting.elements.description" v-model="data.elements.description" />

          <bz-button v-if="setting.paypal.cartButton" v-model="data.elements.button" :link="false" />
        </bz-alignment>

        <bz-items v-model="data.items" :cols="setting.column" :shadow="setting.layouts.shadow" :styles="cardStyles" :enable-sort="edit">
          <template v-slot="{ item }">
            <bz-image v-if="setting.listElements.image" v-model="item.image" :ratio="1 / 2" :styles="{ root: imageStyle }" />

            <div class="w-100 card-body">
              <bz-alignment :alignment="setting.layouts.alignment">
                <bz-title v-if="setting.listElements.title" v-model="item.title" />

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
  name: 'Paypal2',
  components: { BzButton, BzItems, BzSubtitle, BzTitle, BzAlignment, BzContainer, BzBackground },
  mixins: [sectionMixin],
  computed: {
    imageStyle() {
      return {
        clipPath: 'ellipse(100% 90% at 50% 10%)'
      }
    },
    cardBackgroundColor() {
      const originalColor = window.tinycolor(this.backgroundColor)
      return originalColor.brighten(2).toString()
    }
  },
  methods: {
    cardStyles(index) {
      return {
        card: {
          backgroundColor: this.cardBackgroundColor,
          borderRadius: '10px',
          overflow: 'hidden'
        }
      }
    }
  }
}
</script>

<style scoped></style>
