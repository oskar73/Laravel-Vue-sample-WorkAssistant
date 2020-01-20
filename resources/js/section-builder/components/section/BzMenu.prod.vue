<script>
import { defineComponent } from 'vue'
import elementMixin from '../../mixins/elementMixin'

export default defineComponent({
  name: 'BzMenu',
  mixins: [elementMixin],
  props: {
    maxWidth: {
      type: Number,
      default: undefined
    },
    showCount: {
      type: Number,
      default: undefined
    }
  },
  data() {
    return {
      showMenuIndex: 1,
      openDropDown: false
    }
  },
  created() {
    this.showMenuIndex = this.allPages.length
  },
  mounted() {
    const self = this
    if (this.showCount) {
      this.showMenuIndex = this.showCount
    } else {
      new ResizeObserver(() => {
        let menuItemsWidth = 0
        if (self.$refs.menuItems) {
          for (let i = 0; i < self.$refs.menuItems.length; i++) {
            menuItemsWidth += self.$refs.menuItems[i].clientWidth
            if (menuItemsWidth > Math.min(this.maxWidth || self.$el.clientWidth - 170, self.$el.clientWidth - 170)) {
              self.showMenuIndex = i
              break
            }
          }
        }
      }).observe(this.$el)
    }
  },
  methods: {
    toggleDropDown() {
      this.openDropDown = !this.openDropDown
    },
    handleOutsideClick() {
      this.openDropDown = false
    },
    showMenuItem(page) {
      if (page.type === 'module') {
        return this.modules.activeModules.includes(page.module_name)
      }
      return page.type !== 'new-page'
    }
  }
})
</script>

<template>
  <div class="bz-nav-bar">
    <div class="menu-wrap">
      <div class="bz-el-menu">
        <template v-for="(page, index) of allPages" :key="page.id">
          <router-link :to="page.url">
            <div v-if="showMenuItem(page) && index <= showMenuIndex" ref="menuItems">
              <slot :page-name="page.name" :active="isActiveMenu(page.url)" />
            </div>
          </router-link>
        </template>
        <div v-if="showMenuIndex < allPages.length - 1" class="more-menu">
          <div style="width: max-content" @click.prevent="toggleDropDown">
            <slot :page-name="'More'" :active="false" />
          </div>
          <div v-click-outside="handleOutsideClick" class="dropdown" :class="{ open: openDropDown }">
            <template v-for="(page, index) of allPages" :key="page.id">
              <router-link v-if="showMenuItem(page) && index > showMenuIndex" ref="menuItems" :key="index" :to="page.url">
                <slot :page-name="page.name" :active="isActiveMenu(page.url)" />
              </router-link>
            </template>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style lang="scss">
.bz-nav-bar {
  position: relative;
  display: flex;
  align-items: center;
  width: 100%;

  .menu-wrap {
    position: absolute;
    width: max-content;
    right: 0;

    .bz-el-menu {
      position: relative;
      display: flex;
      a {
        text-decoration: none;
        color: var(--bz-theme-text-color);
      }
    }

    .more-menu {
      position: relative;
      padding-right: 16px;
      right: 0;

      &:after {
        content: '';
        width: 0;
        height: 0;
        border-left: 7px solid transparent;
        border-right: 7px solid transparent;
        border-top: 8px solid var(--bz-header-menu-item-color, #555555) !important;
        position: absolute;
        top: calc(50% - 4px);
        right: 0;
      }

      .dropdown {
        position: absolute;
        border-radius: 5px;
        z-index: -1;
        background-color: white;
        padding: 10px 0;
        box-shadow: 0 0 8px 4px #00000023;
        margin-top: 30px;
        opacity: 0;
        transition: all linear 0.2s;

        &.open {
          opacity: 1;
          z-index: -9;
          margin-top: 5px;
        }
      }
    }
  }
}
</style>
