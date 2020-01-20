<template>
  <div class="bz-section-container bz-sec--blog-9-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" :size="sectionSize">
      <bz-container>
        <div class="tw-w-full" style="min-height: 400px">
          <bz-aspect-view :ratio="1 / 2">
            <div class="blog-item" :style="{ backgroundImage: `url(${mainBlog.image.src})` }">
              <div class="blog-content">
                <bz-editor-link :href="mainBlog.url" style="width: 100%; text-align: center">
                  <bz-title v-model="mainBlog.title" :background-color="'#ffffff'" />
                </bz-editor-link>
                <bz-text v-model="mainBlog.description" />
              </div>
            </div>
          </bz-aspect-view>
          <bz-items
            v-model="featuredBlogs"
            :add-item-title="isWebsite ? 'Show more blogs' : 'Add item'"
            :enable-sort="edit"
            :show-add-item="true"
            :cols="3"
            :shadow="false"
            :spacing="true"
          >
            <template v-slot="{ item }">
              <template v-if="item">
                <div class="tw-w-full">
                  <bz-aspect-view :ratio="aspectRatio">
                    <bz-image v-model="item.image" resize-mode="full" />
                  </bz-aspect-view>
                  <div class="card-text-wrapper">
                    <bz-editor-link :href="item.url">
                      <bz-title v-model="item.title" :background-color="'#ffffff'" />
                    </bz-editor-link>
                    <bz-text v-model="item.description" />
                  </div>
                </div>
              </template>
              <template v-else>
                <div class="tw tw-w-full mb-2">
                  <div class="col-12 blog-empty">
                    <bz-aspect-view :ratio="0.25">
                      <div class="tw-flex tw-flex-col">
                        <a :href="postBlogUrl" class="btn btn-info text-white">Add Post</a>
                      </div>
                    </bz-aspect-view>
                  </div>
                </div>
              </template>
            </template>
          </bz-items>
        </div>
        <empty-blog v-if="showEmptySection" :edit="edit" />
      </bz-container>
    </bz-background>
  </div>
</template>

<script>
import BlogBase from './BlogBase.vue'
import BzBackground from '../../components/section/BzBackground.vue'
import BzContainer from '../../components/section/BzContainer.vue'
import BzAspectView from '../../components/section/BzAspectView.vue'
import BzItems from '../../components/section/BzItems.vue'
import BzEditorLink from '../../components/section/BzEditorLink.vue'
import BzTitle from '../../components/section/BzTitle.vue'
import EmptyBlog from './EmptyBlog.vue'

export default {
  name: 'Blog9',
  components: {
    EmptyBlog,
    BzEditorLink,
    BzItems,
    BzAspectView,
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
    }
  }
}
</script>

<style lang="scss" scoped>
.bz-sec--blog-9-root {
  @import 'blog';
  .blog-item {
    width: 100%;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    background-size: cover;
    background-repeat: no-repeat;
  }
  .blog-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    background-color: #ffffffdf;
    padding: 20px;
    border-radius: 2px;
    * {
      color: black;
    }
  }
}
</style>
