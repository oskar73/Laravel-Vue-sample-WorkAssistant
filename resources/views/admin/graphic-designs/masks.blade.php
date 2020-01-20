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
                    <span class="m-nav__link-text">Masks</span>
                </a>
            </li>
            <li class="m-nav__separator">/</li>
            <li class="m-nav__item">
                <a href="" class="m-nav__link">
                    <span class="m-nav__link-text">{{ $graphic->title }}</span>
                </a>
            </li>
        </ul>
    </div>
@endsection

@section('content')
    <div class="m-portlet m-portlet--mobile md-pt-50">
        <div x-cloak x-data="createMaskData" class="m-portlet__body">
            <select id="category" x-model="category_slug" class="form-control !tw-w-60" data-width="100%" data-live-search="true">
                <template x-for="(category, index) in categories" :key="index">
                    <option :value="category.slug" :selected="category.slug == category_slug" x-text="category.title"></option>
                </template>
            </select>
            <div class="tw-mt-5">
                <div class="tw-grid sm:tw-grid-cols-4 lg:tw-grid-cols-6 xl:tw-grid-cols-8 tw-gap-4">
                    <template x-for="(mask, index) in masks" :key="index">
                        <div class="tw-w-full tw-rounded tw-flex tw-justify-center tw-items-center tw-relative" style="padding-top: 100%">
                            <div class="tw-absolute tw-right-1 tw-top-1 tw-cursor-pointer tw-z-50" @click="removeMask(mask)">
                                <i class="mdi mdi-close-box tw-text-red-500 tw-text-2xl"></i>
                            </div>
                            <template x-if="removing_id == mask.id">
                                <div role="status" class="tw-h-full tw-w-full tw-flex tw-justify-center tw-items-center tw-flex-col tw-absolute tw-top-0 tw-left-0">
                                    <x-global.spinner />
                                    <span class="tw-text-sm">Removing...</span>
                                </div>
                            </template>
                            <div class="tw-h-full tw-w-full tw-absolute tw-top-0 tw-left-0 tw-flex tw-justify-center tw-items-center tw-border tw-border-solid">
                                <img :src="mask.original_url" class="tw-absolute tw-w-full tw-object-contain" alt="Mask Image" >
                            </div>
                        </div>
                    </template>
                    <div class="tw-w-full tw-mb-0 tw-border tw-bg-gray-50 tw-cursor-pointer tw-flex-col tw-relative" style="padding-top: 100%">
                        <label class="tw-absolute tw-flex tw-justify-center tw-items-center tw-w-full tw-h-full tw-top-0 tw-left-0" >
                            <input @change="addMask" hidden type="file" name="mask_images" accept="image/svg+xml" />
                            <template x-if="loading">
                                <div role="status" class="tw-flex tw-justify-center tw-items-center tw-flex-col">
                                    <x-global.spinner />
                                    <span class="tw-text-sm">Uploading...</span>
                                </div>
                            </template>
                            <template x-if="!loading">
                                <div class="tw-flex tw-justify-center tw-items-center tw-flex-col">
                                    <i class="mdi mdi-plus tw-text-4xl"></i>
                                    <span class="tw-text-sm">Add Mask</span>
                                </div>
                            </template>
                        </label>
                    </div>
                </div>
            </div>
            <div class="tw-my-5">
                <label for="content" class="tw-flex tw-flex-col tw-m-0">
                    Enter SVG Content:
                    <textarea type="text" name="content" id="content" class="form-control tw-flex-grow"></textarea>
                </label>
                <div class="tw-text-right tw-mt-2">
                    <button @click="addMaskFromContent" class="btn btn-outline-success">Add Mask</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        let graphics = {!! $graphics !!};
        let graphic = {!! $graphic !!};
        let masks = {!! json_encode($masks) !!};
    </script>
    <script src="{{ asset('assets/js/admin/graphic-design/masks.js') }}"></script>
@endsection
