<template>
  <div class="bz-section-container bz-sec--team-2-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" :size="sectionSize">
      <bz-container>
        <bz-alignment :alignment="setting.layouts.alignment">
          <bz-title v-if="setting.elements.title" v-model="data.elements.title" />

          <bz-subtitle v-if="setting.elements.subtitle" v-model="data.elements.subtitle" />

          <bz-text v-if="setting.elements.description" v-model="data.elements.description" />
        </bz-alignment>
        <bz-items v-model="data.items" :shadow="setting.layouts.shadow" :cols="1" :enable-sort="edit">
          <template v-slot="{ item }">
            <div class="col-12">
              <div class="tw-flex tw-flex-col lg:tw-flex-row" :class="{ 'tw-flex-row-reverse': reverse(item) }">
                <div v-if="setting.listElements.image" class="lg:tw-w-1/4 tw-p-0">
                  <bz-aspect-view :ratio="aspectRatio">
                    <bz-image v-model="item.image" resize-mode="full" :rounded="rounded" />
                  </bz-aspect-view>
                </div>
                <div class="lg:tw-w-3/4 tw-p-6">
                  <bz-alignment :alignment="alignment(item)">
                    <bz-title v-if="setting.listElements.title" v-model="item.title" />

                    <bz-text v-if="setting.listElements.subtitle" v-model="item.subtitle" />

                    <bz-text v-if="setting.listElements.description" v-model="item.description" />

                    <bz-divider :line="setting.listElements.line" :width="50" :thickness="3" />
                  </bz-alignment>
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
import BzAspectView from '../../components/section/BzAspectView.vue'
import BzDivider from '../../components/section/BzDivider.vue'

export default {
  name: 'Team2',
  components: { BzDivider, BzItems, BzSubtitle, BzTitle, BzAlignment, BzContainer, BzBackground, BzAspectView },
  mixins: [sectionMixin],
  computed: {
    disableAspectView() {
      return this.setting.layouts.aspectRatio === 'original'
    },
    rounded() {
      return this.setting.layouts.aspectRatio === 'circle'
    },
    aspectRatio() {
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
  },
  methods: {
    reverse(index) {
      if (this.setting.alignment === 'alternate') {
        return index % 2
      }
      return this.setting.alignment === 'right'
    },
    alignment(index) {
      if (this.setting.alignment === 'alternate') {
        if (this.setting.layouts.alignment === 'center') {
          return 'center'
        }

        if (index % 2) {
          if (this.setting.layouts.alignment === 'left') {
            return 'left'
          }
          return 'right'
        } else {
          if (this.setting.layouts.alignment === 'left') {
            return 'right'
          }
          return 'left'
        }
      }

      return this.setting.layouts.alignment
    }
  }
}
</script>

