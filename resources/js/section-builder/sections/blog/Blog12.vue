<template>
  <div class="bz-section-container bz-sec--blog-12-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" :size="sectionSize">
      <bz-container>
        <div class="d-flex align-items-center justify-content-center position-relative" style="min-height: 400px">
          <div class="bz-col-12">
            <div class="bz-row">
              <div class="bz-col-8">
                <div v-if="mainBlog" class="main-blog-item">
                  <div class="bz-row">
                    <div class="bz-col-6">
                      <bz-aspect-view :ratio="aspectRatio">
                        <bz-image v-model="mainBlog.image" resize-mode="full" />
                      </bz-aspect-view>
                    </div>
                    <div class="bz-col-6">
                      <div class="card-text-wrapper">
                        <div class="mb-2">{{ mainBlog.visible_date }}</div>
                        <bz-title v-model="mainBlog.title" :background-color="'#ffffff'" />
                        <bz-text v-model="mainBlog.description" />
                        <bz-editor-link :href="mainBlog.url">
                          <div class="mt-3">
                            <bz-button v-model="data.elements.button" />
                          </div>
                        </bz-editor-link>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="bz-col-4">
                <h3>More Featured</h3>
                <bz-divider :line="true" />
                <bz-items v-model="featuredBlogs" :enable-sort="edit" :show-add-item="true" :cols="1" :shadow="false" :spacing="true">
                  <template v-slot="{ item }">
                    <template v-if="item">
                      <div class="card-text-wrapper mb-2">
                        <bz-editor-link :href="item.url">
                          <bz-title v-model="item.title" :background-color="'#ffffff'" />
                        </bz-editor-link>
                        <bz-text v-model="item.description" />
                      </div>
                    </template>
                    <template v-else>
                      <div class="h-100 w-100 blog-empty mb-2">
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
import BzItems from '../../components/section/BzItems.vue'
import BzAspectView from '../../components/section/BzAspectView.vue'
import BlogBase from './BlogBase.vue'
import BzDivider from '../../components/section/BzDivider.vue'
import BzButton from '../../components/section/BzButton.vue'
import BzEditorLink from '../../components/section/BzEditorLink.vue'
import BzTitle from '../../components/section/BzTitle.vue'
import EmptyBlog from './EmptyBlog.vue'

export default {
  name: 'Blog12',
  components: {
    EmptyBlog,
    BzEditorLink,
    BzButton,
    BzDivider,
    BzAspectView,
    BzItems,
    BzContainer,
    BzBackground,
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
    },
    mainBlogLink() {
      if (this.edit) {
        return 'javascript:void(0)'
      }
      const blogModule = this.$store.state.modules.blog
      if (!blogModule) {
        console.error('blog module does not exist')
        return '/'
      }

      const blogPage = blogModule.page
      if (!blogPage) {
        console.error('blog page does not exist')
        return '/'
      }
      return '/' + blogPage.url + '/detail/' + this.mainBlog.slug
    }
  }
}
</script>

<style lang="scss" scoped>
.bz-sec--blog-12-root {
  @import 'blog';

  .main-blog-item {
    background-color: var(--bz-theme-background-gray);
  }
}
</style>
