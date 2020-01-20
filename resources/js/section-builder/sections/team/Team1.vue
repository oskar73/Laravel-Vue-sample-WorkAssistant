<template>
  <div class="bz-section-container bz-sec--team-1-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" :size="sectionSize">
      <bz-container>
        <bz-alignment :alignment="setting.layouts.alignment">
          <bz-title v-if="setting.elements.title" v-model="data.elements.title" />
          <div class="tw-my-6">
            <bz-subtitle v-if="setting.elements.subtitle" v-model="data.elements.subtitle" />
          </div>
          <bz-text v-if="setting.elements.description" v-model="data.elements.description" />
        </bz-alignment>
        <bz-items v-model="data.items" :shadow="setting.layouts.shadow" :cols="setting.column" :enable-sort="edit">
          <template v-slot="{ item }">
            <div class="tw-flex lg:tw-flex-row tw-flex-col" :style="{ backgroundColor: secondaryColor }">
              <div class="lg:tw-w-2/3 tw-p-4">
                <bz-alignment :alignment="setting.layouts.alignment">
                  <bz-title v-if="setting.listElements.title" v-model="item.title" :background-color="'#ffffff'" />
                  <bz-text v-if="setting.listElements.subtitle" v-model="item.subtitle" :background-color="'#ffffff'" />
                </bz-alignment>
              </div>
              <div v-if="setting.listElements.image" class="lg:tw-w-1/3 tw-p-0 max-lg:tw-h-[240px]">
                <bz-image v-model="item.image" resize-mode="full" />
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
import BzBackground from '../../components/section/BzBackground.vue'
import BzContainer from '../../components/section/BzContainer.vue'
import BzAlignment from '../../components/section/BzAlignment.vue'
import BzTitle from '../../components/section/BzTitle.vue'
import BzSubtitle from '../../components/section/BzSubtitle.vue'
import BzItems from '../../components/section/BzItems.vue'

export default {
  name: 'Team1',
  components: { BzItems, BzSubtitle, BzTitle, BzAlignment, BzContainer, BzBackground },
  mixins: [sectionMixin]
}
</script>
<style lang="scss" scoped>
.bz-sec--team-1-root {
  .team-item {
    background-color: var(--bz-theme-background-gray);
  }
}
</style>
