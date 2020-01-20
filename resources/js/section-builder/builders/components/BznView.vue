<template>
  <div class="bz-view-root" :style="getStyle()">
    <template v-if="viewData.data.child === null">
      <div class="view-empty">
        <div class="tool-bar">
          <i class="mdi mdi-close"></i>
        </div>
        <div v-click-outside="handleEmptySectionOutSideClick" class="view-empty-content" :class="{ active }" @click="handleEmptySectionClick">Click here to add component</div>
      </div>
    </template>
    <component :is="`bzn-${viewData.data.child.name.toLowerCase()}`" v-else :data="viewData.data.child"></component>
  </div>
</template>

<script>
import { merge } from 'lodash'

export default {
  name: 'BznView',
  props: {
    data: {
      type: [Object, undefined],
      default: undefined
    }
  },
  data() {
    return {
      name: 'view',
      active: false,
      viewData: {
        name: 'view',
        data: {
          style: {},
          child: null
        }
      }
    }
  },
  created() {
    this.viewData = merge(this.viewData, this.data ?? {})
  },
  methods: {
    getStyle() {
      const _style = this.viewData.data.style
      return {}
    },
    handleEmptySectionClick() {
      eventBus.$emit('bzn.element.selected', this)
      this.active = true
    },
    handleEmptySectionOutSideClick(event) {
      if (!event.target.closest('#bz-section-builder-control-panel') && this.active) {
        eventBus.$emit('bzn.element.cleared', this)
        this.active = false
      }
    }
  }
}
</script>

<style lang="scss" scoped>
.bz-view-root {
  display: flex;
  width: 100%;
  flex: 1;

  .view-empty {
    display: flex;
    width: 100%;
    padding: 2px;
    position: relative;

    .tool-bar {
      position: absolute;
      right: 0;
    }

    .view-empty-content {
      padding: 20px 20px;
      border: dashed 1px #00000023;
      text-align: center;
      color: #00000078;
      cursor: pointer;
      display: flex;
      width: 100%;
      justify-content: center;
      align-items: center;

      &:hover {
        border-color: #138cf8;
      }

      &.active {
        border-style: solid;
        border-color: #138cf8;
        outline: solid 1px #138cf8;
      }
    }
  }
}
</style>
