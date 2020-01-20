<template>
  <div class="bz-section-root">
    <bzn-view :data="sectionData.data" />
    <div id="bz-section-builder-control-panel" v-drag-move class="components-list-root">
      <div class="d-flex align-items-center">
        <div class="handler">
          <drag-handle-icon />
        </div>
        <div class="expand-close" @click="expand = !expand">
          <bz-arrow-up-icon v-if="expand" />
          <bz-arrow-down-icon v-else />
        </div>
        <span>Controls</span>
      </div>
      <template v-if="expand">
        <div style="min-height: 350px">
          <hr />
          <div v-if="controlView === controlViews.view">
            <div class="component-item layout">
              <label>layouts</label>
              <div class="w-100 mt-1 d-flex flex-row align-items-center flex-wrap">
                <table class="component layout-item" @click="handleClickComponentItem(components.layouts[0])">
                  <tr>
                    <td></td>
                    <td></td>
                  </tr>
                </table>
                <table class="component layout-item ml-2" @click="handleClickComponentItem(components.layouts[1])">
                  <tr>
                    <td></td>
                  </tr>
                  <tr>
                    <td></td>
                  </tr>
                </table>
                <table class="component layout-item ml-2" @click="handleClickComponentItem(components.layouts[2])">
                  <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                  </tr>
                </table>
                <table class="component layout-item ml-2" @click="handleClickComponentItem(components.layouts[3])">
                  <tr>
                    <td></td>
                  </tr>
                  <tr>
                    <td></td>
                  </tr>
                  <tr>
                    <td></td>
                  </tr>
                </table>
              </div>
            </div>
          </div>
          <div v-if="controlView === null" class="d-flex flex-column align-items-center py-5">
            <img :src="asset('assets/img/icons/select-item-to-edit.svg')" alt="select element" class="ml-4" />
            <span>Select and element to edit</span>
          </div>
        </div>
      </template>
    </div>
  </div>
</template>

<script>
import DragHandleIcon from '../components/icons/DragHandle.vue'
import BzArrowDownIcon from '../components/icons/ArrowDown.vue'
import BzArrowUpIcon from '../components/icons/ArrowUp.vue'
import { cloneDeep } from 'lodash'
import BznView from './components/BznView.vue'

export default {
  name: 'SectionBuilder',
  components: { BznView, BzArrowUpIcon, BzArrowDownIcon, DragHandleIcon },
  data() {
    return {
      controlViews: {
        view: 'view'
      },
      controlView: null,
      expand: true,
      selectedElement: null,
      components: {
        layouts: [
          {
            name: 'layout',
            data: {
              children: [{ name: 'view' }, { name: 'view' }]
            }
          },
          {
            name: 'layout',
            data: {
              style: {
                direction: 'column'
              },
              children: [{ name: 'view' }, { name: 'view' }]
            }
          },
          {
            name: 'layout',
            data: {
              children: [{ name: 'view' }, { name: 'view' }, { name: 'view' }]
            }
          },
          {
            name: 'layout',
            data: {
              style: {
                direction: 'column'
              },
              children: [{ name: 'view' }, { name: 'view' }, { name: 'view' }]
            }
          }
        ]
      },
      sectionData: {
        data: {},
        background: {}
      }
    }
  },
  mounted() {
    eventBus.$on('bzn.element.selected', (element) => {
      this.selectedElement = element
      this.controlView = this.controlViews.view
    })
    eventBus.$on('bzn.element.cleared', (element) => {
      if (this.selectedElement === element) {
        this.selectedElement = null
        this.controlView = null
      }
    })
  },
  methods: {
    handleClickComponentItem(data) {
      if (this.controlView === this.controlViews.view) {
        this.selectedElement.viewData.data.child = cloneDeep(data)
        this.selectedElement.viewData = { ...this.selectedElement.viewData }
      }
    }
  }
}
</script>

<style lang="scss" scoped>
.bz-section-root {
  padding: 10px;
  position: relative;

  .components-list-root {
    position: absolute;
    right: 50px;
    top: 100px;
    background-color: white;
    border: solid 1px #00000034;
    border-radius: 4px;
    box-shadow: 0 0 4px 2px #00000012;
    padding: 15px;
    width: 240px;

    .handler {
      cursor: move;
      margin-right: 2px;
    }
    .expand-close {
      cursor: pointer;
      margin-right: 2px;
    }

    .component-item {
      display: flex;
      flex-direction: column;

      .component {
        cursor: pointer;
        width: 100%;
        height: 100%;
        border: dashed 1px #00000034;
      }

      &.layout {
        .layout-item {
          width: 44px;
          height: 40px;

          td {
            border: dashed 1px #00000034;
          }
        }
      }
    }
  }
}
</style>
