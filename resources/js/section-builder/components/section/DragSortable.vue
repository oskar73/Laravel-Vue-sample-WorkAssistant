<template>
  <div ref="itemRef" class="item" :class="{ dragging: dragging }">
    <div ref="draggingItem" class="drag-sortable" :class="{ dragging: dragging, reversing: reversing, edit }">
      <div class="handler" @mousedown.prevent="onMouseDown">
        <drag-handle fill-color="white" />
      </div>
      <div class="remove !tw-bg-white" @click.prevent="remove(index)">
        <i class="mdi mdi-delete tw-text-2xl"></i>
      </div>
      <slot />
    </div>
  </div>
</template>

<script>
import DragHandle from '../icons/DragHandle.vue'
import elementMixin from '../../mixins/elementMixin'
export default {
  name: 'DragSortable',
  components: {
    DragHandle
  },
  mixins: [elementMixin],
  props: {
    modelValue: {
      type: Array,
      required: true
    },
    index: {
      type: Number,
      required: true
    }
  },
  data() {
    return {
      dragging: false,
      reversing: false,
      translateX: 0,
      translateY: 0,
      targetIndex: 0
    }
  },
  watch: {
    modelValue: {
      immediate: true,
      handler() {
        if (!Object.keys(this.modelValue).includes(this.index.toString())) {
          const newValue = this.modelValue
          newValue[this.index] = this
          this.$emit('update:modelValue', newValue)
        }
      }
    }
  },
  mounted() {
    document.addEventListener('mousemove', this.handleMouseMove)
    document.addEventListener('mouseup', this.mouseupHandler)
  },
  beforeDestroy() {
    document.removeEventListener('mousemove', this.handleMouseMove)
    document.removeEventListener('mouseup', this.mouseupHandler)
  },
  methods: {
    moveDragItem() {
      this.$refs.draggingItem.style.transform = `translate(${this.translateX}px, ${this.translateY}px)`
    },
    remove(index) {
      delete this.value[Object.keys(this.value).length - 1]
      this.$emit('update:modelValue', Object.assign({}, this.value))
      this.$emit('remove', index)
      this.clearDragData()
    },
    distance(draggingItem, targetItem) {
      const rect1 = draggingItem.$refs.draggingItem.getBoundingClientRect()
      const p1 = {
        x: rect1.left + rect1.width / 2,
        y: rect1.top + rect1.height / 2
      }
      const rect2 = targetItem.$refs.itemRef.getBoundingClientRect()
      const p2 = {
        x: rect2.left + rect2.width / 2,
        y: rect2.top + rect2.height / 2
      }
      return Math.sqrt(Math.pow(p2.x - p1.x, 2) + Math.pow(p2.y - p1.y, 2))
    },
    onMouseDown() {
      this.dragging = true
      this.targetIndex = this.index
    },
    mouseupHandler() {
      this.handleDragStop()
    },
    clearDragData() {
      for (const index in this.modelValue) {
        const item = this.modelValue[index]
        item.translateX = 0
        item.translateY = 0
        item.moveDragItem()
      }
    },
    handleDragStop() {
      if (this.dragging) {
        this.dragging = false
        if (this.targetIndex !== this.index) {
          this.$emit('sortEnd', {
            oldIndex: this.index,
            newIndex: this.targetIndex
          })
        }
        this.clearDragData()
      }
    },
    handleMouseMove(e) {
      if (this.edit && this.dragging) {
        this.translateX += e.movementX / window.contentScale
        this.translateY += e.movementY / window.contentScale
        this.moveDragItem()
        this.checkTargetItem()
      }
    },
    checkTargetItem() {
      let closest = this
      let shortestDistance = 0
      for (const index in this.modelValue) {
        const item = this.modelValue[index]
        const distance = this.distance(this, item)
        if (shortestDistance === 0 || shortestDistance > distance) {
          closest = item
          shortestDistance = distance
        }
      }

      for (const index in this.modelValue) {
        if (Number(index) !== this.index && Number(index) !== closest.index) {
          const item = this.modelValue[index]
          item.translateX = 0
          item.translateY = 0
          item.moveDragItem()
        }
      }

      const rootRect = this.$refs.itemRef.getBoundingClientRect()
      if (
        (Math.abs(this.translateX) > rootRect.width / 2 || Math.abs(this.translateY) > rootRect.height / 2) &&
        Math.abs(shortestDistance) < Math.max(rootRect.width, rootRect.height) &&
        closest.index !== this.index
      ) {
        const closestRect = closest.$refs.itemRef.getBoundingClientRect()
        closest.translateX = (rootRect.left - closestRect.left) / window.contentScale
        closest.translateY = (rootRect.top - closestRect.top) / window.contentScale
        closest.moveDragItem()
        this.targetIndex = closest.index
      } else {
        this.targetIndex = this.index
      }
    }
  }
}
</script>

<style lang="scss">
.list {
  position: relative;

  .item {
    position: relative;
    &.dragging {
      background-color: #80808034;
    }
  }
}

.drag-sortable {
  -webkit-touch-callout: none;
  -webkit-user-select: none;
  user-select: none;
  transition: margin 0.2s linear;
  outline: solid 2px transparent;
  position: relative;
  width: 100%;
  height: 100%;
  z-index: 1;

  .remove,
  .handler {
    width: 35px;
    height: 30px;
    position: absolute;
    top: -30px;
    display: flex;
    justify-content: center;
    align-items: center;
    visibility: hidden;
  }

  .remove {
    right: 5px;
  }

  .handler {
    right: 40px;
  }

  &.edit {
    &:active,
    &:focus,
    &:hover {
      outline: solid 2px #0076df;
      border-radius: 4px;
      color: black;
      z-index: 2;

      .remove,
      .handler {
        visibility: visible;
        background-color: #0076df;
        transition: 0.5s;
        &:hover {
          background-color: #025bab;
        }
      }
    }
  }
}
.drag-sortable.reversing {
  // reserving animation
  //transition:  all 0.4s;

  &.dragging {
    box-shadow: 0 2px 10px 0 rgba(0, 0, 0, 0.2);
    transition: none !important;
  }
}
</style>
