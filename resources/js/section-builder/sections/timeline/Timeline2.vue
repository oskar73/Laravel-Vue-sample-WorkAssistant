<template>
  <div class="bz-section-container bz-sec--timeline-2-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" :size="sectionSize">
      <bz-container>
        <bz-alignment :alignment="setting.layouts.alignment">
          <bz-title v-if="setting.elements.title" v-model="data.elements.title" :text-color="theme.primaryColor" />
          <bz-text v-if="setting.elements.subtitle" v-model="data.elements.subtitle" />
          <bz-text v-if="setting.elements.description" v-model="data.elements.description" />
        </bz-alignment>

        <div class="tw-w-full tw-relative tw-flex tw-items-start tw-flex-col sm:tw-items-center">
          <div class="tw-h-[calc(100%+20px)] tw-w-1 tw-opacity-10 tw-absolute" :style="{ backgroundColor: boxColor }"></div>
          <bz-items v-model="data.items" :cols="1" :shadow="false" :spacing="false" :enable-sort="edit">
            <template v-slot="{ item, index }">
              <div class="tw-relative tw-w-full tw-flex tw-justify-start sm:tw-justify-center">
                <div v-if="setting.listElements.image" class="tw-absolute tw-w-10 tw-h-10 tw-rounded-full -tw-ml-1 max-sm:tw-mt-1 sm:tw-w-20 sm:tw-h-20 sm:tw-ml-0">
                  <bz-image v-model="item.image" :circle="setting.layouts.shape == 'circle'" />
                </div>
                <div class="tw-w-full">
                  <div class="tw-w-full sm:tw-w-1/2" :class="listStyle(index)">
                    <bz-alignment :alignment="listAlignment(index)">
                      <bz-title v-if="setting.listElements.title" v-model="item.title" />

                      <bz-text v-if="setting.listElements.date" v-model="item.date" />

                      <bz-text v-if="setting.listElements.description" v-model="item.description" />

                      <bz-divider />
                    </bz-alignment>
                  </div>
                </div>
              </div>
            </template>
          </bz-items>
        </div>
      </bz-container>
    </bz-background>
  </div>
</template>

<script>
import sectionMixin from '../../mixins/sectionMixin'
import BzBackground from '../../components/section/BzBackground.vue'
import BzTitle from '../../components/section/BzTitle.vue'
import BzContainer from '../../components/section/BzContainer.vue'
import BzSubtitle from '../../components/section/BzSubtitle.vue'

import BzItems from '../../components/section/BzItems.vue'
import BzAlignment from '../../components/section/BzAlignment.vue'
import BzDivider from '../../components/section/BzDivider.vue'

export default {
  components: { BzDivider, BzAlignment, BzItems, BzSubtitle, BzContainer, BzTitle, BzBackground },
  mixins: [sectionMixin],
  methods: {
    listAlignment(index) {
      if (this.sectionWidth > 640) {
        if (this.setting.alignment === 'alternate') {
          if (index % 2) {
            return 'right'
          }
          return 'left'
        }
        return this.setting.alignment
      }
      return 'left'
    },
    listStyle(index) {
      return this.listAlignment(index) === 'left' ? 'tw-ml-auto tw-pl-[80px] tw-pr-0 max-sm:tw-pr-0 max-sm:tw-pl-[40px]' : 'tw-pr-[80px] max-sm:tw-pr-0 max-sm:tw-pl-[40px]'
    }
  }
}
</script>
