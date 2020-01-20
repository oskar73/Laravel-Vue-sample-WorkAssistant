<script>
import { defineComponent } from 'vue'
import PageLayout from '@/section-builder/components/page/PageLayout.vue'

export default defineComponent({
    name: 'MarketPlacePage',
    components: { PageLayout },
    data() {
        return {
            slides: [
                {
                    slide_image: '1575441444.jpg',
                    slide_title: 'Selling physical products',
                    slide_desc: 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium',
                    slide_btn_text: 'View More',
                    slide_btn_link: '',
                    slide_text_position: ''
                },
                {
                    slide_image: '1576303496.jpg',
                    slide_title: 'Trending Dresses For Kids',
                    slide_desc: 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium',
                    slide_btn_text: 'View More',
                    slide_btn_link: '',
                    slide_text_position: 'right'
                }
            ],
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
            s_modal: false,
            cart_count: 0,
        }
    },
    methods: {
        getSlideImageUrl(image) {
            return `${process.env.APP_URL}/public/assets/img/slideshow/${image}`;
        }
    }
})
</script>

<template>
    <page-layout>
        <header id="header">
            <div class="navbar navbar-expand-lg category-bar row">
                <div class="container-fluid">
                    <div class="col-lg-3 col-md-12 col-sm-12">
                        <button type="button" id="sidebarCollapse" class="btn button-color">
                            <i class="fa fa-bars"></i>
                            <span>Categories</span>
                        </button>
                        <button class="btn button-color d-inline-block d-lg-none ml-auto pull-right" type="button"
                            data-toggle="collapse" data-target="#navbarSupportedContent2"
                            aria-controls="navbarSupportedContent2" aria-expanded="false"
                            aria-label="Toggle navigation">
                            <i class="fa fa-bars"></i>
                        </button>
                        <button class="btn button-color d-inline-block d-lg-none ml-auto mmiddle pull-right"
                            type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <i class="fa fa-bars"></i>
                        </button>
                    </div>
                    <div class="col-lg-4 col-md-12 col-sm-12">
                        <div class="collapse navbar-collapse" id="navbarSupportedContent2">
                            <ul class="nav navbar-nav">
                                <li class="nav-item active">
                                    <a class="nav-link" href="{{ route('/best-sellers') }}">Best Sellers</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('/new-releases') }}">New Releases</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('/top-deals') }}">Top Deals</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('/start-sellings') }}">Start Sellings</a>
                                </li>
                                <!-- <li class="nav-item">
                                    <a class="nav-link" href="{{ route('/track-order') }}">Track Order</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('/wishlist') }}">Wishlist</a>
                                </li> -->
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-5 col-md-12 col-sm-12">
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="nav navbar-nav ml-auto">
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('/track-order') }}">Track Order</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('/wishlist') }}">Wishlist</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('/checkout') }}">Checkout</a>
                                </li>
                                <li class="nav-item"><a href="{{ route('/cart') }}" class="nav-link"><i
                                            class="fa fa-shopping-cart"></i> Cart<span class="cart-badge">{{
                                                cart_count
                                            }}</span></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <main role="main" class="main-content">
            <div id="myCarousel" class="carousel slide carousel-fade" data-ride="carousel">
                <ol class="carousel-indicators">
                    <li v-for="(slide, index) in slides" :key="index" :data-target="'#myCarousel'"
                        :data-slide-to="index" :class="{ active: index === 0 }"></li>
                </ol>
                <div class="carousel-inner">
                    <div v-for="(slide, index) in slides" :key="index"
                        :class="['carousel-item', { active: index === 0 }]">
                        <img class="first-slide img-fluid" :src="getSlideImageUrl(slide.slide_image)"
                            :alt="slide.slide_image" />
                        <div class="container">
                            <div :class="[
                                'carousel-caption',
                                slide.slide_text_position ? 'text-' + slide.slide_text_position : 'text-left',
                            ]">
                                <h1>{{ slide.slide_title }}</h1>
                                <p>{{ slide.slide_desc }}</p>
                                <p v-if="slide.slide_btn_link">
                                    <a class="btn button-color" href="#" role="button" target="_blank">
                                        {{ slide.slide_btn_text }}
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <a class="carousel-control-prev" href="#myCarousel" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">{{ prevTranslation }}</span>
                </a>
                <a class="carousel-control-next" href="#myCarousel" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">{{ nextTranslation }}</span>
                </a>
            </div>
        </main>
    </page-layout>
</template>