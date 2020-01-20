<template>
  <div class="bz-section-container bz-sec--blog-5-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" :size="sectionSize">
      <bz-container>
        <bz-alignment :alignment="setting.layouts.alignment">
          <bz-title v-if="setting.elements.title" v-model="data.elements.title" />
          <bz-subtitle v-if="setting.elements.subtitle" v-model="data.elements.subtitle" />
          <bz-text v-if="setting.elements.description" v-model="data.elements.description" />
        </bz-alignment>
        <div class="tw-flex tw-items-center tw-justify-center tw-relative" style="min-height: 400px">
          <div class="lg:tw-grid tw-grid-cols-12 tw-w-full tw-gap-6">
            <div class="tw-col-span-5">
              <div v-if="mainBlog" class="tw-relative tw-w-full">
                <bz-aspect-view v-if="setting.listElements.image" :ratio="aspectRatio">
                  <bz-image v-model="mainBlog.image" resize-mode="full" />
                </bz-aspect-view>

                <div
                  class="tw-z-[2] tw-rounded main-blog-item tw-w-full"
                  :class="{ 'tw-absolute tw-top-1/2 -tw-translate-x-1/2 -tw-translate-y-1/2 tw-left-1/2 tw-px-4': setting.listElements.image }"
                >
                  <bz-alignment :alignment="setting.layouts.alignment">
                    <bz-editor-link :href="mainBlog.url" class="tw-w-full">
                      <bz-title v-if="setting.listElements.title" v-model="mainBlog.title" :background-color="'#ffffff'" />
                    </bz-editor-link>
                    <bz-text v-if="setting.listElements.description" v-model="mainBlog.description" />
                  </bz-alignment>
                </div>
              </div>
            </div>
            <div class="tw-col-span-7">
              <div class="tw-w-full">
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
                        <bz-alignment :alignment="setting.layouts.alignment">
                          <bz-editor-link :href="item.url" class="tw-w-full">
                            <bz-title v-if="setting.listElements.title" v-model="item.title" :background-color="'#ffffff'" />
                          </bz-editor-link>
                          <bz-text v-if="setting.listElements.description" v-model="item.description" />
                        </bz-alignment>
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
import BzEditorLink from '../../components/section/BzEditorLink.vue'
import EmptyBlog from './EmptyBlog.vue'

export default {
  name: 'Blog5',
  components: { EmptyBlog, BzEditorLink, BzAspectView, BzItems, BzSubtitle, BzTitle, BzAlignment, BzContainer, BzBackground },
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
.bz-sec--blog-5-root {
  @import 'blog';
  .main-blog-item {
    background-color: var(--bz-theme-background-gray);
  }
}
</style>
