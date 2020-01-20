<template>
  <div class="tw-flex tw-flex-col">
    <div class="tw-relative tw-flex tw-bg-gray-200">
      <bz-aspect-view></bz-aspect-view>
    </div>
    <div class="tw-px-2">
      <p class="tw-mt-2">Product Title</p>
      <p class="tw-font-medium tw-text-violet-900">
        Product Standard Price
        <span class="tw-text-sm tw-text-gray-500 tw-line-through">Product Slashed Price</span>
      </p>

      <div class="tw-flex tw-items-center">
        <template v-for="star in [1, 2, 3, 4, 5]" :key="star">
          <i class="mdi mdi-star tw-text-yellow-400"></i>
        </template>
        <p class="text-sm text-gray-400"></p>
      </div>
      <!-- <div>
        <button class="tw-my-5 tw-h-10 tw-w-full tw-bg-violet-900 tw-text-white">Add to cart</button>
      </div> -->
    </div>
  </div>
</template>

<script>
import appMixin from '../../mixins/appMixin'
import BzAspectView from '@/section-builder/components/section/BzAspectView.vue'

export default {
  name: 'EmptyProduct',
  components: {
    BzAspectView
  },
  mixins: [appMixin],
  props: {
    edit: {
      type: Boolean,
      default: false
    }
  },
  computed: {
    postBlogUrl() {
      return this.template.webUrl + '/admin/blog/post/create'
    }
  }
}
</script>

<style lang="scss" scoped>
.blog-empty-section {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 400px;
  z-index: 2;

  .message {
    background-color: black;
    width: 280px;
    border-radius: 4px;
    padding: 10px;
    text-align: center;
  }

  .no-blog-posts {
    color: #444444;
    width: 500px;
    text-align: center;
    background-color: #efefef;
    padding: 10px;
    border-radius: 4px;
  }
}
</style>
