<!DOCTYPE html>
<html lang="en" prefix="og: http://ogp.me/ns#">
<head>
    <title>BizinaboxGraphic Builder - Videos</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{asset('assets/img/favicon.ico')}}" />
    <link rel="shortcut icon" href="{{ asset('/assets/img/favicon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>

    {{
        Vite::useBuildDirectory('assets/resources/vite')
    }}

    @vite(['resources/js/svg-editor/sass/app.scss'])
    @vite(['resources/js/svg-editor/sass/video.scss'])
</head>
<body class="h-100">
<div id="app" class="flex-center position-ref full-height h-100">
    <div class="header">
        <img src="{{asset('assets/img/logo.png')}}" style="width: 150px; object-fit: contain">
    </div>
    <div class="video-container" x-data="videoData">
        <div class="video-category">
            <div class="category-name all" :class="{selected: activeCategory===null}" @click.prevent="selectAllCategory">
                <i class="fa fa-folder-open mr-2"></i>
                <span>All Categories</span>
            </div>
            <template x-for="category in categories">
                <div class="category-item">
                    <div class="category-name" :class="{selected: (activeCategory && activeCategory.id) === category.id}" @click.prevent="selectCategory(category)">
                        <i class="fa fa-folder-open mr-2"></i>
                        <span x-text="category.name"></span>
                    </div>
                    <div x-show.transition="(activeCategory && activeCategory.id) === category.id">
                        <template x-for="subcategory in category.subcategories">
                            <div class="subcategory-item">
                                <div class="subcategory-name">
                                    <i class="fa fa-folder-open"></i>
                                    <span x-text="subcategory.name"></span>
                                </div>
                                <template x-for="item in subcategory.items">
                                    <div class="video-item">
                                        <span class="item-title" x-text="item.title"></span>
                                    </div>
                                </template>
                            </div>
                        </template>
                        <template x-for="item in category.items">
                            <div class="video-title" @click.prevent="selectVideoItem(item)">
                                <i class="fa fa-file-video mr-2"></i>
                                <span x-text="item.title" class="text-black-50"></span>
                            </div>
                        </template>
                    </div>
                </div>
            </template>
        </div>
        <div class="video-area">
            <template x-if="activeVideo === null">
                <div class="col-12">
                    <div class="row">
                        <template x-for="video in videos">
                            <div class="col-lg-4 col-md-6">
                                <div class="video-card">
                                    <div class="content" @click.prevent="selectVideoItem(video)">
                                        <div class="image">
                                            <img :src="video.thumbnail" alt="video thumbnail"/>
                                        </div>
                                        <div class="d-flex justify-content-center align-items-center mt-2">
                                            <span x-text="video.title" class="text-ellipsis"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </template>
            <template x-if="activeVideo">
                <div class="embed-responsive embed-responsive-16by9">
                    <iframe class="embed-responsive-item" :src="treatUrl(activeVideo.link)" allowfullscreen></iframe>
                </div>
            </template>
        </div>
    </div>
</div>
</body>
</html>
<script>
    window.videoData = {
        categories: {!! $categories !!},
        allVideos: {!! $videos !!},
        videos: {!! $videos !!},
        activeCategory: null,
        activeVideo: null,
        selectAllCategory(){
            this.videos = this.allVideos;
            this.activeCategory = null;
            this.activeVideo = null
        },
        selectCategory(category){
            this.activeCategory = category;
            this.videos = category.items;
            this.activeVideo = null;
        },
        selectVideoItem(video){
            this.activeVideo = video;
        },
        treatUrl(url){
            const youtubeUrlPattern = /^(?:https?:\/\/)?(?:www\.)?youtube\.com\/watch\?v=([a-zA-Z0-9_-]{11})/;
            const match = url.match(youtubeUrlPattern);
            return match ? `https://www.youtube.com/embed/${match[1]}` : url;
        }
    }
</script>
