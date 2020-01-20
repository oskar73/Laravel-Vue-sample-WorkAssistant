<template>
  <div class="bz-section-container bz-sec--blog-3-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" :size="sectionSize">
      <bz-container>
        <div class="lg:tw-grid tw-grid-cols-2">
          <div class="tw-w-full">
            <bz-alignment :alignment="setting.layouts.alignment">
              <bz-title v-if="setting.elements.title" v-model="data.elements.title" />
              <bz-subtitle v-if="setting.elements.subtitle" v-model="data.elements.subtitle" />
              <bz-text v-if="setting.elements.description" v-model="data.elements.description" />
            </bz-alignment>
          </div>
          <div class="tw-w-full">
            <div class="tw-flex tw-items-center tw-justify-center tw-relative" style="min-height: 400px">
              <div class="featured-blogs tw-w-full lg:tw-w-[500px] tw-ml-auto tw-rounded tw-p-5">
                <h2>Featured Blogs</h2>
                <bz-items
                  v-model="blogPosts"
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
                      <div class="h-100 w-100 bg-secondary">
                        <bz-aspect-view :ratio="0.2">
                          <div class="tw-flex tw-flex-col">
                            <a :href="postBlogUrl" class="btn btn-info text-white">Add Post</a>
                          </div>
                        </bz-aspect-view>
                      </div>
                    </template>
                  </template>
                </bz-items>
              </div>
              <empty-blog v-if="showEmptySection" :edit="edit" />
            </div>
          </div>
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
  name: 'Blog4',
  components: { EmptyBlog, BzEditorLink, BzAspectView, BzItems, BzSubtitle, BzTitle, BzAlignment, BzContainer, BzBackground },
  extends: BlogBase
}
</script>

<style lang="scss" scoped>
.bz-sec--blog-3-root {
  @import 'blog';
  .featured-blogs {
    background-color: var(--bz-theme-background-gray);
  }
}
</style>
