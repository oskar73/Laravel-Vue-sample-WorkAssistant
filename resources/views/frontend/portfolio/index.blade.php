@extends('layouts.app')

@section('title', $seo['title']??'Portfolios')

@section('style')
@endsection

@section('seo')
    @include('components.front.seo')
@endsection

@section('content')

    <x-front.hero
        image="{{uploadUrl(option('portfolio.front.header.image', ''),'header.png')}}"
        thumbImage="{{uploadUrl(option('portfolio.front.header.image.thumb',''), 'header-thumb.png')}}"
    ></x-front.hero>

    <div class="container container-lg">
        <x-front.portfolio-boxes></x-front.portfolio-boxes>
    </div>

@endsection
@section('script')
@endsection
