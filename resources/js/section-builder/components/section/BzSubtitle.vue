<template>
  <div style="font-size: 1.25em" :class="{ edit }" :style="textStyle" class="bz-element bz-subtitle-root" data-editor-element="true" @mouseup="showTextEditor">
    <div ref="contentRef" :contenteditable="edit" data-editor-element="true" :data-refresh="refresh" @input="handleContentChange" v-html="content" />
  </div>
</template>

<script>
import elementMixin from '../../mixins/elementMixin'
import BzText from './BzText.dev.vue'

export default {
  name: 'BzSubtitle',
  extends: BzText,
  mixins: [elementMixin],
  props: {
    shadow: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      fontType: 'subtitle'
    }
  }
}
</script>
<style lang="scss" scoped>
.bz-subtitle-root {
  cursor: text;
  width: 100%;

  &.edit {
    margin: -4px -8px;
    padding: 4px 8px;

    &:focus,
    &:active,
    &:focus-visible,
    &:focus-within,
    &:hover {
      outline: solid 1px #0076dfff;
    }
  }
}
</style>
