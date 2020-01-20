<template>
  <div class="bz-section-container bz-sec--team-4-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" :size="sectionSize">
      <bz-container>
        <bz-alignment :alignment="setting.layouts.alignment">
          <bz-title v-if="setting.elements.title" v-model="data.elements.title" :text-color="theme.primaryColor" />

          <bz-subtitle v-if="setting.elements.subtitle" v-model="data.elements.subtitle" />

          <bz-text v-if="setting.elements.description" v-model="data.elements.description" />
        </bz-alignment>

        <bz-items v-model="data.items" :shadow="false" :cols="setting.column" :enable-sort="edit">
          <template v-slot="{ item }">
            <bz-alignment :alignment="setting.layouts.alignment">
              <bz-aspect-view v-if="setting.listElements.image" :ratio="ratio">
                <bz-image v-model="item.image" resize-mode="full" :rounded="rounded" :disable-aspect-view="disableAspectView" :ratio="ratio" />
              </bz-aspect-view>
            </bz-alignment>
            <div class="tw-mt-t">
              <bz-alignment :alignment="setting.layouts.alignment">
                <bz-title v-if="setting.listElements.title" v-model="item.title" :background-color="'#ffffff'" />
                <bz-text v-if="setting.listElements.subtitle" v-model="item.subtitle" :background-color="'#ffffff'" />
              </bz-alignment>
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
import BzAspectView from '../../components/section/BzAspectView.vue'

export default {
  name: 'Team4',
  components: { BzAspectView, BzItems, BzSubtitle, BzTitle, BzAlignment, BzContainer, BzBackground },
  mixins: [sectionMixin],
  computed: {
    rounded() {
      return this.setting.layouts.aspectRatio === 'circle'
    },
    disableAspectView() {
      return this.setting.layouts.aspectRatio === 'original'
    },
    ratio() {
      if (this.setting.layouts.aspectRatio === 'circle' || this.setting.layouts.aspectRatio === 'square') {
        return 1
      }
      if (this.setting.layouts.aspectRatio === 'landscape') {
        return 3 / 4
      }
      if (this.setting.layouts.aspectRatio === 'portrait') {
        return 4 / 3
      }
    }
  }
}
</script>
