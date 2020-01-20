<template>
  <div class="bz-section-container bz-sec--blog-7-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" :size="sectionSize">
      <bz-container>
        <bz-alignment :alignment="setting.layouts.alignment">
          <bz-title v-if="setting.elements.title" v-model="data.elements.title" />
          <bz-subtitle v-if="setting.elements.subtitle" v-model="data.elements.subtitle" />
          <bz-text v-if="setting.elements.description" v-model="data.elements.description" />
        </bz-alignment>
        <div class="tw-grid lg:tw-grid-cols-4 md:tw-grid-cols-2 tw-gap-6" style="min-height: 400px">
          <div class="tw-col-span-2">
            <bz-aspect-view v-if="setting.listElements.image" :ratio="aspectRatio">
              <bz-image v-model="mainBlog.image" resize-mode="full" />
            </bz-aspect-view>
            <div class="card-text-wrapper">
              <bz-editor-link :href="mainBlog.url">
                <bz-title v-if="setting.listElements.title" v-model="mainBlog.title" :background-color="'#ffffff'" />
              </bz-editor-link>
              <bz-text v-if="setting.listElements.description" v-model="mainBlog.description" />
            </div>
          </div>
          <div class="tw-col-span-2">
            <bz-items
              v-model="featuredBlogs"
              :add-item-title="isWebsite ? 'Show more blogs' : 'Add item'"
              :enable-sort="edit"
              :show-add-item="true"
              :cols="2"
              :shadow="false"
              :spacing="true"
            >
              <template #default="{ item }">
                <template v-if="item">
                  <div class="card-text-wrapper">
                    <bz-editor-link :href="item.url">
                      <bz-title v-if="setting.listElements.title" v-model="item.title" />
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
      </bz-container>
    </bz-background>
  </div>
</template>
<script>
import BzBackground from '../../components/section/BzBackground.vue'
import BzContainer from '../../components/section/BzContainer.vue'
import BzItems from '../../components/section/BzItems.vue'
import BzAspectView from '../../components/section/BzAspectView.vue'
import BlogBase from './BlogBase.vue'

import BzEditorLink from '../../components/section/BzEditorLink.vue'
import BzAlignment from '../../components/section/BzAlignment.vue'
import BzTitle from '../../components/section/BzTitle.vue'
import BzSubtitle from '../../components/section/BzSubtitle.vue'
import EmptyBlog from './EmptyBlog.vue'

export default {
  name: 'Blog7',
  components: {
    EmptyBlog,
    BzEditorLink,

    BzSubtitle,
    BzTitle,
    BzAlignment,
    BzAspectView,
    BzItems,
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
.bz-sec--blog-7-root {
  @import 'blog';
}
</style>
