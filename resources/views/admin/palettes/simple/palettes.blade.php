@extends('layouts.master', ['alpine' => false])

@section('title', 'Simple Palettes')

@section('style')
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <link href="{{ asset('assets/css/palettes.css') }}" rel="stylesheet" />
@endsection

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
                    <span class="m-nav__link-text">Simple</span>
                </a>
            </li>
            <li class="m-nav__separator">/</li>
            <li class="m-nav__item">
                <a href="" class="m-nav__link">
                    <span class="m-nav__link-text">Palettes</span>
                </a>
            </li>
        </ul>
    </div>
@endsection

@section('content')
    <div x-data="simplePalettesData">
        <div class="tabs-wrapper">
            <div class="clearfix"></div>
            <ul class="tab-nav">
                <li class="tab-item" @click="onSelectType('all')"><a class="tab-link"
                        :class="type === 'all' && 'tab-active'">
                        All Palettes (<span class="all_count" x-text="palettes.length"></span>)</a></li>
                <li class="tab-item" @click="onSelectType('active')"><a class="tab-link"
                        :class="type === 'active' && 'tab-active'"> Active Palettes (<span class="active_count"
                            x-text="palettes.filter(({status}) => status).length"></span>)</a></li>
                <li class="tab-item" @click="onSelectType('inactive')"><a class="tab-link"
                        :class="type === 'inactive' && 'tab-active'"> InActive Palettes
                        (<span class="inactive_count" x-text="palettes.filter(({status}) => !status).length"></span>)</a>
                </li>
            </ul>
        </div>
        <div class="m-portlet m-portlet--mobile tab_area area-active md-pt-50" id="palette_area">
            <div id="app" class="m-portlet__body">
                <div class="palette_body">
                    <div class="d-flex justify-content-end">
                        <button class="btn btn-info" @click="handleCreate">+ Create Palette</button>
                        <button class="btn btn-info ml-2" @click="openSortModal">Order</button>
                    </div>
                    <div class="w-100 mt-2">
                        <x-global.pagination />
                        <template x-if="!loading">
                            <div class="table-responsive">
                                <table class="table table-hover ajaxTable datatable">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Colors</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template
                                            x-if="!filteredPalettes.filter(({status}) => (type === 'all' ? true : type === 'active' ? status : !status)).length">
                                            <tr>
                                                <td colspan="4">
                                                    <div>No palette items</div>
                                                </td>
                                            </tr>
                                        </template>
                                        <template
                                            x-for="(p, index) in filteredPalettes.filter(({status}) => (type === 'all' ? true : type === 'active' ? status : !status))"
                                            :key="JSON.stringify(p)">
                                            <tr>
                                                <td x-text="p.name"></td>
                                                <td>
                                                    <div class="theme-colors">
                                                        <div class="theme-color-item">
                                                            <div :style="{ backgroundColor: p.data.backgroundColor }"></div>
                                                        </div>
                                                        <div class="theme-color-item">
                                                            <div :style="{ backgroundColor: p.data.primaryColor }"></div>
                                                        </div>
                                                        <div class="theme-color-item">
                                                            <div :style="{ backgroundColor: p.data.secondaryColor }"></div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="c-badge"
                                                        :class="p.status ? 'c-badge-success' : 'c-badge-danger'"
                                                        x-text="p.status ? 'Active' : 'Inactive'"></span>
                                                </td>
                                                <td>
                                                    <div>
                                                        <button @click="handleEdit(p)" class="btn btn-info mr-1"
                                                            :disabled="deleting">
                                                            Edit
                                                        </button>
                                                        <button @click="handleRemove(p.id, index)" class="btn btn-danger"
                                                            :disabled="deleting">
                                                            Delete
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                        </template>
                    </div>
                    <x-palettes.simple-modal />
                    <x-palettes.order-modal />
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        window.getUrl = '{{ route('admin.palettes.data', ['type' => 'simple']) }}'
        window.storeUrl = '{{ route('admin.palettes.store', ['type' => 'simple']) }}'
        window.removeUrl = '{{ route('admin.palettes.delete', ['type' => 'simple']) }}'
        window.updateUrl = '{{ route('admin.palettes.update', ['type' => 'simple']) }}'
        window.sortUrl = '{{ route('admin.palettes.sort', ['type' => 'simple']) }}'
        @auth
            window.user = @json(auth()->user());
            window.user.roles = @json(auth()->user()->roles);
        @endauth
    </script>
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
@endsection
