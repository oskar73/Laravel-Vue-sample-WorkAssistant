<template>
  <div class="bz-section-container bz-sec--blog-6-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" :size="sectionSize">
      <bz-container>
        <bz-alignment :alignment="setting.layouts.alignment">
          <bz-title v-if="setting.elements.title" v-model="data.elements.title" />
          <bz-subtitle v-if="setting.elements.subtitle" v-model="data.elements.subtitle" />
          <bz-text v-if="setting.elements.description" v-model="data.elements.description" />
        </bz-alignment>
        <div class="tw-flex tw-items-center tw-justify-center tw-relative" style="min-height: 400px">
          <div class="sm:tw-grid lg:tw-grid-cols-3 md:tw-grid-cols-2 tw-w-full tw-gap-4">
            <div class="lg:tw-col-span-1">
              <bz-aspect-view v-if="setting.listElements.image" :ratio="aspectRatio">
                <bz-image v-model="mainBlog.image" resize-mode="full" />
                <!-- <img class="w-100 blog-image" :src="mainBlog.image.src" alt="image" :style="imageStyle" /> -->
              </bz-aspect-view>
            </div>
            <div class="lg:tw-col-span-1">
              <div class="card-text-wrapper">
                <bz-title v-if="setting.listElements.title" v-model="mainBlog.title" :background-color="'#ffffff'" />
                <!-- <h3 v-if="setting.listElements.title">{{ mainBlog.title }}</h3> -->
                <bz-text v-if="setting.listElements.description" v-model="mainBlog.description" />
                <!-- <div v-if="setting.listElements.description" v-html="mainBlog.description"></div> -->
                <bz-editor-link :href="mainBlog.url">
                  <div class="mt-3">
                    <bz-button v-model="data.elements.button" />
                  </div>
                </bz-editor-link>
              </div>
            </div>
            <div class="md:tw-col-span-2 lg:tw-col-span-1">
              <h3>More Featured</h3>
              <bz-divider :line="true" />
              <bz-items
                v-model="featuredBlogs"
                :add-item-title="isWebsite ? 'Show more blogs' : 'Add item'"
                :enable-sort="edit"
                :show-add-item="true"
                :cols="1"
                :shadow="false"
                :spacing="false"
              >
                <template #default="{ item }">
                  <template v-if="item">
                    <div class="card-text-wrapper">
                      <bz-editor-link :href="item.url">
                        <bz-title v-if="setting.listElements.title" v-model="item.title" :background-color="'#ffffff'" />
                      </bz-editor-link>
                      <bz-text v-if="setting.listElements.description" v-model="item.description" />
                    </div>
                  </template>
                  <template v-else>
                    <div class="tw-w-full tw-h-full blog-empty">
                      <bz-aspect-view :ratio="0.25">
                        <div class="tw-flex tw-flex-col">
                          <a :href="postBlogUrl" class="btn btn-info tw-text-white">Add Post</a>
                        </div>
                      </bz-aspect-view>
                    </div>
                  </template>
                </template>
              </bz-items>
            </div>
          </div>
          <empty-blog v-if="showEmptySection" :edit="edit" />
        </div>
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
import BlogBase from './BlogBase.vue'
import BzDivider from '../../components/section/BzDivider.vue'
import BzButton from '../../components/section/BzButton.vue'
import BzEditorLink from '../../components/section/BzEditorLink.vue'
import EmptyBlog from './EmptyBlog.vue'

export default {
  name: 'Blog6',
  components: {
    EmptyBlog,
    BzEditorLink,
    BzButton,
    BzDivider,
    BzAspectView,
    BzItems,

    BzSubtitle,
    BzTitle,
    BzAlignment,
    BzContainer,
    BzBackground
  },
  extends: BlogBase,
  computed: {
    mainBlog: {
      get() {
        return this.blogPosts[0]
      },
      set(val) {
        this.blogPosts[0] = val
      }
    },
    featuredBlogs: {
      get() {
        return this.blogPosts.slice(1)
      },
      set(newValue) {
        if (newValue.length + 1 !== this.blogPosts.length) this.blogPosts = [this.mainBlog, ...newValue]
      }
    }
  }
}
</script>

<style lang="scss" scoped>
.bz-sec--blog-6-root {
  @import 'blog';
}
</style>
