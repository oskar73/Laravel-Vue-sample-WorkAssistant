@extends('layouts.master')

@section('title', 'Directory Front Setting')

@section('breadcrumb')
    <div class="col-md-6 text-left">
        <ul class="m-subheader__breadcrumbs m-nav m-nav--inline">
            <li class="m-nav__item m-nav__item--home">
                <a href="" class="m-nav__link m-nav__link--icon">
                    <i class="m-nav__link-icon la la-home"></i>
                </a>
            </li>
            <li class="m-nav__separator">/</li>
            <li class="m-nav__item">
                <a href="" class="m-nav__link">
                    <span class="m-nav__link-text">Graphic Design</span>
                </a>
            </li>
            <li class="m-nav__separator">/</li>
            <li class="m-nav__item">
                <a href="" class="m-nav__link">
                    <span class="m-nav__link-text">Images</span>
                </a>
            </li>
            <li class="m-nav__separator">/</li>
            <li class="m-nav__item">
                <a href="" class="m-nav__link">
                    <span class="m-nav__link-text">{{ $category->title }}</span>
                </a>
            </li>
        </ul>
    </div>
@endsection

@section('content')
    <div class="m-portlet m-portlet--mobile md-pt-50">
        <div x-cloak x-data="createImageData" class="m-portlet__body">
            <select id="category" x-model="category_slug" class="form-control !tw-w-60" data-width="100%" data-live-search="true">
                <template x-for="(category, index) in categories" :key="index">
                    <option :value="category.slug" :selected="category.slug == category_slug" x-text="category.title"></option>
                </template>
            </select>
            <div class="tw-mt-5">
                <div class="tw-grid tw-grid-cols-6 tw-gap-4">
                    <template x-for="(image, index) in images" :key="index">
                        <div class="tw-w-full tw-rounded tw-h-60 tw-flex tw-justify-center tw-border tw-items-center tw-relative">
                            <div class="tw-absolute tw-right-1 tw-top-1 tw-cursor-pointer" @click="removeImage(image)">
                                <i class="mdi mdi-close-box tw-text-red-500 tw-text-2xl"></i>
                            </div>
                            <template x-if="removing_id == image.id">
                                <div role="status" class="tw-flex tw-justify-center tw-items-center tw-flex-col tw-absolute">
                                    <x-global.spinner />
                                    <span class="tw-text-sm">Removing...</span>
                                </div>
                            </template>
                            <img :src="image.original_url" class="tw-w-full tw-h-full tw-object-contain tw-border tw-border-solid" alt="Category Image" >
                        </div>
                    </template>
                    <div class="tw-w-full tw-mb-0 tw-border tw-h-60 tw-bg-gray-50 tw-cursor-pointer tw-flex-col tw-relative">
                        <label class=" tw-flex tw-justify-center tw-items-center tw-absolute tw-w-full tw-h-full tw-top-0 tw-left-0" >
                            <input @change="addImage" hidden type="file" accept="image/*" />
                            <template x-if="loading">
                                <div role="status" class="tw-flex tw-justify-center tw-items-center tw-flex-col">
                                    <x-global.spinner />
                                    <span class="tw-text-sm">Uploading...</span>
                                </div>
                            </template>
                            <template x-if="!loading">
                                <div class="tw-flex tw-justify-center tw-items-center tw-flex-col">
                                    <i class="mdi mdi-plus tw-text-4xl"></i>
                                    <span class="tw-text-sm">Add Image</span>
                                </div>
                            </template>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
      let categories = {!! $categories !!};
      let category = {!! $category !!};
      let images = {!! json_encode($images) !!};
    </script>
    <script src="{{ asset('assets/js/admin/graphic-design/images.js') }}"></script>
@endsection
