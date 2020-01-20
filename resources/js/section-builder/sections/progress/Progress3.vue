<template>
  <div class="bz-section-container bz-sec--progress-3-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" :size="sectionSize">
      <bz-container>
        <bz-alignment :alignment="setting.layouts.alignment">
          <bz-title v-if="setting.elements.title" v-model="data.elements.title" :text-color="theme.primaryColor" />

          <bz-subtitle v-if="setting.elements.subtitle" v-model="data.elements.subtitle" />

          <bz-text v-if="setting.elements.description" v-model="data.elements.description" />
        </bz-alignment>

        <bz-items v-model="data.items" :cols="1" :shadow="false" :enable-sort="edit">
          <template v-slot="{ item }">
            <div class="bz-fl-progress-title">
              <bz-title v-if="setting.listElements.title" v-model="item.title" />
            </div>
            <div class="bz-fl-progress" :class="{ edit }">
              <div class="progress-bar" :style="{ '--percent-value': item.value + '%' }">
                <div class="progress-thumb" :style="{ backgroundColor: secondaryColor }"></div>
              </div>
              <div v-if="setting.listElements.value" class="progress-value" :style="{ backgroundColor: socialIconColor }">{{ item.value }}%</div>

              <div class="setting">
                <div class="setting-icon" @click.stop.prevent="openProgressEditForm(item)">
                  <setting-icon fill-color="#808080" />
                </div>
              </div>

              <div v-if="editing === item" v-click-outside="hideProgressEditForm" class="bz-form-progress-value">
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
  name: 'Progress3',
  components: {
    SettingIcon,
    BzItems,
    BzBackground,
    BzContainer,
    BzAlignment,
    BzTitle,

    BzSubtitle,
    Slider
  },
  mixins: [sectionMixin],
  data() {
    return {
      editing: false
    }
  },
  methods: {
    openProgressEditForm(index) {
      this.editing = index
    },
    hideProgressEditForm() {
      this.editing = false
    }
  }
}
</script>

<style lang="scss" scoped>
.bz-sec--progress-3-root {
  .bz-fl-progress {
    display: flex;
    justify-content: space-between;
    align-items: center;

    .setting {
      position: absolute;
      width: 100%;
      height: 100%;
      background-color: transparent;
      left: 0;
      top: 0;
      display: none;
      justify-content: center;
      align-items: center;
      z-index: 2;

      .setting-icon {
        background-color: white;
        padding: 5px;
        box-shadow: 0 0 4px 2px #00000022;
        border-radius: 2px;
      }
    }

    &:hover {
      .setting {
        display: flex;
      }
    }

    .progress-bar {
      width: 80%;
      background-color: #00000012;
      height: 3px;
      border-radius: 4px;
      display: flex;
      align-items: center;
      flex-direction: row;
      justify-content: flex-start;

      .progress-thumb {
        width: var(--percent-value);
        height: 100%;
        display: flex;
        justify-content: flex-end;
        align-items: center;

        &::after {
          position: absolute;
          content: '';
          width: 24px;
          height: 24px;
          border-radius: 24px;
          background-color: var(--bz-primary-color);
        }
      }
    }

    .progress-value {
      width: 80px;
      height: 80px;
      border-radius: 80px;
      background-color: white;
      display: flex;
      justify-content: center;
      align-items: center;
      font-size: 24px;
      font-weight: bold;
      color: white;
    }

    .bz-form-progress-value {
      position: absolute;
      width: 200px;
      padding: 10px;
      box-shadow: 0 0 10px 2px #00000012;
      border: solid 1px #00000012;
      bottom: -10px;
      background-color: white;
      z-index: 100000;
      left: calc(50% - 100px);
    }
  }

  .bz-fl-progress-title {
    margin-bottom: 10px;
  }
}
</style>
