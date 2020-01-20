<template>
  <div class="bz-section-container bz-sec--statistic-1-root" :class="{ [breakPoint]: true }">
    <bz-background :size="sectionSize" :setting="background">
      <bz-container>
        <bz-alignment :alignment="setting.layouts.alignment">
          <bz-title v-if="setting.elements.title" v-model="data.elements.title" :text-color="theme.primaryColor" />
          <bz-text v-if="setting.elements.subtitle" v-model="data.elements.subtitle" />
          <bz-text v-if="setting.elements.description" v-model="data.elements.description" />
        </bz-alignment>

        <bz-items v-model="data.items" :shadow="false" :cols="setting.column" :enable-sort="edit">
          <template v-slot="{ item }">
            <div class="bz-item-container">
              <div class="bz-item-value" :style="itemValueStyle">
                <bz-text v-if="setting.listElements.value" v-model="item.value" :bold="true" size="3em" mb="0" />
              </div>

              <bz-divider :line="true" :width="50" />

              <bz-text v-if="setting.listElements.subtitle" v-model="item.subtitle" />
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
import BzDivider from '../../components/section/BzDivider.vue'

export default {
  components: { BzDivider, BzItems, BzTitle, BzAlignment, BzContainer, BzBackground },
  mixins: [sectionMixin],
  computed: {
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
.bz-sec--statistic-1-root {
  .bz-item-value {
    width: 120px;
    height: 120px;
    display: flex;
    justify-content: center;
    align-items: center;
  }

  .bz-item-container {
    display: flex;
    flex-direction: column;
    align-items: center;
  }
}
</style>
