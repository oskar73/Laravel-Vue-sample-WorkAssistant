<template>
  <div class="bz-editor-panel-root">
    <div v-drag-move class="editor-body handler">
      <div class="d-flex align-items-center">
        <div class="handler">
          <drag-handle-icon />
        </div>
        <div class="expand-close" @click="expand = !expand">
          <bz-arrow-up-icon v-if="expand" />
          <bz-arrow-down-icon v-else />
        </div>
        <span>Editor</span>
      </div>
      <div v-if="expand">
        <hr />

        <button-editor v-if="editor === editors.button" />

        <div v-if="editor === null" class="d-flex flex-column align-items-center py-5">
          <img :src="asset('assets/img/icons/select-item-to-edit.svg')" alt="select element" class="ml-4" />
          <span>Select and element to edit</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import DragHandleIcon from '../icons/DragHandle.vue'
import BzArrowUpIcon from '../icons/ArrowUp.vue'
import BzArrowDownIcon from '../icons/ArrowDown.vue'
import ButtonEditor from './ButtonEditor.vue'
export default {
  name: 'EditorPanel',
  components: { ButtonEditor, BzArrowDownIcon, BzArrowUpIcon, DragHandleIcon },
  data() {
    return {
      expand: true,
      editor: 'button',
      editors: {
        button: 'button'
      },
      selectedElement: null
    }
  }
}
</script>

<style lang="scss" scoped>
.bz-editor-panel-root {
  position: relative;

  .editor-body {
    position: fixed;
    right: 240px;
    bottom: 10px;
    background-color: white;
    border: solid 1px #00000034;
    border-radius: 4px;
    box-shadow: 0 0 4px 2px #00000012;
    padding: 15px;
    width: 320px;
    z-index: 100000;
  }
}
</style>
