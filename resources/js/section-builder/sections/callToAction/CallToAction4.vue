<template>
  <div class="bz-section-container bz-sec-call-to-action-2-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" :size="sectionSize">
      <bz-container>
        <bz-card :background-color="'#ffffffff'">
          <div class="content" :class="{ [setting.layouts.alignment]: true }">
            <bz-icon v-if="data.elements.icon" v-model="data.elements.icon" :background-color="'#ffffffff'" :text-color="theme.primaryColor" />

            <bz-divider />

            <div class="text-elements" :class="{ [setting.layouts.alignment]: true }">
              <bz-title v-if="setting.elements.title" v-model="data.elements.title" :background-color="'#ffffffff'" />

              <bz-subtitle v-if="setting.elements.subtitle" v-model="data.elements.subtitle" :background-color="'#ffffffff'" />

              <bz-text v-if="setting.elements.description" v-model="data.elements.description" :background-color="'#ffffffff'" />
            </div>

            <div class="buttons" :class="{ [setting.layouts.alignment]: true }">
              <bz-button-group v-if="setting.elements.buttons" v-model="data.elements.buttons" :background-color="'#ffffffff'" />
            </div>
          </div>
        </bz-card>
      </bz-container>
    </bz-background>
  </div>
</template>

<script>
import BzBackground from '../../components/section/BzBackground.vue'
import BzContainer from '../../components/section/BzContainer.vue'
import sectionMixin from '../../mixins/sectionMixin'
import BzTitle from '../../components/section/BzTitle.vue'
import BzSubtitle from '../../components/section/BzSubtitle.vue'

import BzButton from '../../components/section/BzButton.vue'
import BzButtonGroup from '../../components/section/BzButtonGroup.vue'
import BzIcon from '../../components/section/BzIcon.vue'
import BzDivider from '../../components/section/BzDivider.vue'
import BzCard from '../../components/section/BzCard.vue'
export default {
  components: {
    BzCard,
    BzDivider,
    BzIcon,
    BzButtonGroup,
    BzButton,

    BzSubtitle,
    BzTitle,
    BzContainer,
    BzBackground
  },
  mixins: [sectionMixin]
}
</script>
<style lang="scss">
.bz-sec-call-to-action-2-root {
  .content {
    flex-direction: column;
    display: flex;
    padding: 20px;

    &.left {
      align-items: flex-start;
    }

    &.center {
      align-items: center;
    }

    &.right {
      align-items: flex-end;
    }

    .text-elements {
      &.left {
        text-align: left;
      }
      &.center {
        text-align: center;
      }
      &.right {
        text-align: right;
      }
    }

    .buttons {
      display: flex;
      align-items: flex-end;
      flex-wrap: wrap;
      justify-content: flex-start;

      &.center {
        align-items: center;
        justify-content: center;
      }

      &.right {
        justify-content: flex-end;
      }
    }
  }
}
</style>
