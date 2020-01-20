<template>
  <div class="bz-section-container bz-sec--timeline-3-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" :size="sectionSize">
      <bz-container>
        <bz-alignment :alignment="setting.layouts.alignment">
          <bz-title v-if="setting.elements.title" v-model="data.elements.title" :text-color="theme.primaryColor" />
          <bz-text v-if="setting.elements.subtitle" v-model="data.elements.subtitle" />
          <bz-text v-if="setting.elements.description" v-model="data.elements.description" />

          <div class="tw-w-full tw-relative tw-flex md:tw-items-center tw-flex-col tw-items-start tw-mt-8 tw-max-w-[600px]">
            <bz-items v-model="data.items" :cols="1" :shadow="false" :spacing="false" :enable-sort="edit">
              <template v-slot="{ item, index }">
                <div class="tw-relative tw-w-full tw-flex tw-items-center" :style="listItemStyle(index)">
                  <div
                    v-if="setting.listElements.date"
                    class="tw-w-20 tw-h-20 tw-min-w-[80px] tw-min-h-[80px] tw-ml-[7px] tw-mt-[5px] tw-flex tw-justify-center tw-items-center"
                    :style="dotStyle"
                  >
                    <bz-text v-model="item.date" :background-color="theme.primaryColor" :mb="0" />
                  </div>
                  <div class="tw-w-full" :style="listContentStyle(index)">
                    <bz-alignment :alignment="contentAlignment(index)">
                      <bz-title v-if="setting.listElements.title" v-model="item.title" />

                      <bz-text v-if="setting.listElements.description" v-model="item.description" />
                    </bz-alignment>
                  </div>
                </div>
              </template>
            </bz-items>
          </div>
        </bz-alignment>
      </bz-container>
    </bz-background>
  </div>
</template>

<script>
import sectionMixin from '../../mixins/sectionMixin'
import BzBackground from '../../components/section/BzBackground.vue'
import BzTitle from '../../components/section/BzTitle.vue'
import BzContainer from '../../components/section/BzContainer.vue'

import BzItems from '../../components/section/BzItems.vue'
import BzAlignment from '../../components/section/BzAlignment.vue'

export default {
  components: { BzAlignment, BzItems, BzContainer, BzTitle, BzBackground },
  mixins: [sectionMixin],
  computed: {
    dotStyle() {
      if (this.setting.layouts.shape === 'circle') {
        return {
          borderRadius: '1000px',
          backgroundColor: this.socialIconColor
        }
      }
      if (this.setting.layouts.shape === 'hexagon') {
        return {
          clipPath: 'polygon(50% 0,100% 25%,100% 75%,50% 100%,0 75%,0 25%)',
          backgroundColor: this.socialIconColor
        }
      }
      return { backgroundColor: this.socialIconColor }
    }
  },
  methods: {
    listItemStyle(index) {
      if (this.sectionWidth > 640) {
        if (this.setting.alignment === 'alternate') {
          if (index % 2) {
            return {
              flexDirection: 'row-reverse'
            }
          }
        }

        if (this.setting.alignment === 'right') {
          return {
            flexDirection: 'row-reverse'
          }
        }
      }
    },
    listContentStyle(index) {
      if (this.sectionWidth > 640) {
        if (this.setting.alignment === 'alternate') {
          if (index % 2) {
            return {
              paddingRight: '30px'
            }
          }
        }

        if (this.setting.alignment === 'right') {
          return {
            paddingRight: '30px'
          }
        }
      }

      return {
        paddingLeft: '30px'
      }
    },
    contentAlignment(index) {
      if (this.sectionWidth > 640) {
        if (this.setting.alignment === 'alternate') {
          if (index % 2) {
            return 'right'
          }
          return 'left'
        }
      }

      return 'left'
    }
  }
}
</script>
