<template>
  <div class="bz-section-container bz-sec--team-6-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" :size="sectionSize">
      <bz-container>
        <bz-alignment :alignment="setting.layouts.alignment">
          <bz-title v-if="setting.elements.title" v-model="data.elements.title" />

          <bz-subtitle v-if="setting.elements.subtitle" v-model="data.elements.subtitle" />

          <bz-text v-if="setting.elements.description" v-model="data.elements.description" />
        </bz-alignment>
        <bz-items v-model="data.items" :shadow="false" :cols="2" :enable-sort="edit">
          <template v-slot="{ item }">
            <div class="col-12" :style="{ backgroundColor: secondaryColor }">
              <div class="lg:tw-grid tw-grid-cols-3">
                <div class="tw-col-span-2 tw-p-6">
                  <bz-alignment :alignment="setting.layouts.alignment">
                    <bz-title v-if="setting.listElements.title" v-model="item.title" />
                    <bz-text v-if="setting.listElements.description" v-model="item.description" />
                  </bz-alignment>
                </div>
                <div v-if="setting.listElements.image" class="tw-p-0">
                  <bz-image v-model="item.image" resize-mode="full" />
                </div>
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
  name: 'Team6',
  components: { BzItems, BzSubtitle, BzTitle, BzAlignment, BzContainer, BzBackground },
  mixins: [sectionMixin]
}
</script>
