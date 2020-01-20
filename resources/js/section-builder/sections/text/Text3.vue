<template>
  <div class="bz-section-container bz-sect--text-3-root" :class="{ [breakPoint]: true }">
    <div class="element-container" :style="{ backgroundColor: boxColor }">
      <bz-section-size :section-size="sectionSize">
        <bz-container>
          <bz-alignment :alignment="setting.layouts.alignment">
            <bz-title v-if="setting.elements.title" v-model="data.elements.title" />

            <bz-subtitle v-if="setting.elements.subtitle" v-model="data.elements.subtitle" />

            <bz-text v-if="setting.elements.description" v-model="data.elements.description" />
          </bz-alignment>
        </bz-container>
      </bz-section-size>
    </div>
    <bz-background :setting="background" class="tw-mt-0 tw-flex">
      <div class="-tw-mt-28">
        <bz-container>
          <bz-card :shadow="setting.layouts.shadow" :bg-color="secondaryColor">
            <bz-items v-model="data.items" :cols="setting.column" category-name="text" :shadow="false" :enable-sort="edit">
              <template v-slot="{ item }">
                <div class="tw-w-full tw-p-4">
                  <bz-title v-if="setting.listElements.title" v-model="item.title" />
                  <bz-text v-if="setting.listElements.description" v-model="item.description" />
                  <div v-if="setting.listElements.buttons" class="tw-flex tw-justify-end">
                    <bz-button-group v-model="item.buttons" />
                  </div>
                </div>
              </template>
            </bz-items>
          </bz-card>
        </bz-container>
      </div>
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
import BzSectionSize from '../../components/section/BzSectionSize.vue'
import BzAlignment from '../../components/section/BzAlignment.vue'

export default {
  name: 'Text3',
  components: {
    BzAlignment,
    BzSectionSize,
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
.bz-sect--text-3-root {
  .element-wrapper {
    padding: 60px;
    min-height: 200px;
  }

  &.bz-sm {
    .element-wrapper {
      padding: 0;
    }
  }

  .element-container {
    padding-bottom: 100px;
  }

  .card-container {
    margin-top: -100px;
  }
}
</style>
