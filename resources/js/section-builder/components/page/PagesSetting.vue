<template>
  <div class="pages_area z-index-999" :class="{ active: activeSlider === 'pages' }">
    <div class="py-2 px-3">
      <div class="row align-items-center">
        <div class="col-10">
          <h5 class="mb-0 text-dark">
            <b>Pages</b> <span>({{ filteredAllPages.length + 1 + (newPage ? 1 : 0) }}/{{ template.page_limit == -1 ? 50 : template.page_limit || 50 }})</span>
          </h5>
        </div>
        <div class="col-2 text-right">
          <span class="bz-close-section-area text-dark cursor-pointer fs-20" @click.prevent="closeSlider()">
            <i class="mdi mdi-close"></i>
          </span>
        </div>
      </div>
    </div>
    <div v-if="activeSlider === 'pages' && renderComponent" class="mt-3 px-3">
      <button v-if="allowedToCreateNewPage" class="btn bz-btn-default w-100" @click.prevent="openNewPageModal">New Page</button>
      <div class="w-100">
        <router-link to="/">
          <div class="page_item item cursor-pointer tw-items-center" :class="{ active: homePage.id === activePage.id }">
            {{ homePage.name }}
            <div class="icons tw-items-center">
              <home-icon :size="18" fill-color="#8080807f" />
              <dropdown-menu class="bz-dropdown show">
                <template #trigger>
                  <more-vert-icon />
                </template>
                <template #body>
                  <div class="bz-dropdown-menu-container">
                    <div class="bz-dropdown-menu" @click.stop.prevent="$store.commit('openPageSetting')">Settings</div>
                    <div class="bz-dropdown-menu" @click.stop.prevent="handleDuplicate">Duplicate</div>
                  </div>
                </template>
              </dropdown-menu>
            </div>
          </div>
        </router-link>

        <draggable v-model="allPages">
          <template #item="{ item: page }">
            <router-link v-if="page.type !== 'new-page' && page.url && page.url !== '/'" :to="page.url">
              <div class="page_item item cursor-pointer" :class="{ active: page.id === activePage.id }">
                {{ page.name }}
                <div v-if="page.type !== 'new-page'" class="icons">
                  <dropdown-menu class="bz-dropdown show">
                    <template #trigger>
                      <more-vert-icon />
                    </template>
                    <template #body>
                      <div class="bz-dropdown-menu-container tw-text-sm">
                        <div class="bz-dropdown-menu" @click="handleSettingsClick(page)">Settings</div>
                        <div class="bz-dropdown-menu" @click="handleDuplicate">Duplicate</div>
                        <div v-if="page.url" class="bz-dropdown-menu" @click.stop.prevent="handleDeletePage(page)">Delete</div>
                        <div v-if="isWebsite && page.url" class="bz-dropdown-menu" @click.stop.prevent="updatePageStatus(page.id)">
                          {{ pageStatus(page) ? 'Inactivate' : 'Activate' }}
                        </div>
                      </div>
                    </template>
                  </dropdown-menu>
                  <div class="d-flex align-items-center">
                    <drag-indicator-icon :size="18" fill-color="#8080807f" />
                  </div>
                </div>
              </div>
            </router-link>
          </template>
        </draggable>
        <router-link v-if="newPage" to="/new-page">
          <div class="page_item item cursor-pointer" :class="{ 'active tw-border tw-border-blue-500': activePage.id === newPage.id }">New Page</div>
        </router-link>
      </div>
    </div>
  </div>
</template>

<script>
import Draggable from '@/public/draggable'
import MoreVertIcon from '../icons/MoreVert.vue'
import HomeIcon from '../icons/HomeIcon.vue'
import DragIndicatorIcon from '../icons/DragIndicator.vue'
import { mapMutations } from 'vuex'
import { useRouter } from 'vue-router'
import DropdownMenu from 'v-dropdown-menu'
import builderMixin from '../../mixins/builderMixin'
import { deletePage, duplicatePage } from '@/section-builder/apis'
import rerenderMixin from '@/section-builder/mixins/rerenderMixin'
import PageContent from '@/section-builder/components/page/PageContent.vue'

export default {
  name: 'PageSetting',
  components: {
    DragIndicatorIcon,
    HomeIcon,
    MoreVertIcon,
    Draggable,
    DropdownMenu
  },
  mixins: [builderMixin, rerenderMixin],
  setup () {
    const router = useRouter()

    return {
      router
    }
  },
  computed: {
    allowedToCreateNewPage () {
      if (!this.template.page_limit || Number(this.template.page_limit) === -1) {
        return true
      }
      return this.allPages.length < Number(this.template.page_limit)
    },
    homePage () {
      return this.allPages.find((page) => !page.url || page.url === '/')
    },
    newPage () {
      return this.isTemplate && this.allPages.find((page) => page.type === 'new-page')
    },
    filteredAllPages: {
      get () {
        return this.allPages.filter((page) => page.type !== 'new-page' && page.url && page.url !== '/')
      },
      set (value) {
        const newPages = [this.homePage, ...value]
        if (this.newPage) {
          newPages.push(this.newPage)
        }
        this.allPages = newPages
      }
    }
  },
  mounted () {
    this.$store.subscribe((mutation, state) => {
      if (mutation.type === 'rerenderSettingPanel' && state.isRerenderSettingPanel) {
        this.forceRerender()
        this.$store.commit('rerenderSettingPanel', false)
      }
    })
  },
  methods: {
    handleSettingsClick(page) {
      this.$router.push({
        path: page.url
      })
      this.$store.commit('openPageSetting')
    },
    openNewPageModal () {
      this.$store.commit('openModal', {
        name: 'newPageModal'
      })
    },
    handlePageDragEnd () {
      this.updatePagesOrder()
    },
    handleDeletePage (page) {
      this.$dialog
        .confirm({
          title: 'Delete Page',
          description: 'Are you sure to delete the page?'
        })
        .then((res) => {
          if (res) {
            if (this.activePage.id === page.id) {
              this.router.push({
                path: '/',
                replace: true
              })
            }
            deletePage(page.id).then(() => {
              this.router.removeRoute(page.name)
              this.allPages = this.allPages.filter((p) => p.id !== page.id)
              this.forceRerender()
            })
          }
        })
    },
    pageStatus (page) {
      let active = page.active
      if (page.type === 'module') {
        active = active && page.data.nav_status
      }
      return active
    },
    handleDuplicate () {
      duplicatePage(this.activePage.id).then((res) => {
        const newPage = res.data.data
        this.allPages = [...this.allPages, newPage]
        this.router.addRoute({
          path: newPage.url,
          name: newPage.name,
          component: PageContent
        })
        this.forceRerender()
      })
    },
    ...mapMutations({
      updatePagesOrder: 'updatePagesOrder',
      updatePageStatus: 'updatePageStatus'
    })
  }
}
</script>

<style lang="scss">
$active: #0076df;

.pages_area {
  width: 300px;
  height: calc(100vh - 60px);
  position: fixed;
  left: 70px;
  top: 60px;
  background-color: rgb(239, 240, 241);
  z-index: 3;
  overflow: hidden;
  transform: translateX(-370px);
  //transition: transform 0.3s linear;

  .bz-close-section-area {
    font-size: 26px;

    &::after {
      width: calc(100vw - 370px);
      height: 100vh;
      position: fixed;
      top: 0;
      left: 370px;
      content: '';
      display: none;
    }
  }

  &.active {
    transform: translateX(0px);

    .bz-close-section-area {
      &::after {
        display: block;
      }
    }
  }

  .list {
    position: relative;
    /* position of list container must be set to `relative` */
  }

  /* dragging item will be added with a `dragging` class */
  /* so you can use this class to define the appearance of it */
  .list > *.dragging {
    box-shadow: 0 2px 10px 0 rgba(0, 0, 0, 0.2);
  }

  .page_item {
    width: 100%;
    background-color: white;
    border-radius: 4px;
    padding: 10px;
    height: 40px;
    margin-top: 10px;
    box-shadow: 0 0 6px 3px #00000023;
    position: relative;
    overflow: hidden;
    display: flex;
    align-items: center;

    &.active {
      border: solid 2px #2196f3;
    }

    &[draggable='true'] {
      box-shadow: 0 0 2px 1px #00000012;
      background-color: #f3f3f3;
    }

    .icons {
      display: flex;
      margin-left: auto;

      .more-vert {
        display: flex;
        color: #8080807f;
      }
    }

    &:hover {
      .icons {
        .more-vert {
          color: #555555;
        }
      }
    }

    .bz-dropdown-menu-container {
      right: 20px;
    }
  }
}
</style>
