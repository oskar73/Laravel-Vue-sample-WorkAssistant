<template>
  <div class="bz-section-container bz-sec-call-to-action-5-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" :size="sectionSize">
      <bz-container>
        <bz-card :background-color="cardBackgroundColor" :padding="48">
          <div class="content" :class="{ [setting.layouts.alignment]: true }">
            <div class="text-elements" :class="{ [setting.layouts.alignment]: true }">
              <bz-title v-if="setting.elements.title" v-model="data.elements.title" :background-color="cardBackgroundColor" />

              <bz-subtitle v-if="setting.elements.subtitle" v-model="data.elements.subtitle" :background-color="cardBackgroundColor" />

              <bz-text v-if="setting.elements.description" v-model="data.elements.description" :background-color="cardBackgroundColor" />
            </div>

            <div class="buttons" :class="{ [setting.layouts.alignment]: true }">
              <bz-button-group v-if="setting.elements.buttons" v-model="data.elements.buttons" class="mt-3" :background-color="cardBackgroundColor" />
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
import BzCard from '../../components/section/BzCard.vue'
export default {
  components: { BzCard, BzButtonGroup, BzButton, BzSubtitle, BzTitle, BzContainer, BzBackground },
  mixins: [sectionMixin],
  computed: {
    cardBackgroundColor() {
      return '#ffffff'
    }
  }
}
</script>
<style lang="scss">
.bz-sec-call-to-action-5-root {
  .content {
    .text-elements {
      width: 70%;

      &.left {
        text-align: left;
      }

      &.center {
        text-align: center;
        width: 100%;
      }

      &.right {
        text-align: right;
      }
    }

    .buttons {
      width: 30%;
      display: flex;
      align-items: flex-end;
      flex-wrap: wrap;
      justify-content: flex-end;

      &.center {
        width: 100%;
        align-items: center;
        justify-content: center;
      }

      &.right {
        justify-content: flex-start;
      }
    }

    &.right,
    &.left {
      display: flex;
      align-items: flex-end;
    }

    &.center {
      flex-direction: column;
    }

    &.right {
      flex-flow: row-reverse;
    }
  }

  &.bz-sm {
    .content {
      flex-direction: column;

      .text-elements {
        width: 100%;
      }

      .buttons {
        width: 100%;
      }
    }
  }
}
</style>
