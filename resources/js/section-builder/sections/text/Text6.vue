<template>
  <div class="bz-section-container bz-sect--section3-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" :size="sectionSize">
      <bz-container>
        <div class="col-12">
          <bz-alignment :alignment="setting.layouts.alignment">
            <bz-title v-if="setting.elements.title" v-model="data.elements.title" />
            <bz-subtitle v-if="setting.elements.subtitle" v-model="data.elements.subtitle" />
            <bz-text v-if="setting.elements.description" v-model="data.elements.description" />
            <bz-button-group v-if="setting.elements.buttons" v-model="data.elements.buttons" class="mt-3" />
          </bz-alignment>
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
  name: 'Text1',
  components: {
    BzAlignment,
    BzButtonGroup,
    BzSubtitle,

    BzContainer,
    BzBackground,
    BzTitle
  },
  mixins: [sectionMixin]
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
