<template>
  <div class="bz-section-container bz-sec--usp-4-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" :size="sectionSize">
      <div class="background-attachment" :class="setting.alignment"></div>
      <bz-container>
        <div class="tw-flex tw-flex-col lg:tw-flex-row" :class="{ 'tw-flex-col-reverse lg:tw-flex-row-reverse': setting.alignment === 'right' }">
          <div class="bz-col-lg-7">
            <bz-image v-if="setting.elements.image" v-model="data.elements.image" :ratio="2 / 3" />
          </div>
          <div class="bz-col-lg-5">
            <bz-alignment :alignment="setting.layouts.alignment" :stretch="false">
              <bz-title v-if="setting.elements.title" v-model="data.elements.title" :text-color="theme.primaryColor" />
              <bz-text v-if="setting.elements.subtitle" v-model="data.elements.subtitle" />
              <bz-text v-if="setting.elements.description" v-model="data.elements.description" />
            </bz-alignment>

            <bz-items v-model="data.items" :cols="1" :shadow="false" :spacing="false" :enable-sort="edit">
              <template v-slot="{ item }">
                <div class="bz-item">
                  <div v-if="setting.listElements.icon" class="bz-usp-item-container">
                    <bz-icon v-model="item.icon" :text-color="'#ffffffff'" :size="60" />
                  </div>
                  <div class="bz-item-content">
                    <bz-alignment :alignment="setting.layouts.alignment">
                      <bz-title v-if="setting.listElements.title" v-model="item.title" />

                      <bz-text v-if="setting.listElements.subtitle" v-model="item.subtitle" />

                      <bz-text v-if="setting.listElements.description" v-model="item.description" />
                    </bz-alignment>
                  </div>
                </div>
              </template>
            </bz-items>
          </div>
        </div>
      </bz-container>
    </bz-background>
  </div>
</template>

<script>
import sectionMixin from '../../mixins/sectionMixin'
import BzBackground from '../../components/section/BzBackground.vue'
import BzAlignment from '../../components/section/BzAlignment.vue'
import BzContainer from '../../components/section/BzContainer.vue'
import BzTitle from '../../components/section/BzTitle.vue'

import BzItems from '../../components/section/BzItems.vue'
import BzDivider from '../../components/section/BzDivider.vue'
import BzIcon from '../../components/section/BzIcon.vue'

export default {
  components: {
    BzIcon,
    BzDivider,
    BzItems,

    BzTitle,
    BzContainer,
    BzAlignment,
    BzBackground
  },
  mixins: [sectionMixin]
}
</script>
<style lang="scss" scoped>
.bz-sec--usp-4-root {
  .bz-item {
    display: flex;
  }

  .bz-usp-item-container {
    width: 40px;
    height: 40px;
    border-radius: 40px;
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: var(--bz-primary-color);
  }

  .bz-item-content {
    width: calc(100% - 40px);
    padding-left: 30px;
  }

  // Background
  .background-attachment {
    position: absolute;
    top: 0;
    width: 100%;
    height: 30%;
    background-color: var(--bz-primary-color);

    &.right {
      right: auto;
      top: auto;
      bottom: 0;
    }
  }

  &.bz-md {
    .background-attachment {
      top: 0;
      width: 30%;
      height: 100%;

      &.right {
        right: 0;
        top: 0;
        bottom: auto;
      }
    }
  }
}
</style>
