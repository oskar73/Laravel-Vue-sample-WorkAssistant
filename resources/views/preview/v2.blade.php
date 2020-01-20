@extends('layouts.previewApp')

@section('style')
    <link type="text/css" rel="stylesheet" href="{{mix('/assets/resources/css/website.css')}}">
    <link type="text/css" rel="stylesheet" href="{{asset('assets/css/animate.min.css')}}">
@endsection

@section('seo')
    @include('components.front.seo')
@endsection

@section('content')
    <div class="out_content overflow-hidden min-vh-100">
        <div id="app" class="bz-page">
            <template-view></template-view>
        </div>
    </div>
@endsection

@section('script')
    <script>
      window.template = {!! $template !!};
    </script>
    <script src="{{mix('assets/resources/js/website.js')}}" type="text/javascript"></script>
@endsection