<template>
  <div ref="viewRef" class="bz-page bz-page-view-content" :class="{ open: activeSlider, close: !activeSlider }" @wheel="handleWheel">
    <div class="wrapper" :style="wrapperStyle">
      <div v-for="(list, key) of dataList" v-show="list.type !== 'module'" id="main_content" :key="key" ref="mainContent" class="main_content_area" :style="{ width: pageWidth }">
        <div class="sections-wrapper">
          <header class="page-browser-bar" :style="{ height: (35 / scale) * 0.8 + 'px' }" @click="handlePageBrowserBarClick(key)">
            <div class="fake-browser-bar text-black" :style="{ fontSize: 14 / scale + 'px' }">
              {{ list.name }}
            </div>
          </header>
          <div ref="headerSectionRef" class="bz-content-section" style="z-index: 2">
            <div ref="headerComponentRef" class="component-root">
              <component :is="header.name" :edit="true" :properties="header" />
            </div>
          </div>
          <draggable class="list-group" :move="onMove" :list="list[list.listName]" group="page" @change="log">
            <div v-for="(section, position) in list[list.listName]" :ref="'sectionRef' + key" :key="position" class="bz-content-section list-group-item">
              <div :ref="'componentRef' + key" class="component-root">
                <component :is="section.name" :position="position" :properties="section" />
              </div>
            </div>
          </draggable>
          <div ref="footerSectionRef" class="bz-content-section">
            <div ref="footerComponentRef" class="component-root">
              <component :is="footer.name" :edit="true" :properties="footer" />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import ZoomOut from '../icons/ZoomOut'
import templateMixin from '../../mixins/templateMixin'
import Draggable from '@/public/draggable'

export default {
  components: {
    ZoomOut,
    Draggable
  },
  mixins: [templateMixin],
  data() {
    return {
      dataList: [],
      resizeObservers: [],
      scale: 0.4,
      translateX: 0,
      translateY: 0
    }
  },
  computed: {
    wrapperStyle() {
      return {
        transform: `scale(${this.scale}) translateX(${this.translateX}px) translateY(${this.translateY}px)`
      }
    },
    pageWidth() {
      return window.innerWidth + 'px'
    },
    header() {
      return this.$store.state.header
    },
    footer() {
      return this.$store.state.footer
    }
  },
  mounted() {
    window.document.getElementsByTagName('html')[0].style.overflow = 'unset'
    this.dataList = this.allPages.map((page, index) => {
      return {
        name: page.name,
        type: page.type,
        listName: 'list' + index,
        ['list' + index]: page.sections
      }
    })
  },
  methods: {
    handlePageBrowserBarClick(pageIndex) {
      this.setActivePage({ index: pageIndex })
      this.closeSlider()
    },
    log: function (evt) {
      if (evt.removed) {
        this.allPages = this.allPages.map((page, index) => {
          return {
            ...page,
            sections: this.dataList[index][this.dataList[index].listName] || []
          }
        })
        this.$store.commit('saveAllPages')
      }
    },
    onMove(e) {},
    handleWheel(event) {
      if (event.ctrlKey === true) {
        event.preventDefault()
        if (event.deltaY > 0) {
          if (this.scale >= 2) {
            this.scale = 1
          } else {
            this.scale *= 1.5
          }
        } else {
          if (this.scale <= 0.1) {
            this.scale = 0.1
          } else {
            this.scale /= 1.5
          }
        }
        // const viewRect = this.$refs.viewRef.getBoundingClientRect()
        // const mouseX = event.clientX - viewRect.left
        // const mouseY = event.clientY - viewRect.top
        // this.translateX = mouseX * (1 - this.scale)
      }
    }
  }
}
</script>

<style lang="scss" scoped>
$active: #0076df;
$dark_active: #0067c1;
$danger: darkred;

.bz-page-view-content {
  position: relative;
  margin-left: 70px;
  width: calc(100% - 470px);
  margin-right: 300px;
  background-color: rgb(217, 222, 227);
  overflow: auto;
  transition: 0.5s;
  display: flex;
  animation-duration: 0.3s;
  height: calc(100vh - 60px);
  margin-bottom: 1000px;

  &.open {
    animation-fill-mode: forwards;
    animation-name: open;
  }

  &.close {
    animation-fill-mode: forwards;
    animation-name: close;
    animation-duration: 0.3s;
  }

  @keyframes open {
    from {
      margin-left: 70px;
    }
    to {
      margin-left: 470px;
    }
  }

  @keyframes close {
    from {
      margin-left: 370px;
      width: calc(100% - 370px);
    }
    to {
      margin-left: 70px;
      width: calc(100% - 70px);
    }
  }

  .wrapper {
    display: flex;
    transform-origin: left top;
    padding: 50px 50px 500px 50px;
    transition: all 0.1s;
    height: min-content;
    position: absolute;
    left: 0;
    top: 0;
  }

  .main_content_area {
    padding: 20px;
    display: flex;
    flex-direction: column;
    min-height: calc(100vh - 60px);
    transition: 0.8s ease-in-out;
    transition-delay: 0.1s;

    .bz-component-container {
      position: relative;
      z-index: 1;
      height: 100%;
    }

    .sections-wrapper {
      position: relative;
      width: 100%;
      transition: 0.8s;
      transition-delay: 0.4s;
      background-color: var(--bz-theme-background-color);

      .list-group {
        border-radius: 0 !important;

        .list-group-item {
          padding: 0 !important;
          background-color: #00000000 !important;

          &[draggable='true'],
          &.sortable-ghost,
          &.sortable-chosen {
            background-color: white;
            opacity: 1;
          }
        }
      }
    }

    .bz-content-section {
      position: relative;
      height: min-content;
      display: flex;
      justify-content: center;
      align-items: center;
      margin-top: 2px;
      outline: solid 2px transparent;

      .component-root {
        width: 100%;
      }

      &:hover {
        outline: solid 2px $active;
        transition: all 0.5s;
        cursor: move;
      }
    }
  }
}
</style>
