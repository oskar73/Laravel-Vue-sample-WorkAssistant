<template>
  <div class="bz-section-container bz-sec--blog-11-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" :size="sectionSize">
      <bz-container>
        <div class="d-flex align-items-center justify-content-center position-relative" style="min-height: 400px">
          <!--          <carousel-->
          <!--            ref="carousel"-->
          <!--            :start-index="0"-->
          <!--            :animation-speed="600"-->
          <!--            :controls-visible="true"-->
          <!--            :display="setting.blog.display"-->
          <!--            :perspective="1"-->
          <!--            :disable3d="true"-->
          <!--            :space="365"-->
          <!--            :height="slideHeight"-->
          <!--          >-->
          <!--            <slide v-for="(postItem, index) of blogPosts" :key="index" :index="index">-->
          <!--              <div v-if="postItem" ref="blogPostItem" class="blog-post-item" style="pointer-events: none">-->
          <!--                <bz-aspect-view :ratio="aspectRatio">-->
          <!--                  <bz-image v-model="postItem.image" resize-mode="full" />-->
          <!--                </bz-aspect-view>-->
          <!--                <div class="card-text-wrapper">-->
          <!--                  <bz-editor-link :href="postItem.url">-->
          <!--                    <bz-title v-if="setting.listElements.title" v-model="postItem.title" :background-color="'#ffffff'" />-->
          <!--                  </bz-editor-link>-->
          <!--                  <bz-text v-if="setting.listElements.description" v-model="postItem.description" />-->
          <!--                </div>-->
          <!--              </div>-->
          <!--              <div v-else class="w-100" :style="{ height: slideHeight + 'px' }">-->
          <!--                <div class="col-12 h-100 blog-empty d-flex align-items-center">-->
          <!--                  <div class="m-auto">-->
          <!--                    <a :href="postBlogUrl" class="btn btn-info text-white">Add Post</a>-->
          <!--                  </div>-->
          <!--                </div>-->
          <!--              </div>-->
          <!--            </slide>-->
          <!--          </carousel>-->
          <empty-blog v-if="showEmptySection" :edit="edit" />
        </div>
      </bz-container>
    </bz-background>
  </div>
</template>

<script>
import BzBackground from '../../components/section/BzBackground.vue'
import BzContainer from '../../components/section/BzContainer.vue'
import BlogBase from './BlogBase.vue'
import BzAspectView from '../../components/section/BzAspectView.vue'
import BzEditorLink from '../../components/section/BzEditorLink.vue'

import BzTitle from '../../components/section/BzTitle.vue'
import EmptyBlog from './EmptyBlog.vue'

export default {
  name: 'Blog11',
  components: {
    EmptyBlog,
    BzEditorLink,
    BzAspectView,
    // Carousel,
    // Slide,
    BzContainer,
    BzBackground,

    BzTitle
  },
  extends: BlogBase,
  data() {
    return {
      slideHeight: 400
    }
  },
  mounted() {
    const self = this
    this.$nextTick(() => {
      if (this.$refs.blogPostItem) {
        this.$refs.blogPostItem.forEach((item) => {
          new ResizeObserver(function (entries) {
            const rect = entries[0].contentRect
            const height = rect.height
            if (height > self.slideHeight) {
              self.slideHeight = height
            }
          }).observe(item)
        })
      }
    })
  }
}
</script>

<style lang="scss" scoped>
.bz-sec--blog-11-root {
  @import 'blog';
  .blog-post-item {
    background-color: var(--bz-theme-background-gray);
  }
}
</style>
