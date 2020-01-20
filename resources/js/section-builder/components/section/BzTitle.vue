<template>
  <div style="font-size: 1.5em; font-weight: bold" :class="{ edit }" class="bz-element bz-title-root" :style="textStyle" @mouseup="showTextEditor">
    <div ref="contentRef" :contenteditable="edit" data-editor-element="true" :data-empty="!data" @input="handleContentChange" v-html="content"></div>
  </div>
</template>

<script>
import elementMixin from '../../mixins/elementMixin'
import BzText from './BzText.dev.vue'

export default {
  name: 'BzTitle',
  extends: BzText,
  mixins: [elementMixin],
  props: {
    mb: {
      type: Number,
      default: 0.5
    },
    shadow: {
      type: Boolean,
      default: false
    },
    size: {
      type: String,
      default: '24px'
    }
  },
  data() {
    return {
      fontType: 'title'
    }
  }
}
</script>
<style lang="scss" scoped>
.bz-title-root {
  width: 100%;

  &.edit {
    margin: -4px -8px;
    padding: 4px 8px;
    &:focus,
    &:hover {
      outline: solid 1px #0076dfff;
    }

    & > div {
      min-width: 10px;
      cursor: text;

      &[data-empty='true'] {
        width: 160px;
        position: relative;

        &:after {
          content: 'Type your text';
          position: absolute;
          color: grey;
          font-style: italic;
          white-space: nowrap;
        }
      }
    }
  }
}
</style>
