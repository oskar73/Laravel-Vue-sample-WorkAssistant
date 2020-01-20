<template>
  <modal :classes="['navigation-builder-modal']" name="navigationBuilder" @closed="onClose">
    <div style="background-color: rgb(246, 246, 246)" class="d-flex p-3 justify-content-between">
      <h3>Edit Navigations</h3>
      <div class="cursor-pointer" @click.prevent="onClose()">
        <i class="mdi mid-close"></i>
      </div>
    </div>
    <div v-if="data" class="my-3 overflow-auto px-2" style="height: calc(100% - 120px)">
      <Tree v-model="data" :fold-all-after-mounted="true">
        <template v-slot="{ node, index, path, tree }">
          <div class="children-item">
            <div v-if="showExpandIcon(tree, path)" class="expand-icon" @click.prevent="tree.toggleFold(node, path)">
              <bz-expand-icon :size="18" />
            </div>
            <div class="d-flex mt-1">
              <bz-input v-model="node.name" label="Menu Name" :height="35" />
            </div>
            <div class="children-image">
              <img v-if="tree.getNodeByPath(path).image" :src="tree.getNodeByPath(path).image.src" />
            </div>
            <div class="children-icon">
              <i v-if="tree.getNodeByPath(path).icon" :class="tree.getNodeByPath(path).icon" style="padding-bottom: 2px" class="mr-1"></i>
            </div>
            <div class="action-buttons">
              <div class="action-button-item" @click.prevent="addNewSubmenu(node, path, tree)">
                <add-circle-icon :size="18" />
                Add sub item
              </div>
              <div class="action-button-item" @click.prevent="openIconSelector(path, tree)">
                <dvr-icon :size="18" />
              </div>
              <div class="action-button-item" @click.prevent="openAttachLinkModal(path, tree)">
                <i class="mdi mdi-link"></i>
              </div>
              <div class="action-button-item" @click.prevent="openSelectImage(path, tree)">
                <image-icon :size="18" />
              </div>
              <div class="action-button-item delete" @click.prevent="deleteNavItem(path, tree)">
                <i class="mdi mid-delete"></i>
              </div>
            </div>
          </div>
        </template>
      </Tree>
      <div class="action-button-item" @click.prevent="addMainMenu()">
        <add-circle-icon :size="18" />
        Add main menu
      </div>
    </div>
    <hr style="margin-top: auto" />
    <div class="w-100 d-flex justify-content-end pb-2">
      <button class="btn bz-btn-default mr-4 d-flex align-items-center" @click="onConfirm">
        <b>Save</b>
      </button>
    </div>
  </modal>
</template>

<script>
import BzInput from '../page/BzInput.vue'
// TODO: need to replace
import { Tree, Draggable, Fold } from 'he-tree-vue'
import modalMixin from '../../mixins/modalMixin'
import ImageIcon from '../icons/Image'
import BzExpandIcon from '../icons/ExpandIcon'
import AddCircleIcon from '../icons/AddCircle'
import DvrIcon from '../icons/Dvr'
import { mapMutations } from 'vuex'
export default {
  name: 'NavigationBuilderModal',
  components: { DvrIcon, AddCircleIcon, BzExpandIcon, ImageIcon, BzInput, Tree: Tree.mixPlugins([Draggable, Fold]) },
  mixins: [modalMixin],
  methods: {
    refresh() {
      this.data = Object.assign([], this.data)
    },
    showExpandIcon(tree, path) {
      const nodeData = tree.getNodeByPath(path)
      return nodeData.children && nodeData.children.length > 0
    },
    addMainMenu() {
      this.data.push({ name: 'New Menu', children: [] })
      this.refresh()
    },
    addNewSubmenu(node, path, tree) {
      const nodeData = tree.getNodeByPath(path)
      if (nodeData.children) {
        nodeData.children.push({ name: 'new item', $folded: false, children: [] })
      } else {
        nodeData.children = [{ name: 'new item', $folded: false, children: [] }]
      }
      this.refresh()
      tree.unfold(node, path)
    },
    openIconSelector(path, tree) {
      this.$store.commit('openModal', {
        name: 'iconSelector',
        onChange: (icon) => {
          const node = tree.getNodeByPath(path)
          node.icon = icon
          this.refresh()
        }
      })
    },
    openAttachLinkModal(path, tree) {
      const node = tree.getNodeByPath(path)
      this.openModal({
        name: 'attachLinkModal',
        data: node.link,
        onChange: (link) => {
          const node = tree.getNodeByPath(path)
          node.link = link
          this.refresh()
        }
      })
    },
    openSelectImage(path, tree) {
      this.openModal({
        name: 'selectImage',
        onChange: ({ url }) => {
          const node = tree.getNodeByPath(path)
          node.image = {
            src: url
          }
          this.refresh()
        }
      })
    },
    deleteNavItem(path, tree) {
      tree.removeNodeByPath(path)
      this.refresh()
    },
    ...mapMutations(['openModal'])
  }
}
</script>

<style lang="scss">
// Document: https://he-tree-vue.phphe.com/api.html
// https://he-tree-vue.phphe.com/guide.html
@import 'he-tree-vue/dist/he-tree-vue.css';
@import 'style';

.vm--modal.navigation-builder-modal {
  .tree-placeholder {
    .tree-placeholder-node {
      height: 50px !important;
      background-color: #00000012 !important;
    }
  }

  .he-tree {
    .tree-node {
      border: 1px solid #ccc;
      margin-bottom: 5px;
      padding: 0;
      background-color: white;
    }

    &.hidden {
      .tree-node {
        display: none;
      }
    }
  }

  .expand-icon {
    cursor: pointer;
    padding: 4px;
    margin-right: 10px;

    &:hover {
      background-color: #00000012;
    }
  }

  .children-item {
    cursor: move;
    display: flex;
    padding: 2px 4px;
    align-items: center;

    .children-image {
      width: 50px;
      height: 50px;
      margin-left: 20px;

      img {
        width: 100%;
        height: 100%;
        object-fit: cover;
      }
    }

    .children-icon {
      margin-left: 20px;
      font-size: 24px;
    }

    .action-buttons {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-left: auto;
    }
  }

  .action-button-item {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 4px;
    border-radius: 4px;
    border: solid 1px #80808012;
    cursor: pointer;
    background-color: #00000012;
    margin: 0 2px;

    &:hover {
      border: solid 1px var(--bz-edit-active);
      color: var(--bz-edit-active);
      background-color: white;

      &.delete {
        border: solid 1px var(--bz-edit-danger);
        color: var(--bz-edit-danger);
      }
    }
  }
}
</style>
