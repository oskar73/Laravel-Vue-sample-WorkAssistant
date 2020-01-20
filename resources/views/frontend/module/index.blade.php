@extends('layouts.app')

@section('title', 'Modules')

@section('style')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/front/template.css')}}">
@endsection
@section('content')
    <x-front.hero>Modules</x-front.hero>

    <div class="tw-w-full tw-mx-auto tw-flex tw-max-w-[1440px]">
        <div class="tw-w-80 tw-pt-3">
            <form class="" action="" method="post" enctype="multipart/form-data">
                <div class="bz-input-base">
                    <input class="form-control" id="keyword" type="text" name="keyword" placeholder="Search..." autocomplete="off">
                    <i class="fas fa-search"></i>
                </div>
            </form>

            <div class="filter-widget mb-0 filter-box tw-mt-4">
                <p class="view_by_txt mb-2">Filter by</p>
                <div class="fw-size-choose fw-filter-choose">
                    <div class="sc-item">
                        <input type="radio" name="filterBy" id="sort-featured" value="featured" checked>
                        <label for="sort-featured"><span>Featured</span></label>
                    </div>
                    <div class="sc-item">
                        <input type="radio" name="filterBy" id="sort-new" value="new">
                        <label for="sort-new"><span>New</span></label>
                    </div>
                    <div class="sc-item">
                        <input type="radio" name="filterBy" id="sort-popular" value="popular">
                        <label for="sort-popular"><span>Most Popular</span></label>
                    </div>
                </div>
            </div>
            <div class="filter-widget">
                <p class="view_by_txt">Filter by Category</p>
                <ul class="category-menu">
                    <li class="all"><a href="#/all">All Categories</a></li>
                    @foreach($categories as $key=>$category)
                        <li class="{{$category->slug}}">
                            <a href="#/{{$category->slug}}">{{$category->name}}</a>
                            @if($category->approvedSubCategories->count()!==0)
                                <ul class="sub-menu">
                                    @foreach($category->approvedSubCategories as $subcat)
                                        <li class="{{$subcat->slug}}">
                                            <a href="#/{{$category->slug}}/{{$subcat->slug}}">{{$subcat->name}}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="tw-w-full tw-p-3">
            <div class="modules_result">
                <div class="tw-text-center tw-min-h-28 tw-pt-6"><i class="fa fa-spinner fa-spin fa-3x"></i></div>
            </div>
        </div>
        <div class="tw-w-80 tw-pt-3" id="sidebar_apps_content"></div>
    </div>

    <div class="modal fade" id="video_modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div id="video_content" class="modal-content"></div>
        </div>
    </div>

    <div class="modal fade" id="view_modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div id="view_content" class="modal-content"></div>
        </div>
    </div>

    <div class="modal fade" id="apps_modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div id="apps_content" class="modal-content"></div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{asset('assets/js/front/module/index.js')}}"></script>
@endsection
