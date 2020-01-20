<template>
  <div class="bz-el--icon-root" :class="{ edit }">
    <div class="icon-wrapper" :style="iconWrapperStyle">
      <i :class="{ [data]: true }" :style="{ color: fill ? backgroundColor : color, fontSize: fontSize }"></i>
    </div>
    <div v-if="edit" class="editor">
      <div class="editor-button" @click="openIconSelector">
        <widgets-icon :fill-color="'#555555'" />
      </div>
    </div>
  </div>
</template>

<script>
import elementMixin from '../../mixins/elementMixin'
import WidgetsIcon from '../icons/Widgets.vue'

export default {
  name: 'BzIcon',
  components: { WidgetsIcon },
  mixins: [elementMixin],
  props: {
    size: {
      type: Number,
      default: 96
    },
    rounded: {
      type: Boolean,
      default: true
    },
    borderRadius: {
      type: Number,
      default: 4
    },
    full: {
      type: Boolean,
      default: false
    },
    fill: {
      type: Boolean,
      default: false
    }
  },
  computed: {
    iconWrapperStyle() {
      return {
        width: this.size + 'px',
        height: this.size + 'px',
        borderRadius: this.rounded ? '50%' : this.borderRadius + 'px',
        backgroundColor: this.fill ? this.color : 'transparent'
      }
    },
    fontSize() {
      if (this.full) {
        return this.size + 'px'
      }
      return this.size / 2.5 + 'px'
    }
  },
  methods: {
    openIconSelector() {
      this.$store.commit('openModal', {
        name: 'iconSelector',
        onChange: (icon) => {
          this.data = icon
        }
      })
    }
  }
}
</script>
<style lang="scss">
.bz-el--icon-root {
  width: max-content;
  height: max-content;
  border: solid 2px transparent;
  position: relative;
  justify-content: center;
  align-items: center;

  .icon-wrapper {
    display: flex;
    justify-content: center;
    align-items: center;
  }

  &.edit {
    .editor {
      display: none;
      position: absolute;
      width: 100%;
      height: 100%;
      top: 0;
      left: 0;
      justify-content: center;
      align-items: center;

      .editor-button {
        background-color: white;
        box-shadow: 0 0 10px 2px #00000012;
        padding: 4px;
        cursor: pointer;
        border-radius: 2px;
      }
    }

    &:hover {
      border: solid 2px var(--bz-section-edit-active-color);
      .editor {
        display: flex;
      }
    }
  }
}
</style>
