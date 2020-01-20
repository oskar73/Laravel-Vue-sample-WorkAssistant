<template>
  <div class="bz-section-container bz-sec--statistic-2-root" :class="{ [breakPoint]: true }">
    <bz-background :size="sectionSize" :setting="background">
      <bz-container>
        <bz-alignment :alignment="setting.layouts.alignment">
          <bz-title v-if="setting.elements.title" v-model="data.elements.title" :text-color="theme.primaryColor" />
          <bz-text v-if="setting.elements.subtitle" v-model="data.elements.subtitle" />
          <bz-text v-if="setting.elements.description" v-model="data.elements.description" />
        </bz-alignment>

        <bz-items v-model="data.items" :cols="setting.column" :shadow="setting.layouts.shadow" :enable-sort="edit">
          <template v-slot="{ item }">
            <div class="bz-item-container" :style="{ backgroundColor: boxColor, borderBottom: setting.listElements.line ? `solid 2px ${secondaryColor}` : 'none' }">
              <bz-alignment :alignment="setting.layouts.alignment">
                <bz-text v-if="setting.listElements.value" v-model="item.value" :bold="true" :text-color="theme.primaryColor" :size="valueSize" :mb="5" />

                <bz-text v-if="setting.listElements.subtitle" v-model="item.subtitle" :mb="0" class="ml-2" />
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

import BzItems from '../../components/section/BzItems.vue'

export default {
  components: { BzItems, BzTitle, BzAlignment, BzContainer, BzBackground },
  mixins: [sectionMixin],
  computed: {
    valueSize() {
      if (this.setting.layouts.valueSize === 'large') {
        return '2.5em'
      }
      if (this.setting.layouts.valueSize === 'medium') {
        return '2em'
      }
      return '1.5em'
    },
    itemValueStyle() {
      const backgroundColor = this.primaryBoxColor

      if (this.setting.layouts.shape === 'circle') {
        return {
          borderRadius: '1000px',
          backgroundColor
        }
      } else if (this.setting.layouts.shape === 'square') {
        return {
          backgroundColor
        }
      } else {
        return {
          clipPath: 'polygon(50% 0,100% 25%,100% 75%,50% 100%,0 75%,0 25%)',
          backgroundColor
        }
      }
    }
  }
}
</script>

<style lang="scss" scoped>
.bz-sec--statistic-2-root {
  .bz-item-container {
    display: flex;
    align-items: center;
    padding: 30px;
    box-shadow: 0 0 4px 2px #00000012;
  }
}
</style>
