<template>
  <div class="choose-logo-content row">
    <div class="col-md-4">
      <div class="my-2 category_area text-right p-2 border-bottom">
        <!--            <select id="category" class="form-control category_select" v-model="selectedCategory" @change="categoryChanges()">-->
        <!--              <option value="all">All Categories ({{ this.totalCount }})</option>-->
        <!--              <option :value="category.id" v-for="category in categories">{{category.name}} ({{category.active_logo_types_count}})</option>-->
        <!--            </select>-->
        <button class="mr-auto btn btn-outline-info compact_view text-uppercase" title="Tile View" @click.prevent="modalOpenFun">
          <i class="el-icon-menu"></i> View All <span>({{ totalCount }})</span>
        </button>
      </div>
      <div class="left-content logoItemContainer" @scroll="scrollLogotypes($event)">
        <transition name="slideLeft">
          <ul v-show="previewsIsReady" id="preview-items-list">
            <li v-for="(url, hash) in previews" :key="hash" :class="{ 'active-preview': hash === activeHash }" class="logo-preview-item">
              <a href="#" :data-hash="hash" class="design-preview-image" @click.prevent="clickPreview(url, hash)">
                <img :src="url" alt="logo" width="300" height="auto" style="margin:auto" />
                <logo-hover :ref="'designHover' + hash" :preview-url="url"></logo-hover>
              </a>
              <a :href="getEditUrl(hash)" class="edit-link-in-preview">Edit</a>
            </li>
          </ul>
        </transition>
      </div>
    </div>
    <div class="col-md-8 right-content">
      <design-preview></design-preview>
    </div>
    <div v-if="modalOpen" class="c_modal">
      <div class="c_modal_overlay" @click="modalOpen = !modalOpen"></div>
      <div class="c_modal_bg">
        <span class="c_modal_close" @click="modalOpen = !modalOpen">×</span>

        <button v-if="hasMore" class="btn load_more_btn text-uppercase" :disable="!isLoaded" @click.prevent="modalLoadMore">Load More <i class="el-icon-more-outline"></i></button>

        <div class="container custom_container custom-scroll-h position-relative" @scroll="scrollLogotypes($event)">
          <div class="modal_area row">
            <div v-for="(url, hash) in previews" :key="url" class="col-md-2 mb-3">
              <div :class="{ 'active-preview': hash === activeHash }" class="modal_logo_preview logoItemContainer" @click.prevent="clickPreview(url, hash)">
                <item-on-modal :url="url" :hash="hash" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import tippy from 'tippy.js'
import 'tippy.js/dist/tippy.css'
import appMixin from '../mixins/app-mixin'
import eventBus from '@/public/eventBus'
import ItemOnModal from './ItemOnModal.vue'
import axios from 'axios'
import LogoHover from './LogoHover.vue'
import DesignPreview from './elements/DesignPreview.vue'

export default {
  name: 'ChooseDesign',
  components: { DesignPreview, LogoHover, ItemOnModal },
  mixins: [appMixin],
  data() {
    return {
      previews: {},
      activeHash: null,
      isLoaded: false,
      totalCount: 0,
      previewsIsReady: false,
      hasMore: true,
      transitionName: 'slideRight',
      modalOpen: false,
      states: {
        component: {
          mounted: false
        },
        preview: {
          updated: false
        }
      }
    }
  },

  mounted() {
    this.loadPreviews().then((success) => {
      if (success) {
        this.setFirstPreview()
        this.initTippy()
      }
      this.$nextTick(() => {
        this.states.component.mounted = true
      })

      $('#preview-items-list').css('padding-bottom', $('#preview-footer').css('height'))
    })
  },
  methods: {
    modalOpenFun() {
      this.modalOpen = true
    },
    modalItemHover(e) {
      console.log(e)
    },
    loadPreviews() {
      // Previews not loaded
      this.isLoaded = false

      return new Promise((resolve) => {
        // Get logos array hash
        const loadedDesigns = Object.keys(this.previews)
        return axios
          .post(this.route('graphics.previews.list.get'), {
            loaded_designs: loadedDesigns
          })
          .then((response) => {
            console.log('choose-logo loadPreviews get preview list catch response', response)

            if (response.data.designs.length === 0) {
              this.hasMore = false
            }
            this.totalCount = response.data.total

            setTimeout(() => {
              this.isLoaded = true
              this.previewsIsReady = true
            })

            if (this.totalCount) {
              this.previews = Object.assign({}, this.previews, response.data.designs)
              resolve(true)
            } else {
              resolve(false)
            }
          })
          .catch((err) => {
            console.log('choose-logo loadPreviews get preview list catch error', err)
          })
      })
    },

    setFirstPreview() {
      const firstHash = Object.keys(this.previews)[0]
      const firstPreviewUrl = this.previews[Object.keys(this.previews)[0]]

      this.clickPreview(firstPreviewUrl, firstHash).then(() => {
        // Change transition after component loading
        this.$nextTick(() => {
          this.transitionName = 'fade'
        })
      })
    },

    clickPreview(previewUrl, hash) {
      this.modalOpen = false
      return new Promise((resolve, reject) => {
        this.setEditUrl(hash)
        this.setLogoToPreview(previewUrl)
        this.setActiveHash(hash)

        return resolve()
      })
    },

    setEditUrl(hash) {
      const editUrl = this.getEditUrl(hash)

      eventBus.$emit('logotype.set.edit-url', editUrl)
    },

    getEditUrl(hash) {
      return this.route('graphics.edit', hash)
    },

    setActiveHash(hash) {
      this.activeHash = hash
    },

    setLogoToPreview(preview) {
      this.states.preview.updated = true

      this.$nextTick(() => {
        // Replace preview
        eventBus.$emit('design.preview.set', {
          preview,
          modal: false
        })

        // Redraw component with animation
        this.states.preview.updated = false
      })
    },

    initTippy() {
      const self = this

      this.$nextTick(() => {
        tippy('a.design-preview-image', {
          duration: 0,
          placement: 'right',
          theme: 'light',
          appendTo: 'parent',
          hideOnClick: false,
          animation: 'fade',
          arrow: false,
          zIndex: 1,
          content(reference) {
            // Return preview image by hash
            const hash = reference.dataset.hash
            const ref = self.$refs['designHover' + hash]

            if (ref.length > 0) {
              return ref[0].$el
            }
          }
        })
      })
    },

    scrollLogotypes(evt) {
      const element = evt.target
      const offsetTrigger = 400
      const isMobile = this.isMobile()

      // Config with element props
      const config = {
        axis: isMobile ? 'scrollLeft' : 'scrollTop',
        offsetIndent: isMobile ? 'offsetWidth' : 'offsetHeight',
        scrollIndent: isMobile ? 'scrollWidth' : 'scrollHeight'
      }

      const isScrolled = Math.abs(element[config.offsetIndent] + element[config.axis] - element[config.scrollIndent]) < offsetTrigger

      if (isScrolled && this.isLoaded && this.hasMore) {
        this.loadPreviews().then(() => {
          this.initTippy()
        })
      }
    },
    modalLoadMore() {
      if (this.isLoaded && this.hasMore) {
        this.loadPreviews().then(() => {
          this.initTippy()
        })
      }
    }
  }
}
</script>

<style lang="scss" scoped>
.logo-preview-image {
  box-shadow: 0 0 15px -3px rgb(207, 207, 207);
  align-items: flex-start;
}

.logo-preview-item {
  .edit-link-in-preview {
    display: none;
    position: absolute;
    top: 6px;
    left: 10px;
    color: #8f8f8f;
  }

  &:hover {
    .edit-link-in-preview {
      display: block;
    }
  }
}

.choose-logo-content {
  display: flex;
  height: calc(100vh - 140px);
  margin: 70px 0 0;
}

.left-content {
  background-color: #fff;
  padding-top: 20px;
  padding-bottom: 20px;
  padding-right: 15px;
  overflow-y: hidden;
  overflow-x: auto;
  max-height: calc(100vh - 140px);

  @media (max-width: 767px) {
    order: 1;
    margin: 0 auto 0px;
  }

  @media (min-width: 768px) {
    overflow-y: auto;
    overflow-x: hidden;
  }
}

.left-content ul {
  margin: 0;
  padding: 0;
  white-space: nowrap;

  @media (min-width: 768px) {
    flex-wrap: wrap;
    list-style-type: none;
    display: flex;
    justify-content: space-around;
  }
}

.left-content li {
  width: 240px;
  border-radius: 5px;

  margin: 0 15px 0 0;
  padding: 0;
  display: inline-block;
  position: relative;

  @media (min-width: 768px) {
    width: 100%;
    display: block;
    margin: 0 0 15px;
  }

  &:hover {
    background-color: #f3f5ff;
  }

  .logo-preview-image {
    display: flex;
    padding: 10px;

    img {
      width: 100%;
      height: auto;
    }
  }
}

.active-preview {
  background-color: #f3f5ff;
}

.left-content li.selected {
  border: 2px solid #4d8ac9;
  box-shadow: 0 4px 14px rgba(0, 0, 0, 0.11);
}

.right-content {
  padding: 0;
  display: flex;
  overflow: auto;
}
.category_area {
  position: relative;
  .category_select {
    border-radius: 0;
    &:focus {
      outline: none;
      box-shadow: none;
      border-color: #4d8ac9;
    }
  }
  .compact_view {
    border-radius: 0;
    color: #4d8ac9;
    border-color: #4d8ac9;
    box-shadow: 1px 3px 5px #3333;
    transition: all 0.2s;
    &:hover,
    &:focus,
    &:active,
    &:visited {
      box-shadow: 1px 5px 8px #3333;
      background-color: #4d8ac9;
      color: #fff;
      outline: none !important;
      border: 1px solid #4d8ac9;
    }
    &:focus {
      box-shadow: none !important;
      background-color: #4d8ac9;
      color: #fff;
      outline: none !important;
      border: 1px solid #4d8ac9;
    }
  }
}
.custom-scroll-h {
  overflow: auto;
  &::-webkit-scrollbar {
    width: 10px;
    cursor: pointer;
  }
  &::-webkit-scrollbar-track {
    background: #f1f1f1;
  }
  &::-webkit-scrollbar-thumb {
    background: #888;
  }

  &::-webkit-scrollbar-thumb:hover {
    background: #555;
  }
}
.c_modal_bg {
  position: fixed;
  top: 10px;
  left: 50%;
  bottom: 10px;
  border: 1px solid #4d8ac9;
  box-shadow: 1px 3px 5px #3333;
  transform: translate(-50%, 0);
  width: 100%;
  max-width: 1350px;

  background-color: #fff;
  z-index: 99999;
  padding-top: 100px;
  padding-bottom: 100px;
  margin: auto;
  display: flex;
  justify-content: center;
  transition: all 0.5s;
  .c_modal_close {
    position: fixed;
    font-size: 40px;
    color: #000;
    top: 20px;
    right: 20px;
    z-index: 9999;
    line-height: 25px;
    cursor: pointer !important;
  }
  .modal_logo_preview {
    background-color: #fff;
    cursor: default;
    box-shadow: 0 0 15px -3px #cfcfcf;
    padding: 10px;
    border: 1px solid #4d8ac9;
  }
}

.c_modal_overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #333a;
  z-index: 9998;
}

.custom_container {
  max-width: 1350px;
}
.load_more_btn {
  border-radius: 0;
  position: fixed;
  top: 50px;
  left: 50%;
  transform: translate(-50%, 0);
  z-index: 9999;
  color: #4d8ac9;
  border-color: #4d8ac9;
  box-shadow: 1px 3px 5px #3333;
  transition: all 0.2s;
  &:hover,
  &:active,
  &:visited {
    box-shadow: 1px 5px 8px #3333 !important;
    background-color: #4d8ac9;
    color: #fff;
    outline: none !important;
    border: 1px solid #4d8ac9;
  }
  &:focus {
    box-shadow: none !important;
    background-color: #4d8ac9;
    color: #fff;
    outline: none !important;
    border: 1px solid #4d8ac9;
  }
}
</style>
