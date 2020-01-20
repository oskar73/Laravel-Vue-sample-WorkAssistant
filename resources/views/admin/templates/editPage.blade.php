@extends('layouts.pageApp')

@section('title', 'Template Management')

@section('style')
    {!! $template->css !!}
    <script src="{{ s3_asset('vendors/tinycolor/tinycolor.min.js') }}"></script>
{{--    <script src="{{ s3_asset('vendors/lc-mouse-drag/lc-mouse-drag.min.js') }}"></script>--}}
@endsection

@section('content')
    <div id="app" class="bz-page bz-page-edit"></div>
@endsection

@section('script')
    <script>
        window.config = {
            websiteId: "{{ $template->id }}",
            template: @json($template),
            token: '{{ csrf_token() }}',
            appURL: '{{ config('app.url') }}',
            s3AssetBaseUrl: '{{ config('app.s3_asset_base_url') }}',
            appDomain: '{{ config('app.domain') }}',
            appEnv: '{{ config('app.env') }}',
            unsplashApiKey: '{{ option('unsplash_api_key') }}',
            isTemplate: true,
            baseUrl: '{{url('admin')}}',
            basePath: '{{ $basePath }}',
            urls: {
                websitePreviewURL: '{{ route('admin.template.item.preview', ['slug' => $template->slug]) }}',
                storeUrl: '{{ route('admin.palettes.store', ['type' => 'advanced']) }}',
            }
        }
        @auth
          window.user = @json(auth()->user());
          window.user.roles = @json(auth()->user()->roles);
        @endauth
    </script>
    {!! $template->script !!}
@endsection
