<template>
  <div class="bz-section-container bz-sect--section3-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" :size="sectionSize">
      <bz-container>
        <bz-alignment :alignment="setting.layouts.alignment">
          <bz-title v-if="setting.elements.title" v-model="data.elements.title" />
          <bz-subtitle v-if="setting.elements.subtitle" v-model="data.elements.subtitle" />
          <bz-text v-if="setting.elements.description" v-model="data.elements.description" />
        </bz-alignment>
        <bz-items v-model="data.items" :cols="setting.column" category-name="text" :shadow="setting.layouts.shadow" :enable-sort="edit">
          <template v-slot="{ item }">
            <bz-card :bg-color="secondaryColor" :padding="20">
              <bz-title v-if="setting.listElements.title" v-model="item.title" />
              <bz-text v-if="setting.listElements.description" v-model="item.description" />
              <div v-if="setting.listElements.buttons" class="d-flex justify-content-end">
                <bz-button-group v-model="item.buttons" />
              </div>
            </bz-card>
          </template>
        </bz-items>
      </bz-container>
    </bz-background>
  </div>
</template>

<script>
import sectionMixin from '../../mixins/sectionMixin'
import BzTitle from '../../components/section/BzTitle.vue'
import BzCard from '../../components/section/BzCard.vue'
import BzBackground from '../../components/section/BzBackground.vue'
import BzContainer from '../../components/section/BzContainer.vue'

import BzItems from '../../components/section/BzItems.vue'
import BzSubtitle from '../../components/section/BzSubtitle.vue'
import BzButtonGroup from '../../components/section/BzButton.vue'
import BzAlignment from '../../components/section/BzAlignment.vue'

export default {
  name: 'Text4',
  components: {
    BzAlignment,
    BzButtonGroup,
    BzSubtitle,
    BzItems,

    BzContainer,
    BzBackground,
    BzCard,
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
