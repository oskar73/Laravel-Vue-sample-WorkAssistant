<template>
  <div class="bz-section-container bz-sect--section3-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" :size="sectionSize">
      <bz-container>
        <div class="sm:tw-flex tw-gap-4" :class="{ 'tw-flex-row-reverse': setting.alignment === 'right' }">
          <div class="sm:tw-w-2/3">
            <div :style="subtitleStyle">
              <bz-alignment :alignment="setting.layouts.alignment">
                <bz-title v-if="setting.elements.title" v-model="data.elements.title" />
                <bz-text v-if="setting.elements.description" v-model="data.elements.description" />
                <bz-button-group v-if="setting.elements.buttons" v-model="data.elements.buttons" class="mt-3" />
              </bz-alignment>
            </div>
          </div>
          <div class="sm:tw-w-1/3 max-sm:tw-mt-4">
            <bz-subtitle v-if="setting.elements.subtitle" v-model="data.elements.subtitle" />
          </div>
        </div>
      </bz-container>
    </bz-background>
  </div>
</template>

<script>
import sectionMixin from '../../mixins/sectionMixin'
import BzTitle from '../../components/section/BzTitle.vue'
import BzBackground from '../../components/section/BzBackground.vue'
import BzContainer from '../../components/section/BzContainer.vue'

import BzSubtitle from '../../components/section/BzSubtitle.vue'
import BzButtonGroup from '../../components/section/BzButton.vue'
import BzAlignment from '../../components/section/BzAlignment.vue'

export default {
  name: 'Text7',
  components: {
    BzAlignment,
    BzButtonGroup,
    BzSubtitle,

    BzContainer,
    BzBackground,
    BzTitle
  },
  mixins: [sectionMixin],
  computed: {
    subtitleStyle() {
      if (this.sectionWidth > 600) {
        if (this.setting.alignment === 'left') {
          return {
            borderRight: 'solid 1px #8080807f',
            paddingRight: '50px'
          }
        }
        return {
          borderLeft: 'solid 1px #8080807f',
          paddingLeft: '50px'
        }
      }
    }
  }
}
</script>
<style lang="scss" scoped>
.bz-sect--section3-root {
  .element-wrapper {
    padding: 60px;
    min-height: 200px;
  }

  &.bz-sm {
    .element-wrapper {
      padding: 0;
    }
  }
}
</style>
