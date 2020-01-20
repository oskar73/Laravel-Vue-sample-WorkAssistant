@extends('layouts.pageApp')

@section('title', 'Template Management')

@section('style')
    {!! $template->css !!}
    <link type="text/css" rel="stylesheet" href="{{ mix('assets/resources/css/builder.css') }}">
    <script src="{{ s3_asset('vendors/tinycolor/tinycolor.min.js') }}"></script>
    <script src="{{ s3_asset('vendors/lc-mouse-drag/lc-mouse-drag.min.js') }}"></script>
@endsection

@section('content')
    <div id="app" class="bz-page bz-page-edit">
        <edit-page></edit-page>
    </div>
@endsection

@section('script')
    <script>
        window.config = {
            websiteId: "{{ $template->id }}",
            token: '{{ csrf_token() }}',
            appURL: '{{ config('app.url') }}',
            appDomain: '{{ config('app.domain') }}',
            appEnv: '{{ config('app.env') }}',
            unsplashApiKey: '{{ option('unsplash_api_key') }}',
            isTemplate: true,
            urls: {
                websitePreviewURL: '{{ route('user.template.item.preview', ['slug' => $template->slug]) }}',
                addPageUrl: '{{ route('user.template.page.addNewPage') }}',
                storeUrl: '{{ route('user.palettes.store', ['type' => 'advanced']) }}'
            }
        }
    </script>
    {!! $template->script !!}
@endsection
