<template>
  <div class="bz-section-container bz-sec--menu-7-root" :class="{ [breakPoint]: true }">
    <bz-background :size="sectionSize">
      <bz-container>
        <bz-alignment :alignment="setting.layouts.alignment">
          <bz-title v-if="setting.elements.title" v-model="data.elements.title" :text-color="theme.primaryColor" />

          <bz-subtitle v-if="setting.elements.subtitle" v-model="data.elements.subtitle" />

          <bz-text v-if="setting.elements.description" v-model="data.elements.description" />
        </bz-alignment>

        <div class="bz-divider-lines"></div>

        <bz-items v-model="data.items" :cols="setting.column" :shadow="false" :spacing="false" :enable-sort="edit">
          <template slot-scope="{ item, index }">
            <div class="bz-col-12 py-0">
              <bz-alignment :alignment="setting.layouts.alignment">
                <bz-title v-if="setting.listElements.title" v-model="item.title" :text-color="theme.primaryColor" />

                <bz-text v-if="setting.listElements.description" v-model="item.subtitle" />

                <bz-text v-if="setting.listElements.price" v-model="item.price" :text-color="theme.primaryColor" :size="'1.2em'" />
              </bz-alignment>
            </div>
          </template>
        </bz-items>

        <div class="bz-divider-lines"></div>
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
import BzDivider from '../../components/section/BzDivider.vue'

export default {
  name: 'Menu5',
  components: { BzDivider, BzItems, BzSubtitle, BzTitle, BzAlignment, BzContainer, BzBackground },
  mixins: [sectionMixin]
}
</script>

<style lang="scss" scoped>
.bz-sec--menu-7-root {
  .bz-divider-lines {
    width: 100%;
    border-bottom: solid 1px var(--bz-primary-color);
    position: relative;

    &::after {
      position: absolute;
      width: 100%;
      content: '';
      bottom: -4px;
      height: 1px;
      background-color: var(--bz-primary-color);
    }
  }
}
</style>
