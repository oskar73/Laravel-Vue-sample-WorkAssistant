<template>
  <div class="bz-section-container bz-sec--team-3-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" :size="sectionSize">
      <bz-container>
        <bz-alignment :alignment="setting.layouts.alignment">
          <bz-title v-if="setting.elements.title" v-model="data.elements.title" />

          <bz-subtitle v-if="setting.elements.subtitle" v-model="data.elements.subtitle" />

          <bz-text v-if="setting.elements.description" v-model="data.elements.description" />
        </bz-alignment>

        <div class="tw-mt-[30px]">
          <bz-items v-model="data.items" :shadow="false" :cols="setting.column" :styles="{ card: { marginTop: '20px' } }" :enable-sort="edit">
            <template v-slot="{ item }">
              <div class="tw-mb-6 tw-relative" :style="{ backgroundColor: secondaryColor }">
                <div class="tw-w-1/2 -tw-mt-8 tw-absolute tw-px-4">
                  <bz-aspect-view :ratio="1">
                    <bz-image v-if="setting.listElements.image" v-model="item.image" resize-mode="full" />
                  </bz-aspect-view>
                </div>

                <div class="tw-pt-[calc(50%-36px)] -tw-mb-[20px] tw-px-4 tw-pb-4">
                  <bz-divider :height="10" />

                  <bz-title v-if="setting.listElements.title" v-model="item.title" :background-color="'#ffffff'" />
                  <bz-text v-if="setting.listElements.subtitle" v-model="item.subtitle" :background-color="'#ffffff'" />
                  <bz-text v-if="setting.listElements.description" v-model="item.description" :background-color="'#ffffff'" />
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
import BzContainer from '../../components/section/BzContainer.vue'
import BzAlignment from '../../components/section/BzAlignment.vue'
import BzTitle from '../../components/section/BzTitle.vue'
import BzSubtitle from '../../components/section/BzSubtitle.vue'
import BzItems from '../../components/section/BzItems.vue'
import BzAspectView from '../../components/section/BzAspectView.vue'
import BzDivider from '../../components/section/BzDivider.vue'

export default {
  name: 'Team3',
  components: { BzDivider, BzAspectView, BzItems, BzSubtitle, BzTitle, BzAlignment, BzContainer, BzBackground },
  mixins: [sectionMixin]
}
</script>
