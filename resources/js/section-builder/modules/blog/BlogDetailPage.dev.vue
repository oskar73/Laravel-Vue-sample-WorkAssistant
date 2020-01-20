<script>
import { defineComponent } from 'vue'
import PageLayout from '@/section-builder/components/page/PageLayout.vue'

export default defineComponent({
  name: 'BlogDetailPage',
  components: { PageLayout },
  data() {
    return {
      p_modules: [],
      featured_posts: [],
      recent_posts: [],
      popular_posts: [],
      most_discussed_posts: [],
      categories: [],
      tags: [],
      random_posts: [],
      c_modal: false,
      c_cat: {
        img: null,
        name: null,
        slug: null,
        desc: null
      },
      t_modal: false,
      s_modal: false,
      isSubscribed: false,
      isFavorite: false,
      following: false
    }
  },
  methods: {
    toggleIsSubscribed() {
      this.isSubscribed = !this.isSubscribed
    },
    toggleIsFavorite() {
      this.isFavorite = !this.isFavorite
    },
    toggleFollowing() {
      this.following = !this.following
    }
  }
})
</script>

<template>
  <page-layout>
    <div class="tw-py-5">
      <div class="container-fluid mt-3">
        <div class="row">
          <div class="col-lg-8 offset-lg-2">
            <div>
              <div
                class="page-nav-info bg-theme"
              >
                <ul class="list-style-none text-center">
                  <li class="d-inline-block m-2">
                    <a href="javascript:void(0);" class="text-white" @click="c_modal = true">Categories</a>
                  </li>
                  <li class="d-inline-block m-2">
                    <a href="javascript:void(0);" class="text-white" @click="t_modal = true">Tags</a>
                  </li>
                  <li class="d-inline-block m-2">
                    <a href="{{route('blog.allPosts')}}" class="text-white">All Blogs</a>
                  </li>
                  <li class="d-inline-block m-2">
                    <a href="javascript:void(0);" class="text-white" @click="s_modal = !s_modal">Search</a>
                  </li>
                  <li v-if="p_modules.includes('blogAds')" class="d-inline-block m-2">
                    <a href="{{ route('blogAds.index') }}" class="text-white">Blog Ads</a>
                  </li>
                  <li v-if="p_modules.includes('advanced_blog')" class="d-inline-block m-2">
                    <a href="{{route('blog.package')}}" class="text-white">Blog Package</a>
                  </li>
                </ul>
                <template v-if="s_modal">
                  <div class="d-flex mb-3">
                    <div class="m-auto pb-3 blog_search_box">
                      <input id="blog_search_input" type="text" class="blog_search_input" placeholder="Type keyword..." />
                    </div>
                  </div>
                </template>
                <template v-if="c_modal">
                  <div class="c_modal_bg custom-scroll-h" style="min-height:100vh;">
                    <span
                      class="position-fixed h-cursor text-white font-size40 c_modal_close"
                      @click="c_modal = !c_modal"
                    >&times;</span>
                    <div class="container">
                      <div class="row">
                        <div class="col-md-3 order-2 order-md-1">
                          <h5 class="text-white h5">Categories</h5>
                          <ul class="list-style-none mb-0 custom-scroll-h c_modal_item_list_ul">
                            <li v-for="category in categories" :key="category.id">
                              <a
                                href="{{route('blog.category', $category->slug)}}"
                                :class="{ 'active': c_cat.slug == category.slug }"
                                @:mouseenter="c_cat.name = category.name; c_cat.slug = category.slug; c_cat.img = category.image; c_cat.desc = category.description"
                              >
                                {{ category.name }}
                              </a>
                            </li>
                          </ul>
                        </div>
                        <div class="col-md-9 order-1 order-md- mb-3">
                          <div>
                            <div class="text-center">
                              <h3 class="mb-3 text-white" x-text="c_cat.name"></h3>
                            </div>
                            <img :src="c_cat.img" class="w-100" />
                            <p x-text="c_cat.desc" class="mt-3 text-white"></p>
                            <hr class="border-top-1px-white" />
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </template>

                <template v-if="t_modal">
                  <div class="c_modal_bg custom-scroll-h" style="min-height:100vh;">
                    <span
                      class="position-fixed h-cursor text-white font-size40 c_modal_close"
                      @click="t_modal = !t_modal"
                    >&times;</span>
                    <div class="container">
                      <h5 class="text-white h5">Tags</h5>
                      <a
                        v-for="tag in tags" :key="tag.name" href="{{route('blog.tag', $tag->slug)}}"
                        class="btn rounded-0 btn-outline-info m-1"
                      >{{ tag.name }}</a>
                    </div>
                  </div>
                </template>
              </div>
            </div>
          </div>
        </div>
        <div class="row mt-5 blog_search_remove_section">
          <div class="col-lg-2">
            <div class="blog-ads-position-11125 text-right"></div>
          </div>
          <div class="col-lg-8">
            <div class="row">
              <div class="col-lg-8 pb-5">
                <h1 class="blog-title-lg mb-4">{{ post.title }}</h1>
                <div class="d-flex justify-content-between position-relative">
                  <div class="d-flex align-center">
                    <a href="{{route('blog.author', $post->user->id)}}" class="mr-2">
                      <img :src="post.user.avatar" :alt="post.user.name" class="rounded-circle width-50px" />
                    </a>
                    <span class="m-auto fs-15">
                      <a href="{{route('blog.author', $post->user->id)}}">{{ post.user.name }}</a>
                      <a href="javascript:void(0);" :class="{ 'follow_btn' : true, 'active': following }">{{ following ? 'Following': 'Follow' }}</a>
                      <br />
                      {{ post.created_at }}
                    </span>
                  </div>
                  <div class="share position-absolute bottom-0 right-0">
                    x-front.socialShare
                  </div>
                </div>
                <div class="blog-ads-position-11123"></div>
                <div class="mt-4 blog_post_detail_area">
                  <figure :data-href="post.image" class="progressive replace h-cursor f_post_item mb-3">
                    <img :src="post.image" :alt="post.title" class="preview" />
                  </figure>

                  {{ post.body }}

                  <br />

                  <a v-for="tag in post.approvedTags" :key="tag.name" href="{{route('blog.tag', $tag->slug)}}" class="btn btn-outline-success rounded-0 m-1 blog_tag_btn">{{ tag.name }}</a>
                  <br />
                  <div id="like-area" class="d-flex justify-content-between mt-3 position-relative">
                    <div id="post-like-count" :class="isFavorite? 'favorite_post' : ''">
                      <div class="clap_area" @click="toggleIsFavorite">
                        <svg width="33" height="33" viewBox="0 0 33 33">
                          <path d="M28.86 17.34l-3.64-6.4c-.3-.43-.71-.73-1.16-.8a1.12 1.12 0 0 0-.9.21c-.62.5-.73 1.18-.32 2.06l1.22 2.6 1.4 2.45c2.23 4.09 1.51 8-2.15 11.66a9.6 9.6 0 0 1-.8.71 6.53 6.53 0 0 0 4.3-2.1c3.82-3.82 3.57-7.87 2.05-10.39zm-6.25 11.08c3.35-3.35 4-6.78 1.98-10.47L21.2 12c-.3-.43-.71-.72-1.16-.8a1.12 1.12 0 0 0-.9.22c-.62.49-.74 1.18-.32 2.06l1.72 3.63a.5.5 0 0 1-.81.57l-8.91-8.9a1.33 1.33 0 0 0-1.89 1.88l5.3 5.3a.5.5 0 0 1-.71.7l-5.3-5.3-1.49-1.49c-.5-.5-1.38-.5-1.88 0a1.34 1.34 0 0 0 0 1.89l1.49 1.5 5.3 5.28a.5.5 0 0 1-.36.86.5.5 0 0 1-.36-.15l-5.29-5.29a1.34 1.34 0 0 0-1.88 0 1.34 1.34 0 0 0 0 1.89l2.23 2.23L9.3 21.4a.5.5 0 0 1-.36.85.5.5 0 0 1-.35-.14l-3.32-3.33a1.33 1.33 0 0 0-1.89 0 1.32 1.32 0 0 0-.39.95c0 .35.14.69.4.94l6.39 6.4c3.53 3.53 8.86 5.3 12.82 1.35zM12.73 9.26l5.68 5.68-.49-1.04c-.52-1.1-.43-2.13.22-2.89l-3.3-3.3a1.34 1.34 0 0 0-1.88 0 1.33 1.33 0 0 0-.4.94c0 .22.07.42.17.61zm14.79 19.18a7.46 7.46 0 0 1-6.41 2.31 7.92 7.92 0 0 1-3.67.9c-3.05 0-6.12-1.63-8.36-3.88l-6.4-6.4A2.31 2.31 0 0 1 2 19.72a2.33 2.33 0 0 1 1.92-2.3l-.87-.87a2.34 2.34 0 0 1 0-3.3 2.33 2.33 0 0 1 1.24-.64l-.14-.14a2.34 2.34 0 0 1 0-3.3 2.39 2.39 0 0 1 3.3 0l.14.14a2.33 2.33 0 0 1 3.95-1.24l.09.09c.09-.42.29-.83.62-1.16a2.34 2.34 0 0 1 3.3 0l3.38 3.39a2.17 2.17 0 0 1 1.27-.17c.54.08 1.03.35 1.45.76.1-.55.41-1.03.9-1.42a2.12 2.12 0 0 1 1.67-.4 2.8 2.8 0 0 1 1.85 1.25l3.65 6.43c1.7 2.83 2.03 7.37-2.2 11.6zM13.22.48l-1.92.89 2.37 2.83-.45-3.72zm8.48.88L19.78.5l-.44 3.7 2.36-2.84zM16.5 3.3L15.48 0h2.04L16.5 3.3z" fill-rule="evenodd">
                          </path>
                        </svg>
                      </div>
                      <span class="ml-3 blog_favorited_count">{{ favorite_count }}</span> claps
                    </div>

                    <div class="share position-absolute bottom-0 right-0">
                      x-front.socialShare
                    </div>
                  </div>
                  <hr />
                  <div class="d-flex justify-content-between">
                    <div class="d-flex align-center">
                      <a href="{{route('blog.author', $post->user->id)}}" class="mr-3">
                        <img src="{{$post->user->avatar()}}" alt="{{$post->user->name}}" class="rounded-circle width-80px" />
                      </a>
                      <span class="m-auto">
                        <small>Written By</small> <br />
                        <a href="{{route('blog.author', $post->user->id)}}">{{ post.user.name }}</a><b></b>
                      </span>
                    </div>
                    <div class="d-flex">
                      <div class="display-inline-block m-auto">
                        <a href="javascript:void(0);" :class="'follow_btn ' + following ? 'active': ''" @click="toggleFollowing">{{ following ? 'Following': 'Follow' }}</a>
                      </div>
                    </div>
                  </div>
                  <div class="blog-ads-position-11124"></div>
                  <hr />
                  <div class="mt-5">
                    <h5 class="p-area-title">You May Also Like</h5>
                    <div class="row">
                      <div v-for="r_post in random_posts" :key="r_post" class="col-sm-6">
                        <a href="{{route('blog.detail', $r_post->slug)}}">
                          <figure :data-href="r_post.image" class="progressive replace h-cursor f_post_item">
                            <img :src="r_post.image" :alt="r_post.title" class="preview" />
                            <div class="position-absolute text-white post-title-bg z-index-2 w-100 bottom-0 p-3">
                              <p class="blog_title_medium">{{ r_post.title }}</p>
                              <span class="post_small_info text-white">{{ r_post.created_at }}</span> &nbsp;&nbsp;
                              <span class="post_small_info"><i class="fa fa-eye"></i> </span>{{ r_post.view_count }} &nbsp;
                              <span class="post_small_info"><i class="fa fa-comment"></i> </span>{{ r_post.approved_comments_count }} &nbsp;
                              <span class="post_small_info"><i class="fa fa-heart"></i> </span>{{ r_post.favoriters_count }}
                            </div>
                          </figure>
                        </a>
                      </div>
                    </div>
                  </div>
                </div>

                <div id="comment_section" class="all_comment_area">
                </div>
                <div class="mt-4">
                  <h5 class="p-area-title">Leave a comment</h5>
                </div>

                <form action="{{route('blog.postComment', $post->id)}}" method="POST" class="post_comment_form">
                  <div id="leave_comment" class="leave_comment_area my-3">
                    <div id="comment" class="minh-100 comment_box comment default_comment"></div>
                    <div class="form-control-feedback error-comment"></div>
                    <div class="text-right">
                      <button class="btn btn-outline-success mt-2 smtBtn border-success" type="submit">Submit</button>
                    </div>
                  </div>
                </form>

                Please <a href="{{route('cart.login')}}?redirect={{url()->current()}}#leave_comment" class="underline">Login</a> to post comment here.
              </div>
              <div class="col-lg-4">
                <div class="blog-ads-position-11120"></div>

                <div>
                  <h5 class="p-area-title">Popular Posts</h5>
                  <div class="mb-5">
                    <a v-for="post in popular_posts" :key="post" href="{{route('blog.detail', $post->slug)}}" class="d-block mb-3">
                      <figure :data-href="post.image" class="progressive replace h-cursor f_post_item width-100px float-left">
                        <img :src="post.image" alt="post.title" class="preview" />
                      </figure>
                      <div class="ml-120px">
                        {{ post.title }}
                        <hr class="m-0" />
                        <span class="post_small_info">{{ post.created_at }}</span> &nbsp;&nbsp;
                        <span class="post_small_info"><i class="fa fa-eye"></i> </span>{{ post.view_count }} &nbsp;
                        <span class="post_small_info"><i class="fa fa-comment"></i> </span>{{ post.approved_comments_count }} &nbsp;
                        <span class="post_small_info"><i class="fa fa-heart"></i> </span>{{ post.favoriters_count }}
                      </div>
                      <div class="clearfix"></div>
                    </a>
                  </div>
                </div>

                <div class="mb-5">
                  <h5 class="p-area-title">Subscribe to this post.</h5>
                  <div class="mb-3">To get notification about update, comments to this post, please subscribe.</div>
                  <div class="text-center">
                    <a href="javascript:void(0);" :class="'post_subscribe_btn ' + isSubscribed ? 'active' : ''" @click="toggleIsSubscribed">
                      {{ isSubscribed ? 'Subscribed' : 'Subscribe' }} <i class="fa fa-bell"></i>
                      <span v-if="subscribers_count" class="font-size14">({{ subscribers_count }})</span> @endif
                    </a>
                  </div>
                </div>

                <div class="blog-ads-position-11121"></div>

                <div>
                  <h5 class="p-area-title">Most Discussed Posts</h5>
                  <div class="mb-5">
                    <a v-for="post in most_discussed_posts" :key="post" href="{{route('blog.detail', $post->slug)}}" class="d-block mb-3">
                      <figure :data-href="post.image" class="progressive replace h-cursor f_post_item width-100px float-left">
                        <img :src="post.image" :alt="post.title" class="preview" />
                      </figure>
                      <div class="ml-120px">
                        {{ post.title }}
                        <hr class="m-0" />
                        <span class="post_small_info">{{ post.created_at }}</span> &nbsp;&nbsp;
                        <span class="post_small_info"><i class="fa fa-eye"></i> </span>{{ post.view_count }} &nbsp;
                        <span class="post_small_info"><i class="fa fa-comment"></i> </span>{{ post.approved_comments_count }} &nbsp;
                        <span class="post_small_info"><i class="fa fa-heart"></i> </span>{{ post.favoriters_count }}
                      </div>
                      <div class="clearfix"></div>
                    </a>
                  </div>
                </div>

                <div class="blog-ads-position-11122"></div>
                <div class="blog-ads-position-11127"></div>
              </div>
            </div>
          </div>
          <div class="col-lg-2">
            <div class="blog-ads-position-11126"></div>
          </div>
        </div>
      </div>
      <div class="blog_append_section"></div>
    </div>
  </page-layout>
</template>

<style scoped lang="scss">

</style>
