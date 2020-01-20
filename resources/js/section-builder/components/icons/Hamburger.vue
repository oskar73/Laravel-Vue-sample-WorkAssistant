<template>
  <div class="hamburger" @click="handleClick" :class="hamburgerOpen ? 'hamburger--is-open' : ''" :style="rootStyle">
    <div :style="itemStyle" class="hamburger__item hamburger__item--first"></div>
    <div :style="itemStyle" class="hamburger__item hamburger__item--middle"></div>
    <div :style="itemStyle" class="hamburger__item hamburger__item--last"></div>
  </div>
</template>

<script>
export default {
  name: 'hamburger-icon',
  props: {
    title: {
      type: String,
      default: 'Menu icon'
    },
    decorative: {
      type: Boolean,
      default: false
    },
    fillColor: {
      type: String,
      default: '#ffffff'
    },
    size: {
      type: Number,
      default: 25
    },
    lineHeight: {
      type: Number,
      default: 3
    },
    hamburgerOpen: {
      type: Boolean,
      default: false
    },
    onOpen: {
      type: Function,
      default: () => {}
    },
    onClose: {
      type: Function,
      default: () => {}
    },
    onClick: {
      type: Function,
      default: () => {}
    }
  },
  methods: {
    handleClick() {
      if (this.hamburgerOpen) {
        this.onOpen()
      } else {
        this.onClose()
      }
      this.onClick()
    }
  },
  computed: {
    rootStyle() {
      return {
        width: this.size + 'px',
        height: this.size + 'px'
      }
    },
    itemStyle() {
      return {
        height: this.lineHeight + 'px',
        backgroundColor: this.fillColor
      }
    }
  }
}
</script>

<style lang="scss" scoped>
.hamburger {
  display: flex;
  flex-direction: column;
  justify-content: space-around;

  &:hover {
    cursor: pointer;
  }

  &__item {
    width: 100%;
    transition: transform 300ms cubic-bezier(0.445, 0.05, 0.55, 0.95), opacity 300ms linear;

    &--first {
      .hamburger--is-open & {
        transform: translate(0, 9px) rotate(45deg);
      }
    }

    &--middle {
      .hamburger--is-open & {
        opacity: 0;
      }
    }

    &--last {
      .hamburger--is-open & {
        transform: translate(0, -8px) rotate(-45deg);
      }
    }
  }
}
</style>
