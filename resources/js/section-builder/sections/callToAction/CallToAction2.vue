<template>
  <div class="bz-section-container bz-sec-call-to-action-2-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" :size="sectionSize">
      <bz-container>
        <div class="content" :class="{ [setting.layouts.alignment]: true }">
          <bz-icon v-if="data.elements.icon" v-model="data.elements.icon" />
          <bz-divider />
          <bz-text-element v-model="data.elements" :setting="setting.elements" :alignment="setting.layouts.alignment" />
          <div class="buttons" :class="{ [setting.layouts.alignment]: true }">
            <bz-button-group v-if="setting.elements.buttons" v-model="data.elements.buttons" class="mt-3" />
          </div>
        </div>
      </bz-container>
    </bz-background>
  </div>
</template>

<script>
import BzBackground from '../../components/section/BzBackground.vue'
import BzContainer from '../../components/section/BzContainer.vue'
import sectionMixin from '../../mixins/sectionMixin'
import BzButtonGroup from '../../components/section/BzButtonGroup.vue'
import BzIcon from '../../components/section/BzIcon.vue'
import BzDivider from '../../components/section/BzDivider.vue'
import BzTextElement from '../../components/section/BzTextElement.vue'
export default {
  components: {
    BzTextElement,
    BzDivider,
    BzIcon,
    BzButtonGroup,
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
