<template>
  <div class="bz-slider-up-down-root">
    <div class="bz-slider-up-down-header" @click.prevent="active = !active">
      <div class="bz-slider-up-down-header-left">
        <slot name="header"></slot>
      </div>
      <div class="bz-slider-up-down-header-right">
        <span v-if="active" class="arrow_top control_item">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="#000000">
            <path d="M0 16.67l2.829 2.83 9.175-9.339 9.167 9.339 2.829-2.83-11.996-12.17z" />
          </svg>
        </span>
        <span v-if="!active" class="arrow_bottom mx-1 control_item">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="#000000">
            <path d="M0 7.33l2.829-2.83 9.175 9.339 9.167-9.339 2.829 2.83-11.996 12.17z" />
          </svg>
        </span>
      </div>
    </div>
    <slide-up-down v-model="active" :duration="100">
      <div class="bz-slider-up-down-content">
        <slot name="content"></slot>
      </div>
    </slide-up-down>
  </div>
</template>

<script>
import elementMixin from '../../mixins/elementMixin'

export default {
  name: 'BzSlideUpDown',
  mixins: [elementMixin],
  data() {
    return {
      active: true
    }
  }
}
</script>

<style lang="scss" scoped>
.bz-slider-up-down- {
  &root {
    width: 100%;
  }

  &header {
    cursor: pointer;
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    align-items: center;
    position: relative;
  }

  &header-left {
    width: 100%;
  }

  &header-right {
    position: absolute;
    right: 10px;
  }

  &content {
  }
}
</style>
