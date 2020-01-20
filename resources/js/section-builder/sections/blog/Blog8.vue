<template>
  <div class="bz-section-container bz-sec--blog-8-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" :size="sectionSize">
      <bz-container>
        <bz-alignment :alignment="setting.layouts.alignment">
          <bz-title v-if="setting.elements.title" v-model="data.elements.title" />
          <bz-subtitle v-if="setting.elements.subtitle" v-model="data.elements.subtitle" />
          <bz-text v-if="setting.elements.description" v-model="data.elements.description" />
        </bz-alignment>
        <div class="tw-grid lg:tw-grid-cols-4 md:tw-grid-cols-2 tw-gap-6" style="min-height: 400px">
          <div v-if="mainBlog" class="tw-col-span-2">
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
              :cols="1"
              :shadow="false"
              :spacing="false"
            >
              <template v-slot="{ item }">
                <template v-if="item">
                  <div class="tw-grid md:tw-grid-cols-2 tw-mb-2">
                    <div class="tw-w-full">
                      <bz-aspect-view v-if="setting.listElements.image" :ratio="aspectRatio">
                        <bz-image v-model="item.image" resize-mode="full" />
                      </bz-aspect-view>
                    </div>
                    <div class="tw-w-full">
                      <div class="card-text-wrapper">
                        <bz-editor-link :href="item.url">
                          <bz-title v-if="setting.listElements.title" v-model="item.title" :background-color="'#ffffff'" />
                        </bz-editor-link>
                        <bz-text v-if="setting.listElements.description" v-model="item.description" />
                      </div>
                    </div>
                  </div>
                </template>
                <template v-else>
                  <div class="tw-w-full tw-h-full tw-mb-2 blog-empty">
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
import BzTitle from '../../components/section/BzTitle.vue'
import BzSubtitle from '../../components/section/BzSubtitle.vue'

import BzAspectView from '../../components/section/BzAspectView.vue'
import BzAlignment from '../../components/section/BzAlignment.vue'
import BlogBase from './BlogBase.vue'
import BzTextElement from '../../components/section/BzTextElement.vue'
import BzEditorLink from '../../components/section/BzEditorLink.vue'
import EmptyBlog from './EmptyBlog.vue'

export default {
  name: 'Blog8',
  components: {
    EmptyBlog,
    BzEditorLink,
    BzTextElement,
    BzAspectView,
    BzAlignment,
    BzItems,
    BzContainer,
    BzBackground,
    BzSubtitle,
    BzTitle
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
.bz-sec--blog-8-root {
  @import 'blog';
}
</style>
