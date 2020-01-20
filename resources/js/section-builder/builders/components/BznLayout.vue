<template>
  <div class="bz-layout-root" :style="getStyle()">
    <template v-for="(item, index) in layoutData.data.children" :key="index">
      <component :is="`bzn-${item.name.toLowerCase()}`" :data="item"></component>
    </template>
  </div>
</template>

<script>
import { merge } from 'lodash'

export default {
  name: 'BznLayout',
  props: {
    data: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      name: 'layout',
      active: false,
      layoutData: {
        name: 'layout',
        data: {
          style: {
            direction: 'row'
          },
          children: []
        }
      }
    }
  },
  created() {
    this.layoutData = merge(this.layoutData, this.data ?? {})
  },
  methods: {
    getStyle() {
      const _style = this.layoutData.data.style
      return {
        flexDirection: _style.direction
      }
    }
  }
}
</script>

<style lang="scss" scoped>
.bz-layout-root {
  display: flex;
  width: 100%;
  align-items: stretch;
  flex: 1;
}
</style>
