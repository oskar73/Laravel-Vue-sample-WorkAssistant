<template>
  <div v-click-outside="handleOutsideClick" class="navigation-item-wrap" @mouseover="openSubMenu" @mouseleave="onMouseLeave">
    <bz-link :link="item.link">
      <div class="d-flex align-items-center cursor-pointer">
        <div>{{ item.name }}</div>
        <div class="navigation-item-image">
          <img v-if="item.image" :src="item.image.src" alt="icon image" />
        </div>
        <div v-if="item.children && item.children.length > 0" class="navigation-item-icon">
          <i v-if="item.icon" :class="item.icon" style="padding-bottom: 2px" class="mr-1"></i>
        </div>
      </div>
    </bz-link>
    <div v-if="item.children && item.children.length > 0" class="sub-item-wrap" :class="{ open: open }">
      <div v-for="(subItem, index) of item.children" :key="index">
        <bz-navigation-item :item="subItem" />
      </div>
    </div>
  </div>
</template>

<script>
import BzLink from '../section/BzLink.vue'
export default {
  name: 'BzNavigationItem',
  components: { BzLink },
  props: {
    item: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      open: false,
      mouseLeave: true
    }
  },
  methods: {
    openSubMenu() {
      this.open = true
    },
    handleOutsideClick() {
      this.open = false
      this.mouseLeave = false
    },
    onMouseLeave(event) {
      this.mouseLeave = true
      const rect = this.$el.getBoundingClientRect()
      if (event.clientY < rect.top || event.clientY > rect.bottom) {
        this.open = false
      }
    }
  }
}
</script>

<style lang="scss">
.navigation-item-wrap {
  display: flex;
  justify-content: space-between;
  padding: 8px 5px 8px 20px;
  align-items: center;
  border-bottom: solid 1px #80808078;
  min-width: 180px;
  cursor: pointer;
  position: relative;

  &:hover {
    background-color: #00000012;
  }

  .navigation-item-image {
    width: 30px;
    height: 30px;
    margin-left: auto;

    img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
  }

  .navigation-item-icon {
    margin-left: 10px;
    font-size: 18px;
  }

  .sub-item-wrap {
    position: absolute;
    left: calc(100% + 30px);
    padding: 10px 0;
    box-shadow: 0 0 4px 2px #00000012;
    background-color: white;
    top: 0;
    opacity: 0;
    transition: all linear 0.2s;

    &.open {
      opacity: 1;
      z-index: 9;
      left: calc(100% + 15px);
    }
  }
}
</style>
