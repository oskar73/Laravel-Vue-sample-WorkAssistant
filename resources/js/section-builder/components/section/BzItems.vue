<template>
  <div class="tw-w-full bz-drag-drop-items-container">
    <div class="list no-gutters" :class="{ 'bz-grid': grid, sort: enableSort, 'bz-row': !grid }" :style="{ flexDirection: reverse ? 'row-reverse' : 'row' }">
      <template v-if="enableSort">
        <draggable v-if="renderComponent" v-model="itemsData" class="tw-w-full draggable sm:tw-grid" :class="{ ['lg:tw-grid-cols-' + cols]: true, 'sm:tw-grid-cols-2': cols > 1 }">
          <template #item="{ item, index }">
            <div
              class="tw-full sm:tw-h-full tw-relative tw-group item"
              :class="{ spacing: spacing, 'hover:tw-outline hover:tw-outline-1 hover:tw-outline-blue-500 hover:tw-z-50': edit }"
            >
              <div v-if="edit" class="tw-absolute tw-right-0 top-0 tw-bg-blue-500 px-2 py-1 tw-hidden group-hover:tw-flex tw-z-10">
                <div class="tw-mr-1">
                  <i class="mdi mdi-drag-horizontal-variant tw-text-white"></i>
                </div>
                <div @click.prevent="removeItem(item)">
                  <i class="mdi mdi-delete !tw-text-white"></i>
                </div>
              </div>
              <div class="tw-w-full sm:tw-h-full" :class="{ 'tw-shadow': spacing && shadow }" :style="cardStyle(item)">
                <slot :item="item" :index="index" />
              </div>
            </div>
          </template>
        </draggable>
      </template>
      <div class="tw-w-full draggable sm:tw-grid" :class="{ ['lg:tw-grid-cols-' + cols]: true, 'sm:tw-grid-cols-2': cols > 1 }" v-else>
        <div v-for="(item, index) in itemsData" :key="index" class="bz-item-content-wrapper item" :class="getColsClass(index)" :style="getStyles(index)">
          <div class="tw-w-full sm:tw-h-full" :class="{ spacing: spacing }">
            <div class="tw-w-full sm:tw-h-full" :class="{ 'tw-shadow': spacing && shadow }" :style="cardStyle(index)">
              <slot :item="item" :index="index" />
            </div>
          </div>
        </div>
      </div>
    </div>
    <button v-if="isBuilder && edit" class="btn-add-item" @click.prevent="handleAddItem()">
      {{ addItemTitle || 'Add Item' }}
    </button>
  </div>
</template>

<script>
import Draggable from '@/public/draggable'
import { cloneDeep } from 'lodash'
import elementMixin from '../../mixins/elementMixin'
import rerenderMixin from '@/section-builder/mixins/rerenderMixin'

export default {
  name: 'BzItems',
  components: {
    Draggable
  },
  mixins: [elementMixin, rerenderMixin],
  props: {
    modelValue: {
      type: Array,
      default: () => {
        return []
      }
    },
    cols: {
      type: Number,
      default: 3
    },
    grid: {
      type: Boolean,
      default: false
    },
    shadow: {
      type: Boolean,
      default: true
    },
    spacing: {
      type: Boolean,
      default: true
    },
    categoryName: {
      type: String,
      default: 'gallery'
    },
    styles: {
      type: [Function, Object],
      default: () => {
        return {}
      }
    },
    reverse: {
      type: Boolean,
      default: false
    },
    showAddItem: {
      type: Boolean,
      default: true
    },
    addItem: {
      type: Function,
      default: null
    },
    addItemTitle: {
      type: String,
      default: ''
    },
    enableSort: {
      type: Boolean,
      default: true
    }
  },
  data() {
    return {
      itemsData: []
    }
  },
  watch: {
    itemsData: {
      deep: true,
      handler(value) {
        this.$emit('update:modelValue', value)
      }
    },
    modelValue() {
      if (this.modelValue !== this.itemsData) {
        this.itemsData = this.modelValue || []
        // this.forceRerender()
      }
    }
  },
  created() {
    this.itemsData = this.modelValue || []
  },
  methods: {
    cardStyle(item) {
      if (typeof this.styles === 'function') {
        return this.styles(item).card
      } else {
        return this.styles.card
      }
    },
    getColsClass() {
      if (!this.grid) {
        return { ['sm:tw-col-span-' + 12 / this.cols]: true }
      }
    },
    getStyles(index) {
      if (this.grid) {
        if (index % 10 === 9) {
          return {
            gridRow: `${(Math.floor(index / 10) + 1) * 4 - 1} / span 2`
          }
        }
      }
    },
    handleAddItem() {
      if (typeof this.addItem === 'function') {
        this.addItem()
      } else {
        const newItem = cloneDeep(this.itemsData[this.itemsData.length - 1])
        if (newItem.image) {
          newItem.image.style = {}
        }
        this.itemsData.push(newItem)
      }
      this.forceRerender()
    },
    removeItem(item) {
      if (this.itemsData.length > 1 && this.edit) {
        const index = this.itemsData.indexOf(item)
        this.itemsData.splice(index, 1)
        this.forceRerender()
      }
    }
  }
}
</script>

<style lang="scss">
.bz-drag-drop-items-container {
  position: relative;

  .bz-item-content-wrapper[class*='col-'] {
    padding-top: 0 !important;
    padding-bottom: 0 !important;
  }

  .spacing {
    padding: 10px;
  }

  .bz-shadow {
    box-shadow: rgb(0 0 0 / 24%) 0px 0.5rem 1rem -0.25rem !important;
  }

  .add-item {
    position: absolute;
    margin-top: 5px;
  }

  .btn-add-item {
    background-color: #0069d9;
    border: none;
    outline: none;
    color: white;
    border-radius: 4px;
    padding: 5px 10px;
    margin-top: 10px;
    position: absolute;
    bottom: -40px;
    left: calc(50% - 30px);
    z-index: 100;

    &:hover {
      background-color: #014fa5;
    }
  }
}
</style>
