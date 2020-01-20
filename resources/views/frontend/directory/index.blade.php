@extends('layouts.app')

@section('title', $seo['title']??'Bizinabox Directory Categories')

@section('style')
@endsection


@section('seo')
    @include('components.front.seo')
@endsection

@section('content')

    <x-front.hero
        image="{{uploadUrl(option('directory.front.header.image'),'header.png')}}"
        thumbImage="{{uploadUrl(option('directory.front.header.image.thumb'),'header-thumb.png')}}"
    ></x-front.hero>

    <div class="container container-lg">
        <x-front.directory-boxes></x-front.directory-boxes>
    </div>

@endsection
@section('script')
@endsection
