<script>
import { defineComponent } from 'vue'
import PageLayout from '@/section-builder/components/page/PageLayout.vue'

export default defineComponent({
  name: 'BlogPage',
  components: { PageLayout },
  data() {
    return {
      p_modules: [],
      featured_posts: [],
      recent_posts: [],
      popular_posts: [],
      most_discussed_posts: [],
      c_modal: false,
      c_cat: {
        img: null,
        name: null,
        slug: null,
        desc: null
      },
      t_modal: false,
      s_modal: false
    }
  }
})
</script>

<template>
  <page-layout>
    <div class="tw-py-5">
      <div class="container-fluid mt-3">
        <div class="row">
          <div class="col-lg-2"></div>
          <div class="col-lg-8">
            <div>
              <div class="page-nav-info bg-theme">
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
                      <input id="blog_search_input" type="text" class="blog_search_input"
                        placeholder="Type keyword..." />
                    </div>
                  </div>
                </template>
                <template v-if="c_modal">
                  <div class="c_modal_bg custom-scroll-h" style="min-height:100vh;">
                    <span class="position-fixed h-cursor text-white font-size40 c_modal_close"
                      @click="c_modal = !c_modal">&times;</span>
                    <div class="container">
                      <div class="row">
                        <div class="col-md-3 order-2 order-md-1">
                          <h5 class="text-white h5">Categories</h5>
                          <ul class="list-style-none mb-0 custom-scroll-h c_modal_item_list_ul">
                            <li v-for="category in categories" :key="category.id">
                              <a href="{{route('blog.category', $category->slug)}}"
                                :class="{ 'active': c_cat.slug == category.slug }"
                                @:mouseenter="c_cat.name = category.name; c_cat.slug = category.slug; c_cat.img = category.image; c_cat.desc = category.description">
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
                    <span class="position-fixed h-cursor text-white font-size40 c_modal_close"
                      @click="t_modal = !t_modal">&times;</span>
                    <div class="container">
                      <h5 class="text-white h5">Tags</h5>
                      <a v-for="tag in tags" :key="tag.name" href="{{route('blog.tag', $tag->slug)}}"
                        class="btn rounded-0 btn-outline-info m-1">{{ tag.name }}</a>
                    </div>
                  </div>
                </template>
              </div>
            </div>

            <div class="h-720x h-sm-auto blog_search_remove_section">
              <div class="h-2-3 h-sm-auto">
                <div class="h-100 w-2-3 w-sm-100 float-left p-1 h-sm-360px">
                  <a v-if="featured_posts[0]" href="{{route('blog.detail', $featured_posts[0]->slug)}}"
                    class="h-100 d-block position-relative h-cursor f_post_item border-solid-1px">
                    <figure :data-href="featured_posts[0].image"
                      class="img-bg progressive replace mb-0 img-bg-effect-container">
                      <img :src="featured_posts[0].image" :alt="featured_posts[0].title"
                        class="preview img-bg-effect" />
                    </figure>
                    <div class="position-absolute text-white post-title-bg z-index-2 w-100 bottom-0 p-3">
                      <p class="blog_title_large">{{ featured_posts[0].title }}</p>
                      <span class="post_small_info text-white">{{ featured_posts[0].created_at }}</span> &nbsp;&nbsp;
                      <span class="post_small_info"><i class="fa fa-eye"></i> </span>{{ featured_posts[0].view_count }}
                      &nbsp;
                      <span class="post_small_info"><i class="fa fa-comment"></i> </span>{{
                        featured_posts[0].approved_comments_count }} &nbsp;
                      <span class="post_small_info"><i class="fa fa-heart"></i> </span>{{
                        featured_posts[0].favoriters_count }}
                    </div>
                  </a>
                </div>
                <div class="h-100 w-1-3 w-sm-100 float-left h-sm-720px">
                  <div class="h-50 w-100 p-1 position-relative blog-ads-position-1111">
                    <a v-if="featured_posts[1]" href="{{route('blog.detail', $featured_posts[1]->slug)}}"
                      class="h-100 d-block position-relative h-cursor f_post_item border-solid-1px">
                      <figure :data-href="featured_posts[1].image"
                        class="img-bg progressive replace mb-0 img-bg-effect-container">
                        <img :src="featured_posts[1].image" :alt="featured_posts[1].title"
                          class="preview img-bg-effect" />
                      </figure>
                      <div class="position-absolute text-white post-title-bg z-index-2 w-100 bottom-0 p-3">
                        <p class="blog_title_medium">{{ featured_posts[1].title }}</p>
                        <span class="post_small_info text-white">{{ featured_posts[1].created_at }}</span> &nbsp;&nbsp;
                        <span class="post_small_info"><i class="fa fa-eye"></i> </span>{{ featured_posts[1].view_count
                        }}
                        &nbsp;
                        <span class="post_small_info"><i class="fa fa-comment"></i> </span>{{
                          featured_posts[1].approved_comments_count }} &nbsp;
                        <span class="post_small_info"><i class="fa fa-heart"></i> </span>{{
                          featured_posts[1].favoriters_count }}
                      </div>
                    </a>
                  </div>
                  <div class="h-50 w-100 p-1">
                    <a v-if="featured_posts[2]" href="{{route('blog.detail', $featured_posts[2]->slug)}}"
                      class="h-100 d-block position-relative h-cursor f_post_item border-solid-1px">
                      <figure :data-href="featured_posts[2].image"
                        class="img-bg progressive replace mb-0 img-bg-effect-container">
                        <img :src="featured_posts[2].image" :alt="featured_posts[2].title"
                          class="preview img-bg-effect" />
                      </figure>
                      <div class="position-absolute text-white post-title-bg z-index-2 w-100 bottom-0 p-3">
                        <p class="blog_title_medium">{{ featured_posts[2].title }}</p>
                        <span class="post_small_info text-white">{{ featured_posts[2].created_at }}</span> &nbsp;&nbsp;
                        <span class="post_small_info"><i class="fa fa-eye"></i> </span>{{ featured_posts[2].view_count
                        }}
                        &nbsp;
                        <span class="post_small_info"><i class="fa fa-comment"></i> </span>{{
                          featured_posts[2].approved_comments_count }} &nbsp;
                        <span class="post_small_info"><i class="fa fa-heart"></i> </span>{{
                          featured_posts[2].favoriters_count }}
                      </div>
                    </a>
                  </div>
                </div>
              </div>
            </div>

            <div class="h-1-3 h-sm-auto">
              <div class="h-100 w-1-3 w-sm-100 float-left p-1 h-sm-360px position-relative blog-ads-position-1112">
                <a v-if="featured_posts[3]" href="{{route('blog.detail', $featured_posts[3]->slug)}}"
                  class="h-100 d-block position-relative h-cursor f_post_item border-solid-1px">
                  <figure :data-href="featured_posts[3].image"
                    class="img-bg progressive replace mb-0 img-bg-effect-container">
                    <img :src="featured_posts[3].image" :alt="featured_posts[3].title" class="preview img-bg-effect" />
                  </figure>
                  <div class="position-absolute text-white post-title-bg z-index-2 w-100 bottom-0 p-3">
                    <p class="blog_title_medium">{{ featured_posts[3].title }}</p>
                    <span class="post_small_info text-white">{{ featured_posts[3].created_at }}</span> &nbsp;&nbsp;
                    <span class="post_small_info"><i class="fa fa-eye"></i> </span>{{ featured_posts[3].view_count }}
                    &nbsp;
                    <span class="post_small_info"><i class="fa fa-comment"></i> </span>{{
                      featured_posts[3].approved_comments_count }} &nbsp;
                    <span class="post_small_info"><i class="fa fa-heart"></i> </span>{{
                      featured_posts[3].favoriters_count
                    }}
                  </div>
                </a>
              </div>
              <div class="h-100 w-1-3 w-sm-100 float-left p-1 h-sm-360px">
                <a v-if="featured_posts[4]" href="{{route('blog.detail', $featured_posts[4]->slug)}}"
                  class="h-100 d-block position-relative h-cursor f_post_item border-solid-1px">
                  <figure :data-href="featured_posts[4].image"
                    class="img-bg progressive replace mb-0 img-bg-effect-container">
                    <img :src="featured_posts[4].image" :alt="featured_posts[4].title" class="preview img-bg-effect" />
                  </figure>
                  <div class="position-absolute text-white post-title-bg z-index-2 w-100 bottom-0 p-3">
                    <p class="blog_title_medium">{{ featured_posts[4].title }}</p>
                    <span class="post_small_info text-white">{{ featured_posts[4].created_at }}</span> &nbsp;&nbsp;
                    <span class="post_small_info"><i class="fa fa-eye"></i> </span>{{ featured_posts[4].view_count }}
                    &nbsp;
                    <span class="post_small_info"><i class="fa fa-comment"></i> </span>{{
                      featured_posts[4].approved_comments_count }} &nbsp;
                    <span class="post_small_info"><i class="fa fa-heart"></i> </span>{{
                      featured_posts[4].favoriters_count
                    }}
                  </div>
                </a>
              </div>
              <div class="h-100 w-1-3 w-sm-100 float-left p-1 h-sm-360px">
                <a v-if="featured_posts[5]" href="{{route('blog.detail', $featured_posts[5]->slug)}}"
                  class="h-100 d-block position-relative h-cursor f_post_item border-solid-1px">
                  <figure :data-href="featured_posts[5].image"
                    class="img-bg progressive replace mb-0 img-bg-effect-container">
                    <img :src="featured_posts[5].image" :alt="featured_posts[5].title" class="preview img-bg-effect" />
                  </figure>
                  <div class="position-absolute text-white post-title-bg z-index-2 w-100 bottom-0 p-3">
                    <p class="blog_title_medium">{{ featured_posts[5].title }}</p>
                    <span class="post_small_info text-white">{{ featured_posts[5].created_at }}</span> &nbsp;&nbsp;
                    <span class="post_small_info"><i class="fa fa-eye"></i> </span>{{ featured_posts[5].view_count }}
                    &nbsp;
                    <span class="post_small_info"><i class="fa fa-comment"></i> </span>{{
                      featured_posts[5].approved_comments_count
                    }} &nbsp;
                    <span class="post_small_info"><i class="fa fa-heart"></i> </span>{{
                      featured_posts[5].favoriters_count
                    }}
                  </div>
                </a>
              </div>
            </div>
          </div>
          <div class="col-lg-2"></div>
        </div>
        <div class="blog_append_section pb-5">
          <div class="row mt-5">
            <div class="col-lg-2">
              <div class="blog-ads-position-11110 text-right"></div>
            </div>
            <div class="col-lg-8">
              <div class="row">
                <div class="col-lg-8">
                  <h5 class="p-area-title">Recent News</h5>
                  <div class="blog-ads-position-1113"></div>
                  <div class="row mb-5">
                    <div class="col-sm-6">
                      <a v-if="recent_posts[0]" href="{{route('blog.detail', $recent_posts[0]->slug)}}">
                        <figure :data-href="recent_posts[0].image"
                          class="progressive replace h-cursor f_post_item border-solid-1px">
                          <img :src="recent_posts[0].image" :alt="recent_posts[0].title" class="preview" />
                          <div class="position-absolute text-white post-title-bg z-index-2 w-100 bottom-0 p-3">
                            <p class="blog_title_medium">{{ recent_posts[0].title }}</p>
                            <span class="post_small_info text-white">{{ recent_posts[0].created_at }}</span>
                            &nbsp;&nbsp;
                            <span class="post_small_info"><i class="fa fa-eye"></i> </span>{{ recent_posts[0].view_count
                            }}
                            &nbsp;
                            <span class="post_small_info"><i class="fa fa-comment"></i> </span>{{
                              recent_posts[0].approved_comments_count }} &nbsp;
                            <span class="post_small_info"><i class="fa fa-heart"></i> </span>{{
                            recent_posts[0].favoriters_count
                            }}
                          </div>
                        </figure>
                      </a>
                    </div>
                    <div class="col-sm-6">
                      <a v-if="recent_posts[1]" href="{{route('blog.detail', $recent_posts[1]->slug)}}"
                        class="d-block mb-3">
                        <figure :data-href="recent_posts[1].image"
                          class="progressive replace h-cursor f_post_item width-100px float-left">
                          <img :src="recent_posts[1].image" :alt="recent_posts[1].title" class="preview" />
                        </figure>
                        <div class="ml-120px">
                          {{ recent_posts[1].title }}
                          <hr class="m-0" />
                          <span class="post_small_info">{{ recent_posts[1].created_at }}</span> &nbsp;&nbsp;
                          <span class="post_small_info"><i class="fa fa-eye"></i> </span>{{ recent_posts[1].view_count
                          }} &nbsp;
                          <span class="post_small_info"><i class="fa fa-comment"></i> </span>{{
                            recent_posts[1].approved_comments_count }} &nbsp;
                          <span class="post_small_info"><i class="fa fa-heart"></i> </span>{{
                            recent_posts[1].favoriters_count
                          }}
                        </div>
                        <div class="clearfix"></div>
                      </a>
                      <a v-if="recent_posts[2]" href="{{route('blog.detail', $recent_posts[2]->slug)}}"
                        class="d-block mb-3">
                        <figure :data-href="recent_posts[2].image"
                          class="progressive replace h-cursor f_post_item width-100px float-left">
                          <img :src="recent_posts[2].image" :alt="recent_posts[2].title" class="preview" />
                        </figure>
                        <div class="ml-120px">
                          {{ recent_posts[2].title }}
                          <hr class="m-0" />
                          <span class="post_small_info">{{ recent_posts[2].created_at }}</span> &nbsp;&nbsp;
                          <span class="post_small_info"><i class="fa fa-eye"></i> </span>{{ recent_posts[2].view_count
                          }} &nbsp;
                          <span class="post_small_info"><i class="fa fa-comment"></i> </span>{{
                            recent_posts[2].approved_comments_count }} &nbsp;
                          <span class="post_small_info"><i class="fa fa-heart"></i> </span>{{
                            recent_posts[2].favoriters_count
                          }}
                        </div>
                        <div class="clearfix"></div>
                      </a>
                      <a v-if="recent_posts[3]" href="{{route('blog.detail', $recent_posts[3]->slug)}}"
                        class="d-block mb-3">
                        <figure :data-href="recent_posts[3].image"
                          class="progressive replace h-cursor f_post_item width-100px float-left">
                          <img :src="recent_posts[3].image" :alt="recent_posts[3].title" class="preview" />
                        </figure>
                        <div class="ml-120px">
                          {{ recent_posts[3].title }}
                          <hr class="m-0" />
                          <span class="post_small_info">{{ recent_posts[3].created_at }}</span> &nbsp;&nbsp;
                          <span class="post_small_info"><i class="fa fa-eye"></i> </span>{{ recent_posts[3].view_count
                          }} &nbsp;
                          <span class="post_small_info"><i class="fa fa-comment"></i> </span>{{
                            recent_posts[3].approved_comments_count }} &nbsp;
                          <span class="post_small_info"><i class="fa fa-heart"></i> </span>{{
                            recent_posts[3].favoriters_count
                          }}
                        </div>
                        <div class="clearfix"></div>
                      </a>
                    </div>
                  </div>
                  <h5 class="p-area-title">All Posts</h5>
                  <div class="blog-ads-position-1115"></div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="blog-ads-position-1116"></div>
                    </div>
                    <div class="col-md-6">
                      <div class="blog-ads-position-1117"></div>
                    </div>
                  </div>
                  <div class="all_post_area"></div>
                </div>
                <div class="col-lg-4">
                  <div class="blog-ads-position-1114"></div>
                  <h5 class="p-area-title">Popular Posts</h5>
                  <div class="mb-5">
                    <a v-for="post in popular_post" :key="post.id" href="{{route('blog.detail', $post->slug)}}"
                      class="d-block mb-3">
                      <figure :data-href="post.image"
                        class="progressive replace h-cursor f_post_item width-100px float-left">
                        <img :src="post.image" :alt="post.title" class="preview" />
                      </figure>
                      <div class="ml-120px">
                        {{ post.title }}
                        <hr class="m-0" />
                        <span class="post_small_info">{{ post.created_at }}</span> &nbsp;&nbsp;
                        <span class="post_small_info"><i class="fa fa-eye"></i> </span>{{ post.view_count }} &nbsp;
                        <span class="post_small_info"><i class="fa fa-comment"></i> </span>{{
                          post.approved_comments_count }}
                        &nbsp;
                        <span class="post_small_info"><i class="fa fa-heart"></i> </span>{{ post.favoriters_count }}
                      </div>
                      <div class="clearfix"></div>
                    </a>
                  </div>
                  <div class="blog-ads-position-1118"></div>
                  <h5 class="p-area-title">Most Discussed Posts</h5>
                  <div class="mb-5">
                    <a v-for="post in most_discussed_posts" :key="post.id" href="{{route('blog.detail', $post->slug)}}"
                      class="d-block mb-3">
                      <figure :data-href="post.image"
                        class="progressive replace h-cursor f_post_item width-100px float-left">
                        <img :src="post.image" :alt="post.title" class="preview" />
                      </figure>
                      <div class="ml-120px">
                        {{ post.title }}
                        <hr class="m-0" />
                        <span class="post_small_info">{{ post.created_at }}</span> &nbsp;&nbsp;
                        <span class="post_small_info"><i class="fa fa-eye"></i> </span>{{ post.view_count }} &nbsp;
                        <span class="post_small_info"><i class="fa fa-comment"></i> </span>{{
                          post.approved_comments_count }}
                        &nbsp;
                        <span class="post_small_info"><i class="fa fa-heart"></i> </span>{{ post.favoriters_count }}
                      </div>
                      <div class="clearfix"></div>
                    </a>
                  </div>
                  <div class="blog-ads-position-1119"></div>
                </div>
              </div>
            </div>
            <div class="col-lg-2">
              <div class="blog-ads-position-11111"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </page-layout>
</template>

<style scoped lang="scss">
.bg-theme {
  background-color: #86bc42;
}
</style>
