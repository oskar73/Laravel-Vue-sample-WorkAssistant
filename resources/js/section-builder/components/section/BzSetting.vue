<template>
  <div class="bz-el-setting-root" :class="{ edit, wrapContent }" @click="handleClick">
    <slot />
    <div v-if="edit" class="setting" :class="{ outline: !hiddenOutline }">
      <div class="setting-icon">
        <setting-icon fill-color="#808080" />
      </div>
    </div>
  </div>
</template>

<script>
import SettingIcon from '../icons/Setting.vue'
import elementMixin from '../../mixins/elementMixin'
export default {
  name: 'BzSetting',
  components: { SettingIcon },
  mixins: [elementMixin],
  props: {
    index: {
      type: Number,
      default: 0
    },
    wrapContent: {
      type: Boolean,
      default: false
    },
    hiddenOutline: {
      type: Boolean,
      default: false
    },
    enablePropagation: {
      type: Boolean,
      default: false
    }
  },
  methods: {
    handleClick(e) {
      if (this.edit) {
        if (!this.enablePropagation) {
          e.preventDefault()
          e.stopPropagation()
        }
        this.$emit('click', this.index)
      }
    }
  }
}
</script>

<style lang="scss" scoped>
.bz-el-setting-root {
  position: relative;

  a {
    text-decoration: none !important;
  }

  &.wrapContent {
    width: max-content;
  }

  .setting {
    position: absolute;
    margin: -12px;
    width: 100%;
    height: 100%;
    background-color: transparent;
    left: 0;
    top: 0;
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 2;
    cursor: pointer;

    &.outline {
      width: calc(100% + 24px);
      height: calc(100% + 24px);
      border: solid 2px var(--bz-edit-active);
    }

    .setting-icon {
      background-color: white;
      padding: 5px;
      box-shadow: 0 0 4px 2px #00000022;
      border-radius: 2px;
    }
  }

  &.edit {
    &:hover {
      .setting {
        display: flex;
      }
    }
  }
}
</style>
