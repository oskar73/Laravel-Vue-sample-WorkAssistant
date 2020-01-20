<template>
  <div class="bz-section-container bz-sect--text-1-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" :size="sectionSize">
      <div class="section-content">
        <bz-container>
          <div class="tw-flex tw-flex-col lg:tw-flex-row tw-w-full py-0" :class="{ 'tw-flex-row-reverse': setting.alignment === 'right' }">
            <div class="tw-w-full lg:tw-w-1/2 tw-p-2">
              <bz-alignment :alignment="setting.layouts.alignment">
                <bz-title v-if="setting.elements.title" v-model="data.elements.title" />
                <bz-subtitle v-if="setting.elements.subtitle" v-model="data.elements.subtitle" />
                <bz-text v-if="setting.elements.description" v-model="data.elements.description" />
              </bz-alignment>
            </div>
            <div class="tw-w-full lg:tw-w-1/2 tw-p-2">
              <bz-card :shadow="setting.layouts.shadow" :bg-color="secondaryColor">
                <bz-items v-model="data.items" :cols="1" category-name="text" :shadow="false" :enable-sort="edit">
                  <template v-slot="{ item }">
                    <bz-alignment :alignment="setting.layouts.alignment">
                      <bz-title v-if="setting.listElements.title" v-model="item.title" />
                      <bz-text v-if="setting.listElements.description" v-model="item.description" />
                      <div v-if="setting.listElements.buttons" class="tw-flex tw-mt-2">
                        <bz-button v-model="item.buttons" />
                      </div>
                    </bz-alignment>
                  </template>
                </bz-items>
              </bz-card>
            </div>
          </div>
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
import BzButton from '../../components/section/BzButton.vue'
import BzAlignment from '../../components/section/BzAlignment.vue'

export default {
  name: 'Text1',
  components: {
    BzAlignment,
    BzButton,
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
.bz-sect--text-1-root {
  @import 'style';
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
