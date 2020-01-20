<template>
  <div class="bz-section-container bz-sect--text-2-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" :size="sectionSize">
      <bz-container class="p-4">
        <bz-title v-if="setting.elements.title" v-model="data.elements.title" />
        <bz-subtitle v-if="setting.elements.subtitle" v-model="data.elements.subtitle" />
        <bz-text v-if="setting.elements.description" v-model="data.elements.description" />
      </bz-container>
      <bz-container>
        <bz-items v-model="data.items" :cols="setting.column" category-name="text" :shadow="false" :enable-sort="edit">
          <template v-slot="{ item }">
            <div class="w-100 p-2">
              <bz-title v-if="setting.listElements.title" v-model="item.title" />
              <bz-text v-if="setting.listElements.description" v-model="item.description" />
              <div v-if="setting.listElements.buttons" class="d-flex justify-content-end">
                <bz-button-group v-model="item.buttons" />
              </div>
            </div>
          </template>
        </bz-items>
      </bz-container>
    </bz-background>
  </div>
</template>

<script>
import sectionMixin from '../../mixins/sectionMixin'
import BzTitle from '../../components/section/BzTitle.vue'
import BzBackground from '../../components/section/BzBackground.vue'
import BzContainer from '../../components/section/BzContainer.vue'
import BzItems from '../../components/section/BzItems.vue'

import BzSubtitle from '../../components/section/BzSubtitle.vue'
import BzButtonGroup from '../../components/section/BzButton.vue'

export default {
  name: 'Text2',
  components: {
    BzButtonGroup,
    BzSubtitle,

    BzItems,
    BzContainer,
    BzBackground,
    BzTitle
  },
  mixins: [sectionMixin]
}
</script>
<style lang="scss" scoped>
.bz-sect--text-2-root {
  .content {
    padding: 30px 0;
  }
}
</style>
