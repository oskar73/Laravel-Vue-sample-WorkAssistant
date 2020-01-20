<template>
  <div class="bz-section-container bz-sec--usp-5-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" :size="sectionSize">
      <bz-container>
        <bz-alignment :alignment="setting.layouts.alignment">
          <bz-title v-if="setting.elements.title" v-model="data.elements.title" :text-color="theme.primaryColor" />
          <bz-text v-if="setting.elements.subtitle" v-model="data.elements.subtitle" />
          <bz-text v-if="setting.elements.description" v-model="data.elements.description" />
        </bz-alignment>

        <bz-divider />

        <bz-items v-model="data.items" :cols="setting.column" :shadow="false" :spacing="false" :enable-sort="edit">
          <template v-slot="{ item }">
            <bz-alignment :alignment="setting.layouts.alignment">
              <div v-if="setting.listElements.icon" class="bz-usp-item-container">
                <bz-icon v-model="item.icon" :text-color="theme.primaryColor" />
              </div>

              <bz-title v-if="setting.listElements.title" v-model="item.title" />

              <bz-text v-if="setting.listElements.subtitle" v-model="item.subtitle" />

              <bz-text v-if="setting.listElements.description" v-model="item.description" />
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
import BzAlignment from '../../components/section/BzAlignment.vue'
import BzContainer from '../../components/section/BzContainer.vue'
import BzTitle from '../../components/section/BzTitle.vue'

import BzItems from '../../components/section/BzItems.vue'
import BzDivider from '../../components/section/BzDivider.vue'
import BzIcon from '../../components/section/BzIcon.vue'

export default {
  components: { BzIcon, BzDivider, BzItems, BzTitle, BzContainer, BzAlignment, BzBackground },
  mixins: [sectionMixin]
}
</script>
<style lang="scss" scoped>
.bz-sec--usp-5-root {
  .bz-usp-item-container {
    width: 80px;
    height: 80px;
    border-radius: 40px;
    display: flex;
    justify-content: center;
    align-items: center;
  }
}
</style>
