<template>
  <bz-setting :edit="edit" @click="handleNavigationClick">
    <div v-click-outside="handleOutsideClick" class="navigation-container" @click="handleNavigationClick">
      <template v-for="(navigation, index) of modelValue" :key="index">
        <div class="navigation-item" :class="{ edit: false }">
          <div style="width: max-content" @mouseover="openDropDown(index)">
            <bz-link :link="navigation.link">
              <div class="cursor-pointer">
                <slot :item="navigation" :index="index" />
              </div>
            </bz-link>
          </div>
          <div v-if="navigation.children?.length > 0" class="dropdown" :class="{ open: openedIndex === index }">
            <div v-if="navigation.children?.length > 0" class="top-arrow"></div>
            <bz-navigation-item v-for="(item, itemIndex) of navigation.children" :key="itemIndex" :item="item" />
          </div>
        </div>
      </template>
    </div>
  </bz-setting>
</template>
<script>
import elementMixin from '../../mixins/elementMixin'
import BzNavigationItem from './BzNavigationItem.vue'
import BzSetting from '../section/BzSetting.vue'
import BzLink from '../section/BzLink.vue'

export default {
  name: 'BzNavigation',
  components: { BzLink, BzSetting, BzNavigationItem },
  mixins: [elementMixin],
  props: {
    modelValue: {
      type: Array,
      default: () => {
        return []
      }
    }
  },
  data() {
    return {
      openedIndex: null
    }
  },
  methods: {
    openDropDown(index) {
      this.openedIndex = index
    },
    handleNavigationClick(e) {
      if (this.edit) {
        this.$store.commit('openModal', {
          name: 'navigationBuilder',
          data: this.modelValue,
          onChange: (newNavigation) => {
            this.$emit('update:modelValue', newNavigation)
          }
        })
      }
    },
    handleOutsideClick() {
      this.openedIndex = null
    }
  }
}
</script>
<style lang="scss" scoped>
.add-navigation {
  background-color: var(--bz-edit-active);
  border-color: var(--bz-edit-active);
  border: solid 1px;
  color: white;
  border-radius: 4px;
  padding: 2px 4px;
  cursor: pointer;

  &:hover {
    background-color: var(--bz-section-edit-active-hover-color);
  }
}

.navigation-container {
  display: flex;
  align-items: center;

  .navigation-item {
    position: relative;
  }
}

.dropdown {
  position: absolute;
  border-radius: 5px;
  z-index: -1;
  background-color: white;
  padding: 20px 10px;
  right: 0;
  box-shadow: 0 0 4px 2px #00000012;
  margin-top: 30px;
  opacity: 0;
  transition: all linear 0.1s;
  pointer-events: none;

  .top-arrow {
    position: absolute;
    width: 0;
    height: 0;
    top: -10px;
    right: 16px;
    border-left: 10px solid transparent;
    border-right: 10px solid transparent;
    border-bottom: 10px solid white;
  }

  &.open {
    opacity: 1;
    z-index: 9;
    margin-top: 5px;
    pointer-events: auto;
  }
}
</style>
