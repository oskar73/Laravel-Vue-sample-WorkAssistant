@extends('layouts.app')

@section('title', 'Bizinabox.com Legal ' . $page->title)

@section('seo')
    @if(isset($root))
        <link rel="canonical" href="{{ config('app.url') }}/{{ $page->slug }}">
    @else
        <link rel="canonical" href="{{ config('app.url') }}/legal/{{ $page->slug }}">
    @endif
@endsection

@section('style')
@endsection
@section('content')
    <div class="container my-5">
        <h2>{{$page->title}}</h2>
        {!! $page->body !!}
    </div>
@endsection
@section('script')
@endsection
