@extends('layouts.master')

@section('title', 'Getting Started')
@section('style')
    <style>
    </style>
@endsection
@section('breadcrumb')
    <div class="col-md-6">
        <x-layout.breadcrumb :menus="['Getting Started', 'Welcome']" :menuLinks="[route('user.getting.started.index')]" />
    </div>
@endsection

@section('content')
<div class="tw-flex tw-justify-center tw-flex-col tw-h-full tw-w-full tw-bg-white tw-relative" style="padding: 20px">
    <video id="videoElem" style="height:500px !important; width: auto !important; background: black; color: white">
        <source src="{{option('welcome.video.url','')}}">
        Your browser does not support HTML5 video.
    </video>
    <div class="tw-flex tw-justify-end mt-5 tw-absolute tw-bottom-0 tw-right-0 tw-w-full" style="padding: 40px; gap: 10px;">
        <button class="btn m-btn--custom btn-success hover:tw-bg-transparent" onclick="handlePlay()">view</button>
        <a href="{{ route('user.getting.started.index') }}" class="btn m-btn--custom btn-outline-success !tw-text-[#34bfa3]">skip</a>
    </div>
</div>
@endsection
@section('script')
<script>
    function handlePlay() {
        document.getElementById('videoElem').play()
     }
</script>
@endsection

