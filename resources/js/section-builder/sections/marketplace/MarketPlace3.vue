<template>
  <div class="bz-section-container bz-sec--blog-3-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" :size="sectionSize">
      <bz-container>
        <div>
          <bz-alignment :alignment="setting.layouts.alignment">
            <bz-title v-if="setting.elements.title" v-model="data.elements.title" />
            <bz-subtitle v-if="setting.elements.subtitle" v-model="data.elements.subtitle" />
            <bz-text v-if="setting.elements.description" v-model="data.elements.description" />
          </bz-alignment>
        </div>
        <div class="md:tw-grid tw-grid-cols-2 tw-gap-6" style="min-height: 400px">
          <div v-if="mainBlog" class="tw-w-full">
            <bz-aspect-view v-if="setting.listElements.image" :ratio="aspectRatio">
              <bz-image v-model="mainBlog.image" resize-mode="full" />
              <!-- <img class="w-100 blog-image" :src="mainBlog.image.src" alt="image" :style="imageStyle" /> -->
            </bz-aspect-view>
            <div class="card-text-wrapper">
              <bz-title v-if="setting.listElements.title" v-model="mainBlog.tile" :background-color="'#ffffff'" />
              <!-- <h3 v-if="setting.listElements.title">{{ mainBlog.title }}</h3> -->
              <!-- <div v-if="setting.listElements.description" v-html="mainBlog.description"></div> -->
              <bz-text v-if="setting.listElements.description" v-model="mainBlog.description" />
            </div>
          </div>
          <div class="tw-w-full">
            <h3>More Featured</h3>
            <bz-divider :line="true" />
            <bz-items v-model="featuredBlogs" :add-item-title="isWebsite ? 'Show more blogs' : 'Add item'"
              :enable-sort="edit" :show-add-item="true" :cols="1" :shadow="false" :spacing="true">
              <template #default="{ item }">
                <template v-if="item">
                  <div class="card-text-wrapper">
                    <bz-editor-link :href="item.url">
                      <h3>{{ item.title }}</h3>
                    </bz-editor-link>
                    <div v-html="item.description"></div>
                  </div>
                </template>
                <template v-else>
                  <div class="tw-w-full tw-h-full blog-empty">
                    <bz-aspect-view :ratio="0.25">
                      <div class="tw-flex tw-flex-col">
                        <a :href="postBlogUrl" class="btn btn-info text-white">Add Post</a>
                      </div>
                    </bz-aspect-view>
                  </div>
                </template>
              </template>
            </bz-items>
          </div>
        </div>
        <empty-blog v-if="showEmptySection" :edit="edit" />
      </bz-container>
    </bz-background>
  </div>
</template>

<script>
import BzBackground from '../../components/section/BzBackground.vue'
import BzContainer from '../../components/section/BzContainer.vue'
import BzAlignment from '../../components/section/BzAlignment.vue'
import BzTitle from '../../components/section/BzTitle.vue'
import BzSubtitle from '../../components/section/BzSubtitle.vue'

import BzItems from '../../components/section/BzItems.vue'
import BzAspectView from '../../components/section/BzAspectView.vue'
import BzDivider from '../../components/section/BzDivider.vue'
import BzEditorLink from '../../components/section/BzEditorLink.vue'
import EmptyMarketPlace from './EmptyMarketPlace.vue'
import MarketPlaceBase from './MarketPlaceBase.vue'

export default {
  name: 'Blog3',
  components: { EmptyMarketPlace, BzEditorLink, BzDivider, BzAspectView, BzItems, BzSubtitle, BzTitle, BzAlignment, BzContainer, BzBackground },
  extends: MarketPlaceBase,
  computed: {
    mainMarketPlaces: {
      get() {
        return this.marketplacePosts[0]
      },
      set(val) {
        this.marketplacePosts[0] = val
      }
    },
    featuredMarketPlaces: {
      get() {
        return this.marketplacePosts.slice(1)
      },
      set(newValue) {
        if (newValue.length + 1 !== this.marketplacePosts.length) this.marketplacePosts = [this.mainBlog, ...newValue]
      }
    }
  }
}
</script>

<style lang="scss" scoped>
.bz-sec--blog-3-root {
  @import 'marketplace';
}
</style>
