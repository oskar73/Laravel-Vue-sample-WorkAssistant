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
                    <span class="m-nav__link-text">Icons</span>
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
    <div class="col-md-6 text-right">
        <a href="javascript:void(0);"
            class="ml-auto btn m-btn--square m-btn--custom m-btn--sm btn-outline-success createBtn mb-2">Add Icon</a>
    </div>
@endsection

@section('content')
    <div class="m-portlet m-portlet--mobile md-pt-50">
        <div x-cloak x-data="createIconData" class="m-portlet__body">
            <select id="category" x-model="category_slug" class="form-control !tw-w-60" data-width="100%" data-live-search="true">
                <template x-for="(category, index) in categories" :key="index">
                    <option :value="category.slug" :selected="category.slug == category_slug" x-text="category.title"></option>
                </template>
            </select>
            <div class="tw-mt-5">
                <div class="tw-grid tw-grid-cols-6 tw-gap-4">
                    <template x-for="(icon, index) in icons" :key="index">
                        <div class="tw-w-full tw-rounded tw-h-60 tw-flex tw-justify-center tw-border tw-items-center tw-relative">
                            <div class="tw-absolute tw-right-1 tw-top-1 tw-cursor-pointer" @click="removeIcon(icon)">
                                <i class="mdi mdi-close-box tw-text-red-500 tw-text-2xl"></i>
                            </div>
                            <template x-if="removing_id == icon.id">
                                <div role="status" class="tw-flex tw-justify-center tw-items-center tw-flex-col tw-absolute">
                                    <x-global.spinner />
                                    <span class="tw-text-sm">Removing...</span>
                                </div>
                            </template>
                            <img :src="icon.original_url" class="tw-w-full tw-h-full tw-object-contain" alt="Category icon" >
                        </div>
                    </template>
                    <div class="tw-w-full tw-mb-0 tw-border tw-h-60 tw-bg-gray-50 tw-cursor-pointer tw-flex-col tw-relative">
                        <label class=" tw-flex tw-justify-center tw-items-center tw-absolute tw-w-full tw-h-full tw-top-0 tw-left-0" >
                            <input @change="addIcon" hidden type="file" accept="image/svg+xml" />
                            <template x-if="loading">
                                <div role="status" class="tw-flex tw-justify-center tw-items-center tw-flex-col">
                                    <x-global.spinner />
                                    <span class="tw-text-sm">Uploading...</span>
                                </div>
                            </template>
                            <template x-if="!loading">
                                <div class="tw-flex tw-justify-center tw-items-center tw-flex-col">
                                    <i class="mdi mdi-plus tw-text-4xl"></i>
                                    <span class="tw-text-sm">Add Icon</span>
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
      let icons = {!! json_encode($icons) !!};
    </script>
    <script src="{{ asset('assets/js/admin/graphic-design/icons.js') }}"></script>
@endsection
