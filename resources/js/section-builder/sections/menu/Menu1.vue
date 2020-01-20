<template>
  <div class="bz-section-container bz-sec--sectionName-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background">
      <div class="tw-flex tw-flex-col lg:tw-flex-row" :class="{ 'tw-flex-col-reverse lg:tw-flex-row-reverse': setting.alignment === 'right' }">
        <div class="bz-col-lg-6 py-0">
          <bz-background size="m">
            <bz-container>
              <bz-alignment :alignment="setting.layouts.alignment">
                <bz-title v-if="setting.elements.title" v-model="data.elements.title" />

                <bz-subtitle v-if="setting.elements.subtitle" v-model="data.elements.subtitle" />

                <bz-text v-if="setting.elements.description" v-model="data.elements.description" />
              </bz-alignment>

              <bz-items v-model="data.items" :cols="1" :shadow="false" :spacing="false" :enable-sort="edit">
                <template slot-scope="{ item }">
                  <div class="bz-col-12 py-0">
                    <div class="bz-row" :class="{ 'bz-row-reverse': setting.layouts.alignment === 'right' }">
                      <div class="bz-col-8 py-0">
                        <bz-alignment :alignment="setting.layouts.alignment">
                          <bz-text v-if="setting.listElements.title" v-model="item.title" :bold="true" :size="'1.1em'" />
                        </bz-alignment>
                      </div>
                      <div class="bz-col-4 py-0">
                        <bz-alignment :alignment="setting.layouts.alignment">
                          <bz-text v-if="setting.listElements.price" v-model="item.price" :bold="true" :size="'1.1em'" />
                        </bz-alignment>
                      </div>
                    </div>
                  </div>
                  <div class="bz-col-12 py-0">
                    <bz-alignment :alignment="setting.layouts.alignment">
                      <bz-text v-if="setting.listElements.description" v-model="item.subtitle" />
                    </bz-alignment>
                  </div>
                </template>
              </bz-items>
            </bz-container>
          </bz-background>
        </div>
        <div class="bz-col-lg-6 py-0"></div>
      </div>
    </bz-background>
  </div>
</template>

<script>
import sectionMixin from '../../mixins/sectionMixin'
import BzBackground from '../../components/section/BzBackground.vue'
import BzTitle from '../../components/section/BzTitle.vue'

import BzAlignment from '../../components/section/BzAlignment.vue'
import BzContainer from '../../components/section/BzContainer.vue'
import BzSubtitle from '../../components/section/BzSubtitle.vue'
import BzItems from '../../components/section/BzItems.vue'

export default {
  name: 'Menu1',
  components: { BzItems, BzSubtitle, BzContainer, BzAlignment, BzTitle, BzBackground },
  mixins: [sectionMixin]
}
</script>

<style scoped></style>
