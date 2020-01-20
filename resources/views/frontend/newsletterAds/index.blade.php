@extends('layouts.app')

@section('title', 'Newsletter Advertisement Positions')

@section('style')
@endsection
@section('content')
    <x-front.hero>Newsletter Advertisement</x-front.hero>

    <div class="container mt-3">
        <x-front.blog-nav></x-front.blog-nav>

        <div class="items_result blog_search_remove_section">
            <div class="text-center minh-100 pt-5"><i class="fa fa-spinner fa-spin fa-3x"></i></div>
        </div>

    </div>

    <div class="blog_append_section"></div>
@endsection
@section('script')
    <script src="{{asset('assets/js/front/newsletterAds/index.js')}}"></script>
@endsection
