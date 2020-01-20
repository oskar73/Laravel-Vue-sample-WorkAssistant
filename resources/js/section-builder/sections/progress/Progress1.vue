<template>
  <div class="bz-section-container bz-sec--progress-1-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" :size="sectionSize">
      <bz-container>
        <bz-alignment :alignment="setting.layouts.alignment">
          <bz-title v-if="setting.elements.title" v-model="data.elements.title" />

          <bz-subtitle v-if="setting.elements.subtitle" v-model="data.elements.subtitle" />

          <bz-text v-if="setting.elements.description" v-model="data.elements.description" />
        </bz-alignment>

        <bz-items v-model="data.items" :cols="1" :shadow="false" :enable-sort="edit">
          <template v-slot="{ item }">
            <div class="tw-w-full sm:tw-flex tw-justify-between tw-items-center">
              <bz-title v-if="setting.listElements.title" v-model="item.title" />

              <div class="tw-flex tw-items-center">
                <bz-text v-if="setting.listElements.value" v-model="item.value" :bold="true" />
                <bz-text v-if="setting.listElements.value" :bold="true" value="%" />
              </div>
            </div>
            <div class="tw-relative tw-justify-center tw-items-center tw-flex tw-px-2.5" :class="{ edit }">
              <div v-if="edit" class="tw-flex tw-absolute tw-w-full tw-h-full tw-top-0 tw-left-0 tw-justify-center tw-items-center tw-border-2 tw-border-transparent">
                <div class="tw-shadow-lg tw-bg-white tw-p-1.5 tw-rounded tw-cursor-pointer" @click.stop.prevent="openProgressEditForm(item)">
                  <setting-icon fill-color="#808080" />
                </div>
              </div>

              <div class="tw-w-full tw-h-4 tw-rounded-[100px]" :style="containerStyle">
                <div class="tw-w-full tw-h-4 tw-rounded-[100px]" :style="contentStyle(item.value)"></div>
              </div>

              <div
                v-if="editing === item"
                v-click-outside="hideProgressEditForm"
                class="tw-absolute tw-w-[200px] tw-p-2.5 tw-border tw-border-[#00000012] -tw-bottom-[50px] tw-bg-white tw-z-[100000] tw-shadow"
              >
                <slider v-model="item.value" :min="0" :max="100" thumb-label :step="1" />
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
import BzSubtitle from '../../components/section/BzSubtitle.vue'

import BzTitle from '../../components/section/BzTitle.vue'
import BzAlignment from '../../components/section/BzAlignment.vue'
import BzContainer from '../../components/section/BzContainer.vue'
import BzBackground from '../../components/section/BzBackground.vue'
import BzItems from '../../components/section/BzItems.vue'
import SettingIcon from '../../components/icons/Setting.vue'
import Slider from '@vueform/slider'

export default {
  name: 'Progress1',
  components: { SettingIcon, BzItems, BzBackground, BzContainer, BzAlignment, BzTitle, BzSubtitle, Slider },
  mixins: [sectionMixin],
  data() {
    return {
      editing: false
    }
  },
  computed: {
    containerStyle() {
      return {
        backgroundColor: this.boxColor
      }
    }
  },
  methods: {
    contentStyle(value) {
      return {
        backgroundColor: this.headingColor,
        width: value + '%'
      }
    },
    openProgressEditForm(index) {
      this.editing = index
    },
    hideProgressEditForm() {
      this.editing = false
    }
  }
}
</script>
